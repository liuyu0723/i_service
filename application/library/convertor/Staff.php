<?php

/**
 * 员工结果转换器
 */
class Convertor_Staff extends Convertor_Base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 员工信息
     *
     * @param array $list
     *            员工信息
     * @return array
     */
    public function userInfoConvertor($list)
    {
        $data = array();
        $platformNameList = Enum_Platform::getPlatformNameList();
        $data ['id'] = $list ['id'];
        $data ['hotelid'] = $list ['hotelid'];
        $data ['groupid'] = $list ['groupid'];
        $data ['staffid'] = $list ['staffid'];
        $data ['createtime'] = $list ['createtime'];
        $data ['lastlogintime'] = $list ['lastlogintime'];
        $data ['lastloginip'] = Util_Tools::ntoip($list ['lastloginip']);
        $data ['platform'] = $list ['platform'];
        $data ['platformName'] = $platformNameList [$list ['platform']];
        $data ['identity'] = $list ['identity'];
        $data ['token'] = $list ['token'];
        $data['staff_web_hotel_id'] = $list['staff_web_hotel_id'];
        $staffModel = new StaffModel();
        $data ['serviceUrl'] = $staffModel->getGsmRedirect(array(
            'StaffID' => $data['staffid']
        ));
        $data['lname'] = $list['lname'];
        $data['hotel_list'] = $list['hotel_list'];
        $data['hotel_list_detail'] = $list['hotel_list_detail'];
        $data['permission'] = $list['permission'];
        return $data;
    }

    /**
     * @param $list
     * @return mixed
     */
    public function getStaffListConvertor($list, $param, $count)
    {
        $data = array();
        $tmp = array();
        foreach ($list as $item) {
            if (empty($item['realname'])) {
                $item['realname'] = $item['lname'];
            }
            $tmp[] = $item;
        }
        $data['list'] = $tmp;
        $data['total'] = $count;
        $data['page'] = $param['page'];
        $data['limit'] = $param['limit'];
        $data['nextPage'] = Util_Tools::getNextPage($data['page'], $data['limit'], $data['total']);
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getStaffDetail($data)
    {
        //todo but not used for now
        return $data;
    }
}