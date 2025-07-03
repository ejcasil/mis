<?php

namespace App\Models;
use CodeIgniter\Model;

class PowerModel extends Model
{
    protected $table = 'power_entered';
    protected $allowedFields = ['hh_id', 'category_id', 'ave_per_mo', 'status'];
    protected $returnType = 'object';
}

?>
