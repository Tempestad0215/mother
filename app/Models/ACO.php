<?php

namespace App\Models;

use App\Enums\AccountCountEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property integer id
 * @property string code
 * @property string name
 * @property AccountCountEnum type
 * @property Carbon deleted_at
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class ACO extends Model
{
    use SoftDeletes;
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
