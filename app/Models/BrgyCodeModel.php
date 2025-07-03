<?php

namespace App\Models;
use CodeIgniter\Model;

class BrgyCodeModel extends Model
{
    protected $table = 'brgy_code';
    protected $primaryKey = 'id';
    protected $allowedFields = ['brgy_name', 'code', 'status'];
    protected $returnType = 'object';

}

?>
