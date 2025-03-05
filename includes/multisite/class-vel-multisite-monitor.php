<?php
namespace VEL\Includes\Multisite;

class VEL_Multisite_Monitor
{
    private $sites;

    public function __construct()
    {
        $this->sites = get_sites(['public' => 1]);
    }

    public function collect_sites_data()
    {
        $data = [];
        foreach ($this->sites as $site) {
            switch_to_blog($site->blog_id);
            $data[$site->blog_id] = [
                'performance' => $this->get_site_performance(),
                'errors' => $this->get_site_errors(),
                'updates' => $this->get_site_updates()
            ];
            restore_current_blog();
        }
        return $data;
    }
}