<?php
/**
 *所有控制器的基类 
 *
 */
abstract class BaseController extends \Yaf_Controller_Abstract
{

	protected $package;

	public function init()
	{
		$this->package = Yaf_Registry::get('package');
	}

	/**
	 * 获取参数信息
	 *
	 * @param string $paramKey
	 *        	获取参数的key
	 * @param string $defualt
	 *        	如果参数不存在的默认值
	 * @return array|string
	 */
	public function getParamList($paramKey = '', $defualt = null)
	{
		$request = $this->getRequest();
		if ($request->isGet()) {
			if ($paramKey) {
				$paramValue = $request->getQuery($paramKey, null);
				return !is_null($paramValue) ? $paramValue : $defualt;
			}
			return $request->getQuery();
		}
		if ($request->isPost()) {
			if ($paramKey) {
				$paramValue = $request->getPost($paramKey, null);
				return !is_null($paramValue) ? $paramValue : $defualt;
			}
			return $request->getPost();
		}
	}

	/**
	 * 输出json
	 *
	 * @param array $data        	
	 */
	public function echoJson($data)
	{
		$response = $this->getResponse();
		$response->setHeader('Content-type', 'application/json');
		$response->setBody(json_encode($data));
	}

	/**
	 * 接口访问成功
	 *
	 * @param array $data
	 *        	成功返回的数据
	 * @return string
	 */
	public function echoSuccessData($data = array())
	{
		if (!is_array($data) && !is_object($data)) {
			$data = array($data);
		}
		$this->echoAndExit(0, "success", $data);
	}

	public function echoAndExit($code, $msg, $data, $debugInfo = null)
	{
		@header("Content-type:application/json");
		$data = $this->clearNullNew($data);
		if (is_null($data) && !is_numeric($data)) {
			$data = array();
		}
		$echoList = array('code' => $code, 'msg' => $msg, 'data' => $data);
		$sysConfig = Yaf_Registry::get('sysConfig');
		if ($sysConfig->api->debug) {
			$echoList['debugInfo'] = is_null($debugInfo) ? ( object )array() : $debugInfo;
		}
		$this->getResponse()->setBody(json_encode($echoList));
	}


	public function echoJsonData($code, $message, $data_field_name = 'data',  $data = array(),  $debugInfo = null)
	{
		if (!is_array($data) && !is_object($data)) {
			$data = array($data);
		}
		$this->echoAndExitWithDataFieldName($code, $message, $data_field_name, $data, $debugInfo);
	}

	public function echoAndExitWithDataFieldName($code, $msg, $data_field_name, $data, $debugInfo = null)
	{
		@header("Content-type:application/json");
		$data = $this->clearNullNew($data);
		if (is_null($data) && !is_numeric($data)) {
			$data = array();
		}
		$echoList = array('code' => $code, 'msg' => $msg, $data_field_name => $data);
		$sysConfig = Yaf_Registry::get('sysConfig');
		if ($sysConfig->api->debug) {
			$echoList['debugInfo'] = is_null($debugInfo) ? ( object )array() : $debugInfo;
		}
		$this->getResponse()->setBody(json_encode($echoList));
	}







	public function clearNullNew($data)
	{
		foreach ($data as $key => $value) {
			$keyTemp = lcfirst($key);
			if ($keyTemp != $key) {
				unset($data[$key]);
				$data[$keyTemp] = $value;
				$key = $keyTemp;
			}
			if (is_array($value) || is_object($value)) {
				if (is_object($data)) {
					$data->$key = $this->clearNullNew($value);
				} else {
					$data[$key] = $this->clearNullNew($value);
				}
			} else {
				if (is_null($value) && !is_numeric($value)) {
					$value = "";
				}
				if (is_numeric($value)) {
					$value = strval($value);
				}
				$data[$key] = $value;
			}
		}
		return $data;
	}

	/**
	 * 抛出异常
	 *
	 * @param string $code        	
	 * @param string $msg        	
	 * @throws Exception
	 */
	protected function throwException($code, $msg)
	{
		throw new Exception($msg, $code);
	}

	/**
	 * 获取分页参数
	 *
	 * @param array $paramList
	 *        	传入引用
	 */
	public function getPageParam(&$paramList)
	{
		$page = intval($this->getParamList('page'));
		$limit = intval($this->getParamList('limit'));
		$paramList['page'] = empty($page) ? 1 : $page;
		$paramList['limit'] = empty($limit) ? 5 : $limit;
	}

	/**
	 * 跳转404页面
	 */
	protected function jump404()
	{
		header('Location:/error/notfound');
		exit();
	}


	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
}
