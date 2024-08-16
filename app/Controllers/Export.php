<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\StockModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\Controller;

class Export extends Controller
{
    public function downloadExcel()
    {
        $penjualanModel = new PenjualanModel();
        $stockModel = new StockModel();
        $status = $this->request->getVar('data');

        if(session()->get('role') == 'superuser') {
            if ($status == 'stocks'){
                $total = $stockModel->orderBy('tgl_penjualan', 'ASC')->findAll();
            } else {
                $total = $penjualanModel->orderBy('tgl_penjualan', 'ASC')->findAll();
            }
        } else {
            if ($status == 'stocks'){
                $total = $stockModel->where('provinsi', session()->get('role'))->findAll();
            } else {
                $total = $penjualanModel->where('provinsi', session()->get('role'))->findAll();
            }
        }

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator(session()->get('username'))
            ->setTitle('Rekap Tabel')
            ->setDescription('Hasil Rekap yang ditarik dari database.');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Tgl Penjualan');
        $sheet->setCellValue('B1', 'ID');
        $sheet->setCellValue('C1', 'Provinsi');
        $sheet->setCellValue('D1', 'Jenis');
        $sheet->setCellValue('E1', 'Total Penjualan');

        $row = 2;
        foreach ($total as $sale) {
            $sheet->setCellValue('A' . $row, $sale['tgl_penjualan']);
            $sheet->setCellValue('B' . $row, $sale['id']);
            $sheet->setCellValue('C' . $row, $sale['provinsi']);
            $sheet->setCellValue('D' . $row, $sale['jenis']);
            $sheet->setCellValue('E' . $row, $sale['total_penjualan']);
            $row++;
        }

        $filename = 'Rekap_Tabel.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
