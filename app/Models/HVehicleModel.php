<?php

namespace App\Models;
use CodeIgniter\Model;

class HVehicleModel extends Model
{
    protected $table = 'h_vehicle_entered';
    protected $allowedFields = ['hh_id', 'category_id', 'qty', 'status'];
    protected $returnType = 'object';
}

?>
