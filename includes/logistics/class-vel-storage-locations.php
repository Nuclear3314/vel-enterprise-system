namespace VEL\Includes\Logistics;

class VEL_Storage_Locations {
    public function register_storage_post_type() {
        register_post_type('vel_storage_location', [
            'labels' => [
                'name' => '寄放點管理',
                'singular_name' => '寄放點'
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
            'show_in_rest' => true
        ]);
    }
}