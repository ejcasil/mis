<?php

namespace App\Models;
use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table = 'tblcertificate';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'res_id', 
        'business_name',
        'purpose', 
        'document_type',
        'ctc_no',
        'ctc_date',
        'control_no',
        'document_fee',
        'amount_paid',
        'payment_status',
        'payment_method',
        'or_no',
        'or_date',
        'application_status',
        'status',
        'brgy_id',
        'created_on',
        'updated_on'
    ];
    protected $returnType = 'object';

}

?>
