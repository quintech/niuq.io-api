<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaBiasFactCheckJson extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $table = 'media_bias_fact_check_json';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'name',
        'b',
        'd',
        'f',
        'n',
        'r',
        'u',
        'p',
        'c',
        'a',
        'q',
    ];
}
