<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaBiasFactCheck extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $table = 'media_bias_fact_check';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'web_name',
        'url',
        'context',
    ];
}
