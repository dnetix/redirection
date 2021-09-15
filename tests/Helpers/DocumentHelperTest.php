<?php

namespace Tests\Helpers;

use Dnetix\Redirection\Helpers\DocumentHelper;
use Tests\BaseTestCase;

class DocumentHelperTest extends BaseTestCase
{
    public function testItExcludesTheGivenDocumentTypes()
    {
        $documentTypes = DocumentHelper::documentTypes();
        $this->assertTrue(in_array(DocumentHelper::TYPE_CI, $documentTypes), 'Document CI Exists');
        $this->assertTrue(in_array(DocumentHelper::TYPE_CC, $documentTypes), 'Document CC Exists');
        $this->assertTrue(in_array(DocumentHelper::TYPE_NIT, $documentTypes), 'Document NIT Exists');

        $exclude = [DocumentHelper::TYPE_CI, DocumentHelper::TYPE_CC];
        $documentTypes = DocumentHelper::documentTypes($exclude);
        $this->assertFalse(in_array(DocumentHelper::TYPE_CI, $documentTypes), 'Document CI Excluded');
        $this->assertFalse(in_array(DocumentHelper::TYPE_CC, $documentTypes), 'Document CC Excluded');
        $this->assertTrue(in_array(DocumentHelper::TYPE_NIT, $documentTypes), 'Document NIT Exists after');
    }

    public function testItHandlesBusinessDocuments()
    {
        $this->assertIsArray(DocumentHelper::businessDocument());
        $this->assertFalse(DocumentHelper::businessDocument(DocumentHelper::TYPE_CC));

        $this->assertFalse(DocumentHelper::isValidDocument('NOT_EXISTS', '1234'));
    }

    public function testItValidatesCorrectlyTheCI()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '1002606430'));
    }

    public function testItValidatesCorrectlyTheRUC()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_RUC, '1798288377001'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_RUC, '1798288377'));
    }

    public function testItValidatesCorrectlyTheNIT()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_NIT, '860000038'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_NIT, '86000003'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_NIT, '8600000384'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_NIT, '8600000384-42'));
    }

    public function testItValidatesCorrectlyDNI()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DNI, '12345678'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DNI, '1234859'));
    }

    public function testItValidatesCorrectlyCRCPF()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CRCPF, '123485989'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CRCPF, '12348598'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CRCPF, '02348598'));
    }

    public function testItValidatesCorrectlyCPJ()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CPJ, '1234567894'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CPJ, '123456789'));
    }

    public function testItValidatesCorrectlyDimex()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DIMEX, '12345678949'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DIMEX, '123456789491'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DIMEX, '1234567894911'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DIMEX, '1234567894'));
    }

    public function testItValidatesCorrectlyDIDI()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DIDI, '12345678949'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DIDI, '123456789491'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DIDI, '1234567894911'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_DIDI, '1234567894'));
    }

    public function testItValidatesCorrectlyTheChileanRUT()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CLRUT, '12.345.678-5'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CLRUT, '30.686.957-4'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CLRUT, '13.342.430-K'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CLRUT, '13.342.430-L'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CLRUT, '15127983-k'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CLRUT, '14096336-4'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CLRUT, '1798288377'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CLRUT, 'Gdsdfgdfghfg'));
    }
}
