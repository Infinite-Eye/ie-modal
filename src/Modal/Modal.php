<?php

namespace InfiniteEye\Modal\Modal;

class Modal
{
    private $_cookie_expiry = 0;
    private $_cookie_value = 0;
    private $_schedule_from = -1;
    private $_schedule_to = -1;
    private $_trigger_callback = null;
    private $_content = null;
    private $_id;
    private $_plugin_args = [];
    private $_mode = 'auto';

    public function __construct($id)
    {
        $this->_id =  $id;
    }

    public function get_id()
    {
        return $this->_id;
    }

    public function content($content)
    {
        $this->_content = $content;
        return $this;
    }

    public function trigger($callback)
    {
        if ($callback === 'manual') {
            $this->_mode = 'manual';
            $this->_trigger_callback = null;
        } else {
            $this->_mode = 'auto';
            $this->_trigger_callback = is_callable($callback) ? $callback : null;
        }
        return $this;
    }

    public function get_mode()
    {
        return $this->_mode;
    }

    public function schedule_from($timestamp)
    {
        $this->_schedule_from = $timestamp;
        return $this;
    }

    public function get_schedule_from()
    {
        return $this->_schedule_from;
    }

    public function schedule_to($timestamp)
    {
        $this->_schedule_to = $timestamp;
        return $this;
    }

    public function get_schedule_to()
    {
        return $this->_schedule_to;
    }

    /**
     * Number of days before the cookie expires
     * 
     * -1 to keep cookie forever, 0 to not store cookie
     * 
     * @param integer $days 
     * @return $this 
     */
    public function cookie($days, $value = 0)
    {
        $this->_cookie_expiry = $days;
        $this->_cookie_value = $value;
        return $this;
    }

    public function get_cookie()
    {
        return $this->_cookie_expiry;
    }

    public function get_cookie_value()
    {
        return $this->_cookie_value;
    }

    public function is_visible()
    {
        if (is_callable($this->_trigger_callback)) {
            return call_user_func($this->_trigger_callback);
        }

        return true;
    }

    public function the_content()
    {
        echo $this->_content;
    }

    public function plugin_args($args)
    {
        $this->_plugin_args = array_merge($this->_plugin_args, $args);
        return $this;
    }

    public function get_plugin_args()
    {
        return $this->_plugin_args;
    }
}
