<?php

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'stocks'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'provinsi', 'jenis', 'total_penjualan', 'tgl_penjualan'];

}