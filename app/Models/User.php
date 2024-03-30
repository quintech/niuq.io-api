<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Uuid;
    use SoftDeletes;

    protected $table = 'users';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'email',
        'account',
        'password',
        'owner_name',
        'google_user_uuid',
        'user_uuid',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }    
    public function google_user(){
        return $this->belongsTo(GoogleUser::class,'google_user_uuid','uuid');
    }
    public function transaction(){
        return $this->hasMany(Transaction::class,'user_uuid','uuid');
    }
}
