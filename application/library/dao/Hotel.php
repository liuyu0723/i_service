<?php

/**
 * 酒店开门数据层
 */
class Dao_Hotel extends Dao_Base
{

    private $access_username = "pegasus2";
    private $access_password = "pegasus2pw";
    private $psp_service_base_url = "http://219.137.213.98:37100/psp/services/CrsService";
    private $message_type = "10,20";
    private $order_status = "W";
    private $res_clerk = "WEB";
    private $acc_type = "A";
    private $mapping_local_hotel_id = 29;

    public function __construct()
    {
        parent::__construct();

        // TODO set access key

    }



    /**
     * Get room detail
     *
     * @param
     *            array 入参
     *            hotelId
     * @return string
     */

    public function getRoomDetailByHotelId(array $param)
    {
        $room_types = array();
        // get room info
        $room_type_xml = $this->sendRequest(
            $this->psp_service_base_url,
            $this->getRoomDetailRequestXml($param['hotelId'])
        );

        if ($room_type_xml) {
            try {
                $data = simplexml_load_string($room_type_xml);
                $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->getRmtypeByHotelidResponse->out->rmTypes;

                if ($response) {
                    foreach ($response->children() as $d) {
                        $room_types[] = array(
                            'CName' => (string) $d->CName,
                            'EName' => (string) $d->EName,
                            'code' => (string) $d->code,
                            'photos' => array(
                                'photo1' => (string) $d->photo1,
                                'photo2' => (string) $d->photo2
                            ),
                            'qty' => (string) $d->qty,
                            'status' => (string) $d->status,
                            'seqId' => (string) $d->seqId
                        );
                    }
                }
            } catch (Exception $e) {
                $room_types = [];
            }
        }

        return $room_types;
    }






    /**
     * Get room price detail
     *
     * @param
     *            array 入参
     *            hotelId, startDate, endDate, hProductCode
     * @return string
     */

