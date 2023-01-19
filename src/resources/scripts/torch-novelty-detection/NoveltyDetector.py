import torch
import torchvision
import numpy as np
from PIL import Image
from torch import nn
from torch import optim
from torch.utils.data import Dataset
from torchvision.transforms import functional as F
import multiprocessing
import random
import ctypes

class RandomPatches(Dataset):
    def __init__(self, images, patch_size, count, transform=None):
        self.images = images
        self.patch_size = patch_size
        self.count = count
        self.transform = transform
        # Shared array implementation, see: https://github.com/ptrblck/pytorch_misc/blob/master/shared_array.py
        shared_array_base = multiprocessing.Array(ctypes.c_float, count * 3 * patch_size * patch_size)
        shared_array = np.ctypeslib.as_array(shared_array_base.get_obj())
        shared_array = shared_array.reshape(count, 3, patch_size, patch_size)
        self.shared_array = torch.from_numpy(shared_array)
        self.use_cache = False

    def set_use_cache(self, use_cache):
        self.use_cache = use_cache

    def __len__(self):
        return self.count

    def __getitem__(self, x):
        if not self.use_cache:
            image = random.choice(self.images).pil_image()
            x_pos = random.randint(0, image.width - self.patch_size)
            y_pos = random.randint(0, image.width - self.patch_size)
            patch = image.crop((x_pos, y_pos, x_pos + self.patch_size, y_pos + self.patch_size))

            if self.transform is not None:
                patch = self.transform(patch)

            image.close()
            self.shared_array[x] = patch

        return self.shared_array[x]

class Autoencoder(nn.Module):
    def __init__(self, kernel_size, channels = 3, stride = 2, hidden_factor = 0.1):
        super().__init__()
        hidden_shape = int(np.round(kernel_size**2 * channels * hidden_factor))

        self.encoder_layer = nn.Conv2d(
            in_channels=channels, out_channels=hidden_shape, kernel_size=kernel_size, stride=stride, padding_mode='replicate'
        )

        # Set stride=kernel_size to produce an output image without overlaps. Each
        # (overlapping) patch of the previous layer should produce a discrete patch in the
        # output.
        self.decoder_layer = nn.ConvTranspose2d(
            in_channels=hidden_shape, out_channels=channels, kernel_size=kernel_size, stride=kernel_size
        )

    def forward(self, x):
        x = self.encoder_layer(x)
        x = torch.relu(x)
        x = self.decoder_layer(x)
        x = torch.relu(x)

        return x

"""
Takes an input image and the patch-recounstruction output of the autoencoder and computes
the novelty map as the patch-wise difference between the two.
"""
class NoveltyMap(nn.Module):
    def __init__(self, kernel_size, channels = 3, stride = 2):
        super().__init__()
        self.channels = channels
        self.kernel_size = kernel_size
        self.stride = stride

    # x is the original image, y is the patch-reconstruction.
    def forward(self, x, y):
        height = x.size(2)
        width = x.size(3)

        # Dimension 0 is the batch, dimension 1 are the color channels.
        x = x.unfold(2, self.kernel_size, self.stride).unfold(3, self.kernel_size, self.stride)
        patch_no_y = x.size(2)
        patch_no_x = x.size(3)

        # Move the unfold dimensions forward to get (..., C, H, W) at the end again.
        x = torch.permute(x, (0, 2, 3, 1, 4, 5))
        # Flatten to (..., C * H * W).
        x = x.reshape(-1, patch_no_y, patch_no_x, self.kernel_size**2 * self.channels)

        y = y.unfold(2, self.kernel_size, self.kernel_size).unfold(3, self.kernel_size, self.kernel_size)
        # Move the unfold dimensions forward to get (..., C, H, W) at the end again.
        y = torch.permute(y, (0, 2, 3, 1, 4, 5))
        # Flatten to (..., C * H * W).
        y = y.reshape(-1, patch_no_y, patch_no_x, self.kernel_size**2 * self.channels)

        # Mean square error between (flattened) original and reconstruction.
        x = torch.mean(torch.square(torch.sub(x, y)), 3)

        x = x.view(-1, patch_no_y, patch_no_x)
        x = F.resize(x, (height, width))

        return x

