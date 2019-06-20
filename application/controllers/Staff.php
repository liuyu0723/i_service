<?php
use Frankli\Itearoa\Models\Staff;

/**
 * 物业员工控制器类
 *
 */
class StaffController extends \BaseController
{

    /**
     *
     * @var StaffModel
     */
    private $model;

    /**
     *
     * @var Convertor_Staff
     */
    private $convertor;

    public function init()
    {
        parent::init();
        $this->model = new StaffModel();
        $this->convertor = new Convertor_Staff();
    }

    /**
     * 获取物业员工列表
     *
     * @return Json
     */
    public function getStaffListAction()
    {
        $param = array();
        $param['name'] = trim($this->getParamList('name'));
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['groupid'] = intval($this->getParamList('groupid'));
        $param['staffid'] = intval($this->getParamList('staffid'));
        $param['id'] = intval($this->getParamList('id'));
        $param['limit'] = $this->getParamList('limit');
        $param['page'] = $this->getParamList('page');
        $data = $this->model->getStaffList($param);
        if ($param['id'] > 0) {
            $count = 1;
        } else {
            $count = $this->model->getStaffListCount($param);
        }
        $data = $this->convertor->getStaffListConvertor($data, $param, $count);
        $this->echoSuccessData($data);
    }

    /**
     * 根据id获取物业员工详情
     *
     * @param
     *            int id 获取详情信息的id
     * @return Json
     */
    public function getStaffDetailAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $data = $this->model->getStaffDetail($id);
            $data = $this->convertor->getStaffDetail($data);
        } else {
            $this->throwException(1, '查询条件错误，id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * Update staff's info
     */
    public function updateStaffByIdAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $param = array();
            $param['lname'] = trim($this->getParamList('lname'));
            $param['staffid'] = intval($this->getParamList('staffid'));
            $param['hotel_list'] = trim($this->getParamList('hotel_list'));
            $param['staff_web_hotel_id'] = intval($this->getParamList('staff_web_hotel_id'));
            $param['schedule'] = $this->getParamList('schedule');
            $param['washing_push'] = $this->getParamList('washing_push');
            $param['permission'] = $this->getParamList('permission');
            $data = $this->model->updateStaffById($param, $id);
            if ($data) {
                $this->echoSuccessData($data);
            } else {
                $this->throwException(1, 'DB fail');
            }
        } else {
            $this->throwException(1, 'id不能为空');
        }
    }

    /**
     * 添加物业员工信息
     *
     */
    public function addStaffAction()
    {
        $param = array();
        $param['lname'] = trim($this->getParamList('lname'));
        $param['staffid'] = intval($this->getParamList('staffid'));
        $param['groupid'] = trim($this->getParamList('groupid'));
        $param['identity'] = trim($this->getParamList('identity'));
        $data = $this->model->addStaff($param);
        $data = $this->convertor->statusConvertor(array('id' => $data));
        $this->echoSuccessData($data);
    }

    /**
     * 登录
     *
     * @param
     *            string lname 员工登录帐号
     * @param
     *            string pwd 员工登录密码
     * @param
     *            int hotelid 物业ID
     * @param
     *            int groupid 集团ID
     * @param
     *            int platform 平台ID
     * @param
     *            string identity 平台标识
     * @return Json
     */
    public function loginAction()
    {
        $param = array();
        $param['lname'] = trim($this->getParamList('lname'));
        $param['pwd'] = trim($this->getParamList('pwd'));
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['groupid'] = intval($this->getParamList('groupid'));
        $param['platform'] = intval($this->getParamList('platform'));
        $param['identity'] = trim($this->getParamList('identity'));
        $param['isAd'] = intval($this->getParamList('ad'));
        $result = $this->model->login($param);
        $result = $this->convertor->userInfoConvertor($result);
        $this->echoSuccessData($result);
    }




    /**
     * app登录
     *
     * @param
     *            string lname 员工登录帐号
     * @param
     *            string pwd 员工登录密码
     * @param
     *            int hotelid 物业ID
     * @param
     *            int groupid 集团ID
     * @param
     *            int platform 平台ID
     * @param
     *            string identity 平台标识
     * @return Json
     */
    public function appLoginAction()
    {
        $param = array();
        $param['lname'] = trim($this->getParamList('lname'));
        $param['pwd'] = trim($this->getParamList('pwd'));
        $param['hotelid'] = intval($this->getParamList('hotelid'));
        $param['groupid'] = intval($this->getParamList('groupid'));
        $param['platform'] = intval($this->getParamList('platform'));
        $param['identity'] = trim($this->getParamList('identity'));
        $param['isAd'] = intval($this->getParamList('ad'));
        $result = $this->model->appLogin($param);
        $result = $this->convertor->userInfoConvertor($result);
        $this->echoSuccessData($result);
    }



    /**
     * 根据Token获取员工信息
     *
     * @param
     *            string token
     * @return Json
     */
    public function getStaffInfoByTokenAction()
    {
        $token = trim($this->getParamList('token'));
        $userId = Auth_Login::getToken($token, 2);
        if (empty($userId)) {
            $this->throwException(2, 'token验证失败');
        }
        $userInfo = $this->model->getStaffDetail($userId);
        $userInfo['token'] = $token;
        $result = $this->convertor->userInfoConvertor($userInfo);
        $this->echoSuccessData($result);
    }

    /**
     * Action for staff help user reset pin code
     */
    public function resetUserPinAction()
    {
        $params = array();
        $params['token'] = trim($this->getParamList('token'));
        $params['userid'] = intval($this->getParamList('userid'));
        $model = new UserModel();
        $result = $model->staffResetPin($params);
        $this->echoSuccessData($result);
    }

    /**
     * todo extend staff table, store staff's language for msg push ESTSSLFMP
     */
    public function updateStaffLangAction()
    { }
}
