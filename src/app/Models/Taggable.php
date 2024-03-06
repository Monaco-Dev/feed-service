<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{
    /**
     * The connection name for the model.
     * 
     * @var string
     */
    protected $connection = 'mysql';
}
