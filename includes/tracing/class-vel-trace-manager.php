<?php
namespace VEL\Includes\Tracing;

class VEL_Trace_Manager
{
    private $trace_id;
    private $spans = [];

    public function __construct()
    {
        $this->trace_id = $this->generate_trace_id();
    }

    public function start_span($name, $attributes = [])
    {
        $span_id = uniqid('span_', true);
        $this->spans[$span_id] = [
            'name' => $name,
            'start_time' => microtime(true),
            'attributes' => $attributes
        ];
        return $span_id;
    }

    public function end_span($span_id)
    {
        if (isset($this->spans[$span_id])) {
            $this->spans[$span_id]['end_time'] = microtime(true);
            $this->spans[$span_id]['duration'] =
                $this->spans[$span_id]['end_time'] -
                $this->spans[$span_id]['start_time'];
        }
    }
}