<?php
namespace VEL\Includes\Prediction;

class VEL_Predictor
{
    private $model_path;
    private $data_collector;

    public function __construct()
    {
        $this->model_path = VEL_PLUGIN_DIR . '/ml/models/';
        $this->data_collector = new \VEL\Includes\Data\VEL_Data_Collector();
    }

    public function predict_trends($timeframe = '24h')
    {
        $historical_data = $this->data_collector->get_historical_data($timeframe);
        return $this->run_prediction($historical_data);
    }
}