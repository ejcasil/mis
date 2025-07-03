<?php

namespace App\Models;
use CodeIgniter\Model;

class VehicleModel extends Model
{
    protected $table = 'vehicle_entered';
    protected $allowedFields = ['res_id', 'category_id', 'qty', 'status'];
    protected $returnType = 'object';
}

?>
