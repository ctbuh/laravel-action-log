<?php

use ctbuh\ActionLog\ActionEvent;

if (!function_exists('action_event')) {

    function action_event($subject, $action_name)
    {
        $event = new ActionEvent();
        $event->setSubject($subject);

        // hacky
        $args = func_get_args();
        array_shift($args);

        return call_user_func_array(array($event, 'logAction'), $args);
    }
}

if (!function_exists('json_get')) {

    function json_get($json_str, $key, $default = null)
    {
        $data = json_decode($json_str, true);

        if (!is_array($data) || !array_key_exists($key, $data)) {
            return $default;
        }

        // might be another array here too
        return $data[$key];
    }
}