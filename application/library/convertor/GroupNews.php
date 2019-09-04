<?php

/**
 * 集团新闻转换器类
 */
class Convertor_GroupNews extends Convertor_Base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 新闻列表
     *
     * @param array $newsList
     *            新闻列表
     * @param array $tagList
     *            新闻标签
     * @param int $newsCount
     *            新闻总数
     * @param array $param
     *            扩展参数
     * @return array
     */
    public function getNewsListConvertor($newsList, $tagList, $newsCount, $param)
    {
        $tagListNew = array();
        foreach ($tagList as $tag) {
            $tagListNew[$tag ['id']] = $tag['title_lang' . $param['langIndex']];
        }
        $data = array('list' => array());
        foreach ($newsList as $news) {
            $newTemp = array();
            $newTemp ['id'] = $news ['id'];
            $newTemp ['title'] = $news['title_lang' . $param['langIndex']];
            $newTemp ['article'] = Enum_Img::getPathByKeyAndType($news['article_lang' . $param['langIndex']]);
            $newTemp ['pdf'] = $news['pdf'] ? Enum_Img::getPathByKeyAndType($news['pdf']) : '';
            $newTemp ['video'] = $news['video'] ? Enum_Img::getPathByKeyAndType($news['video']) : '';
            $newTemp ['pic'] = Enum_Img::getPathByKeyAndType($news['pic'], Enum_Img::PIC_TYPE_KEY_WIDTH750);
            $newTemp ['tagId'] = $news ['tagid'];
            $newTemp ['tagName'] = $tagListNew[$newTemp['tagId']];
            $newTemp ['createtime'] = $news ['createtime'];
            $newTemp ['updatetime'] = $news ['updatetime'];
            $newTemp['outLink'] = $this->handlerMultiLang('link', $news, true);
            $data ['list'] [] = $newTemp;
        }
        $data ['total'] = $newsCount;
        $data ['page'] = $param ['page'];
        $data ['limit'] = $param ['limit'];
        $data ['nextPage'] = Util_Tools::getNextPage($data ['page'], $data ['limit'], $data ['total']);
        return $data;
    }

    /**
     * 后台新闻列表
     *
     * @param array $newsList
     *            新闻列表
     * @param array $tagList
     *            新闻标签
     * @param int $newsCount
     *            新闻总数
     * @param array $param
     *            扩展参数
     * @return array
     */
    public function getAdminNewsListConvertor($newsList, $tagList, $newsCount, $param)
    {
        $tagListNew = array();
        foreach ($tagList as $tag) {
            $tagListNew[$tag ['id']] = $tag['title_lang' . $param['langIndex']];
        }
        $data = array('list' => array());
        foreach ($newsList as $news) {
            $newTemp = array();
            $newTemp['id'] = $news['id'];
            $newTemp['title'] = $news['title_lang' . $param['langIndex']];
            $newTemp['title_lang1'] = $news['title_lang1'];
            $newTemp['title_lang2'] = $news ['title_lang2'];

            $newTemp ['article_lang1'] = $news ['article_lang1'];
            $newTemp ['article_lang2'] = $news ['article_lang2'];

            $newTemp['link_lang1'] = $news['link_lang1'];
            $newTemp['link_lang2'] = $news['link_lang2'];
            $newTemp['link_lang3'] = $news['link_lang3'];

            $newTemp['enable_lang1'] = $news['enable_lang1'];
            $newTemp['enable_lang2'] = $news['enable_lang2'];
            $newTemp['enable_lang3'] = $news['enable_lang3'];

            $newTemp ['status'] = $news ['status'];
            $newTemp ['tagId'] = $news ['tagid'];
            $newTemp ['tagName'] = $tagListNew [$newTemp ['tagId']];
            $newTemp ['sort'] = $news ['sort'];
            $newTemp ['pdf'] = $news ['pdf'];
            $newTemp ['video'] = $news ['video'];
            $newTemp ['pic'] = $news ['pic'];
            $newTemp ['createTime'] = $news ['createtime'];
            $newTemp ['updateTime'] = $news ['updatetime'];
            $data ['list'] [] = $newTemp;
        }
        $data ['total'] = $newsCount;
        $data ['page'] = $param ['page'];
        $data ['limit'] = $param ['limit'];
        $data ['nextPage'] = Util_Tools::getNextPage($data ['page'], $data ['limit'], $data ['total']);
        return $data;
    }

    /**
     * 新闻详情
     *
     * @param array $list
     *            新闻详情
     * @return array
     */
    public function getNewsDetailConvertor($list)
    {
        $data = array();
        $data ['id'] = $list ['id'];
        $data ['title'] = $this->handlerMultiLang('title', $list);
        $data ['article'] = Enum_Img::getPathByKeyAndType($this->handlerMultiLang('article', $list));
        $data ['createTime'] = $list ['createtime'];
        $data ['updateTime'] = $list ['updatetime'];
        $data ['status'] = $list ['status'];
        $data ['tagId'] = $list ['tagid'];
        return $data;
    }

    /**
     * 后台新闻详情
     *
     * @param array $list
     *            后台新闻详情
     * @return array
     */
    public function getAdminNewsDetailConvertor($list)
    {
        $data = array();
        $data ['id'] = $list ['id'];
        $data ['title'] = $list ['title'];
        $data ['article'] = $list ['article'];
        $data ['tagId'] = $list ['tagid'];
        $data ['status'] = $list ['status'];
        $data ['createTime'] = $list ['createtime'];
        $data ['updateTime'] = $list ['updatetime'];
        return $data;
    }
}
