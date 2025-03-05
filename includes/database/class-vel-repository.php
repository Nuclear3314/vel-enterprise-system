<?php
namespace VEL\Includes\Database;

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Repository
{
    protected $wpdb;
    protected $table_name;

    public function __construct($table_name)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . $table_name;
    }

    public function insert($data)
    {
        return $this->wpdb->insert(
            $this->table_name,
            $data,
            $this->get_format($data)
        );
    }

    public function update($data, $where)
    {
        return $this->wpdb->update(
            $this->table_name,
            $data,
            $where,
            $this->get_format($data),
            $this->get_format($where)
        );
    }

    public function delete($where)
    {
        return $this->wpdb->delete(
            $this->table_name,
            $where,
            $this->get_format($where)
        );
    }

    public function get($where = [])
    {
        $query = "SELECT * FROM {$this->table_name}";

        if (!empty($where)) {
            $query .= " WHERE ";
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = $this->wpdb->prepare("`$key` = %s", $value);
            }
            $query .= implode(" AND ", $conditions);
        }

        return $this->wpdb->get_results($query);
    }

    private function get_format($data)
    {
        $format = [];
        foreach ($data as $value) {
            if (is_int($value)) {
                $format[] = '%d';
            } elseif (is_float($value)) {
                $format[] = '%f';
            } else {
                $format[] = '%s';
            }
        }
        return $format;
    }
}