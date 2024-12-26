<?php

namespace App\Models;

use App\Enums\TypePaymentEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string uuid
 * @property string code
 * @property string|null contact
 * @property string company_name
 * @property string|null phone
 * @property string|null email
 * @property integer payment_day
 * @property boolean status
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
*/

class Supplier extends Model implements Auditable
{
    use Searchable;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'contact',
        'company_name',
        'phone',
        'email',
        'status',
        'payment_day'
    ];


    protected $casts = [
        'status' => 'boolean',
        'type_payment' => TypePaymentEnum::class,
    ];


    // Rleaciones
    public function product():HasMany
    {
        return $this->hasMany(Product::class);
    }

    /*
     * Para buscar los datos
     */
    public function toSearchableArray():array
    {
        return [
            'company_name' => $this->company_name,
            'contact' => $this->contact,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }


}
