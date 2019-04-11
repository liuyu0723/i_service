<?php

/**
 * 酒店开门数据层
 */
class Dao_Door extends Dao_Base
{

    private $sys_process_time = "";


    public function __construct()
    {
        parent::__construct();

        $this->sys_process_time = date("ymdHisv");
    }



    /**
     * 查询房间并获取门锁信息
     *
     * @param
     *            array 入参
     *            ROOMCODE  房间名称（门锁管理软件中房间名称） 
     *            CUSCODE   账号或卡号 
     *            IDCODE    信息登记时证件号码 
     * @return string
     */

    public function openLock(array $param)
    {

        // get xml request
        $room_info_request_xml = $this->getRoomInfoRequestXML($param);

        // get room info
        $room_info = $this->sendRequest($room_info_request_xml);

        // get lock code from room info
        // TODO: add validation for xml
        $room_info_xml = simplexml_load_string($room_info);
        $lock_code = $room_info_xml->SVCCONT->LOCKCODE;

        $param['LOCKCODE'] = $lock_code;


        // get open lock request
        $lock_open_request_xml = $this->getOpenLockRequestXML($param);

        // send request 
        $lock_info = $this->sendRequest($lock_open_request_xml);



        return $room_info_request_xml . " \r\n\r\n\r\n\r\n\r\n\r\n" . $room_info . " \r\n\r\n\r\n\r\n\r\n\r\n" . $lock_open_request_xml . " \r\n\r\n\r\n\r\n\r\n\r\n" . $lock_info;
    }


    /**
     * 生成房间请求XML
     *
     * @param
     *            array 入参
     *            ROOMCODE  房间名称（门锁管理软件中房间名称） 
     *            CUSCODE   账号或卡号 
     *            IDCODE    信息登记时证件号码 
     * @return string
     */
    private function getRoomInfoRequestXML(array $param): string
    {
        $room_code = $param['ROOMCODE'];

        $domtree = new DOMDocument('1.0', 'UTF-8');

        /* create the root element of the xml tree */
        $xmlRoot = $domtree->createElement("xml");
        /* append it to the document created */
        $xmlRoot = $domtree->appendChild($xmlRoot);


        // build svchead
        $bipcode = $domtree->createElement("BIPCODE", 101);
        $procid = $domtree->createElement("PROCID", 2);
        // YY MM DD HH MI SS ZZZ 
        $processtime = $domtree->createElement("PROCESSTIME", $this->sys_process_time);
        // TODO: need replace it into setting.
        $sign = $domtree->createElement("sign", "d4526f67e132bbc8eca235185e56b095");

        $svchead = $domtree->createElement("SVCHEAD");

        $svchead->appendChild($bipcode);
        $svchead->appendChild($procid);
        $svchead->appendChild($processtime);
        $svchead->appendChild($sign);



        // build SVCCONT
        $roomcode = $domtree->createElement("ROOMCODE", $room_code);
        $svcont = $domtree->createElement("SVCCONT");

        $svcont->appendChild($roomcode);


        // add svchead and svcont to root
        $svcinter = $domtree->createElement("SVCINTER");
        $svcinter = $xmlRoot->appendChild($svcinter);


        $svcinter->appendChild($svchead);
        $svcinter->appendChild($svcont);


        return  $domtree->saveXML();
    }


    /**
     * 生成开门请求XML
     *
     * @param
     *            array 入参
     *            ROOMCODE  房间名称（门锁管理软件中房间名称） 
     *            CUSCODE   账号或卡号 
     *            IDCODE    信息登记时证件号码 
     * @return string
     */
    private function getOpenLockRequestXML(array $param): string
    {

        // get required data
        $cus_code = $param['CUSCODE'];
        $id_code = $param['IDCODE'];
        $lock_code = $param['LOCKCODE'];


        // build lock open request xml
        $domtree = new DOMDocument('1.0', 'UTF-8');

        /* create the root element of the xml tree */
        $xmlRoot = $domtree->createElement("xml");
        /* append it to the document created */
        $xmlRoot = $domtree->appendChild($xmlRoot);


        // build svchead
        $bipcode = $domtree->createElement("BIPCODE", 103);
        $procid = $domtree->createElement("PROCID", 2);
        // YY MM DD HH MI SS ZZZ 
        $processtime = $domtree->createElement("PROCESSTIME", $this->sys_process_time);
        // TODO: need replace it into setting.
        $sign = $domtree->createElement("sign", "d4526f67e132bbc8eca235185e56b095");

        $svchead = $domtree->createElement("SVCHEAD");

        $svchead->appendChild($bipcode);
        $svchead->appendChild($procid);
        $svchead->appendChild($processtime);
        $svchead->appendChild($sign);



        // build SVCCONT
        $cuscode = $domtree->createElement("CUSCODE", $cus_code);
        $idcode = $domtree->createElement("IDCODE", $id_code);
        $lockcode = $domtree->createElement("LOCKCODE", $lock_code);

        $svcont = $domtree->createElement("SVCCONT");

        $svcont->appendChild($cuscode);
        $svcont->appendChild($idcode);
        $svcont->appendChild($lockcode);


        // add svchead and svcont to root
        $svcinter = $domtree->createElement("SVCINTER");
        $svcinter = $xmlRoot->appendChild($svcinter);


        $svcinter->appendChild($svchead);
        $svcinter->appendChild($svcont);


        return  $domtree->saveXML();
    }


    // private function getLockStatus()



    /**
     * 发送数据到门锁管理系统
     *
     * @param
     *            array 入参
     *            ROOMCODE  房间名称（门锁管理软件中房间名称） 
     *            CUSCODE   账号或卡号 
     *            IDCODE    信息登记时证件号码 
     * @return string
     */
    private function sendRequest($xml): string
    {

        // send request to server
        // TODO: set this url to setting
        $url = "http://183.239.170.26：6007";

        $header = "POST HTTP/1.0 \r\n";
        $header .= "Content-type: text/xml \r\n";
        $header .= "Content-length: " . strlen($xml) . " \r\n";
        $header .= "Content-transfer-encoding: text \r\n";
        $header .= "Connection: close \r\n\r\n";
        // add xml data into request
        $header .= $xml;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $header);


        $data = curl_exec($ch);


        if (curl_errno($ch)) {
            // show error
            echo 'Curl error: ' . curl_error($ch);
        } else {
            // close 
            curl_close($ch);
        }

        return $data;
    }
}
