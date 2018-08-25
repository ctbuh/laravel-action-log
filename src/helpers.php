<?php

use ctbuh\ActionLog\ActionEvent;

// TODO: ae()
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
