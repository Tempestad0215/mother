<?php

namespace App\Models;

use App\Helpers\CodeHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

/**
 * @property integer id
 * @property string code
 * @property string name
 * @property string prefix
 * @property null|string description
 * @property boolean status
 * @property Carbon deleted_at
 * @property Carbon updated_at
 * @property Carbon created_at
*/
class Category extends Model
{
    use Searchable;
    use HasFactory;
    use softDeletes;
    use HasUuids;
    use LogsActivity;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
        'prefix',
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
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo los codigos
        static::creating(function ($model) {
            $model->code = self::generateCode($model);

        });
    }


    /**
     * @return string
     */
    // funcion para generar el codigo
    private static function generateCode(Model $model):string
    {
        // Obtener el ultimo registros
        $total = self::withTrashed()->count() + 1;

        return CodeHelper::generateCode($model, $total);

    }

}
