import torch
import torchvision
import numpy as np
from PIL import Image
from torch import nn
from torch import optim
from torch.utils.data import TensorDataset
from torchvision.transforms import functional as F

class Autoencoder(nn.Module):
    def __init__(self, kernel_size, channels = 3, stride = 2, hidden_factor = 0.1):
        super().__init__()
        input_features = kernel_size**2 * channels
        hidden_features = int(np.round(input_features * hidden_factor))

        self.encoder_layer = nn.Linear(
            in_features=input_features, out_features=hidden_features
        )

        self.decoder_layer = nn.Linear(
            in_features=hidden_features, out_features=input_features
        )

    def forward(self, x):
        x = self.encoder_layer(x)
        x = torch.relu(x)
        x = self.decoder_layer(x)

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

    def forward(self, ae_model, x):
        height = x.size(2)
        width = x.size(3)

        # Dimension 0 is the batch, dimension 1 are the color channels.
        x = x.unfold(2, self.kernel_size, self.stride).unfold(3, self.kernel_size, self.stride)
        patch_no_y = x.size(2)
        patch_no_x = x.size(3)

        # Move the unfold dimensions forward to get (..., C, H, W) at the end again.
        x = torch.permute(x, (0, 2, 3, 1, 4, 5))
        # Flatten to (..., C * H * W).
        x = x.reshape(-1, self.kernel_size**2 * self.channels)

        y = ae_model(x)

        # Mean square error between (flattened) original and reconstruction.
        x = torch.mean(torch.square(torch.sub(x, y)), 1)

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

        train_patches = images.random_patches(number, self.patch_size)
        train_patches = np.moveaxis(train_patches, -1, 1).reshape(number, -1)
        train_patches = torch.tensor(train_patches.astype(np.float32) / 255, device=self.device)

        train_dataset = TensorDataset(train_patches)
        train_loader = torch.utils.data.DataLoader(train_dataset, batch_size=batch_size, shuffle=True)

        for epoch in range(epochs):
            loss = 0
            for batch_features in train_loader:
                batch_features = batch_features[0]

                # reset the gradients back to zero
                # PyTorch accumulates gradients on subsequent backward passes
                optimizer.zero_grad()

                outputs = self.ae_model(batch_features)
                train_loss = criterion(outputs, batch_features)
                train_loss.backward()
                optimizer.step()

                loss += train_loss.item()

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
                novmap = self.novmap_model(self.ae_model, chunk_image)
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
