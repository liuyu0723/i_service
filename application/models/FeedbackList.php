<?php

/**
 * Class FeedbackModel
 * 物业调查反馈
 */
class FeedbackListModel extends \BaseModel
{

    private $dao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new Dao_FeedbackList();
    }

    /**
     * 获取Feedback列表信息
     * @param array param 查询条件
     * @return array
     */
    public function getFeedbackList(array $param)
    {
        $paramList = array();
        $param['id'] ? $paramList['id'] = $param['id'] : false;
        $param['hotelid'] ? $paramList['hotelid'] = $param['hotelid'] : false;
        $param['name'] ? $paramList['name'] = $param['name'] : false;
        isset($param['status']) ? $paramList['status'] = $param['status'] : false;
        $paramList['limit'] = $param['limit'];
        $paramList['page'] = $param['page'];
        return $this->dao->getFeedbackList($paramList);
    }

    /**
     * 获取Feedback数量
     *
     * @param
     *            array param 查询条件
     * @return array
     */
    public function getFeedbackCount(array $param)
    {
        $paramList = array();
        $param['id'] ? $paramList['id'] = $param['id'] : false;
        $param['hotelid'] ? $paramList['hotelid'] = $param['hotelid'] : false;
        $param['name'] ? $paramList['name'] = $param['name'] : false;
        isset($param['status']) ? $paramList['status'] = $param['status'] : false;
        return $this->dao->getFeedbackCount($paramList);
    }

    /**
     * 根据id查询Feedback信息
     * @param int id 查询的主键
     * @return array
     */
    public function getFeedbackDetail($id)
    {
        $result = array();
        if ($id) {
            $result = $this->dao->getFeedbackDetail($id);
        }
        return $result;
    }

    /**
     * 根据id更新Feedback信息
     * @param array param 需要更新的信息
     * @param int id 主键
     * @return array
     */
    public function updateFeedbackById($param, $id)
    {
        $result = false;
        if ($id) {
            isset($param['hotelid']) ? $info['hotelid'] = $param['hotelid'] : false;
            isset($param['status']) ? $info['status'] = $param['status'] : false;
            isset($param['sort']) ? $info['sort'] = $param['sort'] : 0;
            if ($info['sort'] == '') {
                $info['sort'] = 0;
            }
            isset($param['name']) ? $info['name'] = $param['name'] : '';
            $result = $this->dao->updateFeedbackById($info, $id);
        }
        return $result;
    }

    /**
     * Feedback新增信息
     * @param array param 需要增加的信息
     * @return array
     */
    public function addFeedback($param)
    {
        isset($param['hotelid']) ? $info['hotelid'] = $param['hotelid'] : false;
        isset($param['status']) ? $info['status'] = $param['status'] : false;
        isset($param['name']) ? $info['name'] = $param['name'] : false;
        isset($param['sort']) ? $info['sort'] = $param['sort'] : 0;
        isset($param['createtime']) ? $info['createtime'] = $param['createtime'] : $info['createtime'] = time();
        return $this->dao->addFeedback($info);
    }
}
