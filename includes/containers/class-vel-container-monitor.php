<?php
namespace VEL\Includes\Containers;

class VEL_Container_Monitor
{
    private $docker_socket = 'tcp://localhost:2375';

    public function get_container_stats()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->docker_socket . '/containers/json');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public function monitor_container($container_id)
    {
        return [
            'stats' => $this->get_container_metrics($container_id),
            'logs' => $this->get_container_logs($container_id)
        ];
    }
}