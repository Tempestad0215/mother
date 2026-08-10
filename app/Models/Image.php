<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Image extends Model
{
    use SoftDeletes;
    use LogsActivity;


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
