<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $uuid
 * @property string $code
 * @property string $name
 * @property null|string $description
 * @property boolean $status
 * @property string $deleted_at
 * @property string $updated_at
 * @property string $created_at
*/
class Category extends Model implements Auditable
{
    use Searchable;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;


    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'status',
        'deleted_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status'=> 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function product():HasMany
    {
        return $this->hasMany(Product::class);
    }


    /**
     * Para la busqueda de los datos
     * @return array
     */
    public function toSearchableArray():array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

}
