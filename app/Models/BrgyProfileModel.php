<?php

namespace App\Models;
use CodeIgniter\Model;

class BrgyProfileModel extends Model
{
    protected $table = 'tbl_brgy_profile';
    protected $primaryKey = 'id';
    protected $allowedFields = ['logo', 'brgy_id', 'municipality', 'province', 'region', 'official_id'];
    protected $returnType = 'object';

}

?>
