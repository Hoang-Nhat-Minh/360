<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotlink extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'hotlinks';

  protected $guarded = [];

  /**
   * Get the location that owns the hotlink.
   */
  public function location()
  {
    return $this->belongsTo(Location::class, 'location_id');
  }

  /**
   * Get the next location linked by this hotlink.
   */
  public function nextLocation()
  {
    return $this->belongsTo(Location::class, 'link_to_location_id');
  }
}
