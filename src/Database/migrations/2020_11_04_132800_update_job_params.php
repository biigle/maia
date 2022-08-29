<?php

use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateJobParams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        MaiaJob::eachById(function ($job) {
            $params = $job->params;

            if (array_key_exists('skip_nd', $params)) {
                unset($params['skip_nd']);
                $params['training_data_method'] = MaiaJob::TRAIN_OWN_ANNOTATIONS;
            }

            if (array_key_exists('use_existing', $params)) {
                unset($params['use_existing']);
                if (array_key_exists('restrict_labels', $params)) {
                    $params['oa_restrict_labels'] = $params['restrict_labels'];
                    unset($params['restrict_labels']);
                }
            }

            if (array_key_exists('is_epochs_head', $params) && array_key_exists('is_epochs_all', $params)) {
                $params['is_train_scheme'] = [
                    [
                        'layers' => 'head',
                        'epochs' => $params['is_epochs_head'],
                        'learning_rate' => 0.001,
                    ],
                    [
                        'layers' => 'all',
                        'epochs' => $params['is_epochs_all'],
                        'learning_rate' => 0.0001,
                    ],
                ];

                unset($params['is_epochs_head']);
                unset($params['is_epochs_all']);
            }

            $job->params = $params;
            $job->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
