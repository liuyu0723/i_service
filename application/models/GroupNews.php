<?php

/**
 * Class GroupNewsModel
 * 新闻管理
 */
class GroupNewsModel extends \BaseModel {

    const ENABLE_LANG = 'enable_lang';
    const ENABLE = 1;

    private $dao;

    public function __construct() {
        parent::__construct();
        $this->dao = new Dao_GroupNews ();
    }

    /**
     * 获取GroupNews列表信息
     *
     * @param
     *            array param 查询条件
     * @return array
     */
    public function getNewsList(array $param) {
        isset ($param ['groupid']) ? $paramList ['groupid'] = intval($param ['groupid']) : false;
        $param ['tagid'] ? $paramList ['tagid'] = intval($param ['tagid']) : false;
        $param ['id'] ? $paramList ['id'] = intval($param ['id']) : false;
        $param ['title_lang1'] ? $paramList ['title_lang1'] = $param ['title_lang1'] : false;
        $param ['title_lang2'] ? $paramList ['title_lang2'] = $param ['title_lang2'] : false;
        isset ($param ['status']) ? $paramList ['status'] = intval($param ['status']) : false;
        array_key_exists('enable_lang1', $param) ? $paramList['enable_lang1'] = intval($param['enable_lang1']) : false;
        array_key_exists('enable_lang2', $param) ? $paramList['enable_lang2'] = intval($param['enable_lang2']) : false;
        array_key_exists('enable_lang3', $param) ? $paramList['enable_lang3'] = intval($param['enable_lang3']) : false;
        $paramList ['limit'] = $param ['limit'];
        $paramList ['page'] = $param ['page'];
        return $this->dao->getNewsList($paramList);
    }

    /**
     * 获取News数量
     *
     * @param
     *            array param 查询条件
     * @return array
     */
    public function getNewsCount(array $param) {
        isset ($param ['groupid']) ? $paramList ['groupid'] = intval($param ['groupid']) : false;
        $param ['tagid'] ? $paramList ['tagid'] = intval($param ['tagid']) : false;
        isset ($param ['status']) ? $paramList ['status'] = intval($param ['status']) : false;
        array_key_exists('enable_lang1', $param) ? $paramList['enable_lang1'] = intval($param['enable_lang1']) : false;
        array_key_exists('enable_lang2', $param) ? $paramList['enable_lang2'] = intval($param['enable_lang2']) : false;
        array_key_exists('enable_lang3', $param) ? $paramList['enable_lang3'] = intval($param['enable_lang3']) : false;
        return $this->dao->getNewsCount($paramList);
    }

    /**
     * 根据id查询News信息
     *
     * @param
     *            int id 查询的主键
     * @return array
     */
    public function getNewsDetail($id) {
        $result = array();
        if ($id) {
            $result = $this->dao->getNewsDetail($id);
        }
        return $result;
    }

    /**
     * 根据id更新GroupNews信息
     *
     * @param
     *            array param 需要更新的信息
     * @param
     *            int id 主键
     * @return array
     */
    public function updateNewsById($param, $id) {
        $result = false;
        if ($id) {
            $info = array();
            isset($param ['groupid']) ? $info ['groupid'] = $param ['groupid'] : false;
            isset($param ['status']) ? $info ['status'] = $param ['status'] : false;
            isset($param ['enable_lang1']) ? $info ['enable_lang1'] = $param ['enable_lang1'] : false;
            isset($param ['enable_lang2']) ? $info ['enable_lang2'] = $param ['enable_lang2'] : false;
            isset($param ['enable_lang3']) ? $info ['enable_lang3'] = $param ['enable_lang3'] : false;
            $param ['title_lang1'] ? $info ['title_lang1'] = $param ['title_lang1'] : false;
            $param ['title_lang2'] ? $info ['title_lang2'] = $param ['title_lang2'] : false;
            $info['link_lang1'] = trim($param['link_lang1']);
            $info['link_lang2'] = trim($param['link_lang2']);
            $info['link_lang3'] = trim($param['link_lang3']);
            isset($param ['article_lang1']) ? $info ['article_lang1'] = $param ['article_lang1'] : false;
            isset($param ['article_lang2']) ? $info ['article_lang2'] = $param ['article_lang2'] : false;
            isset($param ['tagid']) ? $info ['tagid'] = $param ['tagid'] : false;
            isset($param ['updatetime']) ? $info ['updatetime'] = $param ['updatetime'] : false;
            isset($param['sort']) ? $info['sort'] = $param['sort'] : false;
            isset($param['pdf']) ? $info['pdf'] = $param['pdf'] : false;
            isset($param['video']) ? $info['video'] = $param['video'] : false;
            isset($param['pic']) ? $info['pic'] = $param['pic'] : false;
            $result = $this->dao->updateNewsById($info, $id);
        }
        return $result;
    }

    /**
     * News新增信息
     *
     * @param
     *            array param 需要增加的信息
     * @return array
     */
    public function addNews($param)
    {
        if (is_null($param ['enable_lang1'])) {
            unset($param ['enable_lang1']);
        }
        if (is_null($param ['enable_lang2'])) {
            unset($param ['enable_lang2']);
        }
        if (is_null($param ['enable_lang3'])) {
            unset($param ['enable_lang3']);
        }
        $info = $param;
        return $this->dao->addNews($info);
    }
}
