<?php namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = 'tb_logger';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['log'];
}