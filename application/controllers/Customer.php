<?php

/**
 * customer
 *
 */
class CustomerController extends \BaseController
{

    /**
     *
     * @var CustomerModel
     */
    private $model;


    public function init()
    {
        parent::init();
        $this->model = new CustomerModel();
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
     * `password` VARCHAR(255) NOT NULL  
     * `default_lang_code` VARCHAR(20) NULL DEFAULT 'zh'
     * @return int index
     */
    public function registerAction()
    {
        $params = array();

        $params['full_name'] = trim($this->getParamList('full_name'));
        $params['email'] = trim($this->getParamList('email'));
        $params['phone'] = trim($this->getParamList('phone'));
        $params['address'] = trim($this->getParamList('address'));
        $params['id'] = trim($this->getParamList('id'));
        $params['id_type_code'] = intval($this->getParamList('id_type_code'));
        $params['password'] = trim($this->getParamList('password'));
        $params['default_lang_code'] = trim($this->getParamList('default_lang_code'));


        if (empty($params['email'])) {
            $this->throwException(1, '入参错误');
        } else if (empty($params['password'])) {
            $this->throwException(1, '入参错误');
        }

        if (filter_var($params['email'], FILTER_VALIDATE_EMAIL)) { } else {
            $this->throwException(1, '入参错误');
        }

        if ($this->model->customerCheckEmail($params['email'])) {
            $this->throwException(2, 'Email已被使用');
        }

        $data = $this->model->customerRegister($params);

        $this->echoSuccessData($data);
    }


    /**
     * 
     * @param array
     * `email` VARCHAR(255) NOT NULL,
     * `password` VARCHAR(255) NOT NULL  
     * @return int index
     */
    public function loginAction()
    {
        $params = array();

        $params['email'] = trim($this->getParamList('email'));
        $params['password'] = trim($this->getParamList('password'));

        if (empty($params['email'])) {
            $this->throwException(1, '入参错误');
        } else if (empty($params['password'])) {
            $this->throwException(1, '入参错误');
        }

        if (filter_var($params['email'], FILTER_VALIDATE_EMAIL)) { } else {
            $this->throwException(1, '入参错误');
        }

        $data = $this->model->customerLogin($params);

        $this->echoSuccessData($data);
    }


    /**
     * 
     * @param array
     * `id` 
     * `key`  
     * `email`
     * @return int index
     */
    public function activeAccountAction()
    {
        $params = array();

        $params['id'] = trim($this->getParamList('id'));
        $params['key'] = trim($this->getParamList('key'));
        $params['email'] = trim($this->getParamList('email'));

        if (empty($params['id'])) {
            $this->throwException(1, '入参错误');
        } else if (empty($params['key'])) {
            $this->throwException(1, '入参错误');
        } else if (empty($params['email'])) {
            $this->throwException(1, '入参错误');
        }

        if (filter_var($params['email'], FILTER_VALIDATE_EMAIL)) { } else {
            $this->throwException(1, '入参错误');
        }


        $data = $this->model->activeCustomerAccount($params);

        $this->echoSuccessData($data);
    }
}
