<?php

namespace App\Models;
use CodeIgniter\Model;

class AmachineriesModel extends Model
{
    protected $table = 'amachineries_entered';
    protected $allowedFields = ['hh_id', 'category_id', 'qty', 'status'];
    protected $returnType = 'object';
}

?>
