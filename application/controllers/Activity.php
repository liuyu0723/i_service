<?php

/**
 * 活动控制器类
 *
 */
class ActivityController extends \BaseController
{

    /**
     *
     * @var ActivityModel
     */
    private $model;

    /**
     *
     * @var Convertor_Activity
     */
    private $convertor;

    public function init()
    {
        parent::init();
        $this->model = new ActivityModel();
        $this->convertor = new Convertor_Activity();
    }

    /**
     * 获取可用活动列表
     *
     * @return Json
     */
    public function getEffectiveActivityListAction()
    {
        $param = array();
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['groupid'] = intval($this->getParamList('groupid'));
        $param['tagid'] = intval($this->getParamList('tagid'));
        $param['status'] = 1;
        $this->getPageParam($param);
        $activityList = $this->model->getActivityList($param);
        $activityCount = $this->model->getActivityCount($param);
        $activityTagModel = new ActivityTagModel();
        $tagList = $activityTagModel->getActivityTagList($param);
        $data = $this->convertor->getEffectiveActivityListConvertor($activityList, $tagList, $activityCount, $param);
        $this->echoSuccessData($data);
    }

    /**
     * 获取活动列表
     *
     * @return Json
     */
    public function getActivityListAction()
    {
        $param = array();
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['groupid'] = intval($this->getParamList('groupid'));
        $param['tagid'] = intval($this->getParamList('tagid'));
        $param['title'] = trim($this->getParamList('title'));
        $param['status'] = $this->getParamList('status');
        $param['id'] = intval($this->getParamList('id'));

        if (is_null($param['status'])) {
            $param['status'] = 1;
        }

        $this->getPageParam($param);
        $activityList = $this->model->getActivityList($param);
        $activityCount = $this->model->getActivityCount($param);
        $data = $this->convertor->getActivityListConvertor($activityList, $activityCount, $param);
        $this->echoSuccessData($data);
    }

    /**
     * 根据id获取活动详情
     *
     * @param
     *            int id 获取详情信息的id
     * @return Json
     */
    public function getActivityDetailAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $data = $this->model->getActivityDetail($id);
            $data = $this->convertor->getActivityDetail($data);
        } else {
            $this->throwException(1, '查询条件错误，id不能为空');
        }
        $this->echoJson($data);
    }

    /**
     * 根据id修改活动信息
     *
     * @param
     *            int id 获取详情信息的id
     * @param
     *            array param 需要更新的字段
     * @return Json
     */
    public function updateActivityByIdAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $param = array();
            $param['hotelid'] = $this->getParamList('hotelid');
            $param['groupid'] = $this->getParamList('groupid');
            $param['tagid'] = $this->getParamList('tagid');
            $param['status'] = $this->getParamList('status');
            $param['pic'] = $this->getParamList('pic');
            $param['fromdate'] = $this->getParamList('fromdate');
            $param['todate'] = $this->getParamList('todate');
            $param['ordercount'] = $this->getParamList('ordercount');
            $param['title_lang1'] = $this->getParamList('title_lang1');
            $param['title_lang2'] = $this->getParamList('title_lang2');
            $param['title_lang3'] = $this->getParamList('title_lang3');
            $param['article_lang1'] = $this->getParamList('article_lang1');
            $param['article_lang2'] = $this->getParamList('article_lang2');
            $param['article_lang3'] = $this->getParamList('article_lang3');
            $param['sort'] = $this->getParamList('sort');
            $param['pdf'] = $this->getParamList('pdf');
            $param['video'] = $this->getParamList('video');
            $data = $this->model->updateActivityById($param, $id);
            $data = $this->convertor->statusConvertor($data);
        } else {
            $this->throwException(1, 'id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 添加活动信息
     *
     * @param
     *            array param 需要新增的信息
     * @return Json
     */
    public function addActivityAction()
    {
        $param = array();
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['groupid'] = intval($this->getParamList('groupid'));
        $param['tagid'] = intval($this->getParamList('tagid'));
        $param['status'] = intval($this->getParamList('status'));
        $param['pic'] = $this->getParamList('pic');
        $param['fromdate'] = $this->getParamList('fromdate');
        $param['todate'] = $this->getParamList('todate');
        $param['ordercount'] = $this->getParamList('ordercount');
        $param['title_lang1'] = trim($this->getParamList('title_lang1'));
        $param['title_lang2'] = trim($this->getParamList('title_lang2'));
        $param['title_lang3'] = trim($this->getParamList('title_lang3'));
        $param['sort'] = intval($this->getParamList('sort'));
        $param['pdf'] = trim($this->getParamList('pdf'));
        $param['video'] = trim($this->getParamList('video'));
        $data = $this->model->addActivity($param);
        $data = $this->convertor->statusConvertor(array('id' => $data));
        $this->echoSuccessData($data);
    }
}
