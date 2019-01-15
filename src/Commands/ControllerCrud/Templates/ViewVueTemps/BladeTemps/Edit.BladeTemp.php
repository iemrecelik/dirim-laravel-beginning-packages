<?php
return function($params){
extract($params);

$lwModelName = strtolower($modelName);

return '
@extends(config(\''.$baseTmpPaths.'\'))
@section(\'contents\')

  <'.$lwModelName.'-edit-advanced-component
    :pproutes="{ 
      index: \'{{ route(\'admin.'.$lwModelName.'.index\') }}\', 
      dataList: \'{{ route(\'admin.'.$lwModelName.'.dataList\') }}\',
    }"
    :ppitem="{{ $item }}"
    :ppimgs="{{ $item->images }}"
    :pperrors="{{ count($errors) > 0?$errors:\'{}\' }}"
    :ppsuccess="\'{{ session(\'succeed\') ?? \'\' }}\'"
    :ppimgfilters="{ 
      '.$modelVarName.'ImagesFilt: {{ 
        json_encode(config(\'imageFilters.filter.'.$modelVarName.'ImagesFilt\')) 
      }}
    }"
    :ppoldinput="\'{{ json_encode(session()->getOldInput()) }}\'"
  >
  </'.$lwModelName.'-edit-advanced-component>
@endsection
';
};