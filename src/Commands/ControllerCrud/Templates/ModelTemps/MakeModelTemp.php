<?php
return function ($params) {

extract($params);

$tableName = strtolower(snake_case($modelName));

$modelPrefix = $modelPrefix ?? '';
$modelRepoUsePath =  isset($modelRepoNamespace)
                        ? "use App\ModelsRepository\\".$modelRepoNamespace.";\n"
                        : '';

$fspace = str_repeat(' ', 4);
$modelRepositoryUse =  isset($modelRepositoryName)
                        ? "\n{$fspace}use ".$modelRepositoryName.";\n"
                        : '';
                        
$content = $content ?? '//';

$fillable = '';
foreach ($fields as $vfield) {
    $fillable .= "\n{$fspace}{$fspace}'{$vfield['name']}',";
}

$fillable = $fillable."\n{$fspace}";

$fillable = '
    protected $fillable = ['.$fillable.'];
';

return '<?php

namespace App\Models'.$modelPrefix.';

use Illuminate\Database\Eloquent\Model;
'.$modelRepoUsePath.'
class '.$modelName.' extends Model
{'.$modelRepositoryUse.'
    protected $table = \''.$tableName.'\';

    protected $primaryKey = \''.$primaryKey.'\';
    '.$fillable.'
    '.$content.'
}
';
};
