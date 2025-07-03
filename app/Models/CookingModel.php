<?php

namespace App\Models;
use CodeIgniter\Model;

class CookingModel extends Model
{
    protected $table = 'cooking_entered';
    protected $allowedFields = ['hh_id', 'category_id', 'status'];
    protected $returnType = 'object';
}

?>
