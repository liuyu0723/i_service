<?php

class Enum_System {

    const RPC_REQUEST_PACKAGE = 'ota';
    const IAM_PACKAGE = 'iam';
    const GROUP_PACKAGE = 'ig';

    const RPC_REQUEST_UA = "Iservice/1.0(iservice;)";

    const SYSTEM_NAME = 'EASYISERVICE管理后台';

    const RULE_MENU_PROJECT_ID = 4;

    const MSG_SYSTEM_ERROR = "系统错误";

    public static function notAdminPackage($package) {
        return ($package != self::IAM_PACKAGE && $package != self::GROUP_PACKAGE);
    }
}

?>