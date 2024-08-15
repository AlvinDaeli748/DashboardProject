<?php

namespace App\Controllers;

use App\Models\PenjualanModel;

class Home extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) 
        {
            return redirect()->to('/login')->send();
        } else {
            $penjualanModel = new PenjualanModel();
            
            if(session()->get('role') == 'superuser')
            {
                $data['penjualan'] = $penjualanModel
                                    ->groupBy('DATE(tgl_penjualan)')
                                    ->paginate(10);

                $chart = $penjualanModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                                    ->groupBy('DATE(tgl_penjualan)')
                                    ->findAll();
            } else {
                $role = session()->get('role');
                $data['penjualan'] = $penjualanModel
                                ->where('provinsi', $role)
                                ->groupBy('DATE(tgl_penjualan)')
                                ->paginate(10);

                $chart = $penjualanModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                    ->where('provinsi', $role)
                    ->groupBy('DATE(tgl_penjualan)')
                    ->findAll();
            }
            $data['pager'] = $penjualanModel->pager;

            $juneData = [];
            $julyData = [];

            foreach ($chart as $row) {
                $month = date('m', strtotime($row['tglPenjualan']));
    
                if ($month == '06') {
                    $juneData[] = $row;
                } elseif ($month == '07') {
                    $julyData[] = $row;
                }
            }

            $data['juneDates'] = json_encode(array_column($juneData, 'tglPenjualan'));
            $data['juneSales'] = json_encode(array_column($juneData, 'total_penjualan'));

            $data['julyDates'] = json_encode(array_column($julyData, 'tglPenjualan'));
            $data['julySales'] = json_encode(array_column($julyData, 'total_penjualan'));
    
            return view('dashboard', $data);
        }
    }
}
