<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $table = 'news';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'title',
        'url',
        'count',
        'ad_fontes_media_uuid',
        'media_bias_fact_check_uuid',
        'fact_mata_context',
        'reliability',
        'factual_reporting',
        'fakeness',
        'author',
        'description',
        'addDate',
        'image',
        'from'
    ];

    // Merge the first API query
    public function getAdFontesMedia() : BelongsTo{
        return $this->belongsTo('App\Models\AdFontesMedia','ad_fontes_media_uuid','uuid');

    }
    // Merge the second API query
    public function getMediaBiasFactCheck() : BelongsTo{
        return $this->belongsTo('App\Models\MediaBiasFactCheck','media_bias_fact_check_uuid','uuid');

    }

}
