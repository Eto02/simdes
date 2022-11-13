<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $service_type_id
 * @property string $company_id
 * @property string $name
 * @property string $description
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 * @property MstrCompany $mstrCompany
 * @property TrService[] $trService
 */
class MstrServiceType extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mstr_service_type';

    public $timestamps = false;

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'service_type_id';

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
    protected $fillable = ['company_id', 'name', 'description', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrCompany()
    {
        return $this->belongsTo('App\Models\MstrCompany', 'company_id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trService()
    {
        return $this->hasMany('App\Models\TrService', 'service_type_id', 'service_type_id');
    }
}
