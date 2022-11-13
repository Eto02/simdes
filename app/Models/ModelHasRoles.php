<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $role_id
 * @property string $model_type
 * @property integer $model_id
 * @property Roles $roles
 * @property Users $users
 */
class ModelHasRoles extends Model
{
    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roles()
    {
        return $this->belongsTo('App\Models\Roles', 'role_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\Models\Users', 'id', 'model_id');
    }
}
