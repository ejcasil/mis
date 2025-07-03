<?php

namespace App\Models;
use CodeIgniter\Model;

class MapModel extends Model
{
    protected $table = 'map_source';
    protected $primaryKey = 'id';
    protected $allowedFields = ['filename', 'created_on'];
    protected $returnType = 'object';
}

?>
