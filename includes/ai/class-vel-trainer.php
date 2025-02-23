<?php
/**
 * AI 訓練器類
 *
 * @package VEL_Enterprise_System
 * @subpackage AI
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 */

namespace VEL\AI;

use VEL\Core\Logger;

if (!defined('ABSPATH')) {
    exit;
}

class Trainer {
    /**
     * 訓練一個週期
     *
     * @param int $epoch
     * @return array
     */
    private function train_epoch($epoch) {
        $total_training_loss = 0;
        $total_validation_loss = 0;

        // 批次訓練
        $batches = array_chunk($this->training_set, $this->config['batch_size']);
        foreach ($batches as $batch) {
            $batch_loss = $this->train_batch($batch);
            $total_training_loss += $batch_loss;
        }

        // 驗證
        if (!empty($this->validation_set)) {
            $validation_loss = $this->validate_model($this->validation_set);
            $total_validation_loss = $validation_loss;
        }

        $metrics = array(
            'epoch' => $epoch,
            'training_loss' => $total_training_loss / count($batches),
            'validation_loss' => $total_validation_loss / count($this->validation_set)
        );

        Logger::log('trainer', sprintf(
            'Epoch %d: training_loss=%.4f, validation_loss=%.4f',
            $epoch,
            $metrics['training_loss'],
            $metrics['validation_loss']
        ));

        return $metrics;
    }

    /**
     * 訓練批次
     *
     * @param array $batch
     * @return float
     */
    private function train_batch($batch) {
        $batch_loss = 0;
        $predictions = array();
        $actual_values = array();

        foreach ($batch as $sample) {
            // 前向傳播
            $prediction = $this->forward_pass($sample['features']);
            $predictions[] = $prediction;
            $actual_values[] = $sample['target'];

            // 計算損失
            $loss = $this->calculate_loss($prediction, $sample['target']);
            $batch_loss += $loss;
        }

        // 反向傳播和權重更新
        $this->backward_pass($predictions, $actual_values);

        return $batch_loss / count($batch);
    }

    /**
     * 前向傳播
     *
     * @param array $features
     * @return float
     */
    private function forward_pass($features) {
        // 根據模型類型進行不同的計算
        switch ($this->model->config['type']) {
            case 'linear':
                return $this->linear_forward($features);
            case 'logistic':
                return $this->logistic_forward($features);
            case 'neural_network':
                return $this->neural_forward($features);
            default:
                return 0;
        }
    }

    /**
     * 線性模型前向傳播
     *
     * @param array $features
     * @return float
     */
    private function linear_forward($features) {
        $weights = $this->model->config['parameters']['weights'];
        $bias = $this->model->config['parameters']['bias'];
        
        $output = $bias;
        foreach ($features as $i => $value) {
            $output += $value * $weights[$i];
        }
        
        return $output;
    }

    /**
     * 邏輯回歸前向傳播
     *
     * @param array $features
     * @return float
     */
    private function logistic_forward($features) {
        $linear_output = $this->linear_forward($features);
        return 1 / (1 + exp(-$linear_output)); // sigmoid 函數
    }

    /**
     * 神經網絡前向傳播
     *
     * @param array $features
     * @return float
     */
    private function neural_forward($features) {
        $layers = $this->model->config['parameters']['layers'];
        $current_input = $features;

        foreach ($layers as $layer) {
            $current_input = $this->layer_forward($current_input, $layer);
        }

        return $current_input;
    }

    /**
     * 神經網絡層前向傳播
     *
     * @param array $input
     * @param array $layer
     * @return array
     */
    private function layer_forward($input, $layer) {
        $weights = $layer['weights'];
        $biases = $layer['biases'];
        $activation = $layer['activation'];

        $output = array();
        foreach ($weights as $i => $neuron_weights) {
            $sum = $biases[$i];
            foreach ($neuron_weights as $j => $weight) {
                $sum += $input[$j] * $weight;
            }
            $output[$i] = $this->activate($sum, $activation);
        }

        return $output;
    }

    /**
     * 激活函數
     *
     * @param float  $x
     * @param string $function
     * @return float
     */
    private function activate($x, $function) {
        switch ($function) {
            case 'relu':
                return max(0, $x);
            case 'sigmoid':
                return 1 / (1 + exp(-$x));
            case 'tanh':
                return tanh($x);
            default:
                return $x;
        }
    }

