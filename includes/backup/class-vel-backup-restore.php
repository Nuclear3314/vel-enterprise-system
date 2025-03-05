<?php
namespace VEL\Includes\Backup;

class VEL_Backup_Restore
{
    private $backup_dir;

    public function __construct()
    {
        $upload_dir = wp_upload_dir();
        $this->backup_dir = $upload_dir['basedir'] . '/vel-backups/';
    }

    public function list_backups()
    {
        $backups = [];
        $files = glob($this->backup_dir . '*.sql.gz');

        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => size_format(filesize($file)),
                'date' => date('Y-m-d H:i:s', filemtime($file))
            ];
        }

        return $backups;
    }

    public function restore_backup($filename)
    {
        $backup_file = $this->backup_dir . $filename;

        if (!file_exists($backup_file)) {
            throw new \Exception('備份檔案不存在');
        }

        // 解壓縮備份檔案
        $sql_file = str_replace('.gz', '', $backup_file);
        system("gzip -d -c $backup_file > $sql_file");

        // 執行還原
        $command = sprintf(
            'mysql --host=%s --user=%s --password=%s %s < %s',
            DB_HOST,
            DB_USER,
            DB_PASSWORD,
            DB_NAME,
            $sql_file
        );

        exec($command, $output, $return_var);
        unlink($sql_file); // 清理臨時檔案

        return $return_var === 0;
    }
}