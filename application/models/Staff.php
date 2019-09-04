<?php

/**
 * Class StaffModel
 * 员工管理
 */
class StaffModel extends \BaseModel
{

    const STAFF_WEB_IDENTIFY = 'staff_login_from_web';
    const STAFF_ID_PREFIX = 'iService';


    const GROUP_LOGIN_SERVICE = array(
        2,
    );

    private $dao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new Dao_Staff();
    }

    /**
     * 获取Staff列表信息
     *
     * @param
     *            array param 查询条件
     * @return array
     */
    public function getStaffList(array $param)
    {
        $param['id'] ? $paramList['id'] = $param['id'] : false;
        $param['staffid'] ? $paramList['staffid'] = $param['staffid'] : false;
        $param['hotelid'] ? $paramList['hotelid'] = $param['hotelid'] : false;
        $param['groupid'] ? $paramList['groupid'] = $param['groupid'] : false;
        $param['name'] ? $paramList['name'] = $param['name'] : false;
        $param['department_id'] ? $paramList['department_id'] = $param['department_id'] : false;
        isset($param['level']) ? $paramList['level'] = $param['level'] : false;
        $paramList['limit'] = $param['limit'];
        $paramList['page'] = $param['page'];
        return $this->dao->getStaffList($paramList);
    }


    public function getStaffListCount(array $param)
    {
        $param['id'] ? $paramList['id'] = $param['id'] : false;
        $param['staffid'] ? $paramList['staffid'] = $param['staffid'] : false;
        $param['hotelid'] ? $paramList['hotelid'] = $param['hotelid'] : false;
        $param['groupid'] ? $paramList['groupid'] = $param['groupid'] : false;
        $param['name'] ? $paramList['name'] = $param['name'] : false;
        $param['department_id'] ? $paramList['department_id'] = $param['department_id'] : false;
        isset($param['level']) ? $paramList['level'] = $param['level'] : false;
        $paramList['limit'] = $param['limit'];
        $paramList['page'] = $param['page'];
        return $this->dao->getStaffListCount($paramList);
    }

    /**
     * 根据id查询Staff信息
     *
     * @param
     *            int id 查询的主键
     * @return array
     */
    public function getStaffDetail($id)
    {
        $result = array();
        if ($id) {
            $result = $this->dao->getStaffDetail($id);
        }
        return $result;
    }

    /**
     * 根据staffId查询Staff信息
     *
     * @param
     *            int id 查询的主键
     * @return array
     */
    public function getStaffDetailByStaffId($staffId)
    {
        $result = array();
        if ($staffId) {
            $result = $this->dao->getStaffDetailByStaffId($staffId);
        }
        return $result;
    }

    /**
     * 根据id更新Staff信息
     *
     * @param
     *            array param 需要更新的信息
     * @param
     *            int id 主键
     * @return array
     */
    public function updateStaffById($param, $id)
    {
        $result = false;
        if ($id) {
            $param['lname'] ? $info['lname'] = $param['lname'] : false;
            intval($param['staffid']) ? $info['staffid'] = intval($param['staffid']) : false;
            intval($param['hotelid']) ? $info['hotelid'] = intval($param['hotelid']) : false;
            intval($param['groupid']) ? $info['groupid'] = intval($param['groupid']) : false;
            $info['lastlogintime'] = time();
            $info['lastloginip'] = Util_Tools::ipton(Util_Http::getIP());
            isset($param['platform']) ? $info['platform'] = intval($param['platform']) : false;
            $param['identity'] ? $info['identity'] = $param['identity'] : false;
            $param['staff_web_hotel_id'] ? $info['staff_web_hotel_id'] = intval($param['staff_web_hotel_id']) : false;
            $param['admin_id'] ? $info['admin_id'] = intval($param['admin_id']) : false;
            $param['hotel_list'] ? $info['hotel_list'] = str_replace(',99', '', str_replace('99,', '', trim($param['hotel_list']))) : false;
            if (!is_null($param['schedule'])) {
                $info['schedule'] = trim($param['schedule']);
                unset($info['lastlogintime']);
                unset($info['lastloginip']);
            }
            if (!is_null($param['washing_push'])) {
                $info['washing_push'] = intval($param['washing_push']);
                unset($info['lastlogintime']);
                unset($info['lastloginip']);
            }
            if (!is_null($param['permission'])) {
                $info['permission'] = trim($param['permission']);
                unset($info['lastlogintime']);
                unset($info['lastloginip']);
            }




            $result = $this->dao->updateStaffById($info, $id);
        }
        return $result;
    }

    /**
     * Staff新增信息
     *
     * @param
     *            array param 需要增加的信息
     * @return int
     */
    public function addStaff($param)
    {
        $info['lname'] = $param['lname'];
        $info['groupid'] = intval($param['groupid']);
        $info['staffid'] = strval($param['staffid']);
        $info['createtime'] = time();
        $info['lastlogintime'] = time();
        $info['lastloginip'] = Util_Tools::ipton(Util_Http::getIP());
        $info['platform'] = intval($param['platform']);
        $info['identity'] = $param['identity'];
        $info['admin_id'] = intval($param['admin_id']);
        return $this->dao->addStaff($info);
    }


    /**
     * Staff新增信息
     *
     * @param
     *            array param 需要增加的信息
     * @return int
     */
    public function addStaffWithHotelList($param)
    {
        $info['lname'] = $param['lname'];
        $info['groupid'] = intval($param['groupid']);
        $info['staffid'] = strval($param['staffid']);
        $info['createtime'] = time();
        $info['lastlogintime'] = time();
        $info['lastloginip'] = Util_Tools::ipton(Util_Http::getIP());
        $info['platform'] = intval($param['platform']);
        $info['identity'] = $param['identity'];
        $info['admin_id'] = intval($param['admin_id']);
        $info['hotel_list'] = $param['hotel_list'];
        return $this->dao->addStaff($info);
    }

    /**
     * 获取用StaffId信息，跟gsm接口交互
     *
     * @param
     *            array param 需要增加的信息
     * @return array
     */
    public function getStaffIdInfo($param)
    {
        $paramList = array();
        if (in_array($param['groupid'], self::GROUP_LOGIN_SERVICE)) {
            $staffId = $this->_loginWithService($param);
        } else {
            $paramList['LType'] = intval($param['isAd']);
            $paramList['LName'] = $param['lname'];
            $paramList['Pwd'] = $param['pwd'];
            $paramList = Enum_Gsm::genEncryptGsmParams($paramList);
            $gsmResult = Rpc_Gsm::send(Enum_Gsm::STAFF_LOGIN_METHOD, $paramList);
            $staffId = $gsmResult['StaffID'];
        }

        return array('staffId' => $staffId);
    }

    /**
     * 获取GSM推送的员工地址
     * @param $param
     * @return string
     */
    public function getGsmRedirect($param)
    {
        $paramList = array();
        $paramList['StaffID'] = $param['StaffID'];
        $paramList = Enum_Gsm::genEncryptGsmParams($paramList);
        $paramList['OrderID'] = $param['OrderID'];

        $url = Rpc_Gsm::makeGsmUrl(Enum_Gsm::STAFF_REDIRECT_METHOD, $paramList);
        return $url;
    }

    /**
     * 员工登录
     *
     * @param array $param
     * @return array
     */
    public function login($param)
    {
        if (empty($param['lname']) || empty($param['pwd'])) {
            $this->throwException('登录信息不正确', 2);
        }
        if (empty($param['groupid'])) {
            $this->throwException('缺少集团参数', 3);
        }

        // 获取Oid
        $staffIdInfo = $this->getStaffIdInfo($param);
        if (empty($staffIdInfo['staffId'])) {
            $this->throwException('账号密码错误，登录失败', 4);
        }

        // 获取用户信息
        $getStaffInfo = $this->getStaffDetailByStaffId($staffIdInfo['staffId']);
        $userId = $getStaffInfo['id'];
        if (empty($userId)) {
            $this->throwException('员工未配置，请联系管理员添加', 5);
        } else {
            $hotelList = explode(",", $getStaffInfo['hotel_list']);
            if (!empty($param['hotelid']) && !in_array($param['hotelid'], $hotelList)) {
                $this->throwException('员工无对应物业权限，请选择有权限的物业', 5);
            }
        }

        $newStaffInfo = array(
            'groupid' => $param['groupid'],
            'lname' => $param['lname'],
            'identity' => trim($param['identity'])
        );
        if (!empty($param['hotelid'])) {
            $newStaffInfo['hotelid'] = intval($param['hotelid']);
        }

        //don't change platform when login from web
        if ($newStaffInfo['identity'] != self::STAFF_WEB_IDENTIFY) {
            $newStaffInfo['platform'] = intval($param['platform']);
        }
        $newStaffInfo['admin_id'] = $this->getAdminId(intval($newStaffInfo['groupid']), $staffIdInfo['staffId']);
        if (!$this->updateStaffById($newStaffInfo, $userId)) {
            $this->throwException('登录失败，请重试', 5);
        }

        $userInfo = $this->getStaffDetail($userId);
        if (!empty($userInfo['hotel_list'])) {
            $userInfo['hotel_list'] = explode(',', $userInfo['hotel_list']);
            $hotelModel = new HotelListModel();
            $hotelList = $hotelModel->getHotelListList(array(
                'id' => $userInfo['hotel_list'],
                'groupid' => $param['groupid'],
            ));
        } else {
            $userInfo['hotel_list'] = array();
            $hotelList = array();
        }
        $userInfo['hotel_list_detail'] = array();
        foreach ($hotelList as $hotel) {
            $detail = array(
                'id' => $hotel['id'],
                'name_lang1' => $hotel['name_lang1'],
                'name_lang2' => $hotel['name_lang2'],
                'name_lang3' => $hotel['name_lang3'],
            );
            $userInfo['hotel_list_detail'][] = $detail;
        }

        if (empty($userInfo['permission'])) {
            $userInfo['permission'] = array();
        } else {
            $userInfo['permission'] = explode(',', $userInfo['permission']);
        }

        $userInfo['token'] = Auth_Login::makeToken($userId, Auth_Login::STAFF_MARK, 30 * 24 * 3600);
        return $userInfo;
    }



    /**
     * app员工登录
     * 
     * 1. 添加一个新hotel到hotel_list
     * ID = 99，
     * GroupId = 调用appLogin时发送的GroupId
     * Name = 新员工登录
     * 2. 调用appLogin登录
     * 登录时酒店必须选择 '新员工登录酒店'
     * @param array $param
     * @return array
     */
    public function appLogin($param)
    {
        if (empty($param['lname']) || empty($param['pwd'])) {
            $this->throwException('登录信息不正确', 2);
        }
        if (empty($param['groupid'])) {
            $this->throwException('缺少集团参数', 3);
        }

        if (empty($param['hotelid'])) {
            $this->throwException('缺少酒店参数', 3);
        }

        // 获取Oid
        $staffIdInfo = $this->getStaffIdInfo($param);
        if (empty($staffIdInfo['staffId'])) {
            $this->throwException('账号密码错误，登录失败', 4);
        }

        $_gsmStaffId = $staffIdInfo['staffId'];
        // 获取用户信息
        $getStaffInfo = $this->getStaffDetailByStaffId($staffIdInfo['staffId']);
        $userId = $getStaffInfo['id'];
        if (empty($userId)) {

            if (intval($param['hotelid']) != 99) {
                $this->throwException('员工无对应物业权限，请选择有权限的物业', 5);
            }

            if ($_gsmStaffId > 0) {
                //1. 添加 staff 到iservice数据库
                $paramsForAddStaff = array(
                    'lname' => $param['lname'],
                    'groupid' => $param['groupid'],
                    'staffid' => $_gsmStaffId,
                    'platform' => 1,
                    'identity' => self::STAFF_WEB_IDENTIFY,
                    'admin_id' => 0,
                    'hotel_list' => '99'  //员工临时登录物业
                );
                //1.1. 添加 staff 到iservice数据库
                $newStaffId = $this->addStaffWithHotelList($paramsForAddStaff);
                //2. 检查 add staff 返回值
                if ($newStaffId <= 0) {
                    $this->throwException('添加新员工失败, 请重试', 5);
                }
                //3. 从新调用 app login 服务
                else {
                    return $this->appLogin($param);
                }
            }
        } else {
            $hotelList = explode(",", $getStaffInfo['hotel_list']);
            if (!empty($param['hotelid']) && !in_array($param['hotelid'], $hotelList)) {
                $this->throwException('员工无对应物业权限，请选择有权限的物业', 5);
            }
        }

        $newStaffInfo = array(
            'groupid' => $param['groupid'],
            'lname' => $param['lname'],
            'identity' => trim($param['identity'])
        );
        if (!empty($param['hotelid'])) {
            $newStaffInfo['hotelid'] = intval($param['hotelid']);
        }

        //don't change platform when login from web
        if ($newStaffInfo['identity'] != self::STAFF_WEB_IDENTIFY) {
            $newStaffInfo['platform'] = intval($param['platform']);
        }
        $newStaffInfo['admin_id'] = $this->getAdminId(intval($newStaffInfo['groupid']), $staffIdInfo['staffId']);
        if (!$this->updateStaffById($newStaffInfo, $userId)) {
            $this->throwException('登录失败，请重试', 5);
        }

        $userInfo = $this->getStaffDetail($userId);
        if (!empty($userInfo['hotel_list'])) {
            $userInfo['hotel_list'] = explode(',', $userInfo['hotel_list']);
            $hotelModel = new HotelListModel();
            $hotelList = $hotelModel->getHotelListList(array(
                'id' => $userInfo['hotel_list'],
                'groupid' => $param['groupid'],
            ));
        } else {
            $userInfo['hotel_list'] = array();
            $hotelList = array();
        }
        $userInfo['hotel_list_detail'] = array();
        foreach ($hotelList as $hotel) {
            $detail = array(
                'id' => $hotel['id'],
                'name_lang1' => $hotel['name_lang1'],
                'name_lang2' => $hotel['name_lang2'],
                'name_lang3' => $hotel['name_lang3'],
            );
            $userInfo['hotel_list_detail'][] = $detail;
        }

        if (empty($userInfo['permission'])) {
            $userInfo['permission'] = array();
        } else {
            $userInfo['permission'] = explode(',', $userInfo['permission']);
        }

        $userInfo['token'] = Auth_Login::makeToken($userId, Auth_Login::STAFF_MARK, 30 * 24 * 3600);
        return $userInfo;
    }




    /**
     * Staff login with iService auth
     *
     * @param $param
     * @return string
     */
    private function _loginWithService($param)
    {

        $paramList['username'] = $param['lname'];
        $paramList['password'] = $param['pwd'];

        if (empty($param['lname']) || empty($param['pwd'])) {
            $this->throwException('登录信息不正确', 2);
        }
        if (empty($param['hotelid']) || empty($param['groupid'])) {
            //hotel and group are not necessary when login from staff web
            if ($param['identity'] != self::STAFF_WEB_IDENTIFY) {
                $this->throwException('酒店集团信息不正确', 3);
            }
        }

        $daoAdmin = new Dao_HotelAdministrator();
        $admin = $daoAdmin->getHotelAdministratorDetailByUsername($paramList['username']);
        if (!$admin || $admin['password'] != md5(Enum_Login::getMd5Pass($paramList['password']))) {
            $this->throwException('账号密码错误，登录失败', 4);
        }
        $staffId = $this->_getStaffId($param['hotelid'], $admin['id']);
        return $staffId;
    }

    /**
     * @param int $hotelid
     * @return string
     */
    private function _getStaffId(int $hotelid, int $userId): string
    {
        return self::STAFF_ID_PREFIX . '_' . $hotelid . '_' . $userId;
    }

    /**
     * Extract admin id from staffid
     *
     * @param string $staffId
     * @return int
     */
    public function getAdminId(int $groupId, string $staffId): int
    {
        if (!in_array($groupId, self::GROUP_LOGIN_SERVICE)) {
            return 0;
        }
        preg_match('/_(\d+)$/', $staffId, $matches);
        if (count($matches) != 2) {
            $this->throwException('StaffId format error', 1);
        }
        return intval($matches[1]);
    }
}
