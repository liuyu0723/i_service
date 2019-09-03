<?php

/**
 * 活动结果转换器类
 */
class Convertor_GroupActivity extends Convertor_Base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 可用活动列表转换器
     *
     * @param array $activityList
     *            活动列表数据
     * @param array $tagList
     *            活动标签数据
     * @param int $activityCount
     *            活动列表数据总数
     * @param array $param
     *            扩展参数
     * @return multitype:multitype: array
     */
    public function getEffectiveActivityListConvertor($activityList, $tagList, $activityCount, $param)
    {
        $tagListNew = array();
        foreach ($tagList as $tag) {
            $tagListNew[$tag['id']] = $this->handlerMultiLang('title', $tag);
        }
        $data = array('list' => array());
        foreach ($activityList as $activity) {
            $activityTemp = array();
            $activityTemp['id'] = $activity['id'];
            $activityTemp['title'] = $activity['title'];
            $activityTemp['article'] = Enum_Img::getPathByKeyAndType($activity['article']);
            $activityTemp['pdf'] = $activity['pdf'] ? Enum_Img::getPathByKeyAndType($activity['pdf']) : '';
            $activityTemp['video'] = $activity['video'] ? Enum_Img::getPathByKeyAndType($activity['video']) : '';
            $activityTemp['tagId'] = $activity['tagid'];
            $activityTemp['pic'] = $activity['pic'];
            $activityTemp['fromdate'] = $activity['fromdate'];
            $activityTemp['todate'] = $activity['todate'];
            $activityTemp['ordercount'] = $activity['ordercount'];
            $activityTemp['tagName'] = $tagListNew[$activityTemp['tagId']];
            $activityTemp['createtime'] = $activity['createtime'];
            $activityTemp['updatetime'] = $activity['updatetime'];
            $data['list'][] = $activityTemp;
        }
        $data['total'] = $activityCount;
        $data['page'] = $param['page'];
        $data['limit'] = $param['limit'];
        $data['nextPage'] = Util_Tools::getNextPage($data['page'], $data['limit'], $data['total']);
        return $data;
    }

    /**
     * 后台活动列表转换器
     *
     * @param array $list
     *            活动列表数据
     * @param int $count
     *            活动列表数据总数
     * @param array $param
     *            扩展参数
     * @return multitype:multitype: array
     */
    public function getActivityListConvertor($list, $count, $param)
    {
        $data = array('list' => array());
        $groupIdList = array_column($list, 'groupid');
        $groupModel = new GroupModel();
        $groupInfoList = $groupModel->getGroupList(array('id' => $groupIdList));
        $groupNameList = array_column($groupInfoList, 'name', 'id');
        $tagidList = array_column($list, 'tagid');
        if ($tagidList) {
            $activityTagModel = new GroupActivityTagModel();
            $activityTagList = $activityTagModel->getActivityTagList(array('id' => $tagidList));
            $activityTagNameList = array_column($activityTagList, 'title', 'id');
        }
        foreach ($list as $key => $value) {
            $activityTemp = array();
            $activityTemp['id'] = $value['id'];
            $activityTemp['title'] = $value['title'];
            $activityTemp['article'] = $value['article'];
            $activityTemp['pic'] = $value['pic'];
            $activityTemp['fromdate'] = $value['fromdate'];
            $activityTemp['todate'] = $value['todate'];
            $activityTemp['ordercount'] = $value['ordercount'];
            $activityTemp['groupid'] = $value['groupid'];
            $activityTemp['groupName'] = $groupNameList[$activityTemp['groupid']];
            $activityTemp['status'] = $value['status'];
            $activityTemp['createtime'] = $value['createtime'];
            $activityTemp['updatetime'] = $value['updatetime'];
            $activityTemp['tagid'] = $value['tagid'];
            $activityTemp['tagName'] = $activityTagNameList[$activityTemp['tagid']];
            $activityTemp['sort'] = $value['sort'];
            $activityTemp['pdf'] = $value['pdf'];
            $activityTemp['video'] = $value['video'];
            $activityTemp['pic'] = $value['pic'];
            $activityTemp['fromdate'] = $value['fromdate'];
            $activityTemp['todate'] = $value['todate'];
            $activityTemp['count'] = $value['count'];
            $data['list'][] = $activityTemp;
        }
        $data['total'] = $count;
        $data['page'] = $param['page'];
        $data['limit'] = $param['limit'];
        $data['nextPage'] = Util_Tools::getNextPage($data['page'], $data['limit'], $data['total']);
        return $data;
    }
}
