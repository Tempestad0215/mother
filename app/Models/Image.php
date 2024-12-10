<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    use HasUuids;


    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * @var array
     */
    protected $guarded = [];


    /**
     * Para la relacion de las imagenes
     * @return MorphTo
     */
    public function imageable():MorphTo
    {
        return $this->morphTo();
    }
}
