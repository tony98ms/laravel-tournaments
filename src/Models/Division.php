<?php

namespace Tonystore\LaravelTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'status'];
}
