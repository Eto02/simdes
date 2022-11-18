<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property MstrEmployee[] $mstrEmployee
 * @property ModelHasRoles[] $modelHasRoles
 */
class Users extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrEmployee()
    {
        return $this->belongsTo('App\Models\MstrEmployee', 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function mstrEmployee()
    // {
    //     return $this->hasMany('App\Models\MstrEmployee');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelHasRoles()
    {
        return $this->hasMany('App\Models\ModelHasRoles', 'model_id', 'id');
    }
}
