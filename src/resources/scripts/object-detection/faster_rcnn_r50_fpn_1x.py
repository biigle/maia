classes = ('interesting', )

model = dict(
    type='FasterRCNN',
    data_preprocessor=dict(
        type='DetDataPreprocessor',
        mean=[123.675, 116.28, 103.53],
        std=[58.395, 57.12, 57.375],
        bgr_to_rgb=True,
        pad_size_divisor=32),
    backbone=dict(
        type='ResNet',
        depth=50,
        num_stages=4,
        out_indices=(0, 1, 2, 3),
        frozen_stages=1,
        norm_cfg=dict(type='BN', requires_grad=True),
        norm_eval=True,
        style='pytorch',
        init_cfg=dict(type='Pretrained', checkpoint='torchvision://resnet50')),
    neck=dict(
        type='FPN',
        in_channels=[256, 512, 1024, 2048],
        out_channels=256,
        num_outs=5),
    rpn_head=dict(
        type='RPNHead',
        in_channels=256,
        feat_channels=256,
        anchor_generator=dict(
            type='AnchorGenerator',
            scales=[8],
            ratios=[0.5, 1.0, 2.0],
            strides=[4, 8, 16, 32, 64]),
        bbox_coder=dict(
            type='DeltaXYWHBBoxCoder',
            target_means=[0.0, 0.0, 0.0, 0.0],
            target_stds=[1.0, 1.0, 1.0, 1.0]),
        loss_cls=dict(
            type='CrossEntropyLoss', use_sigmoid=True, loss_weight=1.0),
        loss_bbox=dict(type='L1Loss', loss_weight=1.0)),
    roi_head=dict(
        type='StandardRoIHead',
        bbox_roi_extractor=dict(
            type='SingleRoIExtractor',
            roi_layer=dict(type='RoIAlign', output_size=7, sampling_ratio=0),
            out_channels=256,
            featmap_strides=[4, 8, 16, 32]),
        bbox_head=dict(
            type='Shared2FCBBoxHead',
            in_channels=256,
            fc_out_channels=1024,
            roi_feat_size=7,
            num_classes=1,
            bbox_coder=dict(
                type='DeltaXYWHBBoxCoder',
                target_means=[0.0, 0.0, 0.0, 0.0],
                target_stds=[0.1, 0.1, 0.2, 0.2]),
            reg_class_agnostic=False,
            loss_cls=dict(
                type='CrossEntropyLoss', use_sigmoid=False, loss_weight=1.0),
            loss_bbox=dict(type='L1Loss', loss_weight=1.0))),
    train_cfg=dict(
        rpn=dict(
            assigner=dict(
                type='MaxIoUAssigner',
                pos_iou_thr=0.7,
                neg_iou_thr=0.3,
                min_pos_iou=0.3,
                match_low_quality=True,
                ignore_iof_thr=-1),
            sampler=dict(
                type='RandomSampler',
                num=256,
                pos_fraction=0.5,
                neg_pos_ub=-1,
                add_gt_as_proposals=False),
            allowed_border=-1,
            pos_weight=-1,
            debug=False),
        rpn_proposal=dict(
            nms_pre=2000,
            max_per_img=1000,
            nms=dict(type='nms', iou_threshold=0.7),
            min_bbox_size=0),
        rcnn=dict(
            assigner=dict(
                type='MaxIoUAssigner',
                pos_iou_thr=0.5,
                neg_iou_thr=0.5,
                min_pos_iou=0.5,
                match_low_quality=False,
                ignore_iof_thr=-1),
            sampler=dict(
                type='RandomSampler',
                num=512,
                pos_fraction=0.25,
                neg_pos_ub=-1,
                add_gt_as_proposals=True),
            pos_weight=-1,
            debug=False)),
    test_cfg=dict(
        rpn=dict(
            nms_pre=1000,
            max_per_img=1000,
            # The original config had iou_threshold=0.7.
            # Lowered, because of many overlapping boxes for the same objects in tests.
            nms=dict(type='nms', iou_threshold=0.2),
            min_bbox_size=0),
        rcnn=dict(
            score_thr=0.05,
            # The original config had iou_threshold=0.5.
            # Lowered, because of many overlapping boxes for the same objects in tests.
            nms=dict(type='nms', iou_threshold=0.2),
            max_per_img=100)))

dataset_type = 'CustomDataset'

