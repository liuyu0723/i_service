<?php

/**
 * Class Customer
 * 
 */
class CustomerModel extends \BaseModel
{

    private $dao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new Dao_Customer();
    }

    /**
     * 单条增加customer数据
     * @param array
     * `full_name` VARCHAR(255) NULL,
     * `email` VARCHAR(255) NOT NULL,
     * `phone` VARCHAR(255) NULL ,
     * `address` VARCHAR(2047) NULL ,
     * `id` VARCHAR(255) NULL ,
     * `id_type_code` TINYINT(4) NULL ,
     * `password` VARCHAR(255) NOT NULL  COMMENT 'MD5(customer_password + password_salt)',
     * `password_salt` VARCHAR(255) NOT NULL  COMMENT 'MD5(time())',
     * `default_lang_code` VARCHAR(20) NULL DEFAULT 'zh',
     * `active_key` VARCHAR(255) NULL DEFAULT NULL COMMENT 'MD5(email + time())',
     * @return int index
     */
    public function customerRegister(array $params)
    {

        $sysConfig = Yaf_Registry::get('sysConfig'); //$sysConfig->api->sign

        $password_salt = md5(time() . '' . $sysConfig->api->sign);
        $active_key = rand(1, 9) . '' . rand(1, 9) . '' . rand(1, 9)  . '' .  rand(1, 9) . '' .  rand(1, 9) . '' .  rand(1, 9);

        $paramList = array();

        if (!empty($params['full_name'])) {
            $paramList['full_name'] = $params['full_name'];
        }

        $paramList['email'] = $params['email'];

        if (!empty($params['phone'])) {
            $paramList['phone'] = $params['phone'];
        }
        if (!empty($params['address'])) {
            $paramList['address'] = $params['address'];
        }
        if (!empty($params['id'])) {
            $paramList['id'] = $params['id'];
        }
        if (!empty($params['id_type_code'])) {
            $paramList['id_type_code'] = $params['id_type_code'];
        }

        $paramList['password'] = md5($params['password'] . $password_salt);
        $paramList['password_salt'] = $password_salt;

        if (!empty($params['default_lang_code'])) {
            $paramList['default_lang_code'] = $params['default_lang_code'];
        } else {
            $paramList['default_lang_code'] = 'zh';
        }

        $paramList['active_key'] = $active_key;

        $current_time = time();
        $reset_password_key_expire_date = $current_time + (1 * 24 * 60 * 60);
        $paramList['reset_password_key_expire_date'] = $reset_password_key_expire_date;

        $customer_index = $this->dao->customerRegister($paramList);

        $result = array();
        if ($customer_index > 0) {
            $result['index'] =  $customer_index;
            $result['email'] =  $params['email'];
            $result['full_name'] =  $params['full_name'];
            // send active email to customer
            $email_template = $this->dao->getEmailTemplate('REG', $paramList['default_lang_code']);
            $email_subject = $email_template['subject'];
            $email_body = $email_template['body'];
            //$email_body = str_replace('{{index}}', $customer_index, $email_body);
            $email_body = str_replace('{{full_name}}', $result['full_name'], $email_body);
            $email_body = str_replace('{{active_account_key}}', $active_key, $email_body);

            $smtp = Mail_Email::getInstance();
            $smtp->send(
                array($result['email'] => $result['full_name']),
                $email_subject,
                $email_body
            );

            //$result['email_temp_subject'] = $email_subject;
            //$result['email_temp_body'] = $email_body;
            $result['email_sent'] = true;
        } else {
            $result['error'] = 'SYS Error';
        }

        return $result;
    }



    /**
     * @param array
     * email
     * password
     * @return int id
     */
    public function customerLogin($params)
    {
        $result = array();

        $result = $this->dao->customerLogin($params['email'], $params['password']);
        if (!empty($result)) {
            $result['password'] = '';
            $result['password_salt'] = '';
            $result['active_key'] = '';
            if ($result['disabled'] == '1') {
                $result = array();
                $result['error'] = '该账户未激活';
                $result['error_code'] = 3;
            } else {
                $timeout = intval(Yaf_Registry::get('sysConfig')['auth.timeout']) > 0 ? intval(Yaf_Registry::get('sysConfig')['auth.timeout']) : 3600;
                $result['token'] = Auth_Login::makeToken($result['index'], Auth_Login::USER_MARK, $timeout);
            }
        } else {
            $result['error'] = '用户名密码错误!';
            $result['error_code'] = 4;
        }

        return $result;
    }


    /**
     * @param array
     * email
     * password
     * @return int id
     */
    public function customerCheckEmail($email)
    {
        $emailUsed = false;
        // check email usage
        $customer_exist = $this->dao->customerCheckEmail($email);
        if (!empty($customer_exist)) {
            $emailUsed = true;
        }

        return $emailUsed;
    }


    /**
     * @param array
     * id
     * key
     * @return int id
     */
    public function activeCustomerAccount($params)
    {
        $result = array();
        $hasActive = $this->dao->activeCustomerAccount($params['key'], $params['email']);
        if ($hasActive) {
            $result['message'] = '账号已激活';
            $result['code'] = 0;
        } else {
            $result['error'] = '账号激活失败';
            $result['code'] = 1;
        }

        return $result;
    }







    /**
     * update customer数据
     * @param array
     * `token` BIGINT
     * `full_name` VARCHAR(255) NULL,
     * `phone` VARCHAR(255) NULL ,
     * `address` VARCHAR(2047) NULL ,
     * `id` VARCHAR(255) NULL ,
     * `id_type_code` TINYINT(4) NULL ,
     * `password` VARCHAR(255) NOT NULL  COMMENT 'MD5(customer_password + password_salt)'
     * `default_lang_code` VARCHAR(20) NULL DEFAULT 'zh',
     * @return int index
     */
    public function updateCustomer($params)
    {

        $paramList = array();

        $paramList['customer_id'] =  Auth_Login::getToken($params['token']);

        if (intval($paramList['customer_id']) <= 0) {
            return -1;
        }

        if (!empty($params['full_name'])) {
            $paramList['full_name'] = $params['full_name'];
        } else {
            $paramList['full_name'] = '';
        }

        if (!empty($params['phone'])) {
            $paramList['phone'] = $params['phone'];
        } else {
            $paramList['phone'] = '';
        }

        if (!empty($params['address'])) {
            $paramList['address'] = $params['address'];
        } else {
            $paramList['address'] = '';
        }

        if (!empty($params['id'])) {
            $paramList['id'] = $params['id'];
        } else {
            $paramList['id'] = '';
        }

        if (!empty($params['id_type_code'])) {
            $paramList['id_type_code'] = $params['id_type_code'];
        } else {
            $paramList['id_type_code'] = 1;
        }

        if (!empty($params['password'])) {
            $password_salt = md5(time());
            $paramList['password'] = md5($params['password'] . $password_salt);
            $paramList['password_salt'] = $password_salt;
        } else {
            $paramList['password'] = '';
            $paramList['password_salt'] = '';
        }


        if (!empty($params['default_lang_code'])) {
            $paramList['default_lang_code'] = $params['default_lang_code'];
        } else {
            $paramList['default_lang_code'] = 'zh';
        }

        $result = $this->dao->updateCustomer($paramList);

        return $result;
    }

    public function getRegIdTypeList($lang)
    {
        $result = array();
        $id_list = $this->dao->getRegIdTypeList();

        $dataLangKey = 'id_type_lang' . Enum_Lang::getLangIndex($lang, Enum_Lang::CHINESE);

        foreach ($id_list as &$id) {
            $result[] = array('code' => $id['id_type_code'], 'name' => $id[$dataLangKey]);
        }

        return $result;
    }


    public function requestResetPassword($email, $lang)
    {

        $current_time = time(); //token
        $reset_password_key_expire_date = $current_time + (1 * 24 * 60 * 60);

        $reset_password_key = rand(1, 9) . '' . rand(1, 9) . '' . rand(1, 9)  . '' .  rand(1, 9) . '' .  rand(1, 9) . '' .  rand(1, 9);

        $rows = $this->dao->requestResetPassword($email, $reset_password_key_expire_date, $reset_password_key);

        $result = array();

        if ($rows > 0) {
            $result = array(
                'code' => 0,
                'message' => 'Reset code sent to email: ' . $email
            );

            // send active email to customer
            $email_template = $this->dao->getEmailTemplate('REPAS', $lang);
            $email_subject = $email_template['subject'];
            $email_body = $email_template['body'];
            $email_body = str_replace('{{active_account_key}}', $reset_password_key_expire_date, $email_body);

            $smtp = Mail_Email::getInstance();
            $smtp->send(
                array($email => $email),
                $email_subject,
                $email_body
            );

            //$result['email_temp_subject'] = $email_subject;
            //$result['email_temp_body'] = $email_body;
            $result['email_sent'] = true;
        } else {
            $result = array(
                'code' => 1,
                'message' => 'User not found.'
            );
        }

        return $result;
    }


    public function setNewPasswordByResetKey($params)
    {
        $result = array();

        $sysConfig = Yaf_Registry::get('sysConfig'); //$sysConfig->api->sign
        // make resert key
        $reset_password_key = $params['key'];
        $email = $params['email'];

        //make password and password salt;
        $password_salt = md5(time() . '' . $sysConfig->api->sign);
        $password = md5($params['password'] . $password_salt);

        $rows = $this->dao->setNewPasswordByResetKey($reset_password_key, $password, $password_salt, $email);

        if ($rows > 0) {
            $result = array(
                'code' => 0,
                'message' => 'SUCC'
            );
        } else {
            $result = array(
                'code' => 1,
                'message' => 'ERROR'
            );
        }

        return $result;
    }
}
