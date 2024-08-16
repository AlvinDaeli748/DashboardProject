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

            $db = \Config\Database::connect();
            
            if(session()->get('role') == 'superuser')
            {
                $data['penjualan'] = $penjualanModel
                                    ->groupBy('DATE(tgl_penjualan)')
                                    ->paginate(25);

                $chart = $penjualanModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                                    ->groupBy('DATE(tgl_penjualan)')
                                    ->findAll();
                $outletSales = $penjualanModel->select('SUM(total_penjualan) as totalPenjualan')->findAll();
                $outlet = $penjualanModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Outlet')->findAll();
                $sales = $penjualanModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Sales')->findAll();

                $low = "SELECT COUNT(id) AS low FROM penjualan WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM penjualan)";
                $lowOutlet = "SELECT COUNT(id) AS low FROM penjualan WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM penjualan) AND jenis = 'Outlet'";
                $lowSales = "SELECT COUNT(id) AS low FROM penjualan WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM penjualan) AND jenis = 'Sales'";

            } else {
                $role = session()->get('role');
                $data['penjualan'] = $penjualanModel
                                ->where('provinsi', $role)
                                ->groupBy('DATE(tgl_penjualan)')
                                ->paginate(25);

                $chart = $penjualanModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                    ->where('provinsi', $role)
                    ->groupBy('DATE(tgl_penjualan)')
                    ->findAll();

                $outletSales = $penjualanModel->select('SUM(total_penjualan) as totalPenjualan')->where('provinsi', $role)->findAll();
                $outlet = $penjualanModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Outlet')->where('provinsi', $role)->findAll();
                $sales = $penjualanModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Sales')->where('provinsi', $role)->findAll();

                $low = "SELECT COUNT(id) AS low FROM penjualan WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM penjualan) AND provinsi = '$role'";
                $lowOutlet = "SELECT COUNT(id) AS low FROM penjualan WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM penjualan) AND jenis = 'Outlet' AND provinsi = '$role'";
                $lowSales = "SELECT COUNT(id) AS low FROM penjualan WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM penjualan) AND jenis = 'Sales' AND provinsi = '$role'";
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

            $query = $db->query($low);
            $result = $query->getRow();

            $queryOutlet = $db->query($lowOutlet);
            $resultOutlet = $queryOutlet->getRow();

            $querySales = $db->query($lowSales);
            $resultSales = $querySales->getRow();

            $data['juneDates'] = json_encode(array_column($juneData, 'tglPenjualan'));
            $data['juneSales'] = json_encode(array_column($juneData, 'total_penjualan'));

            $data['julyDates'] = json_encode(array_column($julyData, 'tglPenjualan'));
            $data['julySales'] = json_encode(array_column($julyData, 'total_penjualan'));

            $data['outletSales'] = $outletSales;
            $data['outlet'] = $outlet;
            $data['sales'] = $sales;

            $low = (int) $result->low;
            $data['low'] = $low;

            $low = (int) $result->low;
            $data['low'] = $low;
            $lowOutlet = (int) $resultOutlet->low;
            $data['lowOutlet'] = $lowOutlet;
            $lowSales = (int) $resultSales->low;
            $data['lowSales'] = $lowSales;
    
            return view('dashboard', $data);
        }
    }
}
