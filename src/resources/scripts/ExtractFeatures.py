from PIL import Image
import csv
import json
import numpy
import sys
import torch
import torchvision.transforms as T

# input_json = {
#     cached_filename: {
#         annotation_model_id: [left, top, right, bottom],
#     },
# }

with open(sys.argv[1], 'r') as f:
    input_json = json.load(f)

if torch.cuda.is_available():
    device = torch.device('cuda')
    dinov2_vits14 = torch.hub.load('facebookresearch/dinov2', 'dinov2_vits14').cuda()
else:
    device = torch.device('cpu')
    dinov2_vits14 = torch.hub.load('facebookresearch/dinov2', 'dinov2_vits14')

dinov2_vits14.to(device)

transform = T.Compose([
    T.Resize((224, 224), interpolation=T.InterpolationMode.BICUBIC),
    T.ToTensor(),
    T.Normalize(mean=(0.485, 0.456, 0.406), std=(0.229, 0.224, 0.225)),
])

# TODO: in PHP, move box with negative coordinates into positive

with open(sys.argv[2], 'w') as f:
    writer = csv.writer(f)
    with torch.no_grad():
        for image_path, annotations in input_json.items():
            image = Image.open(image_path)
            for model_id, box in annotations.items():
                image_crop = image.crop(box)
                print('size', image_crop.size)
                image_crop_t = transform(image_crop).unsqueeze(0).to(device)
                print('shape', image_crop_t.shape)
                features = dinov2_vits14(image_crop_t)
                writer.writerow([model_id, json.dumps(features[0].tolist())])
