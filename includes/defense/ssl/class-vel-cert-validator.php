namespace VEL\Includes\Defense\SSL;

class VEL_Cert_Validator {
    protected function setup_certificate_validation() {
        return [
            'cert_pinning' => [
                'enabled' => true,
                'pins' => $this->generate_certificate_pins(),
                'backup_pins' => $this->generate_backup_pins()
            ],
            'ocsp_stapling' => [
                'enabled' => true,
                'must_staple' => true
            ],
            'crl_checking' => [
                'enabled' => true,
                'update_interval' => '1 day'
            ]
        ];
    }
}