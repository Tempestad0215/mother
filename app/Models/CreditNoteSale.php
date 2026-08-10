<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CreditNoteSale extends Model
{
    use SoftDeletes;


    protected $table = 'credit_note_sale';


}
