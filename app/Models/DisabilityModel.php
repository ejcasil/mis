<?php

namespace App\Models;
use CodeIgniter\Model;

class DisabilityModel extends Model
{
    protected $table = 'disability_entered';
    protected $allowedFields = ['res_id', 'category_id', 'status'];
    protected $returnType = 'object';
}

?>
