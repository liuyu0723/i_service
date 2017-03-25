<?php

/**
 * 体验购物订单convertor
 * @author ZXM
 */
class Convertor_ShoppingOrder extends Convertor_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getShoppingOrderListConvertor($list, $count, $param) {
        $data = array(
            'list' => array()
        );
        $statusNameList = Enum_ShoppingOrder::getStatusNameList();
        foreach ($list as $listOne) {
            $listOneTemp = array();
            $listOneTemp['id'] = $listOne['id'];
            $listOneTemp['userid'] = $listOne['userid'];
            $listOneTemp['count'] = $listOne['count'];
            $listOneTemp['createtime'] = $listOne['creattime'];
            $listOneTemp['status'] = $listOne['status'];
            $listOneTemp['statusName'] = $statusNameList[$listOne['status']];
            $listOneTemp['shoppingid'] = $listOne['shoppingid'];
            $listOneTemp['adminid'] = $listOne['adminid'];
            $data['list'][] = $listOneTemp;
        }
        $data['total'] = $count;
        $data['page'] = $param['page'];
        $data['limit'] = $param['limit'];
        $data['nextPage'] = Util_Tools::getNextPage($data['page'], $data['limit'], $data['total']);
        return $data;
    }
}