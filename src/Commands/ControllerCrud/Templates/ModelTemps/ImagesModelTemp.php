<?php
return function ($params) {

extract($params);

return '<?php

namespace App\Models'.$namespace.';

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = \'images\';

    protected $primaryKey = \'img_id\';

    protected $fillable = [
        \'img_path\',
    ];

    /**
     * Get all of the owning owner models.
     */
    public function imgowner()
    {
        return $this->morphTo();
    }
}
';
};
