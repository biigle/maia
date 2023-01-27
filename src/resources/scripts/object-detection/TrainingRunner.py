import os
import os.path as osp
import sys
import json

import time

import mmcv
import torch
from mmcv import Config

from mmdet import __version__
from mmdet.apis import init_random_seed, set_random_seed, train_detector
from mmdet.datasets import build_dataset
from mmdet.models import build_detector
from mmdet.utils import (collect_env, get_device, get_root_logger,
                         replace_cfg_vals, rfnext_init_model,
                         setup_multi_processes, update_data_root)

class TrainingRunner(object):

    def __init__(self, params, trainset):
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']

        self.base_config = params['base_config']

        self.dump_config_name = 'mmdet_config.py'
        self.deterministic = False

        self.cfg_options = {
            # Path to store the logfiles and final checkpoint to.
            'work_dir': osp.join(self.tmp_dir, 'work_dir'),
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
            'data': {
                'samples_per_gpu': params['batch_size'],
                'workers_per_gpu': params['max_workers'],
                'train': {
                    'ann_file': trainset['ann_file'],
                    'img_prefix': trainset['img_prefix'],
                },
            },
            'classes': trainset['classes'],
            'gpu_ids': [0],
        }

    # Based on: https://github.com/open-mmlab/mmdetection/blob/master/tools/train.py
    def run(self):

        cfg = Config.fromfile(self.base_config)

        # replace the ${key} with the value of cfg.key
        cfg = replace_cfg_vals(cfg)

        # update data root according to MMDET_DATASETS
        update_data_root(cfg)

        cfg.merge_from_dict(self.cfg_options)

        # set multi-process settings
        setup_multi_processes(cfg)

        mmcv.mkdir_or_exist(osp.abspath(cfg.work_dir))
        # dump config
        cfg.dump(osp.join(cfg.work_dir, self.dump_config_name))
        # init the logger before other steps
        timestamp = time.strftime('%Y%m%d_%H%M%S', time.localtime())
        log_file = osp.join(cfg.work_dir, f'{timestamp}.log')
        logger = get_root_logger(log_file=log_file, log_level=cfg.log_level)

        # init the meta dict to record some important information such as
        # environment info and seed, which will be logged
        meta = dict()
        # log env info
        env_info_dict = collect_env()
        env_info = '\n'.join([(f'{k}: {v}') for k, v in env_info_dict.items()])
        dash_line = '-' * 60 + '\n'
        logger.info('Environment info:\n' + dash_line + env_info + '\n' +
                    dash_line)
        meta['env_info'] = env_info
        meta['config'] = cfg.pretty_text
        # log some basic info
        logger.info(f'Config:\n{cfg.pretty_text}')

        cfg.device = get_device()
        # set random seeds
        seed = init_random_seed(device=cfg.device)
        logger.info(f'Set random seed to {seed}, '
                    f'deterministic: {self.deterministic}')
        set_random_seed(seed, deterministic=self.deterministic)
        cfg.seed = seed
        meta['seed'] = seed
        meta['exp_name'] = self.dump_config_name

        model = build_detector(
            cfg.model,
            train_cfg=cfg.get('train_cfg'),
            test_cfg=cfg.get('test_cfg'))
        model.init_weights()

        # init rfnext if 'RFSearchHook' is defined in cfg
        rfnext_init_model(model, cfg=cfg)

        datasets = [build_dataset(cfg.data.train)]

        if cfg.checkpoint_config is not None:
            # save mmdet version, config file content and class names in
            # checkpoints as meta data
            cfg.checkpoint_config.meta = dict(
                mmdet_version=__version__,
                CLASSES=datasets[0].CLASSES)

        train_detector(
            model,
            datasets,
            cfg,
            validate=False,
            timestamp=timestamp,
            meta=meta)

        return {
            'work_dir': cfg.work_dir,
            'checkpoint_path': osp.join(cfg.work_dir, 'latest.pth'),
            'config_path': osp.join(cfg.work_dir, self.dump_config_name),
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
