import os
import sys
import json

import time

from mmengine.config import Config
from mmengine.runner import Runner
from mmdet.utils import setup_cache_size_limit_of_dynamo

class TrainingRunner(object):

    def __init__(self, params, trainset):
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']

        self.base_config = params['base_config']

        self.dump_config_name = 'mmdet_config.py'

        self.cfg_options = {
            # Path to store the logfiles and final checkpoint to.
            'work_dir': os.path.join(self.tmp_dir, 'work_dir'),
            'model': {
                'backbone': {
                    'init_cfg': {
                        # Path to the pretrained weights for the backbone
                        'checkpoint': params['backbone_model_path'],
                    },
                },
            },
            # Path to the pretrained weights for the rest of the network
            'load_from': params['model_path'],
            'train_dataloader': {
                # If multi-GPU training is implemented at some point, divide this by the
                # number of GPUs!
                'batch_size': int(params['batch_size']),
                'num_workers': int(params['max_workers']),
                'dataset': {
                    'ann_file': trainset['ann_file'],
                    'data_prefix': {
                        'img': trainset['img_prefix'],
                    },
                },
            },
            'val_dataloader': {
                'dataset': {
                    'ann_file': trainset['ann_file'],
                    'data_prefix': {
                        'img': trainset['img_prefix'],
                    },
                },
            },
            'test_dataloader': {
                'dataset': {
                    'ann_file': trainset['ann_file'],
                    'data_prefix': {
                        'img': trainset['img_prefix'],
                    },
                },
            },
            'val_evaluator': {
                'ann_file': trainset['ann_file'],
            },
            'test_evaluator': {
                'ann_file': trainset['ann_file'],
            },
            'classes': trainset['classes'],
            'gpu_ids': [0],
        }

    # Based on: https://github.com/open-mmlab/mmdetection/blob/master/tools/train.py
    def run(self):
        # Reduce the number of repeated compilations and improve
        # training speed.
        setup_cache_size_limit_of_dynamo()

        # load config
        cfg = Config.fromfile(self.base_config)

        cfg.merge_from_dict(self.cfg_options)

        if not os.path.exists(cfg.work_dir):
            os.makedirs(cfg.work_dir)

        # dump config
        cfg.dump(os.path.join(cfg.work_dir, self.dump_config_name))

        runner = Runner.from_cfg(cfg)
        runner.train()

        return {
            'work_dir': cfg.work_dir,
            'checkpoint_path': os.path.join(cfg.work_dir, 'latest.pth'),
            'config_path': os.path.join(cfg.work_dir, self.dump_config_name),
        }

if __name__ == '__main__':
    with open(sys.argv[1]) as f:
        params = json.load(f)

    with open(sys.argv[2]) as f:
        trainset = json.load(f)

    runner = TrainingRunner(params, trainset)
    output = runner.run()

    with open(params['output_path'], 'w') as f:
        json.dump(output, f)