class NoveltyDetector(object):

    def __init__(self, patch_size, stride = 2, hidden = 0.1):
        self.patch_size = patch_size
        self.stride = stride
        self.hidden_factor = hidden
        self.device = device = torch.device("cuda" if torch.cuda.is_available() else "cpu")

        self.ae_model = None
        self.novmap_model = NoveltyMap(kernel_size=patch_size, stride=stride).to(self.device).eval()
        self.transform = torchvision.transforms.Compose([torchvision.transforms.ToTensor()])

    def train(self, images, number = 10000, display = True, epochs = 20, batch_size=128, display_step = 1, num_workers = 1):
        self.ae_model = Autoencoder(kernel_size=self.patch_size, stride=self.stride).to(self.device)
        optimizer = optim.Adam(self.ae_model.parameters(), lr=1e-3)
        criterion = nn.MSELoss()
        train_dataset = RandomPatches(images, self.patch_size, number, transform=self.transform)
        train_loader = torch.utils.data.DataLoader(
            train_dataset, batch_size=batch_size, shuffle=True, num_workers=num_workers, pin_memory=True
        )

        for epoch in range(epochs):
            loss = 0
            for batch_features in train_loader:
                batch_features = batch_features.to(self.device)

                # reset the gradients back to zero
                # PyTorch accumulates gradients on subsequent backward passes
                optimizer.zero_grad()

                outputs = self.ae_model(batch_features)
                train_loss = criterion(outputs, batch_features)
                train_loss.backward()
                optimizer.step()

                loss += train_loss.item()

            if epoch == 0:
                # Use training data cache once it is filled after the first epoch.
                train_loader.dataset.set_use_cache(True)
                # No need for so many workers once the patches are cached.
                train_loader.num_workers = min(4, num_workers)

            # compute the epoch training loss
            loss = loss / len(train_loader)

            if display and epoch % display_step == 0:
                print("Epoch : {}/{} ({:.6f})".format(epoch + 1, epochs, loss))


    def apply(self, image_path, chunk_dim = 512, chunk_stride = 256):
        if self.ae_model is None:
            raise Exception('No trained model available. Run train() first.')

        self.ae_model.eval()

        image = Image.open(image_path)
        height = image.height
        width = image.width

        novelty_map = torch.zeros((height, width), dtype=torch.float, device=self.device)
        # This counts how many chunks were added to each pixel.
        chunk_count_map = torch.zeros((height, width), dtype=torch.float, device=self.device)

        # Use unsqueeze to create "batch" with single item.
        image = self.transform(image).unsqueeze(0).to(self.device)

        chunks = self._get_chunks(height, width, dim=chunk_dim, stride=chunk_stride)

        with torch.no_grad():
            for chunk_index, chunk in enumerate(chunks):
                chunk_image = image[:, :, chunk[0]:chunk[1], chunk[2]:chunk[3]]
                outputs = self.ae_model(chunk_image)
                novmap = self.novmap_model(chunk_image, outputs)
                novelty_map[chunk[0]:chunk[1], chunk[2]:chunk[3]] += novmap[0]
                chunk_count_map[chunk[0]:chunk[1], chunk[2]:chunk[3]] += 1.0

        # Compute average novelty score for each pixel. Border pixels have a lower number
        # of chunks that passed over them than center pixels.
        novelty_map = novelty_map / chunk_count_map

        novelty_map = novelty_map.detach().cpu().resolve_conj().resolve_neg().numpy()

        return novelty_map

    def _get_chunks(self, height, width, dim = 512, stride = 256):
        height_chunks = height // stride
        width_chunks = width // stride
        stride_overlap = dim // stride - 1

        height_rest = height % stride
        width_rest = width % stride

        chunks = []
        for i_height in range(height_chunks - stride_overlap):
            for i_width in range(width_chunks - stride_overlap):
                chunks.append((
                    i_height * stride,
                    i_height * stride + dim,
                    i_width * stride,
                    i_width * stride + dim
                ))

            if width_rest > 0:
                chunks.append((
                    i_height * stride,
                    i_height * stride + dim,
                    width - dim,
                    width
                ))

        if height_rest > 0:
            for i_width in range(width_chunks - stride_overlap):
                chunks.append((
                    height - dim,
                    height,
                    i_width * stride,
                    i_width * stride + dim
                ))

            if width_rest > 0:
                chunks.append((
                    height - dim,
                    height,
                    width - dim,
                    width
                ))

        return chunks
