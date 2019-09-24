<?php

/**
 * Wifi
 */
class Dao_Wifi extends Dao_Base
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getWifiDetail(array $params)
    {
        $result = array();

        $sql = "select `SSID`, `PWS` from `hotel_wifi` where hotelid=" . $params['hotelid'] . " LIMIT 1";

        $result = $this->db->fetchAssoc($sql, array());

        return $result;
    }
}
