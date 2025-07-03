<?php

namespace App\Models;
use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table = 'tblbanner';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 
        'description', 
        'img_path',
        'status',
        'brgy_id',
        'created_on',
        'updated_on',
    ];
    protected $returnType = 'object';

}

?>
