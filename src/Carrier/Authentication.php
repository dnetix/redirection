<?php


namespace Dnetix\Redirection\Carrier;

use SoapHeader;
use SoapVar;
use stdClass;

/**
 * Class Authentication
 * Generates the needed authentication elements
 */
class Authentication
{
    const WSU = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    const WSSE = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';

    protected $login;
    protected $tranKey;
    protected $additional;
    protected $nonce;
    protected $seed;

    private $key;

    public function __construct($data)
    {
        $this->login = $data['login'];
        $this->key = $data['tranKey'];
        if (isset($data['seed'])) {
            $this->seed = $data['seed'];
        } else {
            $this->seed = date('c');
        }
        if (isset($data['additional'])) {
            $this->additional = $data['additional'];
        }
        if (isset($data['nonce'])) {
            $this->nonce = $data['nonce'];
        } else {
            if (function_exists('random_bytes')) {
                $this->nonce = bin2hex(random_bytes(16));
            } elseif (function_exists('openssl_random_pseudo_bytes')) {
                $this->nonce = bin2hex(openssl_random_pseudo_bytes(16));
            } else {
                $this->nonce = mt_rand();
            }
        }
        $this->digestTranKey();
    }

    public function login()
    {
        return $this->login;
    }

    public function digest()
    {
        return base64_encode(sha1($this->nonce . $this->seed() . $this->key, true));
    }

    public function tranKey()
    {
        return $this->tranKey;
    }

    /**
     * By default, it will set the tranKey to the digested one
     * @return $this
     */
    public function digestTranKey()
    {
        $this->tranKey = $this->digest();
        return $this;
    }

    /**
     * Returns the value for a simple transactional key, i.e. the ones needed for the
     * old services
     * @return self
     */
    public function basicTrankey()
    {
        $this->tranKey = sha1($this->seed() . $this->key, false);
        return $this;
    }

    public function seed()
    {
        return $this->seed;
    }

    public function additional()
    {
        return $this->additional;
    }

    public function nonce()
    {
        return base64_encode($this->nonce);
    }

    public function key()
    {
        return $this->key;
    }

    /**
     * Parses the entity as a SOAP Header
     * @return SoapHeader
     */
    public function getSoapHeader()
    {
        $UsernameToken = new stdClass();
        $UsernameToken->Username = new SoapVar($this->login(), XSD_STRING, NULL, self::WSSE, NULL, self::WSSE);
        $UsernameToken->Password = new SoapVar($this->digest(), XSD_STRING, 'PasswordDigest', NULL, 'Password', self::WSSE);
        $UsernameToken->Nonce = new SoapVar($this->nonce(), XSD_STRING, null, self::WSSE, null, self::WSSE);
        $UsernameToken->Created = new SoapVar($this->seed(), XSD_STRING, NULL, self::WSU, null, self::WSU);

        $security = new stdClass();
        $security->UsernameToken = new SoapVar($UsernameToken, SOAP_ENC_OBJECT, NULL, self::WSSE, 'UsernameToken', self::WSSE);

        return new SoapHeader(self::WSSE, 'Security', $security, true);
    }

    public function toArray()
    {
        return [
            'login' => $this->login(),
            'tranKey' => $this->tranKey(),
            'nonce' => $this->nonce(),
            'seed' => $this->seed(),
            'additional' => $this->additional()
        ];
    }
}