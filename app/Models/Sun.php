<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sun extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'suns';

  protected $guarded = [];

  /**
   * Get the location that belongs to the sun.
   */
  public function location()
  {
    return $this->hasOne(Location::class, 'sun');
  }
}
