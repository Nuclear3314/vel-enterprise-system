    /**
     * 搜索日誌
     *
     * @param array $criteria
     * @return array
     */
    public static function search_logs($criteria = array()) {
        if (!self::$log_dir) {
            self::init();
        }

        $logs = array();
        $files = glob(self::$log_dir . '*.log');

        foreach ($files as $file) {
            if (isset($criteria['date']) && strpos(basename($file), $criteria['date']) !== 0) {
                continue;
            }

            $content = file_get_contents($file);
            $lines = explode(PHP_EOL, $content);

            foreach ($lines as $line) {
                if (empty($line)) {
                    continue;
                }

                // 檢查搜索條件
                $match = true;
                foreach ($criteria as $key => $value) {
                    if ($key === 'date') {
                        continue;
                    }
                    if (stripos($line, $value) === false) {
                        $match = false;
                        break;
                    }
                }

                if ($match) {
                    $logs[] = $line;
                }
            }
        }

        return $logs;
    }

    /**
     * 生成日誌統計報告
     *
     * @param string $date
     * @return array
     */
    public static function generate_statistics($date = '') {
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $stats = array(
            'total' => 0,
            'by_level' => array(),
            'by_type' => array(),
            'by_hour' => array_fill(0, 24, 0),
            'top_users' => array(),
            'top_ips' => array()
        );

        $logs = self::get_logs('', $date);

        foreach ($logs as $log) {
            $stats['total']++;

            // 解析日誌條目
            if (preg_match('/\[(.*?)\] \[(.*?)\] \[(.*?)\] \[User:(.*?)\] \[IP:(.*?)\]/', $log, $matches)) {
                $timestamp = strtotime($matches[1]);
                $level = $matches[2];
                $type = $matches[3];
                $user_id = $matches[4];
                $ip = $matches[5];

                // 按級別統計
                if (!isset($stats['by_level'][$level])) {
                    $stats['by_level'][$level] = 0;
                }
                $stats['by_level'][$level]++;

                // 按類型統計
                if (!isset($stats['by_type'][$type])) {
                    $stats['by_type'][$type] = 0;
                }
                $stats['by_type'][$type]++;

                // 按小時統計
                $hour = (int) date('G', $timestamp);
                $stats['by_hour'][$hour]++;

                // 按用戶統計
                if (!isset($stats['top_users'][$user_id])) {
                    $stats['top_users'][$user_id] = 0;
                }
                $stats['top_users'][$user_id]++;

                // 按 IP 統計
                if (!isset($stats['top_ips'][$ip])) {
                    $stats['top_ips'][$ip] = 0;
                }
                $stats['top_ips'][$ip]++;
            }
        }

        // 排序統計結果
        arsort($stats['by_level']);
        arsort($stats['by_type']);
        arsort($stats['top_users']);
        arsort($stats['top_ips']);

        // 限制結果數量
        $stats['top_users'] = array_slice($stats['top_users'], 0, 10, true);
        $stats['top_ips'] = array_slice($stats['top_ips'], 0, 10, true);

        return $stats;
    }

    /**
     * 檢測異常模式
     *
     * @param string $date
     * @return array
     */
    public static function detect_anomalies($date = '') {
        $stats = self::generate_statistics($date);
        $anomalies = array();

        // 檢測錯誤率異常
        $error_rate = isset($stats['by_level']['ERROR']) ? 
            $stats['by_level']['ERROR'] / $stats['total'] : 0;

        if ($error_rate > 0.1) { // 如果錯誤率超過 10%
            $anomalies['high_error_rate'] = array(
                'type' => 'error_rate',
                'value' => $error_rate,
                'threshold' => 0.1
            );
        }

        // 檢測用戶活動異常
        foreach ($stats['top_users'] as $user_id => $count) {
            if ($count > $stats['total'] * 0.5) { // 如果單個用戶佔總活動的 50%
                $anomalies['user_' . $user_id] = array(
                    'type' => 'user_activity',
                    'user_id' => $user_id,
                    'count' => $count,
                    'total' => $stats['total']
                );
            }
        }

        // 檢測 IP 活動異常
        foreach ($stats['top_ips'] as $ip => $count) {
            if ($count > $stats['total'] * 0.3) { // 如果單個 IP 佔總活動的 30%
                $anomalies['ip_' . $ip] = array(
                    'type' => 'ip_activity',
                    'ip' => $ip,
                    'count' => $count,
                    'total' => $stats['total']
                );
            }
        }

        return $anomalies;
    }
}