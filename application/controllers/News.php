<?php

/**
 * 酒店新闻控制器类
 *
 */
class NewsController extends \BaseController {

    /**
     * @var NewsModel
     */
    private $model;

    /**
     * @var Convertor_News
     */
    private $convertor;

    public function init() {
        parent::init();
        $this->model = new NewsModel ();
        $this->convertor = new Convertor_News ();
    }

    /**
     * 获取酒店新闻列表
     *
     * @return Json
     */
    public function getNewsListAction() {
        $param = array();
        $param ['hotelid'] = intval($this->getParamList('hotelid'));
        $param ['tagid'] = intval($this->getParamList('tagid'));
        $param ['id'] = intval($this->getParamList('id'));
        if (Enum_System::notAdminPackage($this->package)) {
            $param['status'] = 1;
        } else {
            $param ['status'] = $this->getParamList('status');
        }
        $param ['title'] = $this->getParamList('title');
        if (!empty($this->getParamList('lang'))) {
            $langEnable = NewsModel::ENABLE_LANG . Enum_Lang::getLangIndex($this->getParamList('lang'), Enum_Lang::CHINESE);
            $param[$langEnable] = NewsModel::ENABLE;
        }
        $this->getPageParam($param);
        $newsList = $this->model->getNewsList($param);
        $newsCount = $this->model->getNewsCount($param);
        $newsTagModel = new NewsTagModel ();
        $tagList = $newsTagModel->getNewsTagList($param);
        Enum_System::notAdminPackage($this->package) ? $data = $this->convertor->getNewsListConvertor($newsList, $tagList, $newsCount, $param) : $data = $this->convertor->getAdminNewsListConvertor($newsList, $tagList, $newsCount, $param);
        $this->echoSuccessData($data);
    }

    /**
     * 根据id获取酒店新闻详情
     *
     * @param
     *            int id 获取详情信息的id
     * @return Json
     */
    public function getNewsDetailAction() {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $data = $this->model->getNewsDetail($id);
            Enum_System::notAdminPackage($this->package) ? $data = $this->convertor->getNewsDetailConvertor($data) : $data = $this->convertor->getAdminNewsDetailConvertor($data);
        } else {
            $this->throwException(1, '查询条件错误，id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 根据id修改酒店新闻信息
     *
     * @param
     *            int id 获取详情信息的id
     * @param
     *            array param 需要更新的字段
     * @return Json
     */
    public function updateNewsByIdAction() {
        $id = intval($this->getParamList('id'));
        if ($id) {
            $param = array();
            $param ['hotelid'] = $this->getParamList('hotelid');
            $param ['status'] = $this->getParamList('status');
            $param ['title_lang1'] = $this->getParamList('title_lang1');
            $param ['title_lang2'] = $this->getParamList('title_lang2');
            $param ['title_lang3'] = $this->getParamList('title_lang3');
            $param ['article_lang1'] = $this->getParamList('article_lang1');
            $param ['article_lang2'] = $this->getParamList('article_lang2');
            $param ['article_lang3'] = $this->getParamList('article_lang3');
            $param ['tagid'] = $this->getParamList('tagid');
            $param ['sort'] = $this->getParamList('sort');
            $param ['pdf'] = $this->getParamList('pdf');
            $param ['video'] = $this->getParamList('video');
            $param ['pic'] = $this->getParamList('pic');
            $param['enable_lang1'] = $this->getParamList('enable_lang1');
            $param['enable_lang2'] = $this->getParamList('enable_lang2');
            $param['enable_lang3'] = $this->getParamList('enable_lang3');
            $param ['updatetime'] = time();

            $param ['homeShow'] = trim($this->getParamList('homeShow'));
            $param ['startTime'] = trim($this->getParamList('startTime'));
            $param ['endTime'] = trim($this->getParamList('endTime'));

            $data = $this->model->updateNewsById($param, $id);
            $data = $this->convertor->statusConvertor($data);
        } else {
            $this->throwException(1, 'id不能为空');
        }
        $this->echoSuccessData($data);
    }

    /**
     * 添加酒店新闻信息
     *
     * @param
     *            array param 需要新增的信息
     * @return Json
     */
    public function addNewsAction() {
        $param = array();
        $param ['hotelid'] = $this->getParamList('hotelid');
        $param ['status'] = $this->getParamList('status');
        $param ['title_lang1'] = $this->getParamList('title_lang1');
        $param ['title_lang2'] = $this->getParamList('title_lang2');
        $param ['title_lang3'] = $this->getParamList('title_lang3');
        $param ['tagid'] = $this->getParamList('tagid');
        $param ['updatetime'] = time();
        $param ['createtime'] = time();
        $param ['sort'] = intval($this->getParamList('sort'));
        $param ['pdf'] = trim($this->getParamList('pdf'));
        $param ['video'] = trim($this->getParamList('video'));
        $param ['pic'] = trim($this->getParamList('pic'));
        $param['enable_lang1'] = $this->getParamList('enable_lang1', NewsModel::ENABLE);
        $param['enable_lang2'] = $this->getParamList('enable_lang2', NewsModel::ENABLE);
        $param['enable_lang3'] = $this->getParamList('enable_lang3', NewsModel::ENABLE);

        $param ['homeShow'] = trim($this->getParamList('homeShow'));
        $param ['startTime'] = trim($this->getParamList('startTime'));
        $param ['endTime'] = trim($this->getParamList('endTime'));

        $data = $this->model->addNews($param);
        $data = $this->convertor->statusConvertor(array('id' => $data));
        $this->echoSuccessData($data);
    }
}
