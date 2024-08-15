<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'penjualan'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'provinsi', 'jenis', 'total_penjualan', 'tgl_penjualan'];

}
