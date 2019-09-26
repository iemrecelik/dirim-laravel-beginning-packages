<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;

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

return '
    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\\'.$reqRulesUsePath.'  $request
     * @param  \App\Models\\'.$modelUsePath.'  '.$modelVar.'
     * @return \Illuminate\Http\Response
     */
    public function update('.$reqRulesName.' $request, '.$modelName.' '.$modelVar.')
    {
        $params = $request->all();

        '.$modelVar.'->fill($params)->save();
        '.$langHtml.'
        return [
            \'updatedItem\' => '.$modelVar.',
            \'succeed\' => __(\'messages.edit_success\')
        ];
    }
';
};
