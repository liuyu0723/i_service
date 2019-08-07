<?php

/**
 * 雅士阁生活控制器类
 *
 */
class LifeController extends \BaseController
{

    /**
     * @var LifeModel
     */
    private $model;

    /**
     * @var Convertor_Life
     */
    private $convertor;

    public function init()
    {
        parent::init();
        $this->model = new LifeModel();
        $this->convertor = new Convertor_Life();
    }

    /**
     * 获取雅士阁生活列表
     *
     * @return Json
     */
    public function getLifeListAction()
    {
        $param = array();
        $param['typeid'] = intval($this->getParamList('typeid'));
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['name'] = $this->getParamList('name');
        if (Enum_System::notAdminPackage($this->package)) {
            $param['status'] = 1;
        } else {
            $param['status'] = $this->getParamList('status');
        }

        if (is_null($param['status'])) {
            $param['status'] = 1;
        }

        $param['id'] = intval($this->getParamList('id'));
        $this->getPageParam($param);
        if (empty($param['hotelid'])) {
            $this->throwException(2, '入参错误');
        }
        $lifeList = $this->model->getLifeList($param);
        $lifeCount = $this->model->getLifeCount($param);
        if (Enum_System::notAdminPackage($this->package)) {
            $data = $this->convertor->getLifeListConvertor($lifeList, $lifeCount, $param);
        } else {
            $lifeTypeModel = new LifeTypeModel();
            $typeList = $lifeTypeModel->getLifeTypeList($param);
            $data = $this->convertor->getAdminLifeListConvertor($lifeList, $lifeCount, $param, $typeList);
        }
        $this->echoSuccessData($data);
    }

    /**
     * 根据id获取雅士阁生活详情
     *
     * @param
     *            int id 获取详情信息的id
     * @return Json
     */
    public function getLifeDetailAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $data = $this->model->getLifeDetail($id);
            Enum_System::notAdminPackage($this->package) ? $data = $this->convertor->getLifeDetailConvertor($data) : $data = $this->convertor->getAdminLifeDetailConvertor($data);
        } else {
            $this->throwException(1, '查询条件错误，id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 根据id修改雅士阁生活信息
     *
     * @param
     *            int id 获取详情信息的id
     * @param
     *            array param 需要更新的字段
     * @return Json
     */
    public function updateLifeByIdAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $param = array();
            $param['hotelid'] = $this->getParamList('hotelid');
            $param['typeid'] = $this->getParamList('typeid');
            $param['name_lang1'] = $this->getParamList('name_lang1');
            $param['name_lang2'] = $this->getParamList('name_lang2');
            $param['name_lang3'] = $this->getParamList('name_lang3');
            $param['detail_lang1'] = $this->getParamList('detail_lang1');
            $param['detail_lang2'] = $this->getParamList('detail_lang2');
            $param['detail_lang3'] = $this->getParamList('detail_lang3');
            $param['address_lang1'] = $this->getParamList('address_lang1');
            $param['address_lang2'] = $this->getParamList('address_lang2');
            $param['address_lang3'] = $this->getParamList('address_lang3');
            $param['introduct_lang1'] = $this->getParamList('introduct_lang1');
            $param['introduct_lang2'] = $this->getParamList('introduct_lang2');
            $param['introduct_lang3'] = $this->getParamList('introduct_lang3');
            $param['tel'] = $this->getParamList('tel');
            $param['lat'] = $this->getParamList('lat');
            $param['lng'] = $this->getParamList('lng');
            $param['sort'] = $this->getParamList('sort');
            $param['pdf'] = $this->getParamList('pdf');
            $param['video'] = $this->getParamList('video');
            $param['pic'] = $this->getParamList('pic');
            $param['updatetime'] = time();
            $param['status'] = $this->getParamList('status');
            $data = $this->model->updateLifeById($param, $id);
            $data = $this->convertor->statusConvertor($data);
        } else {
            $this->throwException(1, 'id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 添加雅士阁生活信息
     *
     * @param
     *            array param 需要新增的信息
     * @return Json
     */
    public function addLifeAction()
    {
        $param = array();
        $param['hotelid'] = $this->getParamList('hotelid');
        $param['typeid'] = $this->getParamList('typeid');
        $param['name_lang1'] = $this->getParamList('name_lang1');
        $param['name_lang2'] = $this->getParamList('name_lang2');
        $param['name_lang3'] = $this->getParamList('name_lang3');
        $param['address_lang1'] = $this->getParamList('address_lang1');
        $param['address_lang2'] = $this->getParamList('address_lang2');
        $param['address_lang3'] = $this->getParamList('address_lang3');
        $param['introduct_lang1'] = $this->getParamList('introduct_lang1');
        $param['introduct_lang2'] = $this->getParamList('introduct_lang2');
        $param['introduct_lang3'] = $this->getParamList('introduct_lang3');
        $param['tel'] = $this->getParamList('tel');
        $param['lat'] = $this->getParamList('lat');
        $param['lng'] = $this->getParamList('lng');
        $param['updatetime'] = time();
        $param['createtime'] = time();
        $param['status'] = $this->getParamList('status');
        $param['sort'] = intval($this->getParamList('sort'));
        $param['pdf'] = trim($this->getParamList('pdf'));
        $param['video'] = trim($this->getParamList('video'));
        $param['pic'] = trim($this->getParamList('pic'));
        $data = $this->model->addLife($param);
        $data = $this->convertor->statusConvertor(array('id' => $data));
        $this->echoSuccessData($data);
    }
}
