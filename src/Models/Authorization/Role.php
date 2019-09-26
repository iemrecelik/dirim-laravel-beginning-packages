<?php

namespace App\Models\Authorization;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'rol_id';

    protected $fillable = [
        'rol_name',
        'rol_raw_name',
    ];

    public function permissions()
    {
        return $this->belongsToMany(
            'App\Models\Authorization\Permission',
            'roles_permissions',
            'rol_id',
            'perm_id'
        );
    }
}
