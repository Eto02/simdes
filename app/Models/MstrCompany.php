<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $company_id
 * @property integer $user_id
 * @property string $name
 * @property string $address
 * @property string $phone_number
 * @property string $logo
 * @property string $login_background
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 * @property User $user
 * @property MstrLetterType[] $mstrLetterType
 * @property MstrServiceType[] $mstrServiceType
 * @property TrService[] $trService
 */
class MstrCompany extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mstr_company';

    public $timestamps = false;

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'company_id';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'address', 'phone_number', 'logo', 'login_background', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\Models\Users');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mstrLetterType()
    {
        return $this->hasMany('App\Models\MstrLetterType', 'company_id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mstrServiceType()
    {
        return $this->hasMany('App\Models\MstrServiceType', 'company_id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trService()
    {
        return $this->hasMany('App\Models\TrService', 'company_id', 'company_id');
    }
}
