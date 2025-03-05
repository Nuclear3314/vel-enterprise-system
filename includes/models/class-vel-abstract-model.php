<?php
namespace VEL\Includes\Models;

use VEL\Includes\Interfaces\VEL_Model_Interface;

abstract class VEL_Abstract_Model implements VEL_Model_Interface
{
    protected $wpdb;
    protected $table;
    protected $primary_key = 'id';

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $this->get_table_name();
    }

    abstract protected function get_table_name();

    public function find($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE {$this->primary_key} = %d",
                $id
            )
        );
    }

    public function create(array $data)
    {
        return $this->wpdb->insert($this->table, $data);
    }

    public function update($id, array $data)
    {
        return $this->wpdb->update(
            $this->table,
            $data,
            [$this->primary_key => $id]
        );
    }

    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table,
            [$this->primary_key => $id]
        );
    }

    public function getAll(array $args = [])
    {
        $query = "SELECT * FROM {$this->table}";

        if (!empty($args['where'])) {
            $query .= " WHERE " . $args['where'];
        }

        if (!empty($args['orderby'])) {
            $query .= " ORDER BY " . $args['orderby'];
        }

        if (!empty($args['limit'])) {
            $query .= " LIMIT " . intval($args['limit']);
        }

        return $this->wpdb->get_results($query);
    }
}