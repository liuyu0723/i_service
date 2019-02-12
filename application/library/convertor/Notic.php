<?php

/**
 * 酒店通知转换器类
 */
class Convertor_Notic extends Convertor_Base {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 酒店通知列表结果转换器
     *
     * @param array $noticList
     *            酒店通知列表
     * @param array $tagList
     *            酒店通知标签列表
     * @param int $noticCount
     *            酒店通知总数
     * @param array $param
     *            扩展参数
     * @return array
     */
    public function getNoticListConvertor($noticList, $tagList, $noticCount, $param) {
        $tagListNew = array();
        foreach ($tagList as $tag) {
            $tagListNew [$tag ['id']] = $this->handlerMultiLang('title', $tag);
        }
        $data = array('list' => array());
        foreach ($noticList as $notic) {
            $newTemp = array();
            $newTemp ['id'] = $notic ['id'];
            $newTemp ['title'] = $this->handlerMultiLang('title', $notic);
            $newTemp ['article'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('article', $notic));
            $newTemp ['pdf'] = $notic['pdf'] ? Enum_Img::getPathByKeyAndType($notic['pdf']) : '';
            $newTemp ['video'] = $notic['video'] ? Enum_Img::getPathByKeyAndType($notic['video']) : '';
            $newTemp ['tagId'] = $notic ['tagid'];
            $newTemp ['tagName'] = $tagListNew [$newTemp ['tagId']];
            $newTemp ['createtime'] = $notic ['createtime'];
            $newTemp ['updatetime'] = $notic ['updatetime'];
            $newTemp ['pic'] = Enum_Img::getPathByKeyAndType($notic['pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);

            $newTemp ['homeShow'] = $notic ['homeShow'];
            $newTemp ['startTime'] = date('Y-m-d H:i:s', $notic ['startTime']);
            $newTemp ['endTime'] = date('Y-m-d H:i:s', $notic ['endTime']);

            $data ['list'] [] = $newTemp;
        }
        $data ['total'] = $noticCount;
        $data ['page'] = $param ['page'];
        $data ['limit'] = $param ['limit'];
        $data ['nextPage'] = Util_Tools::getNextPage($data ['page'], $data ['limit'], $data ['total']);
        return $data;
    }

    /**
     * 后台酒店通知列表结果转换器
     *
     * @param array $noticList
     *            酒店通知列表
     * @param array $tagList
     *            酒店通知标签列表
     * @param int $noticCount
     *            酒店通知总数
     * @param array $param
     *            扩展参数
     * @return array
     */
    public function getAdminNoticListConvertor($noticList, $tagList, $noticCount, $param) {
        $tagListNew = array();
        foreach ($tagList as $tag) {
            $tagListNew [$tag ['id']] ['titleLang1'] = $tag ['title_lang1'];
            $tagListNew [$tag ['id']] ['titleLang2'] = $tag ['title_lang2'];
            $tagListNew [$tag ['id']] ['titleLang3'] = $tag ['title_lang3'];
        }
        $data = array('list' => array());
        foreach ($noticList as $notic) {
            $noticTemp = array();
            $noticTemp ['id'] = $notic ['id'];
            $noticTemp ['title_lang1'] = $notic ['title_lang1'];
            $noticTemp ['title_lang2'] = $notic ['title_lang2'];
            $noticTemp ['title_lang3'] = $notic ['title_lang3'];
            $noticTemp ['article_lang1'] = $notic ['article_lang1'];
            $noticTemp ['article_lang2'] = $notic ['article_lang2'];
            $noticTemp ['article_lang3'] = $notic ['article_lang3'];
            $noticTemp ['tagId'] = $notic ['tagid'];
            $noticTemp ['status'] = $notic ['status'];
            $noticTemp ['tagName_lang1'] = $tagListNew [$noticTemp ['tagId']] ['titleLang1'];
            $noticTemp ['tagName_lang2'] = $tagListNew [$noticTemp ['tagId']] ['titleLang2'];
            $noticTemp ['tagName_lang3'] = $tagListNew [$noticTemp ['tagId']] ['titleLang3'];
            $noticTemp ['sort'] = $notic ['sort'];
            $noticTemp ['pdf'] = $notic ['pdf'];
            $noticTemp ['video'] = $notic ['video'];
            $noticTemp ['createTime'] = $notic ['createtime'];
            $noticTemp ['updateTime'] = $notic ['updatetime'];
            $noticTemp ['pic'] = $notic ['pic'];

            $noticTemp ['homeShow'] = $notic ['homeShow'];
            $noticTemp ['startTime'] = date('Y-m-d H:i:s', $notic ['startTime']);
            $noticTemp ['endTime'] = date('Y-m-d H:i:s', $notic ['endTime']);

            $data ['list'] [] = $noticTemp;
        }
        $data ['total'] = $noticCount;
        $data ['page'] = $param ['page'];
        $data ['limit'] = $param ['limit'];
        $data ['nextPage'] = Util_Tools::getNextPage($data ['page'], $data ['limit'], $data ['total']);
        return $data;
    }

    /**
     * 酒店通知详情结果转换器
     *
     * @param array $list
     *            酒店通知详情
     * @return array
     */
    public function getNoticDetailConvertor($list) {
        $data = array();
        $data ['id'] = $list ['id'];
        $data ['title'] = $this->handlerMultiLang('title', $list);
        $data ['article'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('article', $list));
        $data ['createTime'] = $list ['createtime'];
        $data ['updateTime'] = $list ['updatetime'];
        $data ['status'] = $list ['status'];
        $data ['tagId'] = $list ['tagid'];
        $data ['pic'] = $list ['pic'];
        return $data;
    }

    /**
     * 后台酒店通知详情结果转换器
     *
     * @param array $list
     *            酒店通知详情
     * @return array
     */
    public function getAdminNoticDetailConvertor($list) {
        $data = array();
        $data ['id'] = $list ['id'];
        $data ['title_lang1'] = $list ['title_lang1'];
        $data ['title_lang2'] = $list ['title_lang2'];
        $data ['title_lang3'] = $list ['title_lang3'];
        $data ['article_lang1'] = $list ['article_lang1'];
        $data ['article_lang2'] = $list ['article_lang2'];
        $data ['article_lang3'] = $list ['article_lang3'];
        $data ['tagId'] = $list ['tagid'];
        $data ['pic'] = $list ['pic'];
        $data ['status'] = $list ['status'];
        $data ['createTime'] = $list ['createtime'];
        $data ['updateTime'] = $list ['updatetime'];
        return $data;
    }
}
