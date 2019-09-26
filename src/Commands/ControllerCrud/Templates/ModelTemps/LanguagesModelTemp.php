<?php
return function ($params) {

extract($params);

return '<?php

namespace App\Models'.$namespace.';

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
    protected $table = \'languages\';

    protected $primaryKey = \'lang_id\';
    
    protected $fillable = [
        \'lang_name\', \'lang_short_name\',
        \'lang_default\'
    ];
}
';
};
