<?php
class Response
{
    
    public $body;
    
    public $success;
    
    function __construct($success = false, $body = null)
    {
        $this->success = $success;
        $this->body = $body;
    }
    
    /**
     *
     * @param Baseobj $obj
     */
    static function baseobj($obj)
    {
        $o = new stdClass();
        $props = $obj->bddvars;
        foreach ($props as $p) {
            if (property_exists($obj, $p))
                $o->{$p} = $obj->{$p};
        }
        return $o;
    }
    
    function serialize()
    {
        $body = $this->body;
        if ($body instanceof Baseobj) {
            $body = self::baseobj($body);
        }
        $obj = new stdClass();
        $obj->success = $this->success;
        $obj->body = $body;
        $obj->sessionId = session_id();
        return json_encode($obj); // , JSON_PRETTY_PRINT);
    }
}
