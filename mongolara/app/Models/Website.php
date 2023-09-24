<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class Website extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'websites';

    protected  $fillable = ['guid', 'data'];

}
