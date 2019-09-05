<?php

/**
 * Class NoticModel
 * 物业通知管理
 */
class NoticModel extends \BaseModel
{

	private $dao;

	public function __construct()
	{
		parent::__construct();
		$this->dao = new Dao_Notic();
	}

	/**
	 * 获取Notic列表信息
	 *
	 * @param
	 *        	array param 查询条件
	 * @return array
	 */
	public function getNoticList(array $param)
	{
		isset($param['hotelid']) ? $paramList['hotelid'] = intval($param['hotelid']) : false;
		$param['tagid'] ? $paramList['tagid'] = intval($param['tagid']) : false;
		$param['id'] ? $paramList['id'] = intval($param['id']) : false;
		$param['title'] ? $paramList['title'] = $param['title'] : false;
		isset($param['status']) ? $paramList['status'] = intval($param['status']) : false;
		$paramList['limit'] = $param['limit'];
		$paramList['page'] = $param['page'];
		return $this->dao->getNoticList($paramList);
	}

	/**
	 * 获取Notic数量
	 *
	 * @param
	 *        	array param 查询条件
	 * @return array
	 */
	public function getNoticCount(array $param)
	{
		isset($param['hotelid']) ? $paramList['hotelid'] = intval($param['hotelid']) : false;
		$param['id'] ? $paramList['id'] = intval($param['id']) : false;
		$param['title'] ? $paramList['title'] = $param['title'] : false;
		$param['tagid'] ? $paramList['tagid'] = intval($param['tagid']) : false;
		isset($param['status']) ? $paramList['status'] = intval($param['status']) : false;
		return $this->dao->getNoticCount($paramList);
	}

	/**
	 * 根据id查询Notic信息
	 *
	 * @param
	 *        	int id 查询的主键
	 * @return array
	 */
	public function getNoticDetail($id)
	{
		$result = array();
		if ($id) {
			$result = $this->dao->getNoticDetail($id);
		}
		return $result;
	}

	/**
	 * 根据id更新Notic信息
	 *
	 * @param
	 *        	array param 需要更新的信息
	 * @param
	 *        	int id 主键
	 * @return array
	 */
	public function updateNoticById($param, $id)
	{
		$result = false;
		if ($id) {
			$info = array();
			isset($param['hotelid']) ? $info['hotelid'] = $param['hotelid'] : false;
			isset($param['status']) ? $info['status'] = $param['status'] : false;
			isset($param['title_lang1']) ? $info['title_lang1'] = $param['title_lang1'] : false;
			isset($param['title_lang2']) ? $info['title_lang2'] = $param['title_lang2'] : false;
			isset($param['title_lang3']) ? $info['title_lang3'] = $param['title_lang3'] : false;
			isset($param['article_lang1']) ? $info['article_lang1'] = $param['article_lang1'] : false;
			isset($param['article_lang2']) ? $info['article_lang2'] = $param['article_lang2'] : false;
			isset($param['article_lang3']) ? $info['article_lang3'] = $param['article_lang3'] : false;
			isset($param['tagid']) ? $info['tagid'] = $param['tagid'] : false;
			isset($param['updatetime']) ? $info['updatetime'] = $param['updatetime'] : false;
			isset($param['sort']) ? $info['sort'] = $param['sort'] : false;
			isset($param['pdf']) ? $info['pdf'] = $param['pdf'] : false;
			isset($param['pic']) ? $info['pic'] = $param['pic'] : false;
			isset($param['video']) ? $info['video'] = $param['video'] : false;

			isset($param['homeShow']) ? $info['homeShow'] = $param['homeShow'] : 0;
			if ($info['homeShow'] == '') {
				$info['homeShow'] = 1;
			}
			isset($param['startTime']) ? $info['startTime'] = $param['startTime'] : time();
			if ($info['startTime'] == '') {
				$info['startTime'] = time();
			}
			isset($param['endTime']) ? $info['endTime'] = $param['endTime'] : time();
			if ($info['endTime'] == '') {
				$info['endTime'] = time();
			}

			$result = $this->dao->updateNoticById($info, $id);
		}
		return $result;
	}

	/**
	 * Notic新增信息
	 *
	 * @param
	 *        	array param 需要增加的信息
	 * @return array
	 */
	public function addNotic($param)
	{
		$info = $param;
		return $this->dao->addNotic($info);
	}
}
