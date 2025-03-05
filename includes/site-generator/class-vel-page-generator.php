namespace VEL\Includes\SiteGenerator;

class VEL_Page_Generator {
    private $permissions_manager;
    private $page_encryptor;
    
    public function __construct() {
        $this->permissions_manager = new VEL_Permissions_Manager();
        $this->page_encryptor = new VEL_Page_Encryptor();
    }

    public function generate_page($config) {
        // 檢查主站點管理員權限
        if (!$this->verify_main_admin()) {
            throw new \Exception('需要主站點管理員授權');
        }

        // 生成加密的頁面內容
        $encrypted_content = $this->page_encryptor->encrypt_page_content([
            'content' => $config['content'],
            'permissions' => $config['permissions'],
            'site_id' => $config['site_id']
        ]);

        return $encrypted_content;
    }
}
