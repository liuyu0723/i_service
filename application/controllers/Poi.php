<?php

/**
 * 酒店本地攻略控制器类
 *
 */
class PoiController extends \BaseController
{

    /** @var  PoiModel */
    private $model;
    /** @var  Convertor_Poi */
    private $convertor;

    public function init()
    {
        parent::init();
        $this->model = new PoiModel();
        $this->convertor = new Convertor_Poi();
    }

    /**
     * 获取酒店本地攻略列表
     *
     * @return Json
     */
    public function getPoiListAction()
    {
        $param = array();
        $param['typeid'] = intval($this->getParamList('typeid'));
        $param['tagid'] = intval($this->getParamList('tagid'));
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['id'] = intval($this->getParamList('id'));
        $param['name'] = $this->getParamList('name');
        $param['status'] = $this->getParamList('status');

        if (is_null($param['status'])) {
            $param['status'] = 1;
        }

        $this->getPageParam($param);
        if (empty($param['hotelid'])) {
            $this->throwException(2, '入参错误');
        }
        $poiList = $this->model->getPoiList($param);
        $poiCount = $this->model->getPoiCount($param);
        if (Enum_System::notAdminPackage($this->package)) {
            $data = $this->convertor->getPoiListConvertor($poiList, $poiCount, $param);
        } else {
            $poiTypeModel = new PoiTypeModel();
            $poiTagModel = new PoiTagModel();
            $typeParam['hotelid'] = $param['hotelid'];
            $typeList = $poiTypeModel->getPoiTypeList($typeParam);
            $tagList = $poiTagModel->getPoiTagList($typeParam);
            $data = $this->convertor->getAdminPoiListConvertor($poiList, $poiCount, $param, $typeList, $tagList);
        }
        $this->echoSuccessData($data);
    }

    /**
     * 根据id获取酒店本地攻略详情
     *
     * @param
     *            int id 获取详情信息的id
     * @return Json
     */
    public function getPoiDetailAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $data = $this->model->getPoiDetail($id);
            $data = $this->convertor->getPoiDetailConvertor($data);
            Enum_System::notAdminPackage($this->package) ? $data = $this->convertor->getPoiDetailConvertor($data) : $data = $this->convertor->getAdminPoiDetailConvertor($data);
        } else {
            $this->throwException(1, '查询条件错误，id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 根据id修改酒店本地攻略信息
     *
     * @param
     *            int id 获取详情信息的id
     * @param
     *            array param 需要更新的字段
     * @return Json
     */
    public function updatePoiByIdAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $param = array();
            $param ['hotelid'] = $this->getParamList('hotelid');
            $param ['typeid'] = $this->getParamList('typeid');
            $param ['tagid'] = $this->getParamList('tagid');
            $param ['name_lang1'] = $this->getParamList('name_lang1');
            $param ['name_lang2'] = $this->getParamList('name_lang2');
            $param ['name_lang3'] = $this->getParamList('name_lang3');
            $param ['detail_lang1'] = $this->getParamList('detail_lang1');
            $param ['detail_lang2'] = $this->getParamList('detail_lang2');
            $param ['detail_lang3'] = $this->getParamList('detail_lang3');
            $param ['address_lang1'] = $this->getParamList('address_lang1');
            $param ['address_lang2'] = $this->getParamList('address_lang2');
            $param ['address_lang3'] = $this->getParamList('address_lang3');
            $param ['introduct_lang1'] = $this->getParamList('introduct_lang1');
            $param ['introduct_lang2'] = $this->getParamList('introduct_lang2');
            $param ['introduct_lang3'] = $this->getParamList('introduct_lang3');
            $param ['tel'] = $this->getParamList('tel');
            $param ['lat'] = $this->getParamList('lat');
            $param ['lng'] = $this->getParamList('lng');
            $param ['sort'] = $this->getParamList('sort');
            $param ['pdf'] = $this->getParamList('pdf');
            $param ['video'] = $this->getParamList('video');
            $param ['pic'] = $this->getParamList('pic');
            $param ['updatetime'] = time();
            $param ['status'] = $this->getParamList('status');

            $param ['homeShow'] = trim($this->getParamList('homeShow'));
            $param ['startTime'] = trim($this->getParamList('startTime'));
            $param ['endTime'] = trim($this->getParamList('endTime'));

            $data = $this->model->updatePoiById($param, $id);
            $data = $this->convertor->statusConvertor($data);
        } else {
            $this->throwException(1, 'id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 添加酒店本地攻略信息
     *
     * @param
     *            array param 需要新增的信息
     * @return Json
     */
    public function addPoiAction()
    {
        $param = array();
        $param ['hotelid'] = $this->getParamList('hotelid');
        $param ['typeid'] = $this->getParamList('typeid');
        $param ['tagid'] = $this->getParamList('tagid');
        $param ['name_lang1'] = $this->getParamList('name_lang1');
        $param ['name_lang2'] = $this->getParamList('name_lang2');
        $param ['name_lang3'] = $this->getParamList('name_lang3');
        $param ['address_lang1'] = $this->getParamList('address_lang1');
        $param ['address_lang2'] = $this->getParamList('address_lang2');
        $param ['address_lang3'] = $this->getParamList('address_lang3');
        $param ['introduct_lang1'] = $this->getParamList('introduct_lang1');
        $param ['introduct_lang2'] = $this->getParamList('introduct_lang2');
        $param ['introduct_lang3'] = $this->getParamList('introduct_lang3');
        $param ['tel'] = $this->getParamList('tel');
        $param ['lat'] = $this->getParamList('lat');
        $param ['lng'] = $this->getParamList('lng');
        $param ['updatetime'] = time();
        $param ['createtime'] = time();
        $param ['status'] = intval($this->getParamList('status'));
        $param ['sort'] = intval($this->getParamList('sort'));
        $param ['pdf'] = trim($this->getParamList('pdf'));
        $param ['video'] = trim($this->getParamList('video'));
        $param ['pic'] = trim($this->getParamList('pic'));
        $data = $this->model->addPoi($param);
        $data = $this->convertor->statusConvertor(array('id' => $data));
        $this->echoSuccessData($data);
    }

    /**
     * 获取首页广告列表
     *
     * @return Json
     */
    public function getHomeAdvAction() {
        $param = array();
        $param ['hotelid'] = intval($this->getParamList('hotelid'));
        $param ['today'] = time();
        $this->getPageParam($param);
        if (empty ($param ['hotelid'])) {
            $this->throwException(2, '入参错误');
        }
        $data = $this->model->getHomeAdv($param);
        $data = $this->convertor->getHomeAdvConvertor($data);
        $this->echoSuccessData($data);
    }
}
