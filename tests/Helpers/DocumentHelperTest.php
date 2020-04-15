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

    public function testItValidatesCorrectlyTheCI()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '100260643-0'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '1002606430'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '178455996-4'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '1784559964'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '171111296-9'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '090881351-2'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '0908813512'));

        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '09088135-12'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_CI, '090881351111'));
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
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_NIT, '860000038-4'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_NIT, '8600000384-42'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_NIT, '0600000384'));
    }

    public function testItValidatesCorrectlyTheRUT()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_RUT, '16863576-1'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_RUT, '168635761'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_RUT, '891180022-6'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentHelper::TYPE_RUT, '8911800226'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentHelper::TYPE_RUT, '0911800226'));
    }
}
