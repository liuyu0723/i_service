<?php

/**
 * 集团通知管理数据层
 */
class Dao_GroupNotice extends Dao_Base {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 查询group_notice列表
     *
     * @param
     *            array 入参
     * @return array
     */
    public function getNoticList(array $param): array {
        $limit = $param['limit'] ? intval($param['limit']) : 0;
        $page = $this->getStart($param['page'], $limit);

        $paramSql = $this->handlerNoticListParams($param);
        $sql = "select * from group_notice {$paramSql['sql']} order by id DESC";
        if ($limit) {
            $sql .= " limit {$page},{$limit}";
        }
        $result = $this->db->fetchAll($sql, $paramSql['case']);
        return is_array($result) ? $result : array();
    }

    /**
     * 查询group_notice数量
     *
     * @param
     *            array 入参
     * @return array
     */
    public function getNoticCount(array $param): int {
        $paramSql = $this->handlerNoticListParams($param);
        $sql = "select count(1) as count from group_notice {$paramSql['sql']}";
        $result = $this->db->fetchAssoc($sql, $paramSql['case']);
        return intval($result['count']);
    }

    /**
     * 列表和数量获取筛选参数处理
     * @param $param
     * @return array
     */
    private function handlerNoticListParams($param) {
        $whereSql = array();
        $whereCase = array();
        if (isset($param['groupid'])) {
            $whereSql[] = 'groupid = ?';
            $whereCase[] = $param['groupid'];
        }
        if (isset($param['tagid'])) {
            $whereSql[] = 'tagid = ?';
            $whereCase[] = $param['tagid'];
        }
        if (isset($param['status'])) {
            $whereSql[] = 'status = ?';
            $whereCase[] = $param['status'];
        }
        if (isset($param['id'])) {
            $whereSql[] = 'id = ?';
            $whereCase[] = $param['id'];
        }
        if (isset($param['title_lang1'])) {
            $whereSql[] = 'title_lang1 = ?';
            $whereCase[] = $param['title_lang1'];
        }
        if (isset($param['title_lang2'])) {
            $whereSql[] = 'title_lang2 = ?';
            $whereCase[] = $param['title_lang2'];
        }
        if (isset($param['title_lang3'])) {
            $whereSql[] = 'title_lang3 = ?';
            $whereCase[] = $param['title_lang3'];
        }
        if (isset($param['enable_lang1'])) {
            $whereSql[] = 'enable_lang1 = ?';
            $whereCase[] = $param['enable_lang1'];
        }
        if (isset($param['enable_lang2'])) {
            $whereSql[] = 'enable_lang2 = ?';
            $whereCase[] = $param['enable_lang2'];
        }
        if (isset($param['enable_lang3'])) {
            $whereSql[] = 'enable_lang3 = ?';
            $whereCase[] = $param['enable_lang3'];
        }
        $whereSql = $whereSql ? ' where ' . implode(' and ', $whereSql) : '';
        return array(
            'sql' => $whereSql,
            'case' => $whereCase
        );
    }

    /**
     * 根据id查询group_notice详情
     *
     * @param
     *            int id
     * @return array
     */
    public function getNoticDetail(int $id): array {
        $result = array();

        if ($id) {
            $sql = "select * from group_notice where id=?";
            $result = $this->db->fetchAssoc($sql, array(
                $id
            ));
        }

        return $result;
    }

    /**
     * 根据id更新group_notice
     *
     * @param
     *            array 需要更新的数据
     * @param
     *            int id
     * @return array
     */
    public function updateNoticById(array $info, int $id) {
        $result = false;

        if ($id) {
            $result = $this->db->update('group_notice', $info, array('id' => $id));
        }

        return $result;
    }

    /**
     * 单条增加group_notice数据
     *
     * @param
     *            array
     * @return int id
     */
    public function addNotic(array $info) {
        $this->db->insert('group_notice', $info);
        return $this->db->lastInsertId();
    }
}
