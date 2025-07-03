<?php

namespace App\Models;
use CodeIgniter\Model;

class GprogramsModel extends Model
{
    protected $table = 'gprograms_entered';
    protected $allowedFields = ['res_id', 'category_id', 'date_acquired', 'status'];
    protected $returnType = 'object';
}

?>
