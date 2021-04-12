<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataEntry extends Model
{
    protected $fillable = [ 'key_name', 'value_name', 'store_timestamp'];
    protected $table = 'entries';

}
