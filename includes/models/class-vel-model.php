<?php
namespace VEL\Includes\Models;

abstract class VEL_Model
{
    protected $wpdb;
    protected $table;
    protected $primary_key = 'id';

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function find($id)
    {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primary_key} = %d",
            $id
        );
        return $this->wpdb->get_row($query);
    }

    public function create($data)
    {
        return $this->wpdb->insert($this->table, $data);
    }

    public function update($id, $data)
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
}