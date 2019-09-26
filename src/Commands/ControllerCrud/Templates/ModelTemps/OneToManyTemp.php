<?php
return function ($params) {

extract($params);

$modelPrefix = $modelPrefix ?? '';
$modelRepoUsePath =  isset($modelRepoNamespace)
                        ? "use App\ModelsRepository\\".$modelRepoNamespace.";\n"
                        : '';

$fspace = str_repeat(' ', 4);
$modelRepositoryUse =  isset($modelRepositoryName)
                        ? "\n{$fspace}use ".$modelRepositoryName.";\n"
                        : '';

$fillable = '';
foreach ($fields as $vfield) {
    $fillable .= "\n{$fspace}{$fspace}'{$vfield['name']}',";
}

$fillable = $fillable."\n{$fspace}";

$fillable = '
    protected $fillable = ['.$fillable.'];
';

$relations = '';
if ($imgModelPath) {
    $relations .= '
    /**
     * Get all of the post\'s images.
     */
    public function images()
    {
        return $this->morphMany(
            \'App\\Models'.$imgUse.'\',
            \'imgowner\'
        );
    }';
}

if ($langModelPath) {
    $relations .= '
    public function '.lcfirst($langModelName).'()
    {
        return $this->hasMany(
            \'App\\Models\\'.$langModelUsePath.'\',
            \''.$primaryKey.'\',
            \''.$primaryKey.'\'
        );
    }';
}

return '<?php

namespace App\Models'.$modelPrefix.';

use Illuminate\Database\Eloquent\Model;
'.$modelRepoUsePath.'
class '.$modelName.' extends Model
{'.$modelRepositoryUse.'
    protected $table = \''.$table.'\';

    protected $primaryKey = \''.$primaryKey.'\';
    '.rtrim($fillable).'
    '.$relations.'
}
';
};
