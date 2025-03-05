<?php
namespace VEL\Includes\Tasks;

class VEL_Retry_Manager
{
    private $max_attempts = 3;
    private $retry_delays = [30, 60, 120]; // 延遲秒數

    public function should_retry($task)
    {
        return $task->attempts < $this->max_attempts;
    }

    public function schedule_retry($task_id)
    {
        global $wpdb;

        $task = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}vel_tasks WHERE id = %d",
            $task_id
        ));

        if ($this->should_retry($task)) {
            $delay = $this->retry_delays[$task->attempts] ?? end($this->retry_delays);
            $next_attempt = date('Y-m-d H:i:s', time() + $delay);

            $wpdb->update(
                $wpdb->prefix . 'vel_tasks',
                [
                    'attempts' => $task->attempts + 1,
                    'next_attempt_at' => $next_attempt,
                    'status' => 'pending'
                ],
                ['id' => $task_id]
            );
        } else {
            $this->mark_as_failed($task_id);
        }
    }
}