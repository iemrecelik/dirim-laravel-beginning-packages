<?php
return function($params){

extract($params);
$modelName = strtolower($modelName);
$routes = '';

if($crudType !== 'modal'){
    $routes .= '
        Route::put(
            \''.$modelName.'/{'.$modelVarName.'}/advanced-update\',
            \''.$controllerName.'@advancedUpdate\'
        )
        ->name(\''.$modelName.'.advancedUpdate\')
        ->where([\''.$modelVarName.'\' => \'[0-9]+\']);

        Route::post(
            \''.$modelName.'/advanced-store\',
            \''.$controllerName.'@advancedStore\'
        )
        ->name(\''.$modelName.'.advancedStore\');
    ';

    $resource = 'Route::resource(\''.$modelName.'\', \''.$controllerName.'\');';
} else {
    $resource = 'Route::resource(\''.$modelName.'\', \''.$controllerName.'\')
            ->except([
                \'create\', \'edit\', \'show\'
            ]);';
}

if($imgModelName){
    $routes .= '
        Route::get(
            \''.$modelName.'/{'.$modelVarName.'}/edit-images\',
            \''.$controllerName.'@getImages\'
        )
        ->name(\''.$modelName.'.editImages\')
        ->where([\''.$modelVarName.'\' => \'[0-9]+\']);

        Route::post(
            \''.$modelName.'/{'.$modelVarName.'}/upload-images\',
            \''.$controllerName.'@updateImages\'
        )
        ->name(\''.$modelName.'.updateImages\')
        ->where([\''.$modelVarName.'\' => \'[0-9]+\']);
    ';
}

if(isset($langModelName)){
    $routes .= '
        Route::get(
            \''.$modelName.'/lang/list\',
            \''.$controllerName.'@getLangs\'
        )
        ->name(\''.$modelName.'.langList\');
    ';
}

return '
        '.$resource.'
        Route::post(
            \''.$modelName.'/data-list\',
            \''.$controllerName.'@getDataList\'
        )
        ->name(\''.$modelName.'.dataList\');
        '.$routes.'
';
};