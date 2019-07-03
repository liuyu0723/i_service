<?php

/**
 * 员工管理数据层
 */
class Dao_Staff extends Dao_Base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 查询hotel_staff列表
     *
     * @param array $param
     * @return array
     */
    public function getStaffList(array $param): array
    {
        $limit = $param['limit'] ? intval($param['limit']) : 0;
        $page = $this->getStart($param['page'], $limit);

        $paramSql = $this->hundleParams($param);

        $sql = "select hotel_staff.*, hotel_administrator.realname
                FROM hotel_staff 
                LEFT JOIN hotel_administrator ON 
                hotel_staff.admin_id = hotel_administrator.id
                {$paramSql['sql']} 
                ORDER BY hotel_staff.id DESC";

        if ($limit) {
            $sql .= " limit {$page},{$limit}";
        }
        $result = $this->db->fetchAll($sql, $paramSql['case']);
        return is_array($result) ? $result : array();
    }

    public function getStaffListCount(array $param): int
    {
        $paramSql = $this->hundleParams($param);
        $sql = "select count(1) as count from hotel_staff {$paramSql['sql']}";
        $result = $this->db->fetchAssoc($sql, $paramSql['case']);
        return intval($result['count']);
    }

    public function hundleParams(array $param): array
    {

        $whereSql = array();
        $whereCase = array();
        if (isset($param['id'])) {
            if (is_array($param['id'])) {
                $whereSql[] = 'hotel_staff.id in (' . implode(',', $param['id']) . ')';
            } else {
                $whereSql[] = 'hotel_staff.id = ?';
                $whereCase[] = $param['id'];
            }
        }
        if (isset($param['staffid'])) {
            if (is_array($param['staffid'])) {
                $whereSql[] = 'hotel_staff.staffid in (' . implode(',', $param['staffid']) . ')';
            } else {
                $whereSql[] = 'hotel_staff.staffid = ?';
                $whereCase[] = $param['staffid'];
            }
        }

        if (isset($param['hotelid'])) {
            if (is_array($param['hotelid'])) {
                $whereSql[] = 'hotel_staff.hotelid in (' . implode(',', $param['hotelid']) . ')';
            } else {
                $whereSql[] = 'hotel_staff.hotelid = ?';
                $whereCase[] = $param['hotelid'];
            }
        }

        if (isset($param['groupid'])) {
            if (is_array($param['groupid'])) {
                $whereSql[] = 'hotel_staff.groupid in (' . implode(',', $param['groupid']) . ')';
            } else {
                $whereSql[] = 'hotel_staff.groupid = ?';
                $whereCase[] = $param['groupid'];
            }
        }

        if (isset($param['name'])) {
            $whereSql[] = 'hotel_staff.lname = ?';
            $whereCase[] = $param['name'];
        }

        if (isset($param['department_id'])) {
            if (is_array($param['department_id'])) {
                $whereSql[] = 'hotel_administrator.department in (' . implode(',', $param['department_id']) . ')';
            } else {
                $whereSql[] = 'hotel_administrator.department = ?';
                $whereCase[] = $param['department_id'];
            }
        }

        if (isset($param['level'])) {
            if (is_array($param['level'])) {
                $whereSql[] = 'hotel_administrator.level in (' . implode(',', $param['level']) . ')';
            } else {
                $whereSql[] = 'hotel_administrator.level = ?';
                $whereCase[] = $param['level'];
            }
        }

        $whereSql = $whereSql ? ' where ' . implode(' and ', $whereSql) : '';
        return array(
            'sql' => $whereSql,
            'case' => $whereCase
        );
    }

    /**
     * 根据id查询hotel_staff详情
     *
     * @param
     *            int id
     * @return array
     */
    public function getStaffDetail(int $id): array
    {
        $result = array();

        if ($id) {
            $sql = "select * from hotel_staff where id=?";
            $result = $this->db->fetchAssoc($sql, array(
                $id
            ));
        }

        return $result;
    }

    /**
     * 根据staffId查询hotel_staff详情
     *
     * @param
     *            string staffId
     * @return array
     */
    public function getStaffDetailByStaffId($staffId)
    {
        $result = array();

        if ($staffId) {
            $sql = "select * from hotel_staff where staffid=?";
            $result = $this->db->fetchAssoc($sql, array(
                $staffId
            ));
        }

        return $result;
    }

    /**
     * 根据id更新hotel_staff
     *
     * @param array $info
     * @param int $id
     * @return bool|number|string
     */
    public function updateStaffById(array $info, int $id)
    {
        $result = false;

        if ($id) {
            $result = $this->db->update('hotel_staff', $info, array(
                'id' => $id
            ));
        }
        return $result;
    }

    /**
     * 单条增加hotel_staff数据
     *
     * @param
     *            array
     * @return int id
     */
    public function addStaff(array $info)
    {
        $this->db->insert('hotel_staff', $info);
        return $this->db->lastInsertId();
    }
}
