<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\StockModel;

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

            $perPage = 10;
            
            if(session()->get('role') == 'superuser')
            {
                $data['mainData'] = $penjualanModel
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->paginate($perPage, 'group');

                $chart = $penjualanModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->findAll();
                $chartOutlet = $penjualanModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                                    ->where('jenis', 'Outlet')
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->findAll();
                $chartSales = $penjualanModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                                    ->where('jenis', 'Sales')
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->findAll();
                $outletSales = $penjualanModel->select('SUM(total_penjualan) as totalPenjualan')->findAll();
                $outlet = $penjualanModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Outlet')->findAll();
                $sales = $penjualanModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Sales')->findAll();

                $low = "SELECT COUNT(id) AS low FROM penjualan WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM penjualan)";
                $lowOutlet = "SELECT COUNT(id) AS low FROM penjualan WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM penjualan) AND jenis = 'Outlet'";
                $lowSales = "SELECT COUNT(id) AS low FROM penjualan WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM penjualan) AND jenis = 'Sales'";

            } else {
                $role = session()->get('role');
                $data['mainData'] = $penjualanModel
                                ->where('provinsi', $role)
                                ->orderBy('tgl_penjualan', 'ASC')
                                ->paginate($perPage, 'group');

                $chart = $penjualanModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                    ->where('provinsi', $role)
                    ->orderBy('tgl_penjualan', 'ASC')
                    ->findAll();
                $chartOutlet = [];
                $chartSales = [];

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

            $juneDataOutlet = [];
            $julyDataOutlet = [];

            $juneDataSales = [];
            $julyDataSales = [];

            foreach ($chart as $row) {
                $month = date('m', strtotime($row['tglPenjualan']));
    
                if ($month == '06') {
                    $juneData[] = $row;
                } elseif ($month == '07') {
                    $julyData[] = $row;
                }
            }
            foreach ($chartOutlet as $row) {
                $month = date('m', strtotime($row['tglPenjualan']));
    
                if ($month == '06') {
                    $juneDataOutlet[] = $row;
                } elseif ($month == '07') {
                    $julyDataOutlet[] = $row;
                }
            }
            foreach ($chartSales as $row) {
                $month = date('m', strtotime($row['tglPenjualan']));
    
                if ($month == '06') {
                    $juneDataSales[] = $row;
                } elseif ($month == '07') {
                    $julyDataSales[] = $row;
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

            $finalDates = array_merge($juneData, $julyData);
            $data['finalDates'] = json_encode((array_column($finalDates, 'tglPenjualan')));

            $data['juneDatesOutlet'] = json_encode(array_column($juneDataOutlet, 'tglPenjualan'));
            $data['juneSalesOutlet'] = json_encode(array_column($juneDataOutlet, 'total_penjualan'));

            $data['julyDatesOutlet'] = json_encode(array_column($julyDataOutlet, 'tglPenjualan'));
            $data['julySalesOutlet'] = json_encode(array_column($julyDataOutlet, 'total_penjualan'));
            
            $data['juneDatesSales'] = json_encode(array_column($juneDataSales, 'tglPenjualan'));
            $data['juneSalesSales'] = json_encode(array_column($juneDataSales, 'total_penjualan'));

            $data['julyDatesSales'] = json_encode(array_column($julyDataSales, 'tglPenjualan'));
            $data['julySalesSales'] = json_encode(array_column($julyDataSales, 'total_penjualan'));

            $data['totalMainData'] = $outletSales;
            $data['totalFirstData'] = $outlet;
            $data['totalSecondData'] = $sales;

            $low = (int) $result->low;
            $data['low'] = $low;
            $lowOutlet = (int) $resultOutlet->low;
            $data['lowFirstData'] = $lowOutlet;
            $lowSales = (int) $resultSales->low;
            $data['lowSecondData'] = $lowSales;
    
            return view('dashboard', $data);
        }
    }

    public function stocks()
    {
        $session = session();
        if (!session()->get('logged_in')) 
        {
            return redirect()->to('/login')->send();
        } else {
            $stocksModel = new StockModel();

            $db = \Config\Database::connect();

            $perPage = 10;
            
            if(session()->get('role') == 'superuser')
            {
                $data['mainData'] = $stocksModel
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->paginate($perPage, 'group');

                $chart = $stocksModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->findAll();
                $kartuVoucher = $stocksModel->select('SUM(total_penjualan) as totalPenjualan')->findAll();
                $kartu = $stocksModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Kartu')->findAll();
                $voucher = $stocksModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Voucher')->findAll();

                $low = "SELECT COUNT(id) AS low FROM stocks WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM stocks)";
                $lowKartu = "SELECT COUNT(id) AS low FROM stocks WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM stocks) AND jenis = 'Kartu'";
                $lowVoucher = "SELECT COUNT(id) AS low FROM stocks WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM stocks) AND jenis = 'Voucher'";

            } else {
                $role = session()->get('role');
                $data['mainData'] = $stocksModel
                                ->where('provinsi', $role)
                                ->orderBy('tgl_penjualan', 'ASC')
                                ->paginate($perPage, 'group');

                $chart = $stocksModel->select('DATE(tgl_penjualan) as tglPenjualan, total_penjualan')
                    ->where('provinsi', $role)
                    ->orderBy('tgl_penjualan', 'ASC')
                    ->findAll();

                $kartuVoucher = $stocksModel->select('SUM(total_penjualan) as totalPenjualan')->where('provinsi', $role)->findAll();
                $kartu = $stocksModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Kartu')->where('provinsi', $role)->findAll();
                $voucher = $stocksModel->select('SUM(total_penjualan) as totalPenjualan')->where('jenis', 'Voucher')->where('provinsi', $role)->findAll();

                $low = "SELECT COUNT(id) AS low FROM stocks WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM stocks) AND provinsi = '$role'";
                $lowKartu = "SELECT COUNT(id) AS low FROM stocks WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM stocks) AND jenis = 'Kartu' AND provinsi = '$role'";
                $lowVoucher = "SELECT COUNT(id) AS low FROM stocks WHERE total_penjualan = (SELECT MIN(total_penjualan) FROM stocks) AND jenis = 'Voucher' AND provinsi = '$role'";
            }


            $data['pager'] = $stocksModel->pager;

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

            $queryKartu = $db->query($lowKartu);
            $resultKartu = $queryKartu->getRow();

            $queryVoucher = $db->query($lowVoucher);
            $resultVoucher = $queryVoucher->getRow();

            $data['juneDates'] = json_encode(array_column($juneData, 'tglPenjualan'));
            $data['juneSales'] = json_encode(array_column($juneData, 'total_penjualan'));

            $data['julyDates'] = json_encode(array_column($julyData, 'tglPenjualan'));
            $data['julySales'] = json_encode(array_column($julyData, 'total_penjualan'));

            $finalDates = array_merge($juneData, $julyData);
            $data['finalDates'] = json_encode((array_column($finalDates, 'tglPenjualan')));

            $data['totalMainData'] = $kartuVoucher;
            $data['totalFirstData'] = $kartu;
            $data['totalSecondData'] = $voucher;

            $low = (int) $result->low;
            $data['low'] = $low;
            $lowKartu = (int) $resultKartu->low;
            $data['lowFirstData'] = $lowKartu;
            $lowVoucher = (int) $resultVoucher->low;
            $data['lowSecondData'] = $lowVoucher;
            
            return view('dashboard', $data);
        }
    }
}
