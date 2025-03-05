<?php
namespace VEL\Includes\API;

class VEL_User_API extends VEL_API_Base
{
    protected $base = 'users';
    private $user_model;

    public function __construct()
    {
        $this->user_model = new \VEL\Includes\Models\VEL_User_Model();
    }

    public function get_items($request)
    {
        $users = $this->user_model->getAll();
        return rest_ensure_response($users);
    }

    public function create_item($request)
    {
        $data = $request->get_params();
        $result = $this->user_model->create($data);

        if (!$result) {
            return new \WP_Error('creation_failed', '無法創建用戶');
        }

        return rest_ensure_response(['success' => true]);
    }
}