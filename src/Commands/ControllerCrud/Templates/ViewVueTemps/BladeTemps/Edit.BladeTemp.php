<?php
return function($params){
extract($params);

$lwModelName = strtolower($modelName);

$ppImg = '';
$ppImgFilt = '';
if (isset($imgModelPath)) {
  $ppImg = '
    :ppimgs="{{ $item->images }}"
  ';
  $ppImgFilt = '
    :ppimgfilters="{ 
      '.$modelVarName.'ImagesFilt: {{ 
        json_encode(config(\'imageFilters.filter.'.$modelVarName.'ImagesFilt\')) 
      }}
    }"
  ';
}

return '
@extends(config(\''.$baseTmpPaths.'\'))
@section(\'contents\')

  <'.$lwModelName.'-edit-advanced-component
    :pproutes="{ 
      index: \'{{ route(\'admin.'.$lwModelName.'.index\') }}\', 
      dataList: \'{{ route(\'admin.'.$lwModelName.'.dataList\') }}\',
    }"
    :ppitem="{{ $item }}"'.$ppImg.'
    :pperrors="{{ count($errors) > 0?$errors:\'{}\' }}"
    :ppsuccess="\'{{ session(\'succeed\') ?? \'\' }}\'"'.$ppImgFilt.'
    :ppoldinput="\'{{ json_encode(session()->getOldInput()) }}\'"
  >
  </'.$lwModelName.'-edit-advanced-component>
@endsection
';
};