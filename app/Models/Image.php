<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Image extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;


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
