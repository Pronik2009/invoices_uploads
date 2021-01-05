<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;
use DateTime;

/**
 * Description of UploadProcess
 *
 * @author Pronik2009
 */
class UploadProcess {
    public $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir.'/';
    }

    public function removeTmp($filePath) {
        $filesystem = new Filesystem();
        try {
            $filesystem->remove($filePath);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while clearing temporary file: ".$exception->getPath();
        }
    }
    
    public function getRows(Spreadsheet $spreadsheet) {
        $rows = [];
        foreach ($spreadsheet->getActiveSheet()->getRowIterator() as $row) {
            $amount = $spreadsheet->getActiveSheet()->getCell('B'.$row->getRowIndex())->getValue();
            $duedate = $spreadsheet->getActiveSheet()->getCell('C'.$row->getRowIndex())->getValue();
            $rows[] = array('rowNum'=>$row->getRowIndex(), 'rowContent'=>array(
                'id'=>$spreadsheet->getActiveSheet()->getCell('A'.$row->getRowIndex())->getValue(),
                'amount'=>$amount,
                'data'=>$duedate,
                //Add selling price for each invoice within service:
                'sellingprice'=> $this->getSellPrice($amount, $duedate),
                )
            );
        };
        return $rows;
    }
    public function getSellPrice($amount,$duedate) {
        $daysleft = date_diff(new DateTime(), new DateTime($duedate), false)->days;
        if ($daysleft<=30) {
            $result = $amount*0.3;
        }
        elseif (($daysleft>30)) {
            $result = $amount*0.5;
        };
        $today = new DateTime('today');
        $userdate = new DateTime($duedate);
        if ($today>$userdate) {
            $result = $this::OutdatedWarningMessage;
        }
        return $result;
    }

    const CsvMimeType = 'application/vnd.ms-excel';
    const TempFileName = 'tmp.csv';
    const OutdatedWarningMessage = 'outdated!';
}
