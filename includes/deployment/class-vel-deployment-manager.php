<?php
namespace VEL\Includes\Deployment;

class VEL_Deployment_Manager
{
    private $pipeline;
    private $test_framework;

    public function __construct()
    {
        $this->pipeline = new \VEL\Includes\Pipeline\VEL_Pipeline();
        $this->test_framework = new \VEL\Includes\Testing\VEL_Test_Framework();
    }

    public function deploy_new_version(string $version): array
    {
        try {
            // 執行部署前測試
            $test_results = $this->test_framework->run_deployment_tests($version);

            if ($test_results['success']) {
                return $this->pipeline->deploy($version);
            }

            return [
                'status' => 'error',
                'message' => '部署前測試失敗',
                'details' => $test_results
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}