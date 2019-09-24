<?php

/**
 * Customer
 */
class Dao_Customer extends Dao_Base
{

    public function __construct()
    {
        parent::__construct();
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
        $this->db->insert('customer', $params);
        return $this->db->lastInsertId();
    }


    /**
     * 单条增加customer数据
     * @param array
     * email
     * password
     * @return int id
     */
    public function customerLogin($email, $password)
    {
        $result = array();

        $sql = "select * from customer where email='" . $email . "' AND password=MD5(CONCAT('" . $password . "',`password_salt`)) LIMIT 1";
        $result = $this->db->fetchAssoc($sql, array());

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
        $result = array();

        $sql = "select * from customer where email=?  LIMIT 1";
        $result = $this->db->fetchAssoc($sql, array($email));

        return $result;
    }

    public function getEmailTemplate($code = 'REG', $lang_code = 'zh')
    {
        $result = array();

        $sql = "select * from customer_email_template where code=? AND lang_code=?   LIMIT 1";
        $result = $this->db->fetchAssoc($sql, array($code, $lang_code));

        return $result;
    }


    public function activeCustomerAccount($key, $email)
    {
        $result = false;

        $sql = "UPDATE customer SET `disabled` = 0, `active_key`='' WHERE `email` = '" . $email . "' AND `active_key` = '" . $key . "'";

        $result = $this->db->executeUpdate($sql);

        return $result;
    }




    /**
     * update customer数据
     * @param array
     * `customer_id` BIGINT
     * `full_name` VARCHAR(255) NULL,
     * `phone` VARCHAR(255) NULL ,
     * `address` VARCHAR(2047) NULL ,
     * `id` VARCHAR(255) NULL ,
     * `id_type_code` TINYINT(4) NULL ,
     * `password` VARCHAR(255) NOT NULL  COMMENT 'MD5(customer_password + password_salt)',
     * `password_salt` VARCHAR(255) NOT NULL  COMMENT 'MD5(time())',
     * `default_lang_code` VARCHAR(20) NULL DEFAULT 'zh'
     * @return int index
     */
    public function updateCustomer(array $params)
    {

        $result = false;
        $sql = '';
        if (!empty($params['password']) && !empty($params['password_salt'])) {
            $sql = "UPDATE customer"
                . " SET `full_name` = '" . $params['full_name'] . "', "
                . " `phone` = '" . $params['phone'] . "', "
                . " `address` = '" . $params['address'] . "', "
                . " `id` = '" . $params['id'] . "', "
                . " `id_type_code` = " . $params['id_type_code'] . ", "
                . " `password` = '" . $params['password'] . "', "
                . " `password_salt` = '" . $params['password_salt'] . "', "
                . " `default_lang_code` = '" . $params['default_lang_code'] . "'"
                . " WHERE `index` = " . $params['customer_id'];
        } else {
            $sql = "UPDATE customer"
                . " SET `full_name` = '" . $params['full_name'] . "', "
                . " `phone` = '" . $params['phone'] . "', "
                . " `address` = '" . $params['address'] . "', "
                . " `id` = '" . $params['id'] . "', "
                . " `id_type_code` = " . $params['id_type_code'] . ", "
                . " `default_lang_code` = '" . $params['default_lang_code'] . "'"
                . " WHERE `index` = " . $params['customer_id'];
        }



        $result = $this->db->executeUpdate($sql);

        return $result;
    }

    public function getRegIdTypeList()
    {
        $result = array();
        $sql = "select * from id_type order by id_type_code";
        $result = $this->db->fetchAll($sql, array());

        return $result;
    }

    public function requestResetPassword($email, $reset_password_key_expire_date, $reset_password_key)
    {
        $result = 0;

        $sql = "UPDATE customer "
            . " SET `reset_password_key_expire_date` = " . $reset_password_key_expire_date
            . ", `reset_password_key`='" . $reset_password_key . "' "
            . " WHERE `email` = '" . $email . "' AND `disabled` = 0";

        $result = $this->db->executeUpdate($sql);

        return $result;
    }

    public function setNewPasswordByResetKey($reset_password_key, $password, $password_salt, $email)
    {
        $result = 0;

        $current_time = time();

        $sql = "UPDATE `customer` "
            . " SET `password` = '" . $password . "' "
            . ", `password_salt`='" . $password_salt . "' "
            . ", `reset_password_key`='' "
            . ", `reset_password_key_expire_date`= 0 "
            . " WHERE `reset_password_key` = '" . $reset_password_key
            . "' AND `disabled` = 0 AND reset_password_key_expire_date >= " . $current_time
            . " AND `email` = '" .  $email . "'";

        $result = $this->db->executeUpdate($sql);

        return $result;
    }
}