    /**
     * 計算損失
     *
     * @param float $prediction
     * @param float $target
     * @return float
     */
    private function calculate_loss($prediction, $target) {
        switch ($this->model->config['hyperparameters']['loss_function']) {
            case 'mse':
                return pow($prediction - $target, 2);
            case 'mae':
                return abs($prediction - $target);
            case 'binary_crossentropy':
                return -($target * log($prediction) + (1 - $target) * log(1 - $prediction));
            default:
                return pow($prediction - $target, 2);
        }
    }

    /**
     * 反向傳播
     *
     * @param array $predictions
     * @param array $targets
     */
    private function backward_pass($predictions, $targets) {
        $learning_rate = $this->model->config['hyperparameters']['learning_rate'];
        
        // 計算梯度
        $gradients = $this->calculate_gradients($predictions, $targets);
        
        // 更新權重
        $this->update_weights($gradients, $learning_rate);
    }

    /**
     * 計算梯度
     *
     * @param array $predictions
     * @param array $targets
     * @return array
     */
    private function calculate_gradients($predictions, $targets) {
        $gradients = array();
        
        // 根據模型類型計算不同的梯度
        switch ($this->model->config['type']) {
            case 'linear':
                $gradients = $this->linear_gradients($predictions, $targets);
                break;
            case 'logistic':
                $gradients = $this->logistic_gradients($predictions, $targets);
                break;
            case 'neural_network':
                $gradients = $this->neural_gradients($predictions, $targets);
                break;
        }
        
        return $gradients;
    }

    /**
     * 更新權重
     *
     * @param array $gradients
     * @param float $learning_rate
     */
    private function update_weights($gradients, $learning_rate) {
        $parameters = &$this->model->config['parameters'];
        
        foreach ($gradients as $param_name => $gradient) {
            if (is_array($gradient)) {
                foreach ($gradient as $i => $grad) {
                    $parameters[$param_name][$i] -= $learning_rate * $grad;
                }
            } else {
                $parameters[$param_name] -= $learning_rate * $gradient;
            }
        }
    }

    /**
     * 驗證模型
     *
     * @param array $validation_data
     * @return float
     */
    private function validate_model($validation_data) {
        $total_loss = 0;

        foreach ($validation_data as $sample) {
            $prediction = $this->forward_pass($sample['features']);
            $loss = $this->calculate_loss($prediction, $sample['target']);
            $total_loss += $loss;
        }

        return $total_loss / count($validation_data);
    }

    /**
     * 評估模型
     *
     * @return array
     */
    private function evaluate_model() {
        $metrics = array(
            'mse' => 0,
            'mae' => 0,
            'r2' => 0
        );

        $predictions = array();
        $actuals = array();

        foreach ($this->validation_set as $sample) {
            $prediction = $this->forward_pass($sample['features']);
            $predictions[] = $prediction;
            $actuals[] = $sample['target'];
        }

        // 計算評估指標
        $metrics['mse'] = $this->calculate_mse($predictions, $actuals);
        $metrics['mae'] = $this->calculate_mae($predictions, $actuals);
        $metrics['r2'] = $this->calculate_r2($predictions, $actuals);

        $this->model->update_metrics($metrics);

        return $metrics;
    }

    /**
     * 計算均方誤差
     *
     * @param array $predictions
     * @param array $actuals
     * @return float
     */
    private function calculate_mse($predictions, $actuals) {
        $sum_squared_error = 0;
        $n = count($predictions);

        for ($i = 0; $i < $n; $i++) {
            $sum_squared_error += pow($predictions[$i] - $actuals[$i], 2);
        }

        return $sum_squared_error / $n;
    }

    /**
     * 計算平均絕對誤差
     *
     * @param array $predictions
     * @param array $actuals
     * @return float
     */
    private function calculate_mae($predictions, $actuals) {
        $sum_absolute_error = 0;
        $n = count($predictions);

        for ($i = 0; $i < $n; $i++) {
            $sum_absolute_error += abs($predictions[$i] - $actuals[$i]);
        }

        return $sum_absolute_error / $n;
    }

    /**
     * 計算 R² 決定係數
     *
     * @param array $predictions
     * @param array $actuals
     * @return float
     */
    private function calculate_r2($predictions, $actuals) {
        $mean_actual = array_sum($actuals) / count($actuals);
        $total_variance = 0;
        $residual_variance = 0;

        for ($i = 0; $i < count($predictions); $i++) {
            $total_variance += pow($actuals[$i] - $mean_actual, 2);
            $residual_variance += pow($actuals[$i] - $predictions[$i], 2);
        }

        return 1 - ($residual_variance / $total_variance);
    }
}