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
        $password_salt = md5(time());
        $active_key = md5($params['email'] . time());

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
            $email_body = str_replace('{{index}}', $customer_index, $email_body);
            $email_body = str_replace('{{full_name}}', $result['full_name'], $email_body);
            $email_body = str_replace('{{active_account_key}}', $active_key, $email_body);

            $smtp = Mail_Email::getInstance();
            $smtp->send(
                array($result['email'] => $result['full_name']),
                $email_subject,
                $email_body
            );

            $result['email_temp_subject'] = $email_subject;
            $result['email_temp_body'] = $email_body;
            $result['email_sent'] = true;
        } else {
            $result['error'] = 'SYS Error';
        }

        return $result;
    }



    /**
     * 单条增加customer数据
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
                $result['error_code'] = 2;
            }
        } else {
            $result['error'] = '用户名密码错误!';
            $result['error_code'] = 1;
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
     * 单条增加customer数据
     * @param array
     * id
     * key
     * @return int id
     */
    public function activeCustomerAccount($params)
    {
        $result = array();
        $hasActive = $this->dao->activeCustomerAccount($params['id'], $params['key'], $params['email']);
        if ($hasActive) {
            $result['message'] = '账号已激活';
            $result['code'] = 0;
        } else {
            $result['error'] = '账号激活失败';
            $result['code'] = 1;
        }

        return $result;
    }
}
