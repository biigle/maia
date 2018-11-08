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
        $router->resource('volumes/{id}/maia', 'MaiaJobController', [
            'only' => ['store'],
            'parameters' => ['volumes' => 'id'],
        ]);

        $router->resource('maia', 'MaiaJobController', [
            'only' => ['destroy'],
            'parameters' => ['maia' => 'id'],
        ]);

        $router->resource('maia/{id}/training-proposals', 'TrainingProposalController', [
            'only' => ['index'],
            'parameters' => ['maia' => 'id'],
        ]);
});
