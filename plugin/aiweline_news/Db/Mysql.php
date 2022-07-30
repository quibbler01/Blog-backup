<?php
/**
 * 文件信息
 *
 * 工具：PhpStorm
 * 作者：邹万才
 * 网名：秋风雁飞
 * 日期：2019-8-3
 * 时间：上午 01:56
 */

namespace Db;

use mysqli;

class Mysql
{
    private static $_instance = null;//该类中的唯一一个实例
    protected $env = null;
    protected $tablepre = null;
    protected $link = null;
    private $table = '';
    private $where = '';
    private $limit = '';
    private $sql = '';
    protected $query_data = null;

    /**
     * Mdb constructor.
     * @param DeploymentConfig $config
     * @param Debug $debug
     */
    public function __construct()
    {
        $this->env = $_SERVER['conf'];

        $mysql_conf = isset($this->env['db']['mysql']['master']) ? $this->env['db']['mysql']['master'] : die('DB conf lost.');
        $conn = new mysqli($mysql_conf['host'], $mysql_conf['user'], $mysql_conf['password'], $mysql_conf['name']);
        /*if (!$conn->set_charset('utf8')) {
            printf("Error loading character set utf8: %s\n", $conn->error);
            exit();
        }*/
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            $this->tablepre = $mysql_conf['tablepre'];
            $conn->query('set names '.$mysql_conf['charset']);
            $this->link = $conn;
        }
        unset($_SERVER['conf']);
    }

    /**
     *禁止通过复制的方式实例化该类
     */
    private function __clone()
    {
    }

    /**
     * 检查实例，如果存在继续使用，不存在重新实例化
     * @return Mdb|null
     */
    /*public static function Db()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }*/

    /**
     * 连接信息
     * @return mixed|null
     */
    public function status()
    {
        return $this->env;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function query($sql)
    {
        $this->sql = $sql;
        $result = $this->link->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: " . $this->link->error, E_USER_ERROR);
        $data = is_bool($result) ? $result : $result->fetch_all(MYSQLI_ASSOC);
        $this->query_data = $data;
        return $this;
    }

    public function table($table)
    {
        if (!strpos($this->tablepre, $table)) {
            $table = $this->tablepre . $table;
        }
        $this->table = " `{$table}` ";
        return $this;
    }

    /********************** helper start ***********************
     * @param $k
     * @param $item
     * @param $data
     * @return string
     * @throws \Exception
     */
    protected function parseInStr($k, $item, $data)
    {
        $not_in_str = '  ';
        if (is_string($item)) {
            $item__fields_arr = explode(',', $item);
            foreach ($item__fields_arr as $item_k => $item_fields) {
                $not_in_str .= "'" . $item_fields . '",';
            }
        } else if (is_array($item)) {
            foreach ($item as $item_k => $item_fields) {
                $not_in_str .= "'" . $item_fields . '",';
            }
        } else {
            $this->addError($data);
        }
        return ' ' . $k . ' (' . rtrim($not_in_str, ',') . ')';
    }
    /********************** helper end ************************/

    /**
     * @param string $where
     * @return $this
     * @throws \Exception
     */
    public function where($where = '')
    {
        $where_str = ' WHERE ';
        if (is_array($where)) {
            $where_str_tmp = '';
            foreach ($where as $key => $value) {
                $tmp_str = ' `' . $key . '`';
                if (is_array($value)) {
                    switch (count($value)) {
                        case 1:
                            if (isset($value[0])) {
                                $tmp_str .= '=' . (is_string($value[0]) ? "'" . $value[0] . "'" : $value[0]);
                            } else {
                                foreach ($value as $k => $item) {
                                    $tmp = '';
                                    switch (strtolower($k)) {
                                        case 'not in':
                                            $tmp .= $this->parseInStr($k, $item, $where);
                                            break;
                                        case 'in':
                                            $tmp .= $this->parseInStr($k, $item, $where);
                                            break;
                                        default:
                                            if ($k == 0) {
                                                $tmp .= ' ' . $item;
                                            } else {
                                                $tmp .= ' ' . (is_string($item) ? "'" . $item . "'"  : $item);
                                            }
                                            break;
                                    }
                                    $tmp_str .= $tmp;
                                }
                            }
                            break;
                        case 2:
                            foreach ($value as $k => $item) {
                                $tmp = ' ';
                                switch (strtolower($k)) {
                                    case 'not in':
                                        $tmp .= $this->parseInStr($k, $item, $where);
                                        break;
                                    case 'in':
                                        $tmp .= $this->parseInStr($k, $item, $where);
                                        break;
                                    default:
                                        if ($k == 0) {
                                            $tmp .= ' ' . $item;
                                        } else {
                                            $tmp .= ' ' . (is_string($item) ? "'"  . $item . "'"  : $item);
                                        }
                                        break;
                                }
                                $tmp_str .= $tmp;
                            }
                            break;
                        case 3:
                            switch (strtolower($value[1])) {
                                case 'or':
                                    $tmp_str .= '=' . (is_string($value[0]) ? "'" . $value[0] . "'" : $value[0]) . ' ' . $value[1] . ' `' . $key . '`' . '=' . (is_string($value[2]) ? "'" . $value[2] . "'" : $value[2]);
                                    break;
                                default:
                                    $this->addError($where);
                            }

                    }

                } else {
                    $tmp_str .= '=' . (is_string($value) ? "'" . $value . "'" : $value);
                }
                $where_str_tmp .= $tmp_str . ' AND ';
            }
            $where_str .= rtrim($where_str_tmp, 'AND ');
        } else {
            $where_str .= $where;
        }
        $this->where = $where_str;
        return $this;
    }

    public function limit($start, $end = '')
    {
        if ($end) {
            $this->limit = " LIMIT $start,$end";
        } else {
            $this->limit = " LIMIT $start";
        }
        return $this;
    }

    public function delete()
    {
        $this->sql = "DELETE FROM {$this->table} {$this->where}";
        $this->query($this->sql);
        return $this;
    }

    public function fetch($flag = false)
    {
        if ($flag) {
            $this->buildSql();
        }
        return $this->query_data;
    }

    public function select($fields = '*')
    {
        $this->sql = "SELECT $fields FROM {$this->table} {$this->where} {$this->limit}";
        $this->query($this->sql);
        return $this;
    }

    public function find()
    {
        $this->sql = "SELECT * FROM {$this->table} {$this->where} limit 1";
        $result = $this->link->query($this->sql) or trigger_error("Query Failed! SQL: {$this->sql} - Error: " . $this->link->error, E_USER_ERROR);;
        return is_bool($result) ? $result : $result->fetch_array(MYSQLI_ASSOC);
    }

    public function insert($data)
    {
        if (is_array($data)) {
            $keys = '(';
            $values = '(';
            foreach ($data as $key => $value) {
                if(is_array($value)){
                    foreach ($value as $fields => $v) {
                        if($key==0) $keys .= '`' . $fields . '`,';
                        $values .= (is_string($v) ? "'$v'" : $v) . ',';
                    }
                }else{
                    $keys .= '`' . $key . '`,';
                    $values .= (is_string($value) ? "'$value'" : $value) . ',';
                }
            }
            $keys = rtrim($keys, ',');
            $keys .= ') ';
            $values = rtrim($values, ',');
            $values .= ') ';
            $data = $keys . ' VALUES ' . $values;

        }
        $this->sql = "INSERT INTO {$this->table} {$data}";
        $this->query($this->sql);
        return $this;
    }

    public function update($data)
    {
        if (is_array($data)) {
            $update_str = '';
            foreach ($data as $key => $value) {
                $update_str .= "`$key`=" . (is_string($value) ? "'$value'," : "$value,");
            }
            $update_str = rtrim($update_str, ',');
            $data = $update_str;
        }
        $this->sql = "UPDATE {$this->table} SET {$data} {$this->where}";
        $this->query($this->sql);
        return $this;
    }

    public function begin_transaction()
    {
//        $this->link->begin_transaction(MYSQLI_TRANS_START_READ_ONLY);
        $this->link->autocommit(false);
    }

    public function rollBack()
    {
        $this->link->rollback();
    }

    public function commit()
    {
        $this->link->commit();
        $this->link->autocommit(true);
    }

    public function close()
    {
        mysqli_close($this->link);
    }

    public function buildSql($flag = true)
    {
        if ($flag) {
            echo '<br><i style="color: red">' . $this->sql . '</i><br><br>';
        }
        return $this->sql;
    }

    private function addError($data)
    {
        throw new \Exception('SQL:parse error!in where method the data is' . is_string($data) ? $data : 'Array:' . implode(',', $data));
    }
}