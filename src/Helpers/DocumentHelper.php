<?php

namespace Dnetix\Redirection\Helpers;

class DocumentHelper
{
    // Colombia Documents
    const TYPE_CC = 'CC';
    const TYPE_CE = 'CE';
    const TYPE_TI = 'TI';
    const TYPE_RC = 'RC';
    const TYPE_NIT = 'NIT';
    const TYPE_RUT = 'RUT';
    // Generic Documents
    const TYPE_PPN = 'PPN';
    const TYPE_TAX = 'TAX';
    const TYPE_LIC = 'LIC';
    // Carnet Diplomatico
    const TYPE_CD = 'CD';
    // USA Documents
    const TYPE_SSN = 'SSN';
    // Panama Documents
    const TYPE_CIP = 'CIP';
    // Brazil Documents
    const TYPE_CPF = 'CPF';
    // Ecuador Documents
    const TYPE_CI = 'CI';
    const TYPE_RUC = 'RUC';
    protected static $DOCUMENT_TYPES = [
        self::TYPE_CC,
        self::TYPE_CE,
        self::TYPE_TI,
        self::TYPE_NIT,
        self::TYPE_RUT,
        self::TYPE_PPN,
        self::TYPE_TAX,
        self::TYPE_LIC,
        self::TYPE_SSN,
        self::TYPE_CIP,
        self::TYPE_CPF,
        self::TYPE_CI,
        self::TYPE_RUC
    ];
    public static $VALIDATION_PATTERNS = [
        self::TYPE_CC => '/^[1-9][0-9]{3,9}$/',
        self::TYPE_CE => '/^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/',
        self::TYPE_TI => '/^[1-9][0-9]{4,11}$/',
        self::TYPE_NIT => '/^[1-9]\d{6,9}$/',
        self::TYPE_RUT => '/^[1-9]\d{6,9}$/',
        self::TYPE_PPN => '/^[a-zA-Z0-9_]{4,16}$/',
        self::TYPE_TAX => '/^[a-zA-Z0-9_]{4,16}$/',
        self::TYPE_LIC => '/^[a-zA-Z0-9_]{4,16}$/',
        self::TYPE_SSN => '/^\d{3}\d{2,3}\d{4}$/',
        self::TYPE_CIP => '/^(PE|N|E|\d+)?\d{2,6}\d{2,6}$/',
        self::TYPE_CPF => '/^\d{10,11}$/',
        self::TYPE_CI => '/^\d{10}$/',
        self::TYPE_RUC => '/^\d{13}$/',
    ];

    public static function documentTypes($exclude = [])
    {
        $types = self::$DOCUMENT_TYPES;
        if ($exclude && is_array($exclude)) {
            $types = array_diff($types, $exclude);
        }
        return $types;
    }

    public static function isValidType($type)
    {
        return in_array($type, self::$DOCUMENT_TYPES);
    }

    public static function isValidDocument($type, $document)
    {
        if (!self::isValidType($type)) {
            return false;
        }

        $pattern = isset(self::$VALIDATION_PATTERNS[$type]) ? self::$VALIDATION_PATTERNS[$type] : null;
        if (!$pattern) {
            return true;
        }

        return !!preg_match($pattern, $document);
    }

    public static function businessDocument($document = null)
    {
        $businessDocuments = [
            self::TYPE_NIT,
            self::TYPE_RUT,
            self::TYPE_RUC,
        ];
        if ($document) {
            return in_array($document, $businessDocuments);
        }
        return $businessDocuments;
    }
}
