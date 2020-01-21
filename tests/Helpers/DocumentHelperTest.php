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
}
