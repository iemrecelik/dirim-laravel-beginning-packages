<?php
return function($params){
extract($params);

$lwModelName = strtolower($modelName);

return '
@extends(config(\''.$baseTmpPaths.'\'))
@section(\'contents\')

  <'.$lwModelName.'-create-advanced-component
    :pproutes="{ 
      index: \'{{ route(\'admin.'.$lwModelName.'.index\') }}\',
      advancedStore: \'{{ route(\'admin.'.$lwModelName.'.advancedStore\') }}\',
    }"
    :pperrors="{{ count($errors) > 0?$errors:\'{}\' }}"
    :ppsuccess="\'{{ session(\'succeed\') ?? \'\' }}\'"
    :ppimgfilters="{ 
      '.$modelVarName.'ImagesFilt: {{ 
        json_encode(config(\'imageFilters.filter.'.$modelVarName.'ImagesFilt\')) 
      }}
    }"
    :ppoldinput="\'{{ json_encode(session()->getOldInput()) }}\'"
  >
  </'.$lwModelName.'-create-advanced-component>
@endsection
';
};