train_pipeline = [
    # Use color_type unchanged to ignore EXIF orientation!
    # See: https://github.com/open-mmlab/mmcv/blob/0b005c52b4571f7cd1a7a882a5acecef6357ef0f/mmcv/image/io.py#L145
    dict(type='LoadImageFromFile', color_type='unchanged'),
    dict(type='LoadAnnotations', with_bbox=True),
    dict(type='RandomCrop', crop_size=(512, 512)),
    # Example: https://github.com/open-mmlab/mmdetection/blob/master/configs/albu_example/mask_rcnn_r50_fpn_albu_1x_coco.py#L44
    dict(
        type='Albu',
        skip_img_without_anno=True,
        bbox_params=dict(
            type='BboxParams',
            format='pascal_voc',
            label_fields=['gt_labels'],
            filter_lost_elements=True,
            min_area=100),
        keymap=dict(img='image', gt_masks='masks', gt_bboxes='bboxes'),
        transforms=[
            dict(
                type='SomeOf',
                # Choose each element with equal probability.
                n=4,
                p=0.25,
                replace=False,
                transforms=[
                    dict(type='Flip'),
                    dict(type='RandomRotate90'),
                    dict(type='GaussianBlur', sigma_limit=[1.0, 2.0]),
                    dict(type='ImageCompression', quality_lower=25, quality_upper=50),
                ])
        ]),
    dict(type='PackDetInputs')
]

test_pipeline = [
    # Use color_type unchanged to ignore EXIF orientation!
    # See: https://github.com/open-mmlab/mmcv/blob/0b005c52b4571f7cd1a7a882a5acecef6357ef0f/mmcv/image/io.py#L145
    dict(type='LoadImageFromFile', color_type='unchanged'),
    dict(
        type='PackDetInputs',
        meta_keys=('img_id', 'img_path', 'ori_shape', 'img_shape',
                   'scale_factor')
    )
]

train_dataloader = dict(
    batch_size=16,
    num_workers=2,
    persistent_workers=True,  # Avoid recreating subprocesses after each iteration
    sampler=dict(type='DefaultSampler', shuffle=True),  # Default sampler, supports both distributed and non-distributed training
    batch_sampler=dict(type='AspectRatioBatchSampler'),  # Default batch_sampler, used to ensure that images in the batch have similar aspect ratios, so as to better utilize graphics memory
    dataset=dict(
        type=dataset_type,
        ann_file='',
        data_prefix=dict(img=''),
        filter_cfg=dict(filter_empty_gt=True, min_size=16),
        pipeline=train_pipeline))

val_dataloader = dict(
    batch_size=1,
    num_workers=2,
    persistent_workers=True,
    drop_last=False,
    sampler=dict(type='DefaultSampler', shuffle=False),
    dataset=dict(
        type=dataset_type,
        ann_file='',
        data_prefix=dict(img=''),
        test_mode=True,
        pipeline=test_pipeline))

test_dataloader = val_dataloader

val_evaluator = dict(
    type='CocoMetric',
    ann_file='',
    metric=['mAP'],
    format_only=False)

test_evaluator = val_evaluator

optim_wrapper = dict(
    type='OptimWrapper',
    optimizer=dict(
        type='SGD',
        lr=0.02,
        momentum=0.9,
        weight_decay=0.0001),
    clip_grad=None)

epochs = 12

param_scheduler = [
    dict(
        type='LinearLR',  # Use linear learning rate warmup
        start_factor=0.001,
        by_epoch=False,
        begin=0,
        end=500),
    dict(
        type='MultiStepLR',  # Use multi-step learning rate strategy during training
        by_epoch=True,
        begin=0,
        end=epochs,
        milestones=[8, 11])
]

train_cfg = dict(type='EpochBasedTrainLoop', max_epochs=epochs, val_interval=1)
val_cfg = dict(type='ValLoop')
test_cfg = dict(type='TestLoop')

default_hooks = dict(
    checkpoint=dict(type='CheckpointHook', interval=epochs),
    logger=dict(type='LoggerHook', interval=1))

custom_hooks = [dict(type='NumClassCheckHook')]

dist_params = dict(backend='nccl')

log_level = 'INFO'

load_from = ''

resume = None

workflow = [('train', 1)]

env_cfg = dict(
    mp_cfg=dict(mp_start_method='fork',
                opencv_num_threads=0))

# Don't change the base_batch_size.
# See: https://mmdetection.readthedocs.io/en/dev/1_exist_data_model.html#learning-rate-automatically-scale
auto_scale_lr = dict(enable=True, base_batch_size=16)

work_dir = ''

auto_resume = False

# See: https://github.com/open-mmlab/mmdetection/issues/10052#issuecomment-1607320127
default_scope = 'mmdet'
