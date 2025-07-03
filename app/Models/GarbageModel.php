<?php

namespace App\Models;
use CodeIgniter\Model;

class GarbageModel extends Model
{
    protected $table = 'garbage_entered';
    protected $allowedFields = ['hh_id', 'hazardous', 'recyclable', 'residual', 'biodegradable', 'status'];
    protected $returnType = 'object';
}

?>
