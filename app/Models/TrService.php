<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $service_id
 * @property string $employee_id
 * @property string $service_type_id
 * @property string $nik
 * @property string $name
 * @property string $letter_number
 * @property string $serviced_by
 * @property string $notes
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 * @property MstrServiceType $mstrServiceType
 * @property MstrEmployee $mstrEmployee
 * @property TrServiceFile[] $trServiceFile
 */
class TrService extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tr_service';

    public $timestamps = false;

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'service_id';

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
    protected $fillable = ['employee_id', 'service_type_id', 'nik', 'name', 'letter_number', 'serviced_by', 'notes', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrServiceType()
    {
        return $this->belongsTo('App\Models\MstrServiceType', 'service_type_id', 'service_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mstrEmployee()
    {
        return $this->belongsTo('App\Models\MstrEmployee', 'employee_id', 'employee_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trServiceFile()
    {
        return $this->hasMany('App\Models\TrServiceFile', 'service_id', 'service_id');
    }
}
