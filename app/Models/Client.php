<?php

namespace App\Models;

use App\Enums\ClientDocumentEnum;
use App\Enums\ClientTypeEnum;
use App\Enums\ClientTypePriceEnum;
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
 * @property string $uuid;
 * @property string $name
 * @property string $phone
 * @property string $personal_id
 * @property string $email
 * @property ClientDocumentEnum $document
 * @property string $address
 * @property boolean $status
 * @property float $limit
 * @property integer $due_date
 * @property ClientTypeEnum $type
 * @property float $late_fee_interest
 * @property float $balance
 * @property float $consumed
 * @property ClientTypePriceEnum $type_price
 * @property boolean $receive_email
 * @property Date $deleted_at
 * @property Date $created_at
 * @property Date $updated_at
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


    /**
     * Para guardar los datos
     * @var array
     */
    protected $guarded = [];

//    protected $fillable = [
//        'name',
//        'document',
//        'personal_id',
//        'phone',
//        'email',
//        'address',
//        'status',
//        'type'
//    ];


    protected $casts = [
        'type' => ClientTypeEnum::class,
        'document' => ClientDocumentEnum::class,
        'type_price' => ClientTypePriceEnum::class,
        'status'=> 'boolean',
    ];


    /**
     * Para buscar los datos
     * @return array
     */
    #[SearchUsingPrefix([ 'email'])]
    public function toSearchableArray(): array
    {
        return [
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


    public function image():MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    public function credit():MorphOne
    {
        return $this->morphOne(Credits::class, 'creditable');
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
