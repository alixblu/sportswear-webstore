<?php
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelUtils
{

    public function readExcelRows($file): array
    {
        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            return $rows;
        } catch (Exception $e) {
            throw new Exception("Lỗi đọc file Excel: " . $e->getMessage(), 400);
        }
    }
    public function exportExcel(array $data, array $headers, $filename = 'export.xlsx')
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->fromArray($headers, null, 'A1');

            $rowIndex = 2;
            foreach ($data as $row) {
                $sheet->fromArray($row, null, "A$rowIndex");
                $rowIndex++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            throw new Exception("Lỗi xuất Excel: " . $e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
