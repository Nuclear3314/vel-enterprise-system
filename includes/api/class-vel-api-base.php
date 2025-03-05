<?php
namespace VEL\Includes\API;

abstract class VEL_API_Base
{
    protected $namespace = 'vel/v1';
    protected $base = '';

    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->base,
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_items'],
                    'permission_callback' => [$this, 'get_items_permissions_check']
                ],
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'create_item'],
                    'permission_callback' => [$this, 'create_item_permissions_check']
                ]
            ]
        );
    }

    abstract public function get_items($request);
    abstract public function create_item($request);
}