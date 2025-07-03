<?php

namespace App\Models;
use CodeIgniter\Model;

class BldgInfoModel extends Model
{
    protected $table = 'bldg_info';
    protected $allowedFields = ['hh_id', 'bldg_type_id', 'construction_yr', 'yr_occupied', 'bldg_permit_no', 'lot_no', 'status'];
    protected $returnType = 'object';
}

?>
