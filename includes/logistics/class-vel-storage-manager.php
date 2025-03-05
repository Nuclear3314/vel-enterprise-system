namespace VEL\Includes\Logistics;

class VEL_Storage_Manager {
    public function register_storage_location($data) {
        // 驗證必要資料
        $required_fields = ['name', 'address', 'capacity', 'hours'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                throw new \Exception("缺少必要欄位: $field");
            }
        }

        // 儲存位置資訊
        $location_id = wp_insert_post([
            'post_type' => 'storage_location',
            'post_title' => $data['name'],
            'post_status' => 'publish',
            'meta_input' => [
                'address' => $data['address'],
                'capacity' => $data['capacity'],
                'operating_hours' => $data['hours'],
                'current_usage' => 0,
                'status' => 'active'
            ]
        ]);

        if (is_wp_error($location_id)) {
            throw new \Exception('無法註冊儲存位置');
        }

        return $location_id;
    }
}