import os
import sys
import json
from Config import TrainingConfig
from Dataset import TrainingDataset
import mrcnn.model as modellib

class TrainingRunner(object):

    def __init__(self, params, trainset):
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']
        # Estimated available GPU memory in bytes.
        self.available_bytes = params['available_bytes']
        # Path to the COCO pretrained weights for Mask R-CNN
        self.coco_model_path = params['coco_model_path']
        # Path to store the trained Mask R-CNN model to.
        self.model_dir = '{}/models'.format(self.tmp_dir)

        self.max_workers = params['max_workers']
        self.config = TrainingConfig(params, trainset)
        self.dataset = TrainingDataset(trainset)

    def ensure_model_dir(self):
        if not os.path.exists(self.model_dir):
           os.makedirs(self.model_dir)

    def run(self):
        self.ensure_model_dir()
        self.config.display()
        self.dataset.prepare()

        model = modellib.MaskRCNN(mode="training", config=self.config, model_dir=self.model_dir)
        # Load weights trained on MS COCO, but skip layers that
        # are different due to the different number of classes.
        model.load_weights(self.coco_model_path, by_name=True, exclude=[
            "mrcnn_class_logits",
            "mrcnn_bbox_fc",
            "mrcnn_bbox",
            "mrcnn_mask",
        ])

        # Train the head branches
        # Passing layers="heads" freezes all layers except the head layers.
        model.train(self.dataset,
                    val_dataset=None,
                    learning_rate=self.config.LEARNING_RATE,
                    augmentation=self.config.AUGMENTATION,
                    workers=self.max_workers,
                    epochs=20,
                    layers='heads')

        # Fine tune all layers
        # The epochs *include* those of the previous training. So set 20 if you want to
        # train for 10 epochs and already trained for 10 epochs.
        model.train(self.dataset,
                    val_dataset=None,
                    learning_rate=self.config.LEARNING_RATE / 10,
                    augmentation=self.config.AUGMENTATION,
                    workers=self.max_workers,
                    epochs=30,
                    layers='all')

        model_path = os.path.join(self.model_dir, "mask_rcnn_final.h5")
        model.keras_model.save_weights(model_path)

        return {
            'model_path': model_path,
        }

with open(sys.argv[1]) as f:
    params = json.load(f)

with open(sys.argv[2]) as f:
    trainset = json.load(f)

runner = TrainingRunner(params, trainset)
output = runner.run()

with open(params['output_path'], 'w') as f:
    json.dump(output, f)
