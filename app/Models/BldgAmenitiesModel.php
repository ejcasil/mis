<?php

namespace App\Models;
use CodeIgniter\Model;

class BldgAmenitiesModel extends Model
{
    protected $table = 'bldg_amenities_entered';
    protected $allowedFields = ['hh_id', 'category_id', 'status'];
    protected $returnType = 'object';
}

?>
