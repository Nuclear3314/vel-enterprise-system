<?php
namespace VEL\Includes\Triggers;

class VEL_Task_Trigger
{
    private $scheduler;
    private $logger;

    public function __construct()
    {
        $this->scheduler = new \VEL\Includes\Scheduler\VEL_Task_Scheduler();
        $this->logger = new \VEL\Includes\Logging\VEL_Task_Logger();
    }

    public function register_triggers()
    {
        add_action('vel_trigger_task', [$this, 'trigger_task'], 10, 2);
        add_action('wp_ajax_vel_manual_trigger', [$this, 'handle_manual_trigger']);
    }

    public function trigger_task($task_id, $parameters = [])
    {
        try {
            $this->logger->log($task_id, "觸發任務開始執行");
            do_action('vel_before_task_execution', $task_id);

            // 執行任務邏輯
            $result = $this->execute_task($task_id, $parameters);

            do_action('vel_after_task_execution', $task_id, $result);
            $this->logger->log($task_id, "任務執行完成");

            return $result;
        } catch (\Exception $e) {
            $this->logger->log($task_id, "任務執行失敗: " . $e->getMessage(), 'error');
            return false;
        }
    }
}