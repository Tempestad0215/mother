<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;



/**
 * @property string|null $contact
 * @property string $company_name
 * @property string|null $phone
 * @property string|null $email
 * @property boolean $status
*/

class Supplier extends Model
{
    use HasFactory;


    protected $fillable = [
        'contact',
        'company_name',
        'phone',
        'email',
        'status'
    ];


    protected $casts = [
        'status' => 'boolean'
    ];


    // Rleaciones
    public function product():HasMany
    {
        return $this->hasMany(Product::class);
    }

}
