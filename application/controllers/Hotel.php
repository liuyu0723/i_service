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
        $param['startDate'] = intval($this->getParamList('startDate'));
        $param['endDate'] = intval($this->getParamList('endDate'));
        $param['hProductCode'] = intval($this->getParamList('hProductCode'));


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
}
