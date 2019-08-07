<?php

/**
 * 物业列表转换器类
 */
class Convertor_HotelList extends Convertor_Base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 物业列表数据转换器
     * @param array $list 物业列表数据
     * @param int $count 物业列表总数
     * @param array $param 扩展参数
     * @return array
     */
    public function getHotelListListConvertor($list, $count, $param)
    {
        $data = array('list' => array());
        $groupIdList = array_column($list, 'groupid');
        $groupModel = new GroupModel();
        $groupInfoList = $groupModel->getGroupList(array('id' => $groupIdList));
        $groupNameList = array_column($groupInfoList, 'name', 'id');
        $cityIdList = array_column($list, 'cityid');
        $cityModel = new CityModel();
        $cityInfoList = $cityModel->getCityList(array('id' => $cityIdList));
        $cityNameList = array_column($cityInfoList, 'name', 'id');
        $cityEnNameList = array_column($cityInfoList, 'enname', 'id');
        foreach ($list as $key => $value) {
            $oneTemp = array();
            $oneTemp['id'] = $value['id'];
            $oneTemp['localpic'] = $value['localpic'];
            $oneTemp['groupid'] = $value['groupid'];
            $oneTemp['groupName'] = $groupNameList[$value['groupid']];
            $oneTemp['propertyinterfid'] = $value['propertyinterfid'];
            $oneTemp['lng'] = $value['lng'];
            $oneTemp['lat'] = $value['lat'];
            $oneTemp['cityid'] = $value['cityid'];
            $oneTemp['cityName'] = $cityNameList[$value['cityid']];
            $oneTemp['cityEnName'] = $cityEnNameList[$value['cityid']];
            $oneTemp['tel'] = $value['tel'];
            $oneTemp['name_lang1'] = $value['name_lang1'];
            $oneTemp['name_lang2'] = $value['name_lang2'];
            $oneTemp['name_lang3'] = $value['name_lang3'];
            $oneTemp['website'] = $value['website'];
            $oneTemp['logo'] = $value['logo'];
            $oneTemp['index_background'] = $value['index_background'];
            $oneTemp['localpic'] = $value['localpic'];
            $oneTemp['listpic'] = $value['listpic'];
            $oneTemp['voice_lang1'] = $value['voice_lang1'];
            $oneTemp['voice_lang2'] = $value['voice_lang2'];
            $oneTemp['voice_lang3'] = $value['voice_lang3'];
            $oneTemp['address_lang1'] = $value['address_lang1'];
            $oneTemp['address_lang2'] = $value['address_lang2'];
            $oneTemp['address_lang3'] = $value['address_lang3'];
            $oneTemp['introduction_lang1'] = $value['introduction_lang1'];
            $oneTemp['introduction_lang2'] = $value['introduction_lang2'];
            $oneTemp['introduction_lang3'] = $value['introduction_lang3'];
            $oneTemp['status'] = $value['status'];
            $oneTemp['lang_list'] = $value['lang_list'];
            $oneTemp['bookurl'] = $value['bookurl'];
            $oneTemp['flighturl'] = $value['flighturl'];
            $oneTemp['surveyurl'] = $value['surveyurl'];
            $oneTemp['invoice_id'] = $value['invoice_id'];
            $oneTemp['robot_pic'] = $value['robot_pic'];
            $oneTemp['washing_machine'] = $value['washing_machine'];
            $oneTemp['groupid'] = $value['groupid'];
            $oneTemp['pdf'] = $value['pdf'];
            $data['list'][] = $oneTemp;
        }
        $data['total'] = $count;
        $data['page'] = $param['page'];
        $data['limit'] = $param['limit'];
        $data['nextPage'] = Util_Tools::getNextPage($data['page'], $data['limit'], $data['total']);
        return $data;
    }

    /**
     * 当前可用的物业列表
     * @param array $list 当前可用的物业列表
     * @return array
     */
    public function getEffectiveHotelListConvertor($list)
    {
        $data = array('list' => array());
        $cityIdList = array_column($list, 'cityid');
        $cityModel = new CityModel();
        $cityInfoList = $cityModel->getCityList(array('id' => $cityIdList));
        $cityInfoList = array_column($cityInfoList, null, 'id');
        foreach ($list as $hotel) {
            $dataTemp = array();
            $dataTemp['hotelId'] = $hotel['id'];
            $dataTemp['localpic'] = Enum_Img::getPathByKeyAndType($hotel['localpic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
            $dataTemp['listpic'] = Enum_Img::getPathByKeyAndType($hotel['listpic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
            $dataTemp['propertyinterfId'] = $hotel['propertyinterfid'];
            $dataTemp['cityId'] = $hotel['cityid'];
            $cityInfo = $cityInfoList[$dataTemp['cityId']];
            $dataTemp['cityName'] = $cityInfo['name'];
            $dataTemp['cityEnName'] = $cityInfo['enname'];
            $dataTemp['countryName'] = $cityInfo['countryname'];
            $dataTemp['countryEnName'] = $cityInfo['countryenname'];
            $dataTemp['name'] = $hotel['name_lang1'];
            $dataTemp['nameEn'] = $hotel['name_lang2'];
            $dataTemp['address'] = $hotel['address'];
            $dataTemp['lat'] = $hotel['lat'];
            $dataTemp['lng'] = $hotel['lng'];
            $dataTemp['bookurl'] = $hotel['bookurl'];
            $dataTemp['distance'] = $hotel['distance'];
            $data['list'][] = $dataTemp;
        }
        return $data;
    }

    /**
     * 根据id获取物业详情
     * @param array $hotelInfo 物业详情
     * @param array $hotelShareIcon 酒店分享信息
     * @param array $hotelShortCutIcon 酒店快捷信息
     * @return array
     */
    public function getHotelListDetailConvertor($hotelInfo, $hotelShareIcon, $hotelShortCutIcon)
    {
        $cityModel = new CityModel();
        $cityInfo = $cityModel->getCityDetail($hotelInfo['cityid']);
        $langNameList = Enum_Lang::getLangNameList();
        $wetherModel = new WetherModel();
        $wetherInfo = $wetherModel->getWeatherFromYahoo($cityInfo['enname'] . ',' . $cityInfo['countryenname']);
        $data = array();
        $data['hotelId'] = $hotelInfo['id'];
        $data['groupId'] = $hotelInfo['groupid'];
        $data['propertyinterfId'] = $hotelInfo['propertyinterfid'];
        $data['portUrl'] = $hotelInfo['portUrl'];
        $data['lng'] = $hotelInfo['lng'];
        $data['lat'] = $hotelInfo['lat'];
        $data['cityId'] = $hotelInfo['cityid'];
        $data['cityName'] = $cityInfo['name'];
        $data['cityEnName'] = $cityInfo['enname'];
        $data['countryName'] = $cityInfo['countryname'];
        $data['countryEnName'] = $cityInfo['countryenname'];
        $data['tel'] = $hotelInfo['tel'];
        $data['name'] = $this->handlerMultiLang('name', $hotelInfo);
        $data['website'] = $hotelInfo['website'];
        $data['logo'] = Enum_Img::getPathByKeyAndType($hotelInfo['logo']);
        $data['indexBackground'] = Enum_Img::getPathByKeyAndType($hotelInfo['index_background'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
        $data['localPic'] = Enum_Img::getPathByKeyAndType($hotelInfo['localpic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
        $data['voice'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('voice', $hotelInfo));
        $data['introduction'] = $this->handlerMultiLang('introduction', $hotelInfo);
        $data['address'] = $this->handlerMultiLang('address', $hotelInfo);
        $langList = array_filter(explode(',', $hotelInfo['lang_list']));
        $data['wetherInfo'] = $wetherInfo;
        $data['langList'] = array_intersect_key($langNameList, array_flip($langList));
        $data['shareList'] = array();
        $data['robot_pic'] = Enum_Img::getPathByKeyAndType($hotelInfo['robot_pic']);
        $data['washing_machine'] = $hotelInfo['washing_machine'];
        foreach ($hotelShareIcon as $share) {
            $data['shareList'][] = array('key' => $share['key']);
        }
        $data['shortcutList'] = array();
        foreach ($hotelShortCutIcon as $shortCut) {
            $data['shortcutList'][] = array('key' => $shortCut['key'], 'title' => $this->handlerMultiLang('title', $shortCut));
        }
        return $data;
    }

    /**
     * 酒店详情
     * @param array $hotel 酒店详情
     * @return array
     */
    public function hotelDetailConvertor($hotel)
    {
        $data = array();
        $hotelInfo = $hotel['hotelInfo'];
        $data['id'] = $hotelInfo['id'];
        $data['groupid'] = $hotelInfo['groupid'];
        $data['propertyinterfid'] = $hotelInfo['propertyinterfid'];
        $data['localpic'] = Enum_Img::getPathByKeyAndType($hotelInfo['localpic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
        $data['lng'] = $hotelInfo['lng'];
        $data['lat'] = $hotelInfo['lat'];
        $data['tel'] = $hotelInfo['tel'];
        $data['name'] = $this->handlerMultiLang('name', $hotelInfo);
        $data['website'] = $hotelInfo['website'];
        $data['bookurl'] = $hotelInfo['bookurl'];
        $data['introduction'] = $this->handlerMultiLang('introduction', $hotelInfo);
        $data['robot_pic'] = Enum_Img::getPathByKeyAndType($hotelInfo['robot_pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
        $data['washing_machine'] = $hotelInfo['washing_machine'];
        $data['pic'] = array();
        foreach ($hotel['picList'] as $pic) {
            $data['pic'][] = Enum_Img::getPathByKeyAndType($pic['pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
        }
        $data['roomTypeList'] = array();
        foreach ($hotel['roomTypeList'] as $roomType) {
            $roomTypeTemp = array();
            $roomTypeTemp['id'] = $roomType['id'];
            $roomTypeTemp['title'] = $this->handlerMultiLang('title', $roomType);
            $roomTypeTemp['size'] = $roomType['size'];
            $roomTypeTemp['detail'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('detail', $roomType));
            $roomTypeTemp['panoramic'] = $roomType['panoramic'];
            $roomTypeTemp['bedtype'] = $this->handlerMultiLang('bedtype', $roomType);
            $roomTypeTemp['pic'] = Enum_Img::getPathByKeyAndType($roomType['pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
            $roomTypeTemp['roomcount'] = $roomType['roomcount'];
            $roomTypeTemp['personcount'] = $roomType['personcount'];
            $data['roomTypeList'][] = $roomTypeTemp;
        }
        $data['facilitiesList'] = array();
        foreach ($hotel['facilitiesList'] as $facilities) {
            $facilitiesTemp = array();
            $facilitiesTemp['icon'] = $facilities['icon'];
            $facilitiesTemp['name'] = $this->handlerMultiLang('name', $facilities);
            $facilitiesTemp['detail'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('detail', $facilities));
            $facilitiesTemp['introduct'] = $this->handlerMultiLang('introduct', $facilities);
            $facilitiesTemp['pdf'] = Enum_Img::getPathByKeyAndType($facilities['pdf']);
            $facilitiesTemp['video'] = Enum_Img::getPathByKeyAndType($facilities['video']);
            $facilitiesTemp['pic'] = Enum_Img::getPathByKeyAndType($facilities['pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
            $data['facilitiesList'][] = $facilitiesTemp;
        }
        $data['floorList'] = array();
        foreach ($hotel['floorList'] as $floor) {
            $floorTemp = array();
            $floorTemp['floor'] = $floor['floor'];
            $floorTemp['detail'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('detail', $floor));
            $floorTemp['pic'] = Enum_Img::getPathByKeyAndType($floor['pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
            $data['floorList'][] = $floorTemp;
        }
        $data['trafficList'] = array();
        foreach ($hotel['trafficList'] as $traffic) {
            $trafficTemp = array();
            $trafficTemp['introduct'] = $this->handlerMultiLang('introduct', $traffic);
            $trafficTemp['detail'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('detail', $traffic));
            $trafficTemp['pdf'] = Enum_Img::getPathByKeyAndType($traffic['pdf']);
            $trafficTemp['video'] = Enum_Img::getPathByKeyAndType($traffic['video']);
            $data['trafficList'][] = $trafficTemp;
        }
        $data['panoramicList'] = array();
        foreach ($hotel['panoramicList'] as $panoramic) {
            $panoramicTemp = array();
            $panoramicTemp['panoramic'] = $panoramic['panoramic'];
            $panoramicTemp['pic'] = Enum_Img::getPathByKeyAndType($panoramic['pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
            $panoramicTemp['title'] = $this->handlerMultiLang('title', $panoramic);
            $data['panoramicList'][] = $panoramicTemp;
        }
        return $data;
    }
}
