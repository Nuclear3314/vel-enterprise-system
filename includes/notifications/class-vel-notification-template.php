<?php
namespace VEL\Includes\Notifications;

class VEL_Notification_Template
{
    private $templates = [];

    public function __construct()
    {
        $this->load_default_templates();
    }

    public function render($template_name, $data = [])
    {
        if (!isset($this->templates[$template_name])) {
            throw new \Exception("Template not found: $template_name");
        }

        $template = $this->templates[$template_name];
        return $this->parse_template($template, $data);
    }

    private function parse_template($template, $data)
    {
        foreach ($data as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }
        return $template;
    }

    private function load_default_templates()
    {
        $this->templates = [
            'system_alert' => "[系統警告] {message}\n時間: {time}\n嚴重程度: {severity}",
            'performance_warning' => "[效能警告] {message}\n目前負載: {load}\n記憶體使用: {memory}",
            'security_alert' => "[安全警告] {message}\nIP: {ip}\n時間: {time}"
        ];
    }
}