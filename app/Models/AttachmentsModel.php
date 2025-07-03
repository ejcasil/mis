<?php

namespace App\Models;
use CodeIgniter\Model;

class AttachmentsModel extends Model
{
    protected $table = 'attachments';
    protected $allowedFields = ['res_id', 'category_id', 'filename', 'status'];
    protected $returnType = 'object';
}

?>
