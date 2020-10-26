<?php
/**
 * User: isliang
 * Date: 2019-09-15
 * Time: 16:39
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Utils;

class SqlBuilder
{
    public static function buildColumn($column)
    {
        $column = trim($column);
        if (false !== strpos($column, ' ')) {
            $arr = explode(' ', $column);
            if ('as' == strtolower($arr[1])) {
                return self::buildColumn($arr[0]).' as '.self::buildColumn($arr[2]);
            } else {
                return $column;
            }
        } elseif (false !== strpos($column, '(') || false !== strpos($column, '`')) {
            return $column;
        } else {
            return '`'.$column.'`';
        }
    }

    public static function buildFields($fields)
    {
        if (empty($fields)) {
            $sql = '*';
        } elseif (is_array($fields)) {
            $sql = [];
            foreach ($fields as $field) {
                $sql[] = self::buildColumn($field);
            }
            $sql = implode(',', $sql);
        } else {
            $fields = explode(',', $fields);
            $sql = [];
            foreach ($fields as $field) {
                $sql[] = self::buildColumn($field);
            }
            $sql = implode(',', $sql);
        }

        return $sql;
    }

    /**
     * @param $fields
     *
     * @return bool|string
     *                     title = ?, user_id = ?
     */
    public static function buildWhereFields($fields)
    {
        $sql = '';
        if (is_array($fields)) {
            $arr = [];
            foreach ($fields as $field) {
                $arr[] = self::buildColumn($field);
            }
            $sql = implode(' = ?, ', $arr).' = ?';
        }

        return $sql;
    }

    public static function buildUpdateFields($fields, $increase = true)
    {
        $sql = '';
        $op = $increase ? '+' : '-';
        if (is_array($fields)) {
            $sql = [];
            foreach ($fields as $field => $count) {
                $field = self::buildColumn($field);
                $sql[] = $field.' = '.$field.$op.$count;
            }
            $sql = implode(',', $sql);
        }

        return $sql;
    }

    /**
     * @param $where
     *
     * @return array
     *               $where = [
     *               '@sql' => ['id>?', [1]],
     *               'user_id' => '1',
     *               'type' => [1,2,3,4],
     *               ];
     *
     * WHERE user_id = ? AND type IN (?,?,?,?) AND id > ?
     */
    public static function buildWhere($where)
    {
        if (empty($where) || !is_array($where)) {
            return [];
        }
        $where_sql = [];
        $values = [];
        foreach ($where as $key => $value) {
            if ('@sql' == $key) {
                $where_sql[] = $value[0];
                $values = array_merge($values, $value[1]);
            } elseif (is_array($value)) {
                $key = self::buildColumn($key);
                $where_sql[] = "{$key} IN (".implode(',', array_fill(0, count($value), '?')).')';
                $values = array_merge($values, $value);
            } else {
                $key = self::buildColumn($key);
                $where_sql[] = "{$key} = ?";
                $values[] = $value;
            }
        }

        return [
            'sql'    => ' WHERE '.implode(' AND ', $where_sql),
            'values' => $values,
        ];
    }

    /**
     * @param $order
     *
     * @return string
     *                $order = [
     *                'id' => 'DESC',
     *                'stime' => 'ASC',
     *                ];
     *
     * ORDER BY id desc, stime asc
     */
    public static function buildOrder($order)
    {
        if (empty($order)) {
            return '';
        }
        $order_sql = [];
        foreach ($order as $key => $value) {
            $key = self::buildColumn($key);
            $order_sql[] = "{$key} {$value}";
        }

        return ' ORDER BY '.implode(', ', $order_sql);
    }
}
