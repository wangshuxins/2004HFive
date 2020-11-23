<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class XcxUser extends Model
{
    protected $table = 'xcx_user';

    protected  $primaryKey = 'id';

    public $timestamps = false;
}
