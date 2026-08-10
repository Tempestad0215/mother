<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class CreditNoteSale extends Model
{
    use SoftDeletes;
    use LogsActivity;


    protected $table = 'credit_note_sale';


}
