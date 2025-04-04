<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paronama extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'paronamas';

  protected $guarded = [];

  /**
   * Get the location that belongs to the paronama.
   */
  public function location()
  {
    return $this->hasOne(Location::class, 'paronama_id');
  }
}
