<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credits extends Model
{
    use HasFactory, SoftDeletes;
    use HasUuids;
    use HasFactory;

    /*
     * Para la llave primaria
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /*
     * Para guardar los datos
     */
    protected $guarded = [];


    public function creditable():MorphTo
    {
        return $this->morphTo();
    }
}
