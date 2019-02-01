<?php

namespace App\Models\Authorization;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $primaryKey = 'perm_id';

    protected $fillable = [
        'perm_name',
        'perm_raw_name',
    ];

    public function scopeFetchUserPermissons($query, $user_id)
    {
        $rlusJoin = ['rlus.rol_id', '=', 'pmrl.rol_id'];
        $userJoin = ['us.user_id', '=', 'rlus.user_id'];
        
        $query->from('permissions as perm')
        ->selectRaw('us.user_id, perm.perm_raw_name')
        ->join('roles_permissions as pmrl', 'pmrl.perm_id', '=', 'perm.perm_id')
        ->join(
            'roles_users as rlus',
            ...$rlusJoin
        )
        ->join(
            'users as us',
            ...$userJoin
        )
        ->whereRaw('us.user_id = :user_id', ['user_id' => $user_id])
        ;

        return $query;
    }

    public function roles()
    {
        return $this->belongsToMany(
            'App\Models\Authorization\Role',
            'roles_permissions',
            'perm_id',
            'rol_id'
        );
    }
}
