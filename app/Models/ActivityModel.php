<?php

namespace App\Models;
use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table = 'tblactivity';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'task_done', 'created_on'];
    protected $returnType = 'object';

}

?>
