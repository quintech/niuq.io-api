<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdFontesMedia extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $table = 'ad_fontes_media';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'source',
        'domain_url',
        'bias',
        'reliability',
        'bias_label',
        'reliability_label',
        'media_type',
    ];
}
