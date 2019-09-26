<?php
return function ($params) {

extract($params);

$table = $table.'_lang';

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
$fields[] = ['name' => $fieldDependsOnLang];
foreach ($fields as $vfield) {
    $fillable .= "\n{$fspace}{$fspace}'{$vfield['name']}',";
}

$fillable = $fillable."\n{$fspace}";

$fillable = '
    protected $fillable = ['.$fillable.'];
';

$relations = '';

$relations .='
    public function languages()
    {
        return $this->belongsTo(
            \'App\Models'.$langUse.'\',
            \''.$fieldDependsOnLang.'\',
            \'lang_short_name\'
        );
    }';


$relations .= '
    public function '.lcfirst($relModelName).'()
    {
        return $this->belongsTo(
            \'App\\Models\\'.$relModelUsePath.'\',
            \''.$primaryKey.'\',
            \''.$primaryKey.'\'
        );
    }';


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
