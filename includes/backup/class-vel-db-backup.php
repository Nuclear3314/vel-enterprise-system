<?php
namespace VEL\Includes\Backup;

class VEL_DB_Backup
{
    private $backup_dir;
    private $file_prefix = 'vel_backup_';

    public function __construct()
    {
        $upload_dir = wp_upload_dir();
        $this->backup_dir = $upload_dir['basedir'] . '/vel-backups/';

        if (!file_exists($this->backup_dir)) {
            wp_mkdir_p($this->backup_dir);
        }
    }

    public function create_backup()
    {
        $timestamp = date('Y-m-d_H-i-s');
        $filename = $this->backup_dir . $this->file_prefix . $timestamp . '.sql';

        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s %s > %s',
            DB_HOST,
            DB_USER,
            DB_PASSWORD,
            DB_NAME,
            $filename
        );

        exec($command, $output, $return_var);

        if ($return_var === 0) {
            $this->compress_backup($filename);
            return true;
        }

        return false;
    }
}