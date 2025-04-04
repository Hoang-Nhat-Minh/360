<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotlinksSpecial extends Model
{
    use HasFactory;

    protected $table = 'hotlinks_special';
    protected $guarded = [];

    protected $casts = [
        'info_content' => 'array',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function getInfoContentAttribute()
    {
        $info_content = json_decode($this->attributes['info_content'], true);
        return is_array($info_content) ? ($info_content[app()->getLocale()] ?? $info_content['vi'] ?? '') : '';
    }
}
