<?php

namespace App\Models;
use CodeIgniter\Model;

class UploadCertificationModel extends Model
{
    protected $table = 'uploaded_certification';
    protected $primaryKey = 'id';
    protected $allowedFields = ['certificate_id', 'file_name', 'created_on'];
    protected $returnType = 'object';

}

?>
