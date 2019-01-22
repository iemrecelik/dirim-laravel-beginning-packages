<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;
$modelInstance = $modelName.' '.$modelVar;
$lwMdPath = strtolower($modelPath);
$lwMdPath = str_replace('/', '.', $lwMdPath);

$langHtml = '';
if(isset($langModelName)){

    $langHtml = '
        '.$modelVar.'->updateMany([
            \'childDatas\' => $params[\'langs\'],
            \'childIDName\' => \''.$langFieldIDName.'\',
            \'childName\' => \''.lcfirst($langModelName).'\',
            \'childInstance\' => new '.$langModelName.'(),
        ]);
    ';
}

$unset = '';
$loadImg = '';
if (isset($imgModelPath)) {
    $unset = str_repeat(' ', 8)."unset(\$params['images']);\n";

    $loadImg = str_repeat(' ', 8)
            ."\$this->loadImages(\$request, {$modelVar});\n";
}


return '
    public function advancedUpdate('.$advancedReqRulesName.' $request, '.$modelInstance.')
    {
        $params = $request->all();
        '.$unset.'
        '.$modelVar.'->fill($params)->save();
        '.$langHtml.'
        '.$loadImg.'

        $msg = [\'succeed\' => __(\'messages.edit_success\')];
        return redirect()->route(\''.$lwMdPath.'.edit\', '.$modelVar.'->'.$fieldIDName.')
                        ->with($msg);
    }
';
};