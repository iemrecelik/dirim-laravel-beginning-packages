<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;


if ($crudType !== 'modal') {
    $lwMdPath = strtolower($modelPath);
    $lwMdPath = "'{$lwMdPath}/edit',";

    $item = "['item' => {$modelVar}]";

    $resInfo = "
            $lwMdPath
            $item
    ";
} else {
    $resInfo = '';
}

$isAjaxResponseArgs = empty($resInfo) ? $modelVar : $modelVar.','.$resInfo;

$foreignKey = strtolower($modelName).'.'.$fieldIDName;

if(empty($langModelName)){

$funcHtml = '
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\\'.$modelUsePath.'  '.$modelVar.'
     * @return \Illuminate\Http\Response
     */
    public function edit('.$modelName.' '.$modelVar.')
    {
        return new IsAjaxResponse(
            '.$isAjaxResponseArgs.'
        );
    }
';

} else {

$funcHtml = '
    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        '.$modelVar.' = '.$modelName.'::with(\''.lcfirst($langModelName).'\')
        ->where(\''.$foreignKey.'\', $id)
        ->first();

        return new IsAjaxResponse(
            '.$isAjaxResponseArgs.'
        );
    }
';
}

return $funcHtml;
};