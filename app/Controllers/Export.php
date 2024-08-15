<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\Controller;

class Export extends Controller
{
    public function downloadExcel()
    {
        $penjualanModel = new PenjualanModel();
        $total = $penjualanModel->where('provinsi', session()->get('role'))->findAll();

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator(session()->get('username'))
            ->setTitle('List Penjualan')
            ->setDescription('Hasil Penjualan yang ditarik dari database.');

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

        $filename = 'list_penjualan.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
