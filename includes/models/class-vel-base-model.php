<?php
namespace VEL\Includes\Models;

class VEL_Base_Model
{
    protected $wpdb;
    protected $table_name;
    protected $primary_key = 'id';

    public function __construct($table_name)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . $table_name;
    }

    public function create($data)
    {
        return $this->wpdb->insert($this->table_name, $data);
    }

    public function update($id, $data)
    {
        return $this->wpdb->update(
            $this->table_name,
            $data,
            [$this->primary_key => $id]
        );
    }

    public function find($id)
    {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = %d",
            $id
        );
        return $this->wpdb->get_row($query);
    }

    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            [$this->primary_key => $id]
        );
    }
}