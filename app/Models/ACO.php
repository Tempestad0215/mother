<?php

namespace App\Models;

use App\Enums\ACOEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property integer id
 * @property string code
 * @property string name
 * @property ACOEnum type
 * @property Carbon deleted_at
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class ACO extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * @var string
     */
    protected $table = 'account_cos';



    /**
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'type',
    ];
}
