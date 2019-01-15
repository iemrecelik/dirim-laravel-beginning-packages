<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;
$lwMdPath = strtolower($modelPath);
$lwMdPath = str_replace('/', '.', $lwMdPath);


if(isset($langModelName)){

    $langHtml = '
        $langParams = array_map(function ($lang) {
            return new '.$langModelName.'($lang);
        }, $params[\'langs\']);

        '.$modelVar.'->'.lcfirst($langModelName).'()->saveMany($langParams);
    ';
}

$unset = '';
$loadImg = '';
if (isset($imgModelPath)) {
    $unset = str_repeat(' ', 8)."unset(\$params[\'images\']);\n";

    $loadImg = str_repeat(' ', 8)
            ."\$this->loadImages(\$request, {$modelVar});\n";
}

return '
    public function advancedStore('.$advancedReqRulesName.' $request)
    {
        $params = $request->all();
        '.$unset.'
        '.$modelVar.' = '.$modelName.'::create($params);
        '.$langHtml.'
        '.$loadImg.'

        $msg = [\'succeed\' => __(\'messages.edit_success\')];
        return redirect()->route(\''.$lwMdPath.'.create\')
                        ->with($msg);
    }
';
};