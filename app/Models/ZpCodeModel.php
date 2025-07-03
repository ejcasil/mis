<?php

namespace App\Models;
use CodeIgniter\Model;

class ZpCodeModel extends Model
{
    protected $table = 'zp_code';
    protected $primaryKey = 'id';
    protected $allowedFields = ['brgy_id', 'description', 'code', 'status'];
    protected $returnType = 'object';

}

?>
