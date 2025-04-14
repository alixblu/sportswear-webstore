<?php 
    require_once dirname(__FILE__) .'/../../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;

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
    }
?>