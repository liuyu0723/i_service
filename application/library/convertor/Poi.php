<?php

/**
 * 本地攻略转换器类
 */
class Convertor_Poi extends Convertor_Base {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 酒店本地攻略列表
     * @param array $poiList 酒店本地攻略列表
     * @param int $poiCount 酒店本地攻略总数
     * @param array $param 扩展参数
     * @return array
     */
    public function getPoiListConvertor($poiList, $poiCount, $param) {
        $data = array('list' => array());
        foreach ($poiList as $pois) {
            $poiTemp = array();
            $poiTemp ['id'] = $pois ['id'];
            $poiTemp ['name'] = $this->handlerMultiLang('name', $pois);
            $poiTemp ['address'] = $this->handlerMultiLang('address', $pois);
            $poiTemp ['tel'] = $pois ['tel'];
            $poiTemp ['introduct'] = $this->handlerMultiLang('introduct', $pois);
            $poiTemp ['detail'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('detail', $pois));
            $poiTemp ['pdf'] = $pois['pdf'] ? Enum_Img::getPathByKeyAndType($pois['pdf']) : '';
            $poiTemp ['video'] = $pois['video'] ? Enum_Img::getPathByKeyAndType($pois['video']) : '';
            $poiTemp ['pic'] = $pois['pic'] ? Enum_Img::getPathByKeyAndType($pois['pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750) : '';
            $poiTemp ['lat'] = $pois ['lat'];
            $poiTemp ['lng'] = $pois ['lng'];
            $data ['list'] [] = $poiTemp;
        }
        $data ['total'] = $poiCount;
        $data ['page'] = $param ['page'];
        $data ['limit'] = $param ['limit'];
        $data ['nextPage'] = Util_Tools::getNextPage($data ['page'], $data ['limit'], $data ['total']);
        return $data;
    }

    /**
     * 后台酒店本地攻略列表
     * @param array $poiList 酒店本地攻略列表
     * @param int $poiCount 酒店本地攻略总数
     * @param array $param 扩展参数
     * @param array $typeList 本地攻略类型结果
     * @param array $tagList 本地攻略标签结果
     * @return array
     */
    public function getAdminPoiListConvertor($poiList, $poiCount, $param, $typeList, $tagList) {
        $data = array('list' => array());

        $typeListNew = array();
        foreach ($typeList as $type) {
            $typeListNew [$type ['id']] ['titleLang1'] = $type ['title_lang1'];
            $typeListNew [$type ['id']] ['titleLang2'] = $type ['title_lang2'];
            $typeListNew [$type ['id']] ['titleLang3'] = $type ['title_lang3'];
        }

        $tagListNew = array();
        foreach ($tagList as $tag) {
            $tagListNew [$tag ['id']] ['titleLang1'] = $tag ['title_lang1'];
            $tagListNew [$tag ['id']] ['titleLang2'] = $tag ['title_lang2'];
            $tagListNew [$tag ['id']] ['titleLang3'] = $tag ['title_lang3'];
        }

        foreach ($poiList as $poi) {
            $poiTemp = array();
            $poiTemp ['id'] = $poi ['id'];
            $poiTemp ['name_lang1'] = $poi ['name_lang1'];
            $poiTemp ['name_lang2'] = $poi ['name_lang2'];
            $poiTemp ['name_lang3'] = $poi ['name_lang3'];
            $poiTemp ['address_lang1'] = $poi ['address_lang1'];
            $poiTemp ['address_lang2'] = $poi ['address_lang2'];
            $poiTemp ['address_lang3'] = $poi ['address_lang3'];
            $poiTemp ['tel'] = $poi ['tel'];
            $poiTemp ['status'] = $poi ['status'];
            $poiTemp ['hotelId'] = $poi ['hotelid'];
            $poiTemp ['tagId'] = $poi ['tagid'];
            $poiTemp ['tagName_lang1'] = $tagListNew [$poiTemp ['tagId']] ['titleLang1'];
            $poiTemp ['tagName_lang2'] = $tagListNew [$poiTemp ['tagId']] ['titleLang2'];
            $poiTemp ['tagName_lang3'] = $tagListNew [$poiTemp ['tagId']] ['titleLang3'];
            $poiTemp ['typeId'] = $poi ['typeid'];
            $poiTemp ['typeName_lang1'] = $typeListNew [$poiTemp ['typeId']] ['titleLang1'];
            $poiTemp ['typeName_lang2'] = $typeListNew [$poiTemp ['typeId']] ['titleLang2'];
            $poiTemp ['typeName_lang3'] = $typeListNew [$poiTemp ['typeId']] ['titleLang3'];
            $poiTemp ['introduct_lang1'] = $poi ['introduct_lang1'];
            $poiTemp ['introduct_lang2'] = $poi ['introduct_lang2'];
            $poiTemp ['introduct_lang3'] = $poi ['introduct_lang3'];
            $poiTemp ['detail_lang1'] = $poi ['detail_lang1'];
            $poiTemp ['detail_lang2'] = $poi ['detail_lang2'];
            $poiTemp ['detail_lang3'] = $poi ['detail_lang3'];
            $poiTemp ['lat'] = $poi ['lat'];
            $poiTemp ['lng'] = $poi ['lng'];
            $poiTemp ['sort'] = $poi ['sort'];
            $poiTemp ['pdf'] = $poi ['pdf'];
            $poiTemp ['video'] = $poi ['video'];
            $poiTemp ['pic'] = $poi ['pic'];
            $poiTemp ['createTime'] = $poi ['createtime'];
            $poiTemp ['updateTime'] = $poi ['updatetime'];

            $poiTemp ['homeShow'] = $poi ['homeShow'];
            $poiTemp ['startTime'] = date('Y-m-d H:i:s', $poi ['startTime']);
            $poiTemp ['endTime'] = date('Y-m-d H:i:s', $poi ['endTime']);

            $data ['list'] [] = $poiTemp;
        }
        $data ['total'] = $poiCount;
        $data ['page'] = $param ['page'];
        $data ['limit'] = $param ['limit'];
        $data ['nextPage'] = Util_Tools::getNextPage($data ['page'], $data ['limit'], $data ['total']);
        return $data;
    }

    /**
     * 酒店本地攻略详情
     * @param array $poi
     * @return array
     */
    public function getPoiDetailConvertor($poi) {
        $data = array();
        $data ['id'] = $poi ['id'];
        $data ['name'] = $this->handlerMultiLang('name', $poi);
        $data ['address'] = $this->handlerMultiLang('address', $poi);
        $data ['tel'] = $poi ['tel'];
        $data ['hotelId'] = $poi ['hotelid'];
        $data ['typeId'] = $poi ['typeid'];
        $data ['introduct'] = $this->handlerMultiLang('introduct', $poi);
        $data ['detail'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('detail', $poi));
        $data ['lat'] = $poi ['lat'];
        $data ['lng'] = $poi ['lng'];
        $data ['createTime'] = $poi ['createtime'];
        $data ['updateTime'] = $poi ['updatetime'];
        $data ['status'] = $poi ['status'];
        return $data;
    }

    /**
     * 后台酒店本地攻略详情
     * @param array $poi
     * @return array
     */
    public function getAdminPoiDetailConvertor($poi) {
        $data = array();
        $data ['id'] = $poi ['id'];
        $data ['name_lang1'] = $poi ['name_lang1'];
        $data ['name_lang2'] = $poi ['name_lang2'];
        $data ['name_lang3'] = $poi ['name_lang3'];
        $data ['address_lang1'] = $poi ['address_lang1'];
        $data ['address_lang2'] = $poi ['address_lang2'];
        $data ['address_lang3'] = $poi ['address_lang3'];
        $data ['tel'] = $poi ['tel'];
        $data ['hotelId'] = $poi ['hotelid'];
        $data ['typeId'] = $poi ['typeid'];
        $data ['introduct_lang1'] = $poi ['introduct_lang1'];
        $data ['introduct_lang2'] = $poi ['introduct_lang2'];
        $data ['introduct_lang3'] = $poi ['introduct_lang3'];
        $data ['detail_lang1'] = Enum_Img::getPathByKeyAndType($poi ['detail_lang1']);
        $data ['detail_lang2'] = Enum_Img::getPathByKeyAndType($poi ['detail_lang2']);
        $data ['detail_lang3'] = Enum_Img::getPathByKeyAndType($poi ['detail_lang3']);
        $data ['lng'] = $poi ['lng'];
        $data ['lat'] = $poi ['lat'];
        $data ['createTime'] = $poi ['createtime'];
        $data ['updateTime'] = $poi ['updatetime'];
        $data ['status'] = $poi ['status'];
        return $data;
    }


    /**
     * 获取首页广告列表
     * @param array $poiList 列表
     * @return array
     */
    public function getHomeAdvConvertor($poiList) {
        $data = array('list' => array());
        foreach ($poiList as $pois) {
            $pois ['pic'] = $pois['pic'] ? Enum_Img::getPathByKeyAndType($pois['pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750) : '';
            $pois ['updatetime'] = $pois['updatetime'] ? date('Y-m-d H:i:s', $pois['updatetime']) : '';
            $data ['list'] [] = $pois;
        }
        return $data;
    }
}
