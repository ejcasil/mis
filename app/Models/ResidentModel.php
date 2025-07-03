<?php

namespace App\Models;
use CodeIgniter\Model;

class ResidentModel extends Model
{
    protected $table = 'tblresident';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'lname', 
        'fname', 
        'mname', 
        'suffix', 
        'fullname', 
        'bday', 
        'age', 
        'bplace', 
        'gender', 
        'cstatus_id', 
        'educ_id', 
        'course_id', 
        'rel_id', 
        'phealth_no', 
        'occ_id', 
        'm_income', 
        'cp', 
        'email', 
        'nstatus', 
        'relation_hh', 
        'relation_fh', 
        'fh_id', 
        'btype', 
        'height', 
        'weight', 
        'img_path', 
        'house_no', 
        'street', 
        'add_id',
        'isHead', 
        'status', 
        'household', 
        'resident_id', 
        'created_on', 
        'updated_on'
    ];
    protected $returnType = 'object';

}

?>
