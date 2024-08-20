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

                $chart = $penjualanModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->findAll();
                $chartOutlet = $penjualanModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                                    ->where('jenis', 'Outlet')
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->findAll();
                $chartSales = $penjualanModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
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

                $chart = $penjualanModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                                ->where('provinsi', $role)
                                ->orderBy('tgl_penjualan', 'ASC')
                                ->findAll();
                $chartOutlet = $penjualanModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                                ->where('jenis', 'Outlet')
                                ->where('provinsi', $role)
                                ->orderBy('tgl_penjualan', 'ASC')
                                ->findAll();;
                $chartSales = $penjualanModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                                ->where('jenis', 'Sales')
                                ->where('provinsi', $role)
                                ->orderBy('tgl_penjualan', 'ASC')
                                ->findAll();;

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
            foreach ($chartOutlet as $rowOutlet) {
                $month = date('m', strtotime($rowOutlet['tglPenjualan']));
    
                if ($month == '06') {
                    $juneDataOutlet[] = $rowOutlet;
                } elseif ($month == '07') {
                    $julyDataOutlet[] = $rowOutlet;
                }
            }
            foreach ($chartSales as $rowSales) {
                $month = date('m', strtotime($rowSales['tglPenjualan']));
    
                if ($month == '06') {
                    $juneDataSales[] = $rowSales;
                } elseif ($month == '07') {
                    $julyDataSales[] = $rowSales;
                }
            }

            $query = $db->query($low);
            $result = $query->getRow();

            $queryOutlet = $db->query($lowOutlet);
            $resultOutlet = $queryOutlet->getRow();

            $querySales = $db->query($lowSales);
            $resultSales = $querySales->getRow();
            

            $finalDates = array_merge($juneData, $julyData);
            $data['finalDates'] = json_encode((array_column($finalDates, 'tglPenjualan')));
            
            $datajuneDates = array_column($juneData, 'tglPenjualan');
            $datajuneSales = array_column($juneData, 'total_penjualan');
            $datajulyDates = array_column($julyData, 'tglPenjualan');
            $datajulySales = array_column($julyData, 'total_penjualan');
                $dataJune = array_combine($datajuneDates, $datajuneSales);
                $dataJuly = array_combine($datajulyDates, $datajulySales);

            $data['finalDataJune'] = json_encode($dataJune);
            $data['finalDataJuly'] = json_encode($dataJuly);


            $finalDatesOutlet = array_merge($juneDataOutlet, $julyDataOutlet);
            $data['finalDatesFirstType'] = json_encode((array_column($finalDatesOutlet, 'tglPenjualan')));

            $datajuneDatesOutlet = array_column($juneDataOutlet, 'tglPenjualan');
            $datajuneSalesOutlet = array_column($juneDataOutlet, 'total_penjualan');
            $datajulyDatesOutlet = array_column($julyDataOutlet, 'tglPenjualan');
            $datajulySalesOutlet = array_column($julyDataOutlet, 'total_penjualan');
                $dataJuneOutlet = array_combine($datajuneDatesOutlet, $datajuneSalesOutlet);
                $dataJulyOutlet = array_combine($datajulyDatesOutlet, $datajulySalesOutlet);

            $data['finalDataJuneFirstType'] = json_encode($dataJuneOutlet);
            $data['finalDataJulyFirstType'] = json_encode($dataJulyOutlet);

            
            $finalDatesSales = array_merge($juneDataSales, $julyDataSales);
            $data['finalDatesSecondType'] = json_encode((array_column($finalDatesSales, 'tglPenjualan')));
            $data['tes'] = json_encode((array_column($finalDatesSales, 'tglPenjualan')));
            
            $datajuneDatesSales = array_column($juneDataSales, 'tglPenjualan');
            $datajuneSalesSales = array_column($juneDataSales, 'total_penjualan');
            $datajulyDatesSales = array_column($julyDataSales, 'tglPenjualan');
            $datajulySalesSales = array_column($julyDataSales, 'total_penjualan');
                $dataJuneSales = array_combine($datajuneDatesSales, $datajuneSalesSales);
                $dataJulySales = array_combine($datajulyDatesSales, $datajulySalesSales);

            $data['finalDataJuneSecondType'] = json_encode($dataJuneSales);
            $data['finalDataJulySecondType'] = json_encode($dataJulySales);
            

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

                $chart = $stocksModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->findAll();
                $chartKartu = $stocksModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                                    ->where('jenis', 'Kartu')
                                    ->orderBy('tgl_penjualan', 'ASC')
                                    ->findAll();
                $chartVoucher = $stocksModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                                    ->where('jenis', 'Voucher')
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

                $chart = $stocksModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                    ->where('provinsi', $role)
                    ->orderBy('tgl_penjualan', 'ASC')
                    ->findAll();
                $chartKartu = $stocksModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                    ->where('jenis', 'Kartu')
                    ->where('provinsi', $role)
                    ->orderBy('tgl_penjualan', 'ASC')
                    ->findAll();
                $chartVoucher = $stocksModel->select('tgl_penjualan as tglPenjualan, total_penjualan')
                    ->where('jenis', 'Voucher')
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

            $juneDataKartu = [];
            $julyDataKartu = [];

            $juneDataVoucher = [];
            $julyDataVoucher = [];

            foreach ($chart as $row) {
                $month = date('m', strtotime($row['tglPenjualan']));
    
                if ($month == '06') {
                    $juneData[] = $row;
                } elseif ($month == '07') {
                    $julyData[] = $row;
                }
            }
            foreach ($chartKartu as $rowKartu) {
                $month = date('m', strtotime($rowKartu['tglPenjualan']));
    
                if ($month == '06') {
                    $juneDataKartu[] = $rowKartu;
                } elseif ($month == '07') {
                    $julyDataKartu[] = $rowKartu;
                }
            }
            foreach ($chartVoucher as $rowVoucher) {
                $month = date('m', strtotime($rowVoucher['tglPenjualan']));
    
                if ($month == '06') {
                    $juneDataVoucher[] = $rowVoucher;
                } elseif ($month == '07') {
                    $julyDataVoucher[] = $rowVoucher;
                }
            }

            $query = $db->query($low);
            $result = $query->getRow();

            $queryKartu = $db->query($lowKartu);
            $resultKartu = $queryKartu->getRow();

            $queryVoucher = $db->query($lowVoucher);
            $resultVoucher = $queryVoucher->getRow();

            $finalDates = array_merge($juneData, $julyData);
            $data['finalDates'] = json_encode((array_column($finalDates, 'tglPenjualan')));
            
            $datajuneDates = array_column($juneData, 'tglPenjualan');
            $datajuneSales = array_column($juneData, 'total_penjualan');
            $datajulyDates = array_column($julyData, 'tglPenjualan');
            $datajulySales = array_column($julyData, 'total_penjualan');
                $dataJune = array_combine($datajuneDates, $datajuneSales);
                $dataJuly = array_combine($datajulyDates, $datajulySales);

            $data['finalDataJune'] = json_encode($dataJune);
            $data['finalDataJuly'] = json_encode($dataJuly);


            $finalDatesKartu = array_merge($juneDataKartu, $julyDataKartu);
            $data['finalDatesFirstType'] = json_encode((array_column($finalDatesKartu, 'tglPenjualan')));

            $datajuneDatesKartu = array_column($juneDataKartu, 'tglPenjualan');
            $datajuneSalesKartu = array_column($juneDataKartu, 'total_penjualan');
            $datajulyDatesKartu = array_column($julyDataKartu, 'tglPenjualan');
            $datajulySalesKartu = array_column($julyDataKartu, 'total_penjualan');
                $dataJuneKartu = array_combine($datajuneDatesKartu, $datajuneSalesKartu);
                $dataJulyKartu = array_combine($datajulyDatesKartu, $datajulySalesKartu);

            $data['finalDataJuneFirstType'] = json_encode($dataJuneKartu);
            $data['finalDataJulyFirstType'] = json_encode($dataJulyKartu);


            $finalDatesVoucher = array_merge($juneDataVoucher, $julyDataVoucher);
            $data['finalDatesSecondType'] = json_encode((array_column($finalDatesVoucher, 'tglPenjualan')));

            $datajuneDatesVoucher = array_column($juneDataVoucher, 'tglPenjualan');
            $datajuneSalesVoucher = array_column($juneDataVoucher, 'total_penjualan');
            $datajulyDatesVoucher = array_column($julyDataVoucher, 'tglPenjualan');
            $datajulySalesVoucher = array_column($julyDataVoucher, 'total_penjualan');
                $dataJuneVoucher = array_combine($datajuneDatesVoucher, $datajuneSalesVoucher);
                $dataJulyVoucher = array_combine($datajulyDatesVoucher, $datajulySalesVoucher);

            $data['finalDataJuneSecondType'] = json_encode($dataJuneVoucher);
            $data['finalDataJulySecondType'] = json_encode($dataJulyVoucher);

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
