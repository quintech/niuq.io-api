<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoogleUser extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $table = 'google_user';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'google_id',
        'owner_name',
        'user_img_url',
        'email',
        'wallet_account',
        'gender',
        'birthday',
        'country',
        'state',
        'zip',
        'address',
        'registered',
    ];
}
