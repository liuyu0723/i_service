<?php

/**
 * 推送服务控制器类
 *
 */
class PushController extends \BaseController {

    /**
     *
     * @var PushModel
     */
    private $model;

    /**
     *
     * @var Convertor_Push
     */
    private $convertor;

    public function init() {
        parent::init();
        $this->model = new PushModel ();
        $this->convertor = new Convertor_Push ();
    }

    /**
     * 获取推送服务列表
     *
     * @return Json
     */
    public function getPushListAction() {
        $param = array();
        $param ['page'] = intval($this->getParamList('page', 1));
        $param ['limit'] = intval($this->getParamList('limit', 5));
        $param ['id'] = intval($this->getParamList('id'));
        $param ['type'] = intval($this->getParamList('type'));
        $param ['dataid'] = $this->getParamList('dataid');
        $param ['result'] = $this->getParamList('result');
        $param ['platform'] = $this->getParamList('platform');
        if (is_null($param ['result'])) {
            unset ($param ['result']);
        }
        $data = $this->model->getPushList($param);
        $count = $this->model->getPushCount($param);
        $data = $this->convertor->getPushListConvertor($data, $count, $param);
        $this->echoSuccessData($data);
    }

    /**
     * 根据id获取推送服务详情
     *
     * @param
     *            int id 获取详情信息的id
     * @return Json
     */
    public function getPushDetailAction() {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $data = $this->model->getPushDetail($id);
            $data = $this->convertor->getPushDetail($data);
        } else {
            $this->throwException(1, '查询条件错误，id不能为空');
        }
        $this->echoJson($data);
    }

    /**
     * 根据id修改推送服务信息
     *
     * @param
     *            int id 获取详情信息的id
     * @param
     *            array param 需要更新的字段
     * @return Json
     */
    public function updatePushByIdAction() {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $param = array();
            $param ['name'] = trim($this->getParamList('name'));
            $data = $this->model->updatePushById($param, $id);
            $data = $this->convertor->commonConvertor($data);
        } else {
            $this->throwException(1, 'id不能为空');
        }
        $this->echoJson($data);
    }

    /**
     * 添加推送服务信息
     *
     * @param
     *            array param 需要新增的信息
     * @return Json
     */
    public function addPushAction() {
        $param = array();
        $param ['type'] = intval($this->getParamList('type'));
        $param ['platform'] = intval($this->getParamList('platform'));
        $param ['dataid'] = intval($this->getParamList('dataid'));
        $param ['cn_title'] = $this->getParamList('cn_title');
        $param ['cn_value'] = $this->getParamList('cn_value');
        $param ['en_title'] = $this->getParamList('en_title');
        $param ['en_value'] = $this->getParamList('en_value');
        $param ['contentType'] = Enum_Push::PUSH_CONTENT_TYPE_URL;
        $param ['contentValue'] = $this->getParamList('url');
        $sendTime = intval($this->getParamList('send_time'));
        if (empty($sendTime)) {
            $data = $this->model->addPushOne($param);
        } else {
            $param['send_time'] = date('Y-m-d H:i:s', $sendTime);
            $data = $this->model->storeMsg($param);
        }
        $data = $this->convertor->statusConvertor(array('id' => $data));
        $this->echoSuccessData($data);
    }

    /**
     * GSM系统新增推送信息
     *
     * @param
     *            array param 需要新增的信息
     *
     * @return Json
     */
    public function gsmPushMsgAction() {
        $param = array();
        $param ['dataid'] = trim($this->getParamList('user_ids'));
        $param ['type'] = intval($this->getParamList('user_type'));
        $param ['cn_title'] = trim($this->getParamList('cn_title'));
        $param ['cn_value'] = trim($this->getParamList('cn_value'));
        $param ['en_title'] = trim($this->getParamList('en_title'));
        $param ['en_value'] = trim($this->getParamList('en_value'));
        $param ['url_code'] = trim($this->getParamList('url_code'));
        $result = $this->model->pushGsmMsg($param);
        $data = $this->convertor->gsmPushMsgConvertor(count($result));
        $logInfo = array('params' => json_encode($this->getParamList()), 'result' => json_encode($data));
        Log_File::writeLog('gsmPushMsg', implode("\n", $logInfo));
        $this->echoSuccessData($data);
    }

    /**
     * 用户获取推送服务列表
     *
     */
    public function getUserMsgListAction()
    {
        $token = trim($this->getParamList('token'));
        $userId = Auth_Login::getToken($token);
        if (empty ($userId)) {
            $this->throwException(2, 'token验证失败');
        }

        $param = array();
        $param['page'] = intval($this->getParamList('page', 1));
        $param['limit'] = intval($this->getParamList('limit', 10));
        $param['type'] = Enum_Push::PUSH_TYPE_USER;
        $param['dataid'] = $userId;
        $param['result'] = 0;
        $param['is_send'] = PushModel::TIMER_MSG_ALREADY_SEND;
        $data = $this->model->getPushList($param);
        $count = $this->model->getPushCount($param);
        $data = $this->convertor->userMsgListConvertor($data, $count, $param);
        $this->echoSuccessData($data);
    }

    /**
     * 员工获取推送服务列表
     */
    public function getStaffMsgListAction()
    {
        $token = trim($this->getParamList('token'));
        $staffId = Auth_Login::getToken($token, Auth_Login::STAFF_MARK);

        if (empty ($staffId)) {
            $this->throwException(2, 'token验证失败');
        }
        $param = array();
        $param ['page'] = intval($this->getParamList('page', 1));
        $param ['limit'] = intval($this->getParamList('limit', 10));
        $param ['type'] = Enum_Push::PUSH_TYPE_STAFF;
        $param ['dataid'] = $staffId;
        $param ['result'] = 0;
        $param['is_send'] = PushModel::TIMER_MSG_ALREADY_SEND;
        $data = $this->model->getPushList($param);
        $count = $this->model->getPushCount($param);
        $data = $this->convertor->userMsgListConvertor($data, $count, $param);
        $this->echoSuccessData($data);
    }


    /**
     * Get user's history message
     */
    public function getUserAppMsgListAction()
    {
        $token = trim($this->getParamList('token'));
        $userId = Auth_Login::getToken($token);
        if (empty ($userId)) {
            $this->throwException(2, 'token验证失败');
        }

        $param = array();
        $param['page'] = intval($this->getParamList('page', 1));
        $param['limit'] = intval($this->getParamList('limit', 20));
        $param['type'] = Enum_Push::PUSH_TYPE_USER;
        $param['dataid'] = $userId;
        $param['result'] = 0;
        $param['is_send'] = PushModel::TIMER_MSG_ALREADY_SEND;

        $data = $this->model->getPushList($param);
        $count = $this->model->getPushCount($param);
        $data = $this->convertor->userAppMsgListConvertor($data, $count, $param);
        $this->echoSuccessData($data);
    }


    /**
     * Get staff's history message
     */
    public function getStaffAppMsgListAction()
    {
        $token = trim($this->getParamList('token'));
        $staffId = Auth_Login::getToken($token, Auth_Login::STAFF_MARK);
        if (empty ($staffId)) {
            $this->throwException(2, 'token验证失败');
        }

        $param = array();
        $param['page'] = intval($this->getParamList('page', 1));
        $param['limit'] = intval($this->getParamList('limit', 20));
        $param['type'] = Enum_Push::PUSH_TYPE_STAFF;
        $param['dataid'] = $staffId;
        $param['result'] = 0;
        $param['is_send'] = PushModel::TIMER_MSG_ALREADY_SEND;
        $data = $this->model->getPushList($param);
        $count = $this->model->getPushCount($param);
        $data = $this->convertor->userAppMsgListConvertor($data, $count, $param);
        $this->echoSuccessData($data);
    }

    /**
     * 购物柜推送消息API
     */
    public function shoppingPushMsgAction()
    {
        $param = array();
        $param['type'] = intval($this->getParamList('user_type', Enum_Push::PUSH_TYPE_USER));
        $param['dataid'] = intval($this->getParamList('user_id'));
        $param['platform'] = intval($this->getParamList('platform', Enum_Push::PHONE_TYPE_ANDROID));

        $param['cn_title'] = trim($this->getParamList('cn_title'));
        $param['cn_value'] = trim($this->getParamList('cn_value'));
        $param['en_title'] = trim($this->getParamList('en_title'));
        $param['en_value'] = trim($this->getParamList('en_value'));

        $param['contentType'] = Enum_Push::PUSH_CONTENT_TYPE_URL;
        $param['contentValue'] = trim($this->getParamList('url'));

        $param['message_type'] = Enum_Push::PUSH_MESSAGE_TYPE_SHOPPING_BOX;

        $result = $this->model->addPushOne($param);
        $data = $this->convertor->shoppingMsgConvertor($result);
        $logInfo = array('params' => json_encode($this->getParamList()), 'result' => json_encode($data));
        Log_File::writeLog('shoppingboxPushMsg', implode("\n", $logInfo));
        $this->echoSuccessData($data);
    }

    /**
     * Get message list and send it if necessary
     */
    public function timerPushAction()
    {
        $now = time();
        $param = array(
            'is_send' => PushModel::TIMER_MSG_NOT_SEND,
            'send_time' => date("Y-m-d H:i:s", $now),
        );
        $msgList = $this->model->getPushList($param);
        if (count($msgList) == 0) {
            exit();
        } else {
            Log_File::writeSimpleLog($this->model::TIMER_CRONTAB_LOG, "Start");
            Log_File::writeSimpleLog($this->model::TIMER_CRONTAB_LOG, sprintf("%s messages need to be sent.", count($msgList)));
            $this->model->processList($msgList);
            Log_File::writeSimpleLog($this->model::TIMER_CRONTAB_LOG, "Finish");
        }

    }
}
