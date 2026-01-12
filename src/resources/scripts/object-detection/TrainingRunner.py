from torch.utils.data import DataLoader
from torchvision.datasets import CocoDetection
from torchvision.transforms import functional as F
from torchvision.models.detection import fasterrcnn_resnet50_fpn
from torchvision.models.detection.faster_rcnn import FastRCNNPredictor
from torchvision.models.detection.rpn import AnchorGenerator
import albumentations as A
from albumentations.pytorch import ToTensorV2
import json
import os
import sys
import torch
import math

# class CocoDataset(CocoDetection):
#     def __init__(self, root, annFile, transform=None):
#         super().__init__(root, annFile)
#         self.transform = transform

#     def __getitem__(self, idx):
#         # Load image and standard COCO annotation
#         img, target = super().__getitem__(idx)
#         image_id = self.ids[idx]

#         # Parse the COCO annotations
#         boxes = []
#         labels = []
#         area = []
#         iscrowd = []

#         for obj in target:
#             # COCO bbox: [x, y, w, h] -> PyTorch bbox: [x1, y1, x2, y2]
#             xmin = max(obj['bbox'][0], 0)
#             ymin = max(obj['bbox'][1], 0)
#             xmax = min(xmin + obj['bbox'][2], img.width)
#             ymax = min(ymin + obj['bbox'][3], img.height)

#             # Filter out small/empty boxes to prevent NaN loss
#             if xmax > xmin and ymax > ymin:
#                 boxes.append([xmin, ymin, xmax, ymax])
#                 labels.append(obj['category_id'])
#                 area.append(obj['area'])
#                 iscrowd.append(obj['iscrowd'])

#         # Convert python lists to torch tensors
#         boxes = torch.as_tensor(boxes, dtype=torch.float32)
#         labels = torch.as_tensor(labels, dtype=torch.int64)
#         area = torch.as_tensor(area, dtype=torch.float32)
#         iscrowd = torch.as_tensor(iscrowd, dtype=torch.int64)
#         image_id = torch.tensor([image_id])

#         target_dict = {
#             "boxes": boxes,
#             "labels": labels,
#             "image_id": image_id,
#             "area": area,
#             "iscrowd": iscrowd
#         }

#         img_tensor = F.to_tensor(img)

#         return img_tensor, target_dict

class CocoDataset(CocoDetection):
    def _load_target(self, id):
        anns = self.coco.loadAnns(self.coco.getAnnIds(id))
        boxes = [a['bbox'] for a in anns]
        boxes = [[b[0], b[1], b[0] + b[2], b[1] + b[3]] for b in boxes]
        labels = [a['category_id'] for a in anns]

        return {
            'boxes': boxes,
            'labels': labels,
        }

    def __getitem__(self, index):
        if not isinstance(index, int):
            raise ValueError(f"Index must be of type integer, got {type(index)} instead.")

        id = self.ids[index]
        image = self._load_image(id)
        target = self._load_target(id)

        if self.transforms is not None:
            ret = self.transforms(
                image=np.array(image),
                bboxes=np.array(target['boxes'], dtype=np.float32),
                labels=target['labels']
            )
            image = ret['image']
            target['boxes'] = torch.tensor(ret['bboxes'])
            target['labels'] = torch.tensor(ret['labels'], dtype=torch.int64)

        return image, target

def collate_fn(batch):
    return tuple(zip(*batch))

def get_model(num_classes, **kwargs):
    model = fasterrcnn_resnet50_fpn(**kwargs)
    # Replace the classifier with a new one having the user-defined number of classes.
    num_classes = num_classes + 1  # +1 for background
    in_features = model.roi_heads.box_predictor.cls_score.in_features
    model.roi_heads.box_predictor = FastRCNNPredictor(in_features, num_classes)

    return model

if __name__ == '__main__':
    with open(sys.argv[1]) as f:
        params = json.load(f)

    with open(sys.argv[2]) as f:
        trainset = json.load(f)

    num_classes = len(trainset['classes'])

    device = torch.accelerator.current_accelerator() if torch.accelerator.is_available() else torch.device('cpu')
    print(f"Training on: {device}")

    bbox_params = A.BboxParams(
        format='pascal_voc',
        label_fields=['labels'],
        # Clip boxes that overflow image boundaries.
        clip=True,
        # Remove all boxes with less than 10% of their content in the random crop.
        min_visibility=0.1,
        filter_invalid_bboxes=True,
    )
    transforms = A.Compose([
        A.AtLeastOneBBoxRandomCrop(512, 512, erosion_factor=0.5),
        A.SomeOf([
            A.HorizontalFlip(p=0.25),
            A.VerticalFlip(p=0.25),
            A.RandomRotate90(p=0.25),
            A.GaussianBlur(sigma_limit=[1.0, 2.0]),
            A.ImageCompression(quality_range=[25, 50]),
        ], n=4, p=0.25),
        A.ToFloat(),
        ToTensorV2(),
    ], bbox_params=bbox_params)

    dataset = CocoDataset(root=trainset['img_prefix'], annFile=trainset['ann_file'], transforms=transforms)

    data_loader = DataLoader(
        dataset,
        batch_size=int(params['batch_size']),
        shuffle=True,
        num_workers=int(params['max_workers']),
        pin_memory=True,
        collate_fn=collate_fn
    )

    model = get_model(num_classes, weights='DEFAULT', min_size=512)
    model.to(device)

    model_params = [p for p in model.parameters() if p.requires_grad]
    optimizer = torch.optim.SGD(model_params, lr=0.005, momentum=0.9, weight_decay=0.0005)

    model.train()

    NUM_EPOCHS = 12
    for epoch in range(NUM_EPOCHS):
        print(f"--- Epoch {epoch+1}/{NUM_EPOCHS} ---")
        epoch_loss = 0

        for i, (images, targets) in enumerate(data_loader):
            # Move data to GPU
            images = list(image.to(device) for image in images)
            targets = [{k: v.to(device) for k, v in t.items()} for t in targets]

            loss_dict = model(images, targets)
            losses = sum(loss for loss in loss_dict.values())
            loss_value = losses.item()

            if not math.isfinite(loss_value):
                print(f"Loss is {loss_value}, stopping training")
                print(loss_dict)
                sys.exit(1)

            # Backward pass
            optimizer.zero_grad()
            losses.backward()
            optimizer.step()

            epoch_loss += losses.item()

            if i % 10 == 0:
                print(f"Batch {i}: Loss = {losses.item():.4f}")

        print(f"Epoch {epoch+1} Average Loss: {epoch_loss / len(data_loader):.4f}")

    checkpoint_path = os.path.join(params['tmp_dir'], 'model.pth')
    torch.save(model.state_dict(), checkpoint_path)

    output = {
        'checkpoint_path': checkpoint_path,
        'num_classes': num_classes,
    }

    with open(params['output_path'], 'w') as f:
        json.dump(output, f)
