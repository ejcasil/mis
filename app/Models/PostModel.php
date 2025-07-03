<?php

namespace App\Models;
use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'tblposts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 
        'description', 
        'category_id',
        'img_path',
        'status',
        'brgy_id',
        'created_on',
        'updated_on',
    ];
    protected $returnType = 'object';

}

?>
