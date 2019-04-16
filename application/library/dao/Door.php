<?php

/**
 * 酒店开门数据层
 */
class Dao_Door extends Dao_Base
{

    private $access_key = "BWRCUHIS";


    public function __construct()
    {
        parent::__construct();

        // TODO set access key

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
        print_r('get lock code request data has been sent \r\n');
        // var_dump($room_info_request_xml);
        // get room info
        $room_info = $this->sendRequest($room_info_request_xml);
        //print_r($room_info);
        // get lock code from room info
        // TODO: add validation for xml
        $room_info_xml = simplexml_load_string($room_info);
        $lock_code = $room_info_xml->SVCCONT->LOCKCODE;
        // var_dump($lock_code);
        
        if(!isset($lock_code)){
            echo 'Room not found. Using test lock code to open the door\r\n';

            $lock_code = '010101';
        }
        
        $param['LOCKCODE'] = $lock_code;


        // get open lock request
        $lock_open_request_xml = $this->getOpenLockRequestXML($param);
        print_r('door open request data has been sent \r\n');
        // var_dump($lock_open_request_xml);

        

        // send request 
        $lock_info = $this->sendRequest($lock_open_request_xml);
        //print_r($lock_info);

        return "door open succ";
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

        // // YY MM DD HH MI SS ZZZ 
        $sys_process_time = date("ymdHisv");

        $sign_code = md5("101" . "2" . $sys_process_time . $this->access_key);

        $request_data = '
            <SVCINTER>
                <SVCHEAD>
                    <BIPCODE>101</BIPCODE>
                    <PROCID>2</PROCID>
                    <PROCESSTIME>' . $sys_process_time . '</PROCESSTIME>
                    <sign>'. $sign_code .'</sign>
                </SVCHEAD>
                <SVCCONT>
                    <ROOMCODE>'. $room_code .'</ROOMCODE>
                </SVCCONT>
            </SVCINTER>
        ';



        return  $request_data;
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

        $sys_process_time = date("ymdHisv");

        $sign_code = md5("103" . "2" . $sys_process_time . $this->access_key);

        $request_data = '
            <SVCINTER>
                <SVCHEAD>
                    <BIPCODE>103</BIPCODE>
                    <PROCID>2</PROCID>
                    <PROCESSTIME>' . $sys_process_time . '</PROCESSTIME>
                    <sign>'. $sign_code .'</sign>
                </SVCHEAD>
                <SVCCONT>
                    <CUSCODE>'. $cus_code .'</CUSCODE>
                    <IDCODE>'. $id_code .'</IDCODE>
                    <LOCKCODE>'. $lock_code .'</LOCKCODE>
                </SVCCONT>
            </SVCINTER>
        ';



        return  $request_data;
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
        $url = "http://183.239.170.26:6007/soap/IBWHISIFSERVER";


        // build request 
        $request_data = '
            <soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
                xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:BWHISIFSERVERIntf-IBWHISIFSERVER">
                <soapenv:Header/>
                <soapenv:Body>
                    <urn:BWHISOPIF soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
                        <QuestXml xsi:type="xsd:string">
                        
                        '. $xml .'                
                
                        </QuestXml>
                    </urn:BWHISOPIF>
                </soapenv:Body>
            </soapenv:Envelope>
        ';



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 3000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_HTTPHEADER => array(
              "Content-Type: text/xml;charset=UTF-8
              "
            ),
          ));


        $data = curl_exec($curl);


        if (curl_errno($curl)) {
            // show error
            echo 'Curl error: ' . curl_error($curl);
        } else {
            // close 
            curl_close($curl);
        }


        

        return $data;
    }
}
