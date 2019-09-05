<?php

use Illuminate\Database\Capsule\Manager as DB;


class ActivityModel extends \BaseModel
{

    private $dao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new Dao_Activity();
    }

    /**
     * 获取活动列表信息
     *
     * @param
     *            array param 查询条件
     * @return array
     */
    public function getActivityList(array $param)
    {
        $param['id'] ? $paramList['id'] = $param['id'] : false;
        $param['hotelid'] ? $paramList['hotelid'] = intval($param['hotelid']) : false;
        $param['groupid'] ? $paramList['groupid'] = intval($param['groupid']) : false;
        $param['tagid'] ? $paramList['tagid'] = intval($param['tagid']) : false;
        $param['title'] ? $paramList['title'] = $param['title'] : false;
        isset($param['status']) ? $paramList['status'] = intval($param['status']) : false;
        $paramList['limit'] = $param['limit'];
        $paramList['page'] = $param['page'];
        return $this->dao->getActivityList($paramList);
    }

    /**
     * 获取Activity数量
     *
     * @param
     *            array param 查询条件
     * @return array
     */
    public function getActivityCount(array $param)
    {
        $paramList = array();
        $param['id'] ? $paramList['id'] = intval($param['id']) : false;
        $param['hotelid'] ? $paramList['hotelid'] = intval($param['hotelid']) : false;
        $param['groupid'] ? $paramList['groupid'] = intval($param['groupid']) : false;
        $param['tagid'] ? $paramList['tagid'] = intval($param['tagid']) : false;
        $param['title'] ? $paramList['title'] = intval($param['title']) : false;
        isset($param['status']) ? $paramList['status'] = intval($param['status']) : false;
        return $this->dao->getActivityCount($paramList);
    }

    /**
     * 根据id查询Activity信息
     *
     * @param
     *            int id 查询的主键
     * @return array
     */
    public function getActivityDetail($id)
    {
        $result = array();
        if ($id) {
            $result = $this->dao->getActivityDetail($id);
        }
        return $result;
    }

    /**
     * 根据id更新Activity信息
     *
     * @param
     *            array param 需要更新的信息
     * @param
     *            int id 主键
     * @return array
     */
    public function updateActivityById($param, $id)
    {
        $result = false;
        if ($id) {
            isset($param['hotelid']) ? $info['hotelid'] = $param['hotelid'] : false;
            isset($param['groupid']) ? $info['groupid'] = $param['groupid'] : false;
            isset($param['tagid']) ? $info['tagid'] = $param['tagid'] : false;
            isset($param['pic']) ? $info['pic'] = $param['pic'] : false;
            isset($param['fromdate']) ? $info['fromdate'] = $param['fromdate'] : false;
            isset($param['todate']) ? $info['todate'] = $param['todate'] : false;
            isset($param['ordercount']) ? $info['ordercount'] = $param['ordercount'] : false;
            isset($param['status']) ? $info['status'] = $param['status'] : false;
            isset($param['title_lang1']) ? $info['title_lang1'] = $param['title_lang1'] : false;
            isset($param['title_lang2']) ? $info['title_lang2'] = $param['title_lang2'] : false;
            isset($param['title_lang3']) ? $info['title_lang3'] = $param['title_lang3'] : false;
            isset($param['article_lang1']) ? $info['article_lang1'] = $param['article_lang1'] : false;
            isset($param['article_lang2']) ? $info['article_lang2'] = $param['article_lang2'] : false;
            isset($param['article_lang3']) ? $info['article_lang3'] = $param['article_lang3'] : false;
            isset($param['header_lang1']) ? $info['header_lang1'] = $param['header_lang1'] : false;
            isset($param['header_lang2']) ? $info['header_lang2'] = $param['header_lang2'] : false;
            isset($param['header_lang3']) ? $info['header_lang3'] = $param['header_lang3'] : false;
            isset($param['footer_lang1']) ? $info['footer_lang1'] = $param['footer_lang1'] : false;
            isset($param['footer_lang2']) ? $info['footer_lang2'] = $param['footer_lang2'] : false;
            isset($param['footer_lang3']) ? $info['footer_lang3'] = $param['footer_lang3'] : false;
            isset($param['sort']) ? $info['sort'] = $param['sort'] : false;
            isset($param['pdf']) ? $info['pdf'] = $param['pdf'] : false;
            isset($param['video']) ? $info['video'] = $param['video'] : false;

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
            $result = $this->dao->updateActivityById($info, $id);
        }
        return $result;
    }

    /**
     * Activity新增信息
     *
     * @param
     *            array param 需要增加的信息
     * @return array
     */
    public function addActivity($param)
    {
        isset($param['hotelid']) ? $info['hotelid'] = $param['hotelid'] : false;
        isset($param['groupid']) ? $info['groupid'] = $param['groupid'] : false;
        isset($param['tagid']) ? $info['tagid'] = $param['tagid'] : false;
        isset($param['status']) ? $info['status'] = $param['status'] : false;
        isset($param['pic']) ? $info['pic'] = $param['pic'] : false;
        isset($param['fromdate']) ? $info['fromdate'] = $param['fromdate'] : false;
        isset($param['todate']) ? $info['todate'] = $param['todate'] : false;
        isset($param['ordercount']) ? $info['ordercount'] = $param['ordercount'] : false;
        isset($param['title_lang1']) ? $info['title_lang1'] = $param['title_lang1'] : false;
        isset($param['title_lang2']) ? $info['title_lang2'] = $param['title_lang2'] : false;
        isset($param['title_lang3']) ? $info['title_lang3'] = $param['title_lang3'] : false;
        isset($param['sort']) ? $info['sort'] = $param['sort'] : false;
        isset($param['pdf']) ? $info['pdf'] = $param['pdf'] : false;
        isset($param['video']) ? $info['video'] = $param['video'] : false;
        $info['createtime'] = time();
        $info['updatetime'] = $info['createtime'];

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

        return $this->dao->addActivity($info);
    }

    public function addPhoto($params): int
    {
        if (empty($params['activity_id']) || empty($params['pic']) || empty($params['hotelid'])) {
            $this->throwException("lack param[activity_id, pic]", 1);
        }
        $model = new Eloquent_ActivityPhoto();
        $model->hotelid = $params['hotelid'];
        $model->activity_id = $params['activity_id'];
        $model->pic = $params['pic'];
        $model->status = $params['status'];
        $model->sort = $params['sort'];
        $model->save();

        return $model->id;
    }

    public function updatePhotoById(array $params, int $id)
    {
        if (empty($id)) {
            $this->throwException("lack param[id]", 1);
        }
        $info = array();
        is_null($params['activity_id']) ? false : $info['activity_id'] = intval($params['activity_id']);
        is_null($params['pic']) ? false : $info['pic'] = trim($params['pic']);
        is_null($params['sort']) ? false : $info['sort'] = intval($params['sort']);
        is_null($params['status']) ? false : $info['status'] = intval($params['status']);

        $result = DB::table('hotel_activity_photos')->where('id', '=', $id)->update($info);
        return $result;
    }

    public function getPhotoList(array $params)
    {
        $paramList = array();
        is_null($params['hotelid']) ? false : $paramList['hotelid'] = intval($params['hotelid']);
        is_null($params['activity_id']) ? false : $paramList['activity_id'] = intval($params['activity_id']);
        is_null($params['status']) ? false : $paramList['status'] = intval($params['status']);

        $query = Eloquent_ActivityPhoto::where($paramList)->orderBy('sort', 'DESC')->orderBy('id', 'DESC');
        if ($params['limit'] > 0) {
            $data = $query->paginate($params['limit'], ['*'], 'page', $params['page'])->toArray();
        } else {
            $data = $query->get()->toArray();
        }

        return $data;
    }
}
