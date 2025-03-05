<?php
namespace VEL\Includes\Interfaces;

interface VEL_Model_Interface
{
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getAll(array $args = []);
}