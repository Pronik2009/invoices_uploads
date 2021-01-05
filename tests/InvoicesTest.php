<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Service\UploadProcess;
use DateTime;

class InvoicesTest extends TestCase
{
    public function testSellingPriceLogic() {
        $uploadprocess = new UploadProcess('');
        $today = new DateTime('today');
        $this->assertEquals($uploadprocess::OutdatedWarningMessage,
            $uploadprocess->getSellPrice(100,
            $today->modify('-1 day')->format('Y-m-d')
        ));//Outdated would mark with special message
        $this->assertEquals(30,
            $uploadprocess->getSellPrice(100,
            $today->modify('+15 day')->format('Y-m-d')
        ));//less then 30 days coefficient is 0.3
        $this->assertEquals(50,
            $uploadprocess->getSellPrice(100,
            $today->modify('+35 day')->format('Y-m-d')
        ));//more then 30 days coefficient is 0.5
        
    }
}