    public function getRoomPriceDetails(array $param)
    {
        $result = array();
        // get room info
        $room_type_xml = $this->sendRequest(
            $this->psp_service_base_url,
            $this->getRoomPriceDetailRequestXml(
                $param['hotelId'],
                'ALL',
                $param['startDate'],
                $param['endDate'],
                $param['hProductCode']
            )
        );

        if ($room_type_xml) {
            try {
                $data = simplexml_load_string($room_type_xml);
                $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->selectHotelRoomRatesResponse->out->roomRateWS;

                if ($response) {
                    foreach ($response->children() as $d) {
                        $result[] = array(
                            'rateCode' => (string) $d->rateCode,
                            'rateCodeCName' => (string) $d->rateCodeCName,
                            'rateCodeEName' => (string) $d->rateCodeEName,
                            'rateDate' => (string) $d->rateDate,
                            'ratePrice' => (string) $d->ratePrice,
                            'rmTypeCDesc' => (string) $d->rmTypeCDesc,
                            'rmTypeCName' => (string) $d->rmTypeCName,
                            'rmTypeEDesc' => (string) $d->rmTypeEDesc,
                            'rmTypeEName' => (string) $d->rmTypeEName,
                            'rmtypeSwitch' => (string) $d->rmtypeSwitch,
                            'vacRooms' => (string) $d->vacRooms,
                            'rmType' => (string) $d->rmType,

                            'minVacRooms' => (string) $d->minVacRooms,
                            'needGuarant' => (string) $d->needGuarant,
                            'needPay' => (string) $d->needPay,

                            'breakfastDesc' => (string) $d->breakfastDesc,
                            'breakfastEDesc' => (string) $d->breakfastEDesc,



                            'size' => '',
                            'title_lang1' => '',
                            'title_lang2' => '',
                            'title_lang3' => '',
                            'panoramic' => '',
                            'bedtype_lang1' => '',
                            'bedtype_lang2' => '',
                            'bedtype_lang3' => '',
                            'detail_lang1' => '',
                            'detail_lang2' => '',
                            'detail_lang3' => '',
                            'resid_list' => '',
                            'pic' => ''
                        );
                    }

                    // load iservice data to 
                    $romeTypeListDao = new Dao_Roomtype();
                    $rtparamList = array();
                    $rtparamList['hotelid'] = $this->mapping_local_hotel_id;
                    $rtparamList['limit'] = 999;

                    $roomTypeList = $romeTypeListDao->getRoomtypeList($rtparamList);
                    if ($roomTypeList && count($roomTypeList) > 0 && $result && count($result) > 0) {
                        for ($i = 0; $i < count($result); $i++) {
                            for ($j = 0; $j < count($roomTypeList); $j++) {
                                if ($result[$i]['rmTypeCName'] == $roomTypeList[$j]['title_lang1']) {
                                    $result[$i]['size'] = $roomTypeList[$j]['size'];
                                    $result[$i]['title_lang1'] = $roomTypeList[$j]['title_lang1'];
                                    $result[$i]['title_lang2'] = $roomTypeList[$j]['title_lang2'];
                                    $result[$i]['title_lang3'] = $roomTypeList[$j]['title_lang3'];
                                    $result[$i]['panoramic'] = $roomTypeList[$j]['panoramic'];
                                    $result[$i]['bedtype_lang1'] = $roomTypeList[$j]['bedtype_lang1'];
                                    $result[$i]['bedtype_lang2'] = $roomTypeList[$j]['bedtype_lang2'];
                                    $result[$i]['bedtype_lang3'] = $roomTypeList[$j]['bedtype_lang3'];
                                    $result[$i]['detail_lang1'] = $roomTypeList[$j]['detail_lang1'];
                                    $result[$i]['detail_lang2'] = $roomTypeList[$j]['detail_lang2'];
                                    $result[$i]['detail_lang3'] = $roomTypeList[$j]['detail_lang3'];
                                    $result[$i]['resid_list'] = $roomTypeList[$j]['resid_list'];
                                    $result[$i]['pic'] = $roomTypeList[$j]['pic'];
                                    break;
                                }
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $result = [];
            }
        }

        return $result;
    }




    /**
     * Get room price detail
     *
     * @param
     *            array 入参
     *            hotelId, startDate, endDate, hProductCode
     * @return string
     */

    public function roomReserve(array $param)
    {
        $result = array();
        // get room info

        $request_xml = $this->getRoomReserveRequestXml(
            $param['adults'],
            $param['arrDate'],
            $param['depDate'],
            $param['booker'],
            $param['bookTel'],
            $param['gstName'],
            $param['gstTel'],
            $param['hotelId'],
            $param['rateCode'],
            $param['rmType'],
            $param['source'],
            $param['market'],
            $this->acc_type,
            $this->res_clerk,
            $param['rmQty'],
            $param['rmRate'],
            $param['nights'],
            $this->order_status,
            $this->message_type,
            $param['channel']
        );

        $xml = $this->sendRequest(
            $this->psp_service_base_url,
            $request_xml
        );

        if ($xml) {
            try {
                $data = simplexml_load_string($xml);
                $response =
                    $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->addOrderResponse->out->result;
                $errorMessageZh =
                    $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->addOrderResponse->out->errorMsgZh;
                $errorMessageEn =
                    $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->addOrderResponse->out->errorMsgEn;


                //if ($response) {
                $result[] = array(
                    'errorMsgEn' => $errorMessageEn,
                    'errorMsgZh' => $errorMessageZh,
                    'result' => $response
                );
                // }
            } catch (Exception $e) {
                $result = [];
            }
        }

        return $result;
    }







    private function getRoomDetailRequestXml($hotelId)
    {
        $xml = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:crs="http://xfire.super8.com/CrsService">
            ' . $this->getAuthHeader() . '
            <soapenv:Body>
                <crs:getRmtypeByHotelid>
                    <crs:in0>' . $hotelId . '</crs:in0>
                </crs:getRmtypeByHotelid>
            </soapenv:Body>
        </soapenv:Envelope>
        ';


        return $xml;
    }


    private function getRoomReserveRequestXml(
        $adults,
        $arrDate,
        $depDate,
        $booker,
        $bookTel,
        $gstName,
        $gstTel,
        $hotelId,
        $rateCode,
        $rmType,
        $source,
        $market,
        $AccType,
        $resClerk,
        $rmQty,
        $rmRate,
        $nights,
        $status,
        $msgType,
        $channel
    ) {
        $xml = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:crs="http://xfire.super8.com/CrsService" xmlns:web="http://webservice.system.business.ipegasus.armitage.com" xmlns:web1="http://webservice.crs.business.ipegasus.armitage.com">
            ' . $this->getAuthHeader() . '
            <soapenv:Body>
                <crs:addOrder>
                    <crs:in0>
                        <web:adults>' . $adults . '</web:adults>
                        <web:arrDate>' . $arrDate . '</web:arrDate>
                        <web:depDate>' . $depDate . '</web:depDate>
                        <web:booker>' . $booker . '</web:booker>
                        <web:bookTel>' . $bookTel . '</web:bookTel>
                        <web:gstName>' . $gstName . '</web:gstName>
                        <web:gstTel>' . $gstTel . '</web:gstTel>
                        <web:hotelId>' . $hotelId . '</web:hotelId>
                        <web:rateCode>' . $rateCode . '</web:rateCode>
                        <web:rmType>' . $rmType . '</web:rmType>
                        <web:source>' . $source . '</web:source>
                        <web:market>' . $market . '</web:market>
                        <web:AccType>' . $AccType . '</web:AccType>
                        <web:resClerk>' . $resClerk . '</web:resClerk>
                        <web:rmQty>' . $rmQty . '</web:rmQty>
                        <web:rmRate>' . $rmRate . '</web:rmRate>
                        <web:nights>' . $nights . '</web:nights>
                        <web:status>' . $status . '</web:status>
                        <web:msgType>' . $msgType . '</web:msgType>
                        <web:channel>' . $channel . '</web:channel>
                    </crs:in0>
                </crs:addOrder>
            </soapenv:Body>
        </soapenv:Envelope>
        ';


        return $xml;
    }



    private function getRoomPriceDetailRequestXml($hotelId, $rate_code, $start, $end, $prod_code)
    {
        $xml = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:crs="http://xfire.super8.com/CrsService">
            ' . $this->getAuthHeader() . '
            <soapenv:Body>
                <crs:selectHotelRoomRates>
                    <crs:in0>' . $hotelId . '</crs:in0>
                    <crs:in1>' . $rate_code . '</crs:in1>
                    <crs:in2>' . $start . '</crs:in2>
                    <crs:in3>' . $end . '</crs:in3>
                    <crs:in4>' . $prod_code . '</crs:in4>
                </crs:selectHotelRoomRates>
            </soapenv:Body>
        </soapenv:Envelope>
        ';


        return $xml;
    }


    private function getAuthHeader()
    {
        $xml = '
        <soapenv:Header>
            <AuthenticationToken>
                <Username>' . $this->access_username . '</Username>
                <Password>' . $this->access_password . '</Password>
            </AuthenticationToken>
        </soapenv:Header>        
        ';

        return $xml;
    }


    /**
     * Get SOAP result
     * @return string
     */
    private function sendRequest($url,  $xml)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));

        // post_data
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        // set timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 400);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds

        $result = curl_exec($ch);


        // error
        if (!$result) {
            $result = null;
        }


        curl_close($ch);

        return $result;
    }
}
