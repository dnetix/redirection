<?php


use Dnetix\Redirection\Message\Notification;

class NotificationTest extends TestCase
{

    public function testItParsesCorrectlyTheNotification()
    {
        $data = unserialize('a:4:{s:6:"status";a:4:{s:6:"status";s:8:"APPROVED";s:6:"reason";s:2:"00";s:7:"message";s:82:"Se ha aprobado su pago, puede imprimir el recibo o volver a la pagina del comercio";s:4:"date";s:25:"2016-10-10T16:39:57-05:00";}s:9:"requestId";i:83;s:9:"reference";s:20:"TEST_20161010_213937";s:9:"signature";s:40:"8fb4beea130ab3e75a1de956bd0213892e0f6839";}');
        $notification = new Notification($data, '024h1IlD');

        $this->assertTrue($notification->isValidNotification(), 'Valid notification');
        $this->assertTrue($notification->isApproved(), $notification->status()->status());
        $this->assertFalse($notification->isRejected(), $notification->status()->status());
        $this->assertEquals($notification->requestId(), 83, 'Same request identifier');
        $this->assertEquals($notification->reference(), 'TEST_20161010_213937', 'Same reference');
    }
    
}