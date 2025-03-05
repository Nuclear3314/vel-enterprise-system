<?php
namespace VEL\Includes\ML;

class VEL_ML_Integration
{
    private $python_path;
    private $model_path;

    public function __construct()
    {
        $this->python_path = 'python';
        $this->model_path = WP_CONTENT_DIR . '/vel-models/';
    }

    public function predict_performance()
    {
        $command = sprintf(
            '%s %s/ml/predictor.py --action predict',
            $this->python_path,
            VEL_PLUGIN_DIR
        );

        $output = shell_exec($command);
        return json_decode($output, true);
    }
}