<?php

use ctbuh\ActionLog\ActionEvent;

if (!function_exists('action_event')) {

    function action_event($subject, $action_name)
    {
        $event = app(ActionEvent::class);
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
        // otherwise you get json_decode() expects parameter 1 to be string, array given
        // $data = json_decode($json_str, true);
        $data = is_array($json_str) ? $json_str : json_decode($json_str, true);

        if (!is_array($data) || !array_key_exists($key, $data)) {
            return $default;
        }

        // might be another array here too
        return $data[$key];
    }
}
