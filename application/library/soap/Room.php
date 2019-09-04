<?php

class Soap_Room
{

    const ERROR_CONFIG = 1;

    const IBWHISIFSERVER = '/wsdl/IBWHISIFSERVER';
    const REDIS_PROC_NUM = 'redis_proc_num';

    const OPEN_LOCK_XML = '<?xml version="1.0" encoding="utf-8"?>
        <SVCINTER>
            <SVCHEAD>
                <BIPCODE>%s</BIPCODE>
                <PROCID>%s</PROCID>
                <PROCESSTIME>%s</PROCESSTIME>
                <SIGN>%s</SIGN>
            </SVCHEAD>
            <SVCCONT>
                <CUSCODE>%s</CUSCODE>
                <IDCODE>%s</IDCODE>
                <LOCKCODE>%s</LOCKCODE>
            </SVCCONT>
        </SVCINTER>';

    const GET_ROOM_XML = '<?xml version="1.0" encoding="utf-8"?>
        <SVCINTER>
            <SVCHEAD>
                <BIPCODE>%s</BIPCODE>
                <PROCID>%s</PROCID>
                <PROCESSTIME>%s</PROCESSTIME>
                <SIGN>%s</SIGN>
            </SVCHEAD>
            <SVCCONT>
                <ROOMCODE/>
            </SVCCONT>
        </SVCINTER>';


    /**
     * @var SoapClient
     */
    public $soapClient;

    private $key;


    public function __construct()
    {
        $baseUrl = Yaf_Registry::get('sysConfig')['lock']['default']['soap'];
        if (empty($baseUrl)) {
            throw new Exception("Config[soap.lock] not exist", self::ERROR_CONFIG);
        }
        libxml_disable_entity_loader(false);

        $this->soapClient = new SoapClient($baseUrl . self::IBWHISIFSERVER);
        $this->key = Yaf_Registry::get('sysConfig')['lock']['default']['key'];

    }


    private function getLockData($cusCode, $idCode, $lockCode)
    {
        $bipCode = '103';
        $redis = Cache_Redis::getInstance();
        $procId = $redis->incr(self::REDIS_PROC_NUM);
        if (empty($procId)) {
            throw  new RedisException("Redis fail");
        } else {
            $procId = str_pad($procId, 3, 0, STR_PAD_LEFT);
        }
        $processTime = date("ymdHisv");
        $sign = md5($bipCode . $procId . $processTime . $this->key);
        return sprintf(self::OPEN_LOCK_XML, $bipCode, $procId, $processTime, $sign, $cusCode, $idCode, $lockCode);
    }

    private function getRoomListParam()
    {
        $bipCode = '101';
        $redis = Cache_Redis::getInstance();
        $procId = $redis->incr(self::REDIS_PROC_NUM);
        if (empty($procId)) {
            throw  new RedisException("Redis fail");
        } else {
            $procId = str_pad($procId, 3, 0, STR_PAD_LEFT);
        }
        $processTime = date("ymdHisv");
        $sign = md5($bipCode . $procId . $processTime . $this->key);

        return sprintf(self::GET_ROOM_XML, $bipCode, $procId, $processTime, $sign);
    }

    /**
     * Unlock the door
     *
     * @param $cusCode
     * @param $idCode
     * @param $lockCode
     * @return mixed
     */
    public function unLock($cusCode, $idCode, $lockCode)
    {
        $param = $this->getLockData($cusCode, $idCode, $lockCode);
        $result = $this->soapClient->BWHISOPIF($param);
        return $result;
    }


    public function output()
    {
        $result = $this->unLock('B56976DD', '', '010101');
        var_dump($result);

        $result = $this->sourceRoomLockInfo();

        echo json_encode($result);
        var_dump($result);


    }

    public function sourceRoomLockInfo(){
        $param = $this->getRoomListParam();
        $xmlResult = $this->soapClient->BWHISOPIF($param);

        try {
            $result = $this->parseXML($xmlResult);
            if ($result['RESPCODE'] !== "00") {
                throw new Exception("API respcode not 00");
            } else {
                foreach ($result['ROOMLIST'] as $room){
                    $roomNo = $room['ROOMNO'];
                    $lockCode = $room['LOCKCODE'];
                }
            }
            return $result;
        } catch (Exception $e) {
            echo $xmlResult;
            throw $e;
        }


    }


    public function parseXML($xmlStr)
    {
        $domObject = new DOMDocument();
        $domObject->loadXML($xmlStr);
        $rootObject = $domObject->documentElement;
        $array = $this->parseNodeArray($rootObject);

        return $array;
    }

    function parseNodeArray(DOMElement $node)
    {
        $array = array();

        if ($node->tagName == "ROOMLIST") {
            if (!$node->hasChildNodes()) {
                throw new Exception("BP 101 format change");
            }
            foreach ($node->childNodes as $childNode) {
                $tmp = array();
                if ($childNode->childNodes->length < 2) {
                    throw new Exception("BP 101 format change");
                }
                $tmp['ROOMNO'] = $childNode->childNodes[0]->nodeValue;
                foreach ($childNode->childNodes[1]->childNodes as $lockCode) {
                    $tmp['LOCKCODE'][] = $lockCode->nodeValue;
                }
                $array[] = $tmp;
            }

            return array("ROOMLIST" => $array);
        }

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attrabute) {
                $array[$attrabute->nodeName] = $attrabute->nodeValue;
            }
        }
        if ($node->hasChildNodes()) {
            $child_array = $node->childNodes;
            if ($child_array->length == 1) {
                $array[$node->nodeName] = $node->nodeValue;
            } else {
                foreach ($child_array as $child_item) {
                    if ($child_item->nodeType != XML_TEXT_NODE) {
                        $array = array_merge($array, $this->parseNodeArray($child_item));
                    }
                }
            }
        } else {
            return $node->nodeValue;
        }

        return $array;
    }


}