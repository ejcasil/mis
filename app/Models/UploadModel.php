<?php

namespace App\Models;
use CodeIgniter\Model;

class UploadModel extends Model
{
    protected $table = 'tbluploads';
    protected $primaryKey = 'id';
    protected $allowedFields = ['file_path','created_on'];
    protected $returnType = 'object';
}

?>
