<?php

return [
    /*
    | Storage disk where the training proposal patch images will be stored
    */
    'training_proposal_storage_disk' => env('MAIA_TRAINING_PROPOSAL_STORAGE_DISK'),

    /*
    | Maximum number of automatically generated training proposals that are created for
    | a job. This does not include any training proposals that were generated from
    | existing annotations. The limit applies to the list of training proposals sorted
    | by novelty score in descending order. Set to INF to allow any number.
    */
    'training_proposal_limit' => 50000,

    /*
    | Storage disk where the annotation candidate patch images will be stored
    */
    'annotation_candidate_storage_disk' => env('MAIA_ANNOTATION_CANDIDATE_STORAGE_DISK'),

    /*
    | Queue to submit new MAIA jobs to.
    */
    'request_queue' => env('MAIA_REQUEST_QUEUE', 'default'),

    /*
    | Queue connection to submit new MAIA jobs to.
    */
    'request_connection' => env('MAIA_REQUEST_CONNECTION', 'gpu'),

    /*
    | Queue to submit the result data of MAIA jobs to.
    */
    'response_queue' => env('MAIA_RESPONSE_QUEUE', 'default'),

    /*
    | Queue connection to submit the result data of MAIA jobs to.
    */
    'response_connection' => env('MAIA_RESPONSE_CONNECTION', 'gpu-response'),

    /*
    | Directory where the temporary files of novelty detection or object detection
    | should be stored.
    */
    'tmp_dir' => env('MAIA_TMP_DIR', storage_path('maia_jobs')),

    /*
    | Path to the Python executable.
    */
    'python' => '/usr/bin/python3',

    /*
    | Number of worker threads to use during novelty detection or object detection.
    | Set this to the number of available CPU cores.
    */
    'max_workers' => env('MAIA_MAX_WORKERS', 2),

    /*
    | Path to the novelty detection script.
    */
    'novelty_detection_script' => __DIR__.'/../resources/scripts/novelty-detection/DetectionRunner.py',

    /*
    | Path to the script that generates the training dataset for MMDetection.
    */
    'mmdet_dataset_script' => __DIR__.'/../resources/scripts/object-detection/DatasetGenerator.py',

    /*
    | Path to the script that trains the MMDetection model.
    */
    'mmdet_training_script' => __DIR__.'/../resources/scripts/object-detection/TrainingRunner.py',

    /*
    | Path to the script that performs inference with the trained MMDetection model.
    */
    'mmdet_inference_script' => __DIR__.'/../resources/scripts/object-detection/InferenceRunner.py',

    /*
    | Path to the MMDetection base config file.
    */
    'mmdet_base_config' => __DIR__.'/../resources/scripts/object-detection/faster_rcnn_r50_fpn_1x_coco.py',

    /*
    | URL from which to download the pretrained weights for the model backbone.
    */
    'backbone_model_url' => env('MAIA_BACKBONE_MODEL_URL', 'https://download.pytorch.org/models/resnet50-11ad3fa6.pth'),

    /*
    | URL from which to download the trained weights for the model.
    */
    'model_url' => env('MAIA_MODEL_URL', 'https://download.openmmlab.com/mmdetection/v2.0/faster_rcnn/faster_rcnn_r50_fpn_1x_coco/faster_rcnn_r50_fpn_1x_coco_20200130-047c8118.pth'),

    /*
    | Path to the file to store the pretrained backbone weights to.
    */
    'backbone_model_path' => storage_path('maia_jobs').'/resnet50-11ad3fa6.pth',

    /*
    | Path to the file to store the pretrained model weights to.
    */
    'model_path' => storage_path('maia_jobs').'/faster_rcnn_r50_fpn_1x_coco_20200130-047c8118.pth',

    /*
    | Number of 512x512 px images in a training batch of MMDetection.
    | This can be increased with larger GPU memory to achieve faster training.
    |
    | Default is 12.
    */
    'mmdet_train_batch_size' => env('MAIA_MMDET_TRAIN_BATCH_SIZE', 12),


    'notifications' => [
        /*
        | Set the way notifications for MAIA job state changes are sent by default.
        |
        | Available are: "email", "web"
        */
        'default_settings' => 'email',

        /*
        | Choose whether users are allowed to change their notification settings.
        | If set to false the default settings will be used for all users.
        */
        'allow_user_settings' => true,
    ],

    /*
     | Specifies which queue should be used for the job to convert annotation sessions.
     */
    'convert_annotations_queue' => env('MAIA_CONVERT_ANNOTATIONS_QUEUE', 'default'),

    /*
     | Enable to disallow submission of new jobs.
     */
    'maintenance_mode' => env('MAIA_MAINTENANCE_MODE', false),
];
