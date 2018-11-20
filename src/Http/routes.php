<?php

$router->group([
        'middleware' => 'auth',
        'namespace' => 'Views',
    ], function ($router) {
        $router->get('volumes/{id}/maia', [
            'as' => 'volumes-maia',
            'uses' => 'MaiaJobController@index',
        ]);

        $router->get('maia/{id}', [
            'as' => 'maia',
            'uses' => 'MaiaJobController@show',
        ]);
});

$router->group([
        'middleware' => 'auth:web,api',
        'namespace' => 'Api',
        'prefix' => 'api/v1',
    ], function ($router) {
        $router->resource('volumes/{id}/maia-jobs', 'MaiaJobController', [
            'only' => ['store'],
            'parameters' => ['volumes' => 'id'],
        ]);

        $router->resource('maia-jobs', 'MaiaJobController', [
            'only' => ['update', 'destroy'],
            'parameters' => ['maia-jobs' => 'id'],
        ]);

        $router->get('maia-jobs/{id}/training-proposals', 'TrainingProposalController@index');
        $router->get('maia-jobs/{id}/annotation-candidates', 'AnnotationCandidateController@index');

        $router->get('maia-annotations/{id}/file', 'MaiaAnnotationController@showFile');

        $router->resource('maia-annotations', 'MaiaAnnotationController', [
            'only' => ['update'],
            'parameters' => ['maia-annotations' => 'id'],
        ]);
});
