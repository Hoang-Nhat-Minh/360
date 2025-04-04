<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sort', 'status', 'parent_id', 'background_music'];

    protected $casts = [
        'name' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function location()
    {
        return $this->hasMany(Location::class, 'category_id');
    }

    public function getNameAttribute()
    {
        $name = json_decode($this->attributes['name'], true);
        return is_array($name) ? ($name[app()->getLocale()] ?? $name['vi'] ?? '') : '';
    }

    public function getRawNameAttribute()
    {
        $name = json_decode($this->attributes['name'] ?? '[]', true);
        return is_array($name) ? $name : [];
    }
}
