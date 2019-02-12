<?php

/**
 * 酒店促销控制器类
 *
 */
class PromotionController extends \BaseController {

    /**
     * @var PromotionModel
     */
    private $model;

    /** @var  Convertor_Promotion */
    private $convertor;

    public function init() {
        parent::init();
        $this->model = new PromotionModel ();
        $this->convertor = new Convertor_Promotion ();
    }

    /**
     * 获取酒店促销列表
     *
     * @return Json
     */
    public function getPromotionListAction() {
        $param = array();
        $param ['hotelid'] = intval($this->getParamList('hotelid'));
        $param ['tagid'] = intval($this->getParamList('tagid'));
        $param ['id'] = intval($this->getParamList('id'));
        $param ['status'] = $this->getParamList('status');
        if(Enum_System::notAdminPackage($this->package)){
            $param ['status'] = 1;
        }
        if (!empty($this->getParamList('lang'))) {
            $langEnable = PromotionModel::ENABLE_LANG . Enum_Lang::getLangIndex($this->getParamList('lang'), Enum_Lang::CHINESE);
            $param[$langEnable] = PromotionModel::ENABLE;
        }
        $param ['title'] = $this->getParamList('title');
        $this->getPageParam($param);
        $promotionList = $this->model->getPromotionList($param);
        $promotionCount = $this->model->getPromotionCount($param);
        $promotionTagModel = new PromotionTagModel ();
        $tagList = $promotionTagModel->getPromotionTagList($param);
        Enum_System::notAdminPackage($this->package) ? $data = $this->convertor->getPromotionListConvertor($promotionList, $tagList, $promotionCount, $param) : $data = $this->convertor->getAdminPromotionListConvertor($promotionList, $tagList, $promotionCount, $param);
        $this->echoSuccessData($data);
    }

    /**
     * 根据id获取酒店促销详情
     *
     * @param
     *            int id 获取详情信息的id
     * @return Json
     */
    public function getPromotionDetailAction() {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $data = $this->model->getPromotionDetail($id);
            Enum_System::notAdminPackage($this->package) ? $data = $this->convertor->getPromotionDetailConvertor($data) : $data = $this->convertor->getAdminPromotionListConvertor($data);
        } else {
            $this->throwException(1, '查询条件错误，id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 根据id修改酒店促销信息
     *
     * @param
     *            int id 获取详情信息的id
     * @param
     *            array param 需要更新的字段
     * @return Json
     */
    public function updatePromotionByIdAction() {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $param = array();
            $param ['hotelid'] = $this->getParamList('hotelid');
            $param ['url'] = $this->getParamList('url');
            $param ['title_lang1'] = $this->getParamList('title_lang1');
            $param ['title_lang2'] = $this->getParamList('title_lang2');
            $param ['title_lang3'] = $this->getParamList('title_lang3');
            $param ['article_lang1'] = $this->getParamList('article_lang1');
            $param ['article_lang2'] = $this->getParamList('article_lang2');
            $param ['article_lang3'] = $this->getParamList('article_lang3');
            $param ['tagid'] = $this->getParamList('tagid');
            $param ['status'] = $this->getParamList('status');
            $param ['sort'] = $this->getParamList('sort');
            $param ['pdf'] = $this->getParamList('pdf');
            $param ['video'] = $this->getParamList('video');
            $param ['pic'] = $this->getParamList('pic');
            $param['enable_lang1'] = $this->getParamList('enable_lang1');
            $param['enable_lang2'] = $this->getParamList('enable_lang2');
            $param['enable_lang3'] = $this->getParamList('enable_lang3');
            $param ['updatetime'] = time();

            $param ['homeShow'] = trim($this->getParamList('homeShow'));
            $param ['startTime'] = trim($this->getParamList('startTime'));
            $param ['endTime'] = trim($this->getParamList('endTime'));

            $data = $this->model->updatePromotionById($param, $id);
            $data = $this->convertor->statusConvertor($data);
        } else {
            $this->throwException(1, 'id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 添加酒店促销信息
     *
     * @param
     *            array param 需要新增的信息
     * @return Json
     */
    public function addPromotionAction() {
        $param = array();
        $param ['hotelid'] = $this->getParamList('hotelid');
        $param ['url'] = $this->getParamList('url');
        $param ['title_lang1'] = $this->getParamList('title_lang1');
        $param ['title_lang2'] = $this->getParamList('title_lang2');
        $param ['title_lang3'] = $this->getParamList('title_lang3');
        $param ['tagid'] = $this->getParamList('tagid');
        $param ['status'] = $this->getParamList('status');
        $param ['updatetime'] = time();
        $param ['createtime'] = time();
        $param ['sort'] = intval($this->getParamList('sort'));
        $param ['pdf'] = trim($this->getParamList('pdf'));
        $param ['video'] = trim($this->getParamList('video'));
        $param ['pic'] = trim($this->getParamList('pic'));
        $param['enable_lang1'] = $this->getParamList('enable_lang1');
        $param['enable_lang2'] = $this->getParamList('enable_lang2');
        $param['enable_lang3'] = $this->getParamList('enable_lang3');

        $param ['homeShow'] = trim($this->getParamList('homeShow'));
        $param ['startTime'] = trim($this->getParamList('startTime'));
        $param ['endTime'] = trim($this->getParamList('endTime'));

        $data = $this->model->addPromotion($param);
        $data = $this->convertor->statusConvertor(array('id' => $data));
        $this->echoSuccessData($data);
    }
}
