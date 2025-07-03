<?php

namespace App\Models;
use CodeIgniter\Model;

class AlivestockModel extends Model
{
    protected $table = 'alivestock_entered';
    protected $allowedFields = ['hh_id', 'category_id', 'qty', 'status'];
    protected $returnType = 'object';
}

?>
