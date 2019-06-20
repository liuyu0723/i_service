<?php

/**
 * Class HotelModel
 * 门信息管理
 */
class HotelModel extends \BaseModel
{

    private $dao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new Dao_Hotel();
    }

    /**
     * 开门
     *
     * @param
     *            array param 
     *            hotelId 
     * @return array
     */
    public function getRoomDetailByHotelId(array $param)
    {
        // TODO: check param
        return $this->dao->getRoomDetailByHotelId($param);
    }

    public function getRoomPriceDetails(array $param)
    {
        return $this->dao->getRoomPriceDetails($param);
    }

    public function roomReserve(array $param)
    {
        return $this->dao->roomReserve($param);
    }
}
