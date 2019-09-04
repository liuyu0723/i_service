<?php

/**
 * 集团通知控制器类
 *
 */
class GroupNoticeController extends \BaseController
{

    /**
     * @var GroupNoticeModel
     */
    private $model;

    /**
     * @var Convertor_GroupNotice
     */
    private $convertor;

    public function init()
    {
        parent::init();
        $this->model = new GroupNoticeModel ();
        $this->convertor = new Convertor_GroupNotice ();
    }

    /**
     * 获取集团通知列表
     *
     * @return Json
     */
    public function getNoticListAction()
    {
        $param = array();
        $param['groupid'] = intval($this->getParamList('groupid'));
        $param['tagid'] = intval($this->getParamList('tagid'));
        $param['id'] = intval($this->getParamList('id'));
        $param['status'] = $this->getParamList('status');
        $param['title_lang1'] = $this->getParamList('title_lang1');
        $param['title_lang2'] = $this->getParamList('title_lang2');
        $param['title_lang3'] = $this->getParamList('title_lang3');
        $param['lang'] = trim($this->getParamList('lang', Enum_Lang::CHINESE));
        if (!empty($this->getParamList('lang')) && $this->getParamList('lang') !== "all") {
            $langEnable = GroupNoticeModel::ENABLE_LANG . Enum_Lang::getLangIndex($this->getParamList('lang'));
            $param[$langEnable] = GroupNoticeModel::ENABLE;
        }
        $this->getPageParam($param);
        $list = $this->model->getNoticList($param);
        $count = $this->model->getNoticCount($param);
        $tagModel = new GroupNoticeTagModel();
        $tagList = $tagModel->getNoticeTagList($param);
        $data = Enum_System::notAdminPackage($this->package) ? $this->convertor->getNoticListConvertor($list, $tagList, $count, $param) : $this->convertor->getAdminNoticListConvertor($list, $tagList, $count, $param);
        $this->echoSuccessData($data);
    }

    /**
     * 根据id获取集团通知详情
     *
     * @param
     *            int id 获取详情信息的id
     * @return Json
     */
    public function getNoticDetailAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $data = $this->model->getNoticDetail($id);
            Enum_System::notAdminPackage($this->package) ? $data = $this->convertor->getNoticDetailConvertor($data) : $data = $this->convertor->getAdminNoticDetailConvertor($data);
        } else {
            $this->throwException(1, '查询条件错误，id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 根据id修改集团通知信息
     *
     * @param
     *            int id 获取详情信息的id
     * @param
     *            array param 需要更新的字段
     * @return Json
     */
    public function updateNoticByIdAction()
    {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $param = array();
            $param ['groupid'] = $this->getParamList('groupid');
            $param ['status'] = $this->getParamList('status');
            $param ['title_lang1'] = $this->getParamList('title_lang1');
            $param ['title_lang2'] = $this->getParamList('title_lang2');
            $param ['title_lang3'] = $this->getParamList('title_lang3');
            $param ['article_lang1'] = $this->getParamList('article_lang1');
            $param ['article_lang2'] = $this->getParamList('article_lang2');
            $param ['article_lang3'] = $this->getParamList('article_lang3');
            $param ['link_lang1'] = $this->getParamList('link_lang1');
            $param ['link_lang2'] = $this->getParamList('link_lang2');
            $param ['link_lang3'] = $this->getParamList('link_lang3');
            $param['enable_lang1'] = $this->getParamList('enable_lang1');
            $param['enable_lang2'] = $this->getParamList('enable_lang2');
            $param['enable_lang3'] = $this->getParamList('enable_lang3');
            $param ['tagid'] = $this->getParamList('tagid');
            $param ['sort'] = $this->getParamList('sort');
            $param ['pdf'] = $this->getParamList('pdf');
            $param ['pic'] = $this->getParamList('pic');
            $param ['video'] = $this->getParamList('video');
            $param ['updatetime'] = time();
            $data = $this->model->updateNoticById($param, $id);
            $data = $this->convertor->statusConvertor($data);
        } else {
            $this->throwException(1, 'id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 添加集团通知信息
     *
     * @param
     *            array param 需要新增的信息
     * @return Json
     */
    public function addNoticAction()
    {
        $param = array();
        $param ['groupid'] = $this->getParamList('groupid');
        $param ['status'] = $this->getParamList('status');
        $param ['title_lang1'] = $this->getParamList('title_lang1');
        $param ['title_lang2'] = $this->getParamList('title_lang2');
        $param ['title_lang3'] = $this->getParamList('title_lang3');
        $param ['link_lang1'] = $this->getParamList('link_lang1');
        $param ['link_lang2'] = $this->getParamList('link_lang2');
        $param ['link_lang3'] = $this->getParamList('link_lang3');
        $param['enable_lang1'] = $this->getParamList('enable_lang1');
        $param['enable_lang2'] = $this->getParamList('enable_lang2');
        $param['enable_lang3'] = $this->getParamList('enable_lang3');
        $param ['tagid'] = $this->getParamList('tagid');
        $param ['sort'] = intval($this->getParamList('sort'));
        $param ['pdf'] = trim($this->getParamList('pdf'));
        $param ['pic'] = trim($this->getParamList('pic'));
        $param ['video'] = trim($this->getParamList('video'));
        $param ['updatetime'] = time();
        $param ['createtime'] = time();
        $data = $this->model->addNotic($param);
        $data = $this->convertor->statusConvertor(array('id' => $data));
        $this->echoSuccessData($data);
    }
}
