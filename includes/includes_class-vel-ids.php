<?php
/**
 * Class VEL_IDS
 *
 * 入侵檢測與防護系統。
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class VEL_IDS {

    /**
     * 檢測並響應可疑活動。
     *
     * @param WP_REST_Request $request
     * @return void
     */
    public static function detect_intrusion( $request ) {
        $ip_address = $request->get_header( 'X-Forwarded-For' ) ?: $request->get_header( 'REMOTE_ADDR' );
        $user_agent = $request->get_header( 'User-Agent' );
        $uri = $request->get_route();
        $method = $request->get_method();

        // 紀錄可疑活動
        error_log( "可疑活動檢測: IP=$ip_address, UserAgent=$user_agent, URI=$uri, Method=$method" );

        // 簡單的入侵檢測邏輯，可以擴展為更複雜的檢測
        if ( self::is_suspicious( $request ) ) {
            self::block_request( $ip_address );
            self::notify_sites( $ip_address );
            self::launch_counterattack( $ip_address );
        }
    }

    /**
     * 判斷請求是否可疑。
     *
     * @param WP_REST_Request $request
     * @return bool
     */
    private static function is_suspicious( $request ) {
        $user_agent = $request->get_header( 'User-Agent' );

        // 例子：檢測常見的惡意 User-Agent
        $suspicious_user_agents = array( 'sqlmap', 'nikto', 'nmap' );
        foreach ( $suspicious_user_agents as $agent ) {
            if ( stripos( $user_agent, $agent ) !== false ) {
                return true;
            }
        }
        
        // 檢測其他可疑行為（可根據需求擴展）
        return false;
    }

    /**
     * 封鎖可疑請求。
     *
     * @param string $ip_address
     * @return void
     */
    private static function block_request( $ip_address ) {
        // 將可疑 IP 地址加入黑名單
        $blocked_ips = get_option( 'vel_blocked_ips', array() );
        $blocked_ips[] = $ip_address;
        update_option( 'vel_blocked_ips', $blocked_ips );

        // 紀錄封鎖行為
        error_log( "封鎖可疑請求: IP=$ip_address" );

        // 發出警報
        self::alert_admin( $ip_address );
    }

    /**
     * 向管理員發出警報。
     *
     * @param string $ip_address
     * @return void
     */
    private static function alert_admin( $ip_address ) {
        $admin_email = get_option( 'admin_email' );
        $subject = 'VEL Enterprise System 警報: 可疑請求被封鎖';
        $message = "來自 IP 地址 $ip_address 的可疑請求已被封鎖。請檢查系統日誌以獲取更多信息。";
        wp_mail( $admin_email, $subject, $message );
    }

    /**
     * 通知其他站點有可疑活動。
     *
     * @param string $ip_address
     * @return void
     */
    private static function notify_sites( $ip_address ) {
        $sites = get_option( 'vel_other_sites', array() ); // 主站點和子站點列表
        foreach ( $sites as $site ) {
            wp_remote_post( $site . '/wp-json/vel/v1/notify', array(
                'body' => json_encode( array( 'ip_address' => $ip_address ) ),
                'headers' => array( 'Content-Type' => 'application/json' ),
            ));
        }
    }

    /**
     * 發起反擊攻擊。
     *
     * @param string $ip_address
     * @return void
     */
    private static function launch_counterattack( $ip_address ) {
        // 示例反擊邏輯，可根據需求擴展
        error_log( "發起反擊攻擊: IP=$ip_address" );

        // 這裡應該是反擊的具體實現邏輯，例如 DDoS 攻擊等
        // 請注意這樣的行為可能違反法律法規，一定要在了解法律風險的前提下進行
    }
}