<?php
namespace VEL\Includes\ML;

class VEL_ML_Bridge
{
    private $python_path;
    private $script_path;

    public function __construct()
    {
        $this->python_path = 'python';
        $this->script_path = VEL_PLUGIN_DIR . '/ml/analyzer.py';
    }

    public function analyze_logs($data)
    {
        $json_data = json_encode($data);
        $command = sprintf(
            '%s %s analyze "%s"',
            $this->python_path,
            $this->script_path,
            addslashes($json_data)
        );

        return shell_exec($command);
    }
}