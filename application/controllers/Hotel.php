<?php

/**
 * 酒店开门控制器类
 *
 */
class HotelController extends \BaseController
{

    /**
     *
     * @var HotelModel
     */
    private $model;


    public function init()
    {
        parent::init();
        $this->model = new HotelModel();
    }

    /**
     * Get room detail by hotel id
     * http://devservice.easyiservice.com/Hotel/roomDetail?hotelId=xx
     *            hotelId  
     * @return Json
     */
    public function roomDetailAction()
    {
        $param = array();
        $param['hotelId'] = intval($this->getParamList('hotelId'));
        if (!$param['hotelId']) {
            $this->echoJsonData(3, 'Invalid hotelId', 'rooms', [], null);
        } else {
            $data = $this->model->getRoomDetailByHotelId($param);

            if ($data) {
                $this->echoJsonData(0, 'success', 'rooms', $data, null);
            } else {
                $this->echoJsonData(3, 'server does not respond', 'rooms', [], null);
            }
        }
    }



    /**
     * Get room detail by hotel id
     * http://devservice.easyiservice.com/Hotel/hotelRoomDetails?hotelId=xx&startDate=xx&endDate=xx&hProductCode=xx
     *            hotelId  
     *            startDate
     *            endDate
     *            hProductCode
     * @return Json
     */
    public function hotelRoomDetailsAction()
    {
        $param = array();
        $param['hotelId'] = intval($this->getParamList('hotelId'));
        $param['startDate'] = $this->getParamList('startDate');
        $param['endDate'] = $this->getParamList('endDate');
        $param['hProductCode'] = $this->getParamList('hProductCode');


        if (!$param['hotelId'] || !$param['startDate'] || !$param['endDate'] || !$param['hProductCode']) {
            $this->echoJsonData(3, 'Invalid param', 'rooms', [], null);
        } else {

            $data = $this->model->getRoomPriceDetails($param);

            if ($data) {
                $this->echoJsonData(0, 'success', 'rooms', $data, null);
            } else {
                $this->echoJsonData(3, 'server does not respond', 'rooms', [], null);
            }
        }
    }



    /**
     * room reserve
     * http://devservice.easyiservice.com/Hotel/reserve
     *            adults   int 人数
     *            arrDate   yyyy-mm-dd  到达日期
     *            depDate   yyyy-mm-dd  离店日期
     *            booker    string   订房人姓名
     *            bookTel   string   订房人电话
     *            gstName   string   客人姓名
     *            gstTel    string   客人电话
     *            hotelId   int
     *            rateCode  string    房价代码 
     *            rmType    string    房间类型
     *            source    string    客人来源   通用代码 source
     *            market    string    市场类别   通用代码 market
     *            rmQty     int       房间数
     *            rmRate    float     房价 / 夜
     *            channel   string    MSD马上订官网和手机APP, WEDCT 微信
     * @return Json
     */
    public function reserveAction()
    {
        $param = array();
        $param['adults'] = intval($this->getParamList('adults'));
        $param['arrDate'] = $this->getParamList('arrDate');
        $param['depDate'] = $this->getParamList('depDate');
        $param['booker'] = $this->getParamList('booker');
        $param['bookTel'] = $this->getParamList('bookTel');
        $param['gstName'] = $this->getParamList('gstName');
        $param['gstTel'] = $this->getParamList('gstTel');
        $param['hotelId'] = intval($this->getParamList('hotelId'));
        $param['rateCode'] = intval($this->getParamList('rateCode'));
        $param['rateCode'] = $this->getParamList('rateCode');
        $param['rmType'] = $this->getParamList('rmType');
        $param['source'] = $this->getParamList('source');
        $param['market'] = intval($this->getParamList('market'));
        $param['rmQty'] = intval($this->getParamList('rmQty'));
        $param['rmRate'] = floatval($this->getParamList('rmRate'));
        $param['channel'] = $this->getParamList('channel');

        // check param
        $passValidation = true;
        foreach ($param as $p) {
            if (!$p) {
                $passValidation = false;
                break;
            }
        }


        if ($passValidation) {
            if ($this->validateDate($param['arrDate'], 'Y-m-d') && $this->validateDate($param['depDate'], 'Y-m-d')) {
                $date1 = new DateTime("now");
                $date2 = new DateTime($param['arrDate']);
                if ($date2 < $date1) {
                    $passValidation = false;
                } else {
                    $datetime1 = date_create($param['arrDate']);
                    $datetime2 = date_create($param['depDate']);

                    $interval = date_diff($datetime1, $datetime2);
                    $night =  $interval->format('%a');

                    if ($night <= 0 && $night > 31) {
                        $passValidation = false;
                    } else {
                        $param['nights'] = $night;
                        $param['rmRate'] = floatval($this->getParamList('rmRate')) * intval($this->getParamList('rmQty')) * $night;
                    }
                }
            } else {
                $passValidation = false;
            }
        }



        if (!$passValidation) {
            $this->echoJsonData(3, 'Invalid param', 'Reserve', [], null);
        } else {

            $data = $this->model->roomReserve($param);

            if ($data) {
                $this->echoJsonData(0, 'success', 'Reserve', $data, null);
            } else {
                $this->echoJsonData(3, 'server does not respond', 'Reserve', [], null);
            }
        }
    }
}
