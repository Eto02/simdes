<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $file_type_id
 * @property string $employee_id
 * @property string $name
 * @property string $description
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 * @property MstrEmployee $mstrEmployee
 * @property TrServiceFile[] $trServiceFile
 */
class MstrFileType extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mstr_file_type';

    public $timestamps = false;

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'file_type_id';

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
    protected $fillable = ['employee_id', 'name', 'description', 'created_by', 'created_at', 'updated_by', 'updated_at'];

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
        return $this->hasMany('App\Models\TrServiceFile', 'file_type_id', 'file_type_id');
    }
}
