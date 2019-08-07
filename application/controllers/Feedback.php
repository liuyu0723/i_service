<?php

/**
 * 酒店问卷调查控制器类
 *
 */
class FeedbackController extends \BaseController
{

    /**
     *
     * @var FeedbackModel
     */
    private $model;

    /**
     *
     * @var Convertor_Feedback
     */
    private $convertor;

    public function init()
    {
        parent::init();
        $this->model = new FeedbackModel();
        $this->convertor = new Convertor_Feedback();
    }

    /**
     * 获取酒店问卷调查列表
     *
     * @return Json
     */
    public function getFeedbackListAction()
    {
        $param = array();
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['listid'] = intval($this->getParamList('listid'));
        if (empty($param['hotelid'])) {
            $this->throwException(2, '物业ID不能为空');
        }
        if (empty($param['listid'])) {
            $this->throwException(2, '表单ID不能为空');
        }
        $param['status'] = 1;
        $data = $this->model->getFeedbackList($param);
        $data = $this->convertor->getFeedbackListConvertor($data);
        $this->echoSuccessData($data);
    }

    /**
     * 获取后台酒店问卷调查列表
     *
     * @return Json
     */
    public function getListAction()
    {
        $param = array();
        $param['page'] = intval($this->getParamList('page', 1));
        $param['limit'] = intval($this->getParamList('limit', 5));
        $param['id'] = intval($this->getParamList('id'));
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['listid'] = intval($this->getParamList('listid'));
        $param['question'] = trim($this->getParamList('question'));
        $param['type'] = intval($this->getParamList('type'));
        $param['status'] = $this->getParamList('status');
        if (is_null($param['status'])) {
            unset($param['status']);
        }
        $data = $this->model->getFeedbackList($param);
        $count = $this->model->getFeedbackCount($param);
        $data = $this->convertor->getListConvertor($data, $count, $param);
        $this->echoSuccessData($data);
    }

    /**
     * 根据id获取酒店问卷调查详情
     *
     * @param
     *            int id 获取详情信息的id
     * @return Json
     */
    public function getFeedbackDetailAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $data = $this->model->getFeedbackDetail($id);
            $data = $this->convertor->getFeedbackDetail($data);
        } else {
            $this->throwException(1, '查询条件错误，id不能为空');
        }
        $this->echoJson($data);
    }

    /**
     * 根据id修改酒店问卷调查信息
     *
     * @param
     *            int id 获取详情信息的id
     * @param
     *            array param 需要更新的字段
     * @return Json
     */
    public function updateFeedbackByIdAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $param = array();
            $param['hotelid'] = $this->getParamList('hotelid');
            $param['type'] = $this->getParamList('type');
            $param['sort'] = $this->getParamList('sort');
            $param['question'] = $this->getParamList('question');
            $param['status'] = $this->getParamList('status');
            $param['option'] = $this->getParamList('option');
            $param['listid'] = $this->getParamList('listid');
            $data = $this->model->updateFeedbackById($param, $id);
            $data = $this->convertor->statusConvertor($data);
        } else {
            $this->throwException(1, 'id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 添加酒店问卷调查信息
     *
     * @param
     *            array param 需要新增的信息
     * @return Json
     */
    public function addFeedbackAction()
    {
        $param = array();
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['type'] = intval($this->getParamList('type'));
        $param['sort'] = trim($this->getParamList('sort'));
        $param['question'] = trim($this->getParamList('question'));
        $param['status'] = intval($this->getParamList('status'));
        $param['listid'] = intval($this->getParamList('listid'));
        $data = $this->model->addFeedback($param);
        $data = $this->convertor->statusConvertor(array('id' => $data));
        $this->echoSuccessData($data);
    }
}
