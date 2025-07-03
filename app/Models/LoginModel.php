<?php

namespace App\Models;
use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'tbluser';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username',
        'password',
        'lname',
        'fname',
        'mname',
        'suffix',
        'fullname',
        'gender',
        'bday',
        'age',
        'cstatus_id',
        'email',
        'cp',
        'role',
        'brgy_id',
        'res_id',
        'status',
        'image',
        'created_on',
        'updated_on'
    ];
    protected $returnType = 'object';
}

?>
