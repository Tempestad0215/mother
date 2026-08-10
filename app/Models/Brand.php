<?php

namespace App\Models;

use App\Enums\ModelStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Spatie\Activitylog\Models\Concerns\LogsActivity;


/**
 *
 * @property Date $deleted_at
 * @property string $updated_at
 * @property string $created_at
 */

class Brand extends Model
{
    use softDeletes;
    use HasUuids;
    use LogsActivity;


    protected $table = 'brands';

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

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

    // Relaciones
    public function products():HasMany
    {
        return $this->hasMany(Product::class);
    }


}
