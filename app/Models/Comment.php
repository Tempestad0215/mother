<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string $uuid
 * @property string $content
 * @property int $commentable_id
 * @property string $commentable_type
 * @property string $created_at
 * @property string $updated_at
 * @property Date $deleted_at
 */
class Comment extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;


    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        'content'
    ];

    //Formatear los datos

    /**
     * convertir la iniciar a mayuscula
     * @return Attribute
     */
    protected function content():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
        );
    }

    /**
     * @return Attribute
     */
    protected function createdAt():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s')
        );
    }


    /**
     * Relacion polimorfica
     * @return MorphTo
     */
    public function commentable():morphTo
    {
        return $this->morphTo();
    }


}
