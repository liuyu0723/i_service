<?php

/**
 * 酒店开门数据层
 */
class Dao_Hotel extends Dao_Base
{

    private $access_username = "pegasus2";
    private $access_password = "pegasus2pw";
    private $psp_service_base_url = "http://219.137.213.98:37100/psp/services/CrsService";


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
                            'CName' => (string)$d->CName,
                            'EName' => (string)$d->EName,
                            'code' => (string)$d->code,
                            'photos' => array(
                                'photo1' => (string)$d->photo1,
                                'photo2' => (string)$d->photo2
                            ),
                            'qty' => (string)$d->qty,
                            'status' => (string)$d->status,
                            'seqId' => (string)$d->seqId
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
     *            hotelId
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

                if ($response) { }
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

        $result = curl_exec($ch);


        // error
        if (!$result) {
            $result = null;
        }


        curl_close($ch);

        return $result;
    }
}
