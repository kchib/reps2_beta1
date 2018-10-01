<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReplayMap extends Model
{
    /**
     * Using table name
     *
     * @var string
     */
    protected $table = 'replay_maps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'url'];


}
