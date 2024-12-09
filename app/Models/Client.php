<?php

namespace App\Models;

use App\Enums\ClientDocumentEnum;
use App\Enums\ClientTypeEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string $code
 * @property string $name
 * @property string $phone
 * @property string $personal_id
 * @property string $email
 * @property ClientDocumentEnum $document
 * @property string $address
 * @property boolean $status
 * @property int $type
 * @property float $credit_limit
 * @property integer $credit_day
 * @property float $credit_available
 * @property float $credit_consumed
 * @property float $credit_expired
 * @property float $advance_amount
 * @property float $advance_date
 * @property float $advance_expire
 * @property float $advance_consumed
 * @property float $advance_available
 * @property Date $deleted_at
 */

class Client extends Model implements Auditable
{
    use Searchable;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'document',
        'personal_id',
        'phone',
        'email',
        'address',
        'status',
        'type'
    ];


    protected $casts = [
        'type' => ClientTypeEnum::class,
        'document' => ClientDocumentEnum::class,
        'status'=> 'boolean',
    ];


    /**
     * Para buscar los datos
     * @return array
     */
    #[SearchUsingPrefix(['id', 'email'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'document' => $this->document,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }



    /**
     * Relaciones
     */
    public function comment():MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }

    /**
     * Cliente
     * @return HasOne
     */
    public function sale():HasOne
    {
        return $this->hasOne(Sale::class);
    }


}
