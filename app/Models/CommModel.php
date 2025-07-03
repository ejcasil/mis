<?php

namespace App\Models;
use CodeIgniter\Model;

class CommModel extends Model
{
    protected $table = 'comm_entered';
    protected $allowedFields = ['hh_id', 'category_id', 'status'];
    protected $returnType = 'object';
}

?>
