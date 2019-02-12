<?php
/**
 * 酒店通知控制器类
 *
 */
class NoticController extends \BaseController {

    /**
     * @var NoticModel
     */
	private $model;

    /**
     * @var Convertor_Notic
     */
	private $convertor;

	public function init() {
		parent::init ();
		$this->model = new NoticModel ();
		$this->convertor = new Convertor_Notic ();
	}

	/**
	 * 获取酒店通知列表
	 *
	 * @return Json
	 */
	public function getNoticListAction() {
		$param = array ();
		$param ['hotelid'] = intval ( $this->getParamList ( 'hotelid' ) );
		$param ['tagid'] = intval ( $this->getParamList ( 'tagid' ) );
		$param ['id'] = intval ( $this->getParamList ( 'id' ) );
		$param ['status'] = $this->getParamList ( 'status' );
        if(Enum_System::notAdminPackage($this->package)){
            $param ['status'] = 1;
        }
		$param ['title'] = $this->getParamList ( 'title' );
		$this->getPageParam ( $param );
		$list = $this->model->getNoticList ( $param );
		$count = $this->model->getNoticCount ( $param );
		$tagModel = new NoticTagModel ();
		$tagList = $tagModel->getNoticTagList ( $param );
        Enum_System::notAdminPackage($this->package) ? $data = $this->convertor->getNoticListConvertor ( $list, $tagList, $count, $param ) : $data = $this->convertor->getAdminNoticListConvertor ( $list, $tagList, $count, $param );
		$this->echoSuccessData ( $data );
	}

	/**
	 * 根据id获取酒店通知详情
	 *
	 * @param
	 *        	int id 获取详情信息的id
	 * @return Json
	 */
	public function getNoticDetailAction() {
		$id = intval ( $this->getParamList ( 'id' ) );
		if ($id) {
			$data = $this->model->getNoticDetail ( $id );
            Enum_System::notAdminPackage($this->package) ? $data = $this->convertor->getNoticDetailConvertor ( $data ) : $data = $this->convertor->getAdminNoticDetailConvertor ( $data );
		} else {
			$this->throwException ( 1, '查询条件错误，id不能为空' );
		}
		$this->echoSuccessData ( $data );
	}

	/**
	 * 根据id修改酒店通知信息
	 *
	 * @param
	 *        	int id 获取详情信息的id
	 * @param
	 *        	array param 需要更新的字段
	 * @return Json
	 */
	public function updateNoticByIdAction() {
		$id = intval ( $this->getParamList ( 'id' ) );
		if ($id) {
			$param = array ();
			$param ['hotelid'] = $this->getParamList ( 'hotelid' );
			$param ['status'] = $this->getParamList ( 'status' );
			$param ['title_lang1'] = $this->getParamList ( 'title_lang1' );
			$param ['title_lang2'] = $this->getParamList ( 'title_lang2' );
			$param ['title_lang3'] = $this->getParamList ( 'title_lang3' );
			$param ['article_lang1'] = $this->getParamList ( 'article_lang1' );
			$param ['article_lang2'] = $this->getParamList ( 'article_lang2' );
			$param ['article_lang3'] = $this->getParamList ( 'article_lang3' );
			$param ['tagid'] = $this->getParamList ( 'tagid' );
            $param ['sort'] = $this->getParamList('sort');
            $param ['pdf'] = $this->getParamList('pdf');
            $param ['pic'] = $this->getParamList('pic');
            $param ['video'] = $this->getParamList('video');
			$param ['updatetime'] = time ();

			$param ['homeShow'] = trim($this->getParamList('homeShow'));
            $param ['startTime'] = trim($this->getParamList('startTime'));
			$param ['endTime'] = trim($this->getParamList('endTime'));
			
			$data = $this->model->updateNoticById ( $param, $id );
			$data = $this->convertor->statusConvertor ( $data );
		} else {
			$this->throwException ( 1, 'id不能为空' );
		}
		$this->echoSuccessData ( $data );
	}

	/**
	 * 添加酒店通知信息
	 *
	 * @param
	 *        	array param 需要新增的信息
	 * @return Json
	 */
	public function addNoticAction() {
		$param = array ();
		$param ['hotelid'] = $this->getParamList ( 'hotelid' );
		$param ['status'] = $this->getParamList ( 'status' );
		$param ['title_lang1'] = $this->getParamList ( 'title_lang1' );
		$param ['title_lang2'] = $this->getParamList ( 'title_lang2' );
		$param ['title_lang3'] = $this->getParamList ( 'title_lang3' );
		$param ['tagid'] = $this->getParamList ( 'tagid' );
        $param ['sort'] = intval($this->getParamList('sort'));
        $param ['pdf'] = trim($this->getParamList('pdf'));
        $param ['pic'] = trim($this->getParamList('pic'));
        $param ['video'] = trim($this->getParamList('video'));
		$param ['updatetime'] = time ();
		$param ['createtime'] = time ();

		$param ['homeShow'] = trim($this->getParamList('homeShow'));
		$param ['startTime'] = trim($this->getParamList('startTime'));
		$param ['endTime'] = trim($this->getParamList('endTime'));
			
		$data = $this->model->addNotic ( $param );
		$data = $this->convertor->statusConvertor ( array ('id' => $data ) );
		$this->echoSuccessData ( $data );
	}
}
