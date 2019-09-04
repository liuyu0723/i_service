<?php

/**
 * 物业管理员枚举
 * @author ZXM
 */
class Enum_HotelAdministrator
{

    const PERMISSION_TYPE_BASE = 1;
    const PERMISSION_TYPE_TASK = 2;
    const PERMISSION_TYPE_ALL = 3;

    const DEPARTMENT_DEFAULT = 0;
    const LEVEL_DEFAULT = 0;

    /**
     * Permission enum list
     *
     * @var array
     */
    private static $_permission = array(
        self::PERMISSION_TYPE_BASE => array(
            1 => '物业管理',
            2 => '活动管理',
            3 => '雅士阁生活',
            4 => '本地攻略',
            5 => '物业促销',
            6 => '客房管理',
            7 => '体验购物',
            8 => '预约看房',
            9 => '电话黄页',
            10 => '物业新闻',
            11 => '物业通知',
            12 => '调查反馈',
            13 => 'APP管理',
            14 => '评论管理',
            15 => '互动服务',
            16 => '签到系统',
        ),
    );

    private static $staffPermission = array(
        1 => '机器人控制',
        2 => '购物订单',
        3 => '活动管理',
        4 => '员工服务',
        5 => '个人设置',
    );

    /**
     * Department enum list
     *
     * @var array
     */
    private static $_department = array(
        self::DEPARTMENT_DEFAULT => array(
            1 => '前厅部',
            2 => '销售部',
            3 => '客房部',
            4 => '餐饮部',
            5 => '客服部',
            6 => '保安部',
            7 => '工程部',
        )
    );

    private static $_level = array(
        self::LEVEL_DEFAULT => array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
        )
    );


    /**
     * Get the permission list
     *
     * @param int $type
     * @return array
     */
    public static function getPermission($type = self::PERMISSION_TYPE_BASE)
    {
        if ($type != self::PERMISSION_TYPE_BASE && !isset(self::$_permission[self::PERMISSION_TYPE_TASK])) {
            $dao = new Dao_TaskPermission();
            $taskPermissionList = $dao->getTaskPermissionList();
            self::$_permission[self::PERMISSION_TYPE_TASK] = $taskPermissionList;
        }
        if ($type == self::PERMISSION_TYPE_ALL) {
            return self::$_permission;
        } else {
            return self::$_permission[$type];
        }
    }

    /**
     * @return array
     */
    public static function getStaffPermission()
    {
        return self::$staffPermission;
    }

    /**
     * Return the department list
     *
     * @param int $hotelId
     * @return array
     */
    public static function getDepartment($hotelId = self::DEPARTMENT_DEFAULT)
    {
        if (!isset(self::$_department[$hotelId])) {
            $index = self::DEPARTMENT_DEFAULT;
        } else {
            $index = $hotelId;
        }
        return self::$_department[$index];
    }

    /**
     * Return the level list
     *
     * @param int $hotelId
     * @return array
     */
    public static function getLevel($hotelId = self::LEVEL_DEFAULT): array
    {
        if (!isset(self::$_level[$hotelId])) {
            $index = self::LEVEL_DEFAULT;
        } else {
            $index = $hotelId;
        }
        return self::$_level[$index];
    }


}

?>
