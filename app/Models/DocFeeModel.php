<?php

namespace App\Models;
use CodeIgniter\Model;

class DocFeeModel extends Model
{
    protected $table = 'tbldocument_fee';
    protected $primaryKey = 'id';
    protected $allowedFields = ['document_type', 'fee', 'brgy_id'];
    protected $returnType = 'object';

}

?>
