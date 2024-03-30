<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $table = 'transaction';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'news_uuid',
        'google_user_uuid',
        'user_uuid',
        'transaction_hex',
        'niuq_erc20_quantity',
    ];

    // Merge Google_user
    public function getGoogleUserData() : BelongsTo{
        return $this->belongsTo('App\Models\GoogleUser','google_user_uuid','uuid');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_uuid','uuid');
    }
}
