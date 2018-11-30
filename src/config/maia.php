<?php

return [
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
    | Directory where the annotation patch images will be stored
    */
    'patch_storage' => storage_path('maia_patches'),

    /*
    | Directory where the temporary files of novelty detection or instance segmentation
    | should be stored.
    */
    'tmp_dir' => env('MAIA_TMP_DIR', storage_path('maia_jobs')),

    /*
    | Estimated available GPU memory in bytes. The Python scripts use this to determine
    | their memory load to get the best performance (or work at all in low memory cases).
    |
    | Default is 8 GB.
    */
    'available_bytes' => env('MAIA_AVAILABLE_BYTES', 8E+9),

    /*
    | Path to the Python executable.
    */
    'python' => '/usr/bin/python3',

    /*
    | Number of worker threads to use during novelty detection or instance segmentation.
    | Set this to the number of available CPU cores.
    */
    'max_workers' => env('MAIA_MAX_WORKERS', 2),

    /*
    | Path to the novelty detection script.
    */
    'novelty_detection_script' => __DIR__.'/../resources/scripts/novelty-detection/DetectionRunner.py',

    /*
    | Path to the script that generates the training dataset for Mask R-CNN.
    */
    'mrcnn_dataset_script' => __DIR__.'/../resources/scripts/instance-segmentation/DatasetGenerator.py',

    /*
    | Path to the script that trains Mask R-CNN.
    */
    'mrcnn_training_script' => __DIR__.'/../resources/scripts/instance-segmentation/TrainingRunner.py',

    /*
    | Path to the script that performs inference with the trained Mask R-CNN.
    */
    'mrcnn_inference_script' => __DIR__.'/../resources/scripts/instance-segmentation/InferenceRunner.py',

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
];
