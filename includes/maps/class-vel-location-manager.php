namespace VEL\Includes\Maps;

class VEL_Location_Manager {
    public function get_public_locations() {
        return $this->query_locations([
            'post_type' => 'vel_storage_location',
            'meta_query' => [
                [
                    'key' => 'location_visibility',
                    'value' => 'public',
                    'compare' => '='
                ]
            ]
        ]);
    }
}