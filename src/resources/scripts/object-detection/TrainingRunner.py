from albumentations.pytorch import ToTensorV2
from torch.utils.data import DataLoader
from torch_utils import collate_fn, train_one_epoch
from torchvision.datasets import CocoDetection
from torchvision.models.detection import fasterrcnn_resnet50_fpn_v2
from torchvision.models.detection.faster_rcnn import FastRCNNPredictor
import albumentations as A
import json
import numpy as np
import os
import sys
import torch

# Based on: https://docs.pytorch.org/tutorials/intermediate/torchvision_tutorial.html#torchvision-object-detection-finetuning-tutorial

with open(sys.argv[1]) as f:
    params = json.load(f)

with open(sys.argv[2]) as f:
    trainset = json.load(f)

model = fasterrcnn_resnet50_fpn_v2(weights='DEFAULT')
# Replace the classifier with a new one having the user-defined number of classes.
num_classes = len(trainset['classes']) + 1  # +1 for background
in_features = model.roi_heads.box_predictor.cls_score.in_features
model.roi_heads.box_predictor = FastRCNNPredictor(in_features, num_classes)

transforms = A.Compose([
    A.AtLeastOneBBoxRandomCrop(512, 512),
    A.SomeOf([
        A.HorizontalFlip(p=0.25),
        A.VerticalFlip(p=0.25),
        A.RandomRotate90(p=0.25),
        A.GaussianBlur(sigma_limit=[1.0, 2.0]),
        A.ImageCompression(quality_range=[25, 50]),
    ], n=4, p=0.9),
    A.ToFloat(),
    ToTensorV2(),
], bbox_params=A.BboxParams(format='pascal_voc', label_fields=['labels']))

class MyCocoDetection(CocoDetection):
    def _load_target(self, id):
        anns = self.coco.loadAnns(self.coco.getAnnIds(id))
        img = self.coco.imgs[id]
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

dataset = MyCocoDetection(root=trainset['img_prefix'], annFile=trainset['ann_file'], transforms=transforms)

data_loader = DataLoader(
    dataset,
    batch_size=int(params['batch_size']),
    shuffle=True,
    num_workers=int(params['max_workers']),
    collate_fn=collate_fn
)

# Freeze the backbone.
for param in model.backbone.parameters():
    param.requires_grad = False

optim_params = [p for p in model.parameters() if p.requires_grad]
optimizer = torch.optim.SGD(
    optim_params,
    lr=0.005,
    momentum=0.9,
    weight_decay=0.0005
)

lr_scheduler = torch.optim.lr_scheduler.StepLR(
    optimizer,
    step_size=3,
    gamma=0.1
)

device = torch.accelerator.current_accelerator() if torch.accelerator.is_available() else torch.device('cpu')
model.to(device)

# TODO
# num_epochs = 12
num_epochs = 2

for epoch in range(num_epochs):
    # train for one epoch, printing every 10 iterations
    train_one_epoch(model, optimizer, data_loader, device, epoch, print_freq=10)
    # update the learning rate
    lr_scheduler.step()

checkpoint_path = os.path.join(params['tmp_dir'], 'model.pth')
torch.save(model.state_dict(), checkpoint_path)

# TODO
output = {
    # 'work_dir': cfg.work_dir,
    'checkpoint_path': checkpoint_path,
    # 'config_path': os.path.join(cfg.work_dir, self.dump_config_name),
}

with open(params['output_path'], 'w') as f:
    json.dump(output, f)
