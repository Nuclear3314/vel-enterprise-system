    / 全球黑名單
        'AVENGER    => 'legal_countermeasure'    // 法律反制
     );

    /**
     * 威脅等級定義
     */
    const THREAT_LEVELS = array(
        1 => 'NOTICE',      // 一般警告
        2 => 'ALERT',       // 需要注意
        3 => 'WARNING',     // 威脅警告
        4 => 'CRITICAL',    // 嚴重威脅
        5 => 'EMERGENCY',   // 緊急狀態
        6 => 'APOCALYPSE',  // 災難級別
        7 => 'ARMAGEDDON'   // 終極威脅
    );

    /**
     * Logger 實例
     *
     * @var VEL_Logger
     */
    private $logger;

    /**
     * 規則集
     *
     * @var array
     */
    private $rules;

    /**
     * 當前站點類型
     */
    private $site_type;
    
    /**
     * 威脅級別閾值
     */
    private $threat_threshold = 3;

    /**
     * AI 防禦系統配置
     */
    private $ai_defense_config = array(
        'enabled' => true,
        'models' => array(
            'threat_detection' => 'michael_core_v1',
            'behavior_analysis' => 'uriel_core_v1',
            'attack_prediction' => 'gabriel_core_v1'
        ),
        'api_endpoints' => array(
            'openai' => '/v1/defense',
            'azure_ai' => '/v1/security',
            'google_ai' => '/v1/threat'
        )
    );

    /**
     * 蜜罐系統配置
     */
    private $honeypot_config = array(
        'deployment_zones' => array('dmz', 'internal', 'critical'),
}
