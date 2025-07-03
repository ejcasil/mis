<?php

namespace App\Models;
use CodeIgniter\Model;

class HAppliancesModel extends Model
{
    protected $table = 'h_appliances_entered';
    protected $allowedFields = ['hh_id', 'category_id', 'qty', 'status'];
    protected $returnType = 'object';
}

?>
