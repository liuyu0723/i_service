<?php

/**
 * WiFi
 *
 */
class WifiController extends \BaseController
{

    /**
     *
     * @var WifiModel
     */
    private $model;


    public function init()
    {
        parent::init();
        $this->model = new WifiModel();
    }


    /**
     * @param array
     */
    public function getWifiAction()
    {
        $params = array();

        $params['hotelid'] = $this->getParamList('hotelid');

        if (empty($params['hotelid'])) {
            $params['hotelid'] = 29;
        }

        $data = $this->model->getWifiDetail($params);

        $this->echoSuccessData($data);
    }
}
