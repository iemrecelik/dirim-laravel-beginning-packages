<?php
return function($params){
extract($params);

$lwModelName = strtolower($modelName);


$ppImgFilt = '';
if (isset($imgModelPath)) {
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
  <'.$lwModelName.'-component
    :pproutes="{ 
      index: \'{{ route(\'admin.'.$lwModelName.'.index\') }}\', 
      dataList: \'{{ route(\'admin.'.$lwModelName.'.dataList\') }}\', 
    }"
    :pperrors="{{ count($errors) > 0?$errors:\'{}\' }}"'.$ppImgFilt.'
  >
  </'.$lwModelName.'-component>
@endsection
';
};