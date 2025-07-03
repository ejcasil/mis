<?php

namespace App\Models;
use CodeIgniter\Model;

class SanitationModel extends Model
{
    protected $table = 'sanitation_entered';
    protected $allowedFields = ['hh_id', 'category_id', 'status'];
    protected $returnType = 'object';
}

?>
