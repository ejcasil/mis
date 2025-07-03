<?php

namespace App\Models;
use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'tblcategory';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category',
        'description',
        'status'
    ];
    protected $returnType = 'object';
}

?>
