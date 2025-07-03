<?php

namespace App\Models;
use CodeIgniter\Model;

class SincomeModel extends Model
{
    protected $table = 'sincome_entered';
    protected $allowedFields = ['res_id', 'category_id', 'status'];
    protected $returnType = 'object';
}

?>
