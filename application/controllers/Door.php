<?php

/**
 * 酒店体验式购物控制器类
 *
 */
class DoorController extends \BaseController
{

    /**
     *
     * @var DoorModel
     */
    private $model;


    public function init()
    {
        parent::init();
        $this->model = new DoorModel();
    }

    /**
     * 开门
     *            ROOMCODE  房间名称（门锁管理软件中房间名称） 
     *            CUSCODE   账号或卡号 
     *            IDCODE    信息登记时证件号码 
     * @return Json
     */
    public function openDoorAction()
    {
        $param = array();
        $param['ROOMCODE'] = intval($this->getParamList('ROOMCODE'));
        $param['CUSCODE'] = intval($this->getParamList('CUSCODE'));
        $param['IDCODE'] = intval($this->getParamList('IDCODE'));

        $data = $this->model->openDoor($param);

        $this->echoSuccessData($data);
    }
}
