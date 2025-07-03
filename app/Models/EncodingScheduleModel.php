<?php

namespace App\Models;
use CodeIgniter\Model;

class EncodingScheduleModel extends Model
{
    protected $table = 'encoding_schedule';
    protected $primaryKey = 'id';
    protected $allowedFields = ['start_date', 'end_date'];
    protected $returnType = 'object';

}

?>
