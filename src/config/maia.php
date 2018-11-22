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
    'tmp_dir' => sys_get_temp_dir(),

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
    | Path to the novelty detection script.
    */
    'novelty_detection_script' => __DIR__.'/../resources/scripts/novelty-detection/DetectionRunner.py',
];
