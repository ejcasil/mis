<?php

namespace App\Models;
use CodeIgniter\Model;

class ComorbiditiesModel extends Model
{
    protected $table = 'comorbidities_entered';
    protected $allowedFields = ['res_id', 'category_id', 'status'];
    protected $returnType = 'object';
}

?>
