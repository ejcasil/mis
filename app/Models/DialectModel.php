<?php

namespace App\Models;
use CodeIgniter\Model;

class DialectModel extends Model
{
    protected $table = 'dialect_entered';
    protected $allowedFields = ['res_id', 'category_id', 'status'];
    protected $returnType = 'object';
}

?>
