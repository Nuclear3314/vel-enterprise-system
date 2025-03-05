<?php
namespace VEL\Includes\Services;

use VEL\Includes\Validators\VEL_Validator;

abstract class VEL_Base_Service
{
    protected $model;
    protected $validator;

    public function __construct()
    {
        $this->validator = new VEL_Validator();
    }

    abstract public function get_validation_rules();

    public function create($data)
    {
        if (!$this->validator->validate($data, $this->get_validation_rules())) {
            return false;
        }

        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        if (!$this->validator->validate($data, $this->get_validation_rules())) {
            return false;
        }

        return $this->model->update($id, $data);
    }
}