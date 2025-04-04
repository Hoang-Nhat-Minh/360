<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'locations';

    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    public function nextLocation()
    {
        return $this->belongsTo(Location::class, 'next_location_id');
    }

    /**
     * Get the sun associated with the location.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function sun()
    {
        return $this->belongsTo(Sun::class, 'sun');
    }

    /**
     * Get the paronama associated with the location.
     */
    public function paronama()
    {
        return $this->belongsTo(Paronama::class, 'paronama_id');
    }

    /**
     * Get the hotlinks for the location.
     */
    public function hotlinks()
    {
        return $this->hasMany(Hotlink::class, 'location_id');
    }
    public function hotlinksSpecial()
    {
        return $this->hasMany(HotlinksSpecial::class, 'location_id');
    }

    public function destinationHotlinks()
    {
        return $this->hasMany(Hotlink::class, 'link_to_location_id');
    }

    public function getNameAttribute()
    {
        $name = json_decode($this->attributes['name'], true);
        return is_array($name) ? ($name[app()->getLocale()] ?? $name['vi'] ?? '') : '';
    }


    public function getDescriptionAttribute()
    {
        $description = json_decode($this->attributes['description'] ?? '[]', true); // Decode the JSON to an array.
        return is_array($description) ? ($description[app()->getLocale()] ?? $description['vi'] ?? '') : '';
    }

    public function getRawNameAttribute()
    {
        $name = json_decode($this->attributes['name'] ?? '[]', true);
        return is_array($name) ? $name : [];
    }

    // This method returns the raw JSON-decoded description as an array
    public function getRawDescriptionAttribute()
    {
        $description = json_decode($this->attributes['description'] ?? '[]', true);
        return is_array($description) ? $description : [];
    }
}
