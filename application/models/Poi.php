<?php

/**
 * Class PoiModel
 * 本地攻略管理
 */
class PoiModel extends \BaseModel
{

    private $dao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new Dao_Poi();
    }

    /**
     * 获取Poi列表信息
     *
     * @param
     *            array param 查询条件
     * @return array
     */
    public function getPoiList(array $param)
    {
        $param['typeid'] ? $paramList['typeid'] = $param['typeid'] : false;
        $param['tagid'] ? $paramList['tagid'] = $param['tagid'] : false;
        $param['id'] ? $paramList['id'] = $param['id'] : false;
        $param['name'] ? $paramList['name'] = $param['name'] : false;
        isset($param['hotelid']) ? $paramList['hotelid'] = $param['hotelid'] : false;
        isset($param['status']) ? $paramList['status'] = $param['status'] : false;
        $paramList['limit'] = $param['limit'];
        $paramList['page'] = $param['page'];
        return $this->dao->getPoiList($paramList);
    }

    /**
     * 获取Poi数量
     *
     * @param
     *            array param 查询条件
     * @return array
     */
    public function getPoiCount(array $param)
    {
        $param['typeid'] ? $paramList['typeid'] = $param['typeid'] : false;
        $param['tagid'] ? $paramList['tagid'] = $param['tagid'] : false;
        $param['id'] ? $paramList['id'] = $param['id'] : false;
        $param['name'] ? $paramList['name'] = $param['name'] : false;
        isset($param['hotelid']) ? $paramList['hotelid'] = $param['hotelid'] : false;
        isset($param['status']) ? $paramList['status'] = $param['status'] : false;
        return $this->dao->getPoiCount($paramList);
    }

    /**
     * 根据id查询Poi信息
     *
     * @param
     *            int id 查询的主键
     * @return array
     */
    public function getPoiDetail($id)
    {
        $result = array();
        if ($id) {
            $result = $this->dao->getPoiDetail($id);
        }
        return $result;
    }

    /**
     * 根据id更新Poi信息
     *
     * @param
     *            array param 需要更新的信息
     * @param
     *            int id 主键
     * @return array
     */
    public function updatePoiById($param, $id)
    {
        $result = false;
        if ($id) {
            $info = array();
            isset($param['hotelid']) ? $info['hotelid'] = intval($param['hotelid']) : false;
            isset($param['typeid']) ? $info['typeid'] = intval($param['typeid']) : false;
            isset($param['tagid']) ? $info['tagid'] = intval($param['tagid']) : false;
            isset($param['name_lang1']) ? $info['name_lang1'] = $param['name_lang1'] : false;
            isset($param['name_lang2']) ? $info['name_lang2'] = $param['name_lang2'] : false;
            isset($param['name_lang3']) ? $info['name_lang3'] = $param['name_lang3'] : false;
            isset($param['detail_lang1']) ? $info['detail_lang1'] = $param['detail_lang1'] : false;
            isset($param['detail_lang2']) ? $info['detail_lang2'] = $param['detail_lang2'] : false;
            isset($param['detail_lang3']) ? $info['detail_lang3'] = $param['detail_lang3'] : false;
            isset($param['address_lang1']) ? $info['address_lang1'] = $param['address_lang1'] : false;
            isset($param['address_lang2']) ? $info['address_lang2'] = $param['address_lang2'] : false;
            isset($param['address_lang3']) ? $info['address_lang3'] = $param['address_lang3'] : false;
            isset($param['introduct_lang1']) ? $info['introduct_lang1'] = $param['introduct_lang1'] : false;
            isset($param['introduct_lang2']) ? $info['introduct_lang2'] = $param['introduct_lang2'] : false;
            isset($param['introduct_lang3']) ? $info['introduct_lang3'] = $param['introduct_lang3'] : false;
            isset($param['tel']) ? $info['tel'] = $param['tel'] : false;
            isset($param['lat']) ? $info['lat'] = $param['lat'] : false;
            isset($param['lng']) ? $info['lng'] = $param['lng'] : false;
            isset($param['status']) ? $info['status'] = intval($param['status']) : false;
            isset($param['sort']) ? $info['sort'] = $param['sort'] : false;
            isset($param['pdf']) ? $info['pdf'] = $param['pdf'] : false;
            isset($param['video']) ? $info['video'] = $param['video'] : false;
            isset($param['pic']) ? $info['pic'] = $param['pic'] : false;

            isset($param['homeShow']) ? $info['homeShow'] = $param['homeShow'] : 0;
            if ($info['homeShow'] == '') {
                $info['homeShow'] = 1;
            }
            isset($param['startTime']) ? $info['startTime'] = $param['startTime'] : time();
            if ($info['startTime'] == '') {
                $info['startTime'] = time();
            }
            isset($param['endTime']) ? $info['endTime'] = $param['endTime'] : time();
            if ($info['endTime'] == '') {
                $info['endTime'] = time();
            }
            $info['updatetime'] = time();
            $result = $this->dao->updatePoiById($info, $id);
        }
        return $result;
    }

    /**
     * Poi新增信息
     *
     * @param
     *            array param 需要增加的信息
     * @return array
     */
    public function addPoi($param)
    {
        $info = $param;
        return $this->dao->addPoi($info);
    }
}
