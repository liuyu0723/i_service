<?php

/**
 * Class Wifi
 * 
 */
class WifiModel extends \BaseModel
{

    private $dao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new Dao_Wifi();
    }

    public function getWifiDetail(array $params)
    {

        $result = $this->dao->getWifiDetail($params);

        return $result;
    }
}
