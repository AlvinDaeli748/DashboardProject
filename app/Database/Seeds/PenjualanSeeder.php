<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    public function run()
    {
        $prov = ['aceh', 'sumut', 'sumbar', 'riau', 'kepri', 'jambi', 'bengkulu', 'sumsel', 'babel', 'lampung'];
        $jenis = ['Outlet', 'Sales'];

        for($i = 0; $i < 500; $i++){
            $data = [
                'provinsi' => $prov[array_rand($prov)],
                'jenis' => $jenis[array_rand($jenis)],
                'total_penjualan' => mt_rand(0, 250),
                'tgl_penjualan' => $this->generateRandomDatetime(),
            ];
            $this->db->table('penjualan')->insertBatch($data);
        }
    }

    private function generateRandomDatetime()
    {
        $startTimestamp = strtotime('2024-06-01 00:00:00');
        $endTimestamp = strtotime('2024-07-31 23:59:59');

        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);

        return date('Y-m-d H:i:s', $randomTimestamp);
    }
}
