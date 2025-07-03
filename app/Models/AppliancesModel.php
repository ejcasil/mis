<?php

namespace App\Models;
use CodeIgniter\Model;

class AppliancesModel extends Model
{
    protected $table = 'appliances_entered';
    protected $allowedFields = ['res_id', 'category_id', 'qty', 'status'];
    protected $returnType = 'object';
}

?>
