<?php

namespace app\services;

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class ParseExcelFile
 *
 * This file parses an excel file and returns the data to be charted
 *
 * @package app\services
 */
class ParseExcelFile
{
    public function processSpreadSheet($filePath){

        //chart data
        $chartData = ['data' => []];

        try {
            //open the spreadsheet
            $excelFileReader = IOFactory::createReader('Xls');
            $excelFileReader->setLoadSheetsOnly(['title', 'data']);
            $spreadsheet = $excelFileReader->load($filePath);

            //make sure the spreadsheet is valid
            $spreadsheetData = $this->verifySpreadsheetIntegrity($spreadsheet);
            if(!is_array($spreadsheetData)) {
                return $spreadsheetData;
            }

            //set the title
            $chartData['title'] = $spreadsheetData['title'];

            //verify each row of data
            foreach ($spreadsheetData['data'] as $index => $row) {

                if($index == 0) { //we don't need the data header row
                    continue;
                }
                $rowIntegrity = $this->verifyRowData($row, $index);

                if(!is_bool($rowIntegrity)) {
                    return $rowIntegrity;
                }
                $chartData['data'][] = [
                    'count' => $row[0],
                    'name' => $row[1]
                ];
            }

            return $chartData;

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }

    /**
     * This method verifies the spreadsheet integrity
     * 1. there are 2 worksheets
     * 2. the title worksheet contains a title
     * 3. the data worksheet contains data
     *
     * on success return the data for processing
     *
     * @param $spreadsheet
     * @return array |string
     */
    protected function verifySpreadsheetIntegrity($spreadsheet) {
        //sanity checks
        //1. based on the samples, there should be 2 sheets
        if($spreadsheet->getSheetCount() != 2) {
            return 'Spreadsheet should contain 2 worksheets.';
        }

        //get the title and the data
        $spreadsheet->setActiveSheetIndexByName('title');
        $titleData = $spreadsheet->getActiveSheet()->toArray();

        //title data should be the first row / column
        $title = array_pop($titleData)[0];
        if(!is_string($title) || !strlen($title) >= 1) {
            return 'Chart title not set properly, please use the first row / column cell in the 
            worksheet named "title".';
        }

        //verify the data exists
        $spreadsheet->setActiveSheetIndexByName('data');
        $data = $spreadsheet->getActiveSheet()->toArray();

        //verify there is actually data to display
        /**
         * Empty spreadsheet output
         * array(1) { [0]=> array(1) { [0]=> NULL } }
         */
        $dataExists = (
            count($data) >= 1 && isset($data[0])
            && !is_null($data[0][0])
        );

        if(!$dataExists) {
            return 'Spreadsheet contains no data.';
        }

        //spreadsheet is good
        return [
            'title' => $title,
            'data' => $data
        ];
    }

    /**
     * Verify the data integrity, first row contains 2 strings
     * then float / string (phpspreadsheet imports them as floats)
     *
     * @param $row
     * @param $index
     * @return bool|string
     */
    protected function verifyRowData($row, $index) {
        //verify both rows are set
        if(!isset($row[0]) || !isset($row[1])) {
            return "Row " . ($index + 1) ." data incorrect.  Please check your data and try again";
        }

        if($index == 0) { //header row
            if(!is_string($row[0]) || !is_string($row[1])) {
                return 'Header row incorrect.';
            }
            return true;
        }

        //verify the data
        if(!is_float($row[0]) || !is_string($row[1])) {
            return "Row ". ($index + 1) ." data incorrect.";
        }

        return true;
    }

}
