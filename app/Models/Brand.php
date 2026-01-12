<?php

namespace App\Models;

use App\Enums\ModelStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;


/**
 *
 * @property Date $deleted_at
 * @property string $updated_at
 * @property string $created_at
 */

class Brand extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use softDeletes;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'description',
        'model_status',
    ];


    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'model_status' => ModelStatusEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }


}
