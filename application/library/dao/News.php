<?php
class Dao_News extends Dao_Base{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * 查询hotel_news列表
     * @param array 入参
     * @return array
     */
    public function getNewsList(array $param):array {
        $limit = $param['limit']?intval($param['limit']):0;
        $page = $this->getStart($param['page'],$limit);
        $sql = "select * from hotel_news limit {$page},{$limit}";
        $result = $this->db->fetchAll($sql, array());
        return is_array($result)?$result:array();
    }

    /**
     * 根据id查询hotel_news详情
     * @param int id 
     * @return array
     */
    public function getNewsDetail (int $id):array{
        $result = array ();
        
        if ($id){
            $sql = "select * from hotel_news where id=?";
            $result = $this->db->fetchAssoc($sql,array($id));
        }

        return $result;
    }

    /**
     * 根据id更新hotel_news
     * @param array 需要更新的数据
     * @param int id 
     * @return array
     */
    public function updateNewsById(array $info,int $id){
        $result = false;

        if ($id){
            $result = $this->db->update('hotel_news',$info,$id);
        }

        return $result;
    }

    /**
     * 单条增加hotel_news数据
     * @param array
     * @return int id
     */
    public function addNews(array $info){
        $this->db->insert('hotel_news', $info);
        return $this->db->lastInsertId();
    }
}
