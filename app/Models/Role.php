<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role
{
    use HasUuids;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;


}
