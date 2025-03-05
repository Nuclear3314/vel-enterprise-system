<?php
namespace VEL\Tests\Unit;

use PHPUnit\Framework\TestCase;
use VEL\Includes\Database\VEL_DB_Manager;

class DatabaseTest extends TestCase
{
    private $db_manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db_manager = new VEL_DB_Manager();
    }

    public function test_install_creates_tables()
    {
        global $wpdb;

        // 執行安裝
        $this->db_manager->install();

        // 檢查表格是否存在
        $table_name = $wpdb->prefix . 'vel_logs';
        $table_exists = $wpdb->get_var(
            $wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $table_name
            )
        );

        $this->assertEquals($table_name, $table_exists);
    }
}