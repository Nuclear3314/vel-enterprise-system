<?php
namespace VEL\Includes\Scheduler;

class VEL_Dependency_Manager
{
    private $dependencies = [];

    public function add_dependency($task_id, $depends_on)
    {
        if (!isset($this->dependencies[$task_id])) {
            $this->dependencies[$task_id] = [];
        }
        $this->dependencies[$task_id][] = $depends_on;
    }

    public function can_run($task_id)
    {
        if (!isset($this->dependencies[$task_id])) {
            return true;
        }

        foreach ($this->dependencies[$task_id] as $dependency) {
            if (!$this->is_completed($dependency)) {
                return false;
            }
        }
        return true;
    }

    private function is_completed($task_id)
    {
        global $wpdb;

        return (bool) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}vel_tasks 
            WHERE id = %d AND status = 'completed'",
            $task_id
        ));
    }
}