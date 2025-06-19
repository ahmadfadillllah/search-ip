<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topology extends Model
{
    use HasFactory;
    protected $connection = 'dbms';
    protected $table = 'HIS_TOPOLOGY_NETWORK';

    protected $guarded = [];
    public $timestamps = false;

}
