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
    | Maximum number of automatically generated annottation candidates that are created
    | for a job. The limit applies to the list of annotation candidates sorted by
    | confidence score in descending order. Set to INF to allow any number.
    */
    'annotation_candidate_limit' => 100000,

    /*
    | Storage disk where the annotation candidate patch images will be stored
    */
    'annotation_candidate_storage_disk' => env('MAIA_ANNOTATION_CANDIDATE_STORAGE_DISK'),

    /*
    | Queue to submit new MAIA jobs to.
    */
    'job_queue' => env('MAIA_JOB_QUEUE', env('MAIA_REQUEST_QUEUE', 'default')),

    /*
    | Queue connection to submit new MAIA jobs to.
    */
    'job_connection' => env('MAIA_JOB_CONNECTION', env('MAIA_REQUEST_CONNECTION', 'gpu')),

    /*
    | Queue to submit MAIA feature vector jobs to.
    */
    'feature_vector_queue' => env('MAIA_FEATURE_VECTOR_QUEUE', env('MAIA_JOB_QUEUE', env('MAIA_REQUEST_QUEUE', 'default'))),

    /*
    | Directory where the temporary files of novelty detection or object detection
    | should be stored.
    */
    'tmp_dir' => env('MAIA_TMP_DIR', storage_path('maia_jobs')),

    /*
    | Keep the temporary files of a MAIA job in case of a failure.
    | For debugging purposes only.
    */
    'debug_keep_files' => env('MAIA_DEBUG_KEEP_FILES', false),

    /*
    | Path to the Python executable.
    */
    'python' => env('MAIA_PYTHON', '/usr/bin/python3'),

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
    'dataset_script' => __DIR__.'/../resources/scripts/object-detection/DatasetGenerator.py',

    /*
    | Path to the script that trains the MMDetection model.
    */
    'training_script' => __DIR__.'/../resources/scripts/object-detection/TrainingRunner.py',

    /*
    | Path to the script that performs inference with the trained MMDetection model.
    */
    'inference_script' => __DIR__.'/../resources/scripts/object-detection/InferenceRunner.py',

    /*
    | Number of 512x512 px images in one training batch.
    | This can be increased with larger GPU memory to achieve faster training.
    |
    | Default is 16.
    */
    'train_batch_size' => env('MAIA_TRAIN_BATCH_SIZE', 8),


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
