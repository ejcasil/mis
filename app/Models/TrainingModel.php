<?php

namespace App\Models;
use CodeIgniter\Model;

class TrainingModel extends Model
{
    protected $table = 'training_entered';
    protected $allowedFields = ['res_id', 'category_id', 'status'];
    protected $returnType = 'object';
}

?>
