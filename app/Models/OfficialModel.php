<?php

namespace App\Models;
use CodeIgniter\Model;

class OfficialModel extends Model
{
    protected $table = 'tblofficial';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'lname',
        'fname',
        'mname',
        'suffix',
        'fullname',
        'bday',
        'age',
        'email',
        'cp',
        'img_path',
        'position_id',
        'term',
        'brgy_id',
        'created_on',
        'updated_on'
    ];
    protected $returnType = 'object';

}

?>
