<?php

namespace ctbuh\ActionLog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

/**
 * Class ActionEvent
 * @property int user_id
 * @property int subject_id
 * @property Model subject
 * @property string action_name
 * @property string meta_key
 * @property string meta_value
 * @property string extra
 * @package ctbuh\ActionLog
 */
class ActionEvent extends Model
{
    protected $guarded = array();
    protected $fillable = array('action_name');

    // only available since 5.2
    protected $casts = array();

    public function setUpdatedAt($value)
    {
        // do nothing - we only want created_at
    }

    public function user()
    {
        return $this->belongsTo(Config::get('action_log.user_model'), 'user_id');
    }

    public function subject()
    {
        return $this->morphTo('subject', 'subject_type', 'subject_id')->withTrashed();
    }

    public function setSubject($model)
    {
        $this->subject()->associate($model);
        return $this;
    }

    // 2 args = name, value;
    // 3 args = name, key, value
    // 4 args = name, key, value, extra
    public function logAction($action_name, $meta_key = null, $meta_value = null, $extra = null)
    {
        if (func_num_args() == 2) {
            $meta_value = $meta_key;
            $meta_key = null; // otherwise key & value is repeated twice
        }

        if (is_array($meta_value)) {
            $meta_value = json_encode($meta_value);
        }

        if (is_array($extra)) {
            $extra = json_encode($extra);
        }

        // TODO: userResolver
        if (Auth::check()) {

            $custom_guard = Config::get('action_log.auth_guard');

            if ($custom_guard) {
                $user = Auth::guard($custom_guard)->user();

                $this->user_id = data_get($user, 'id');
            } else {
                $this->user_id = Auth::user()->id;
            }
        }

        $this->action_name = $action_name;
        $this->meta_key = $meta_key;
        $this->meta_value = $meta_value;
        $this->extra = $extra;
        $this->save();
    }

    public function getMetaValue($key, $default = null)
    {
        return json_get($this->meta_value, $key, $default);
    }

    /**
     * If text stored inside 'extra' field is not a JSON, null will be returned
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getExtra($key, $default = null)
    {
        return json_get($this->extra, $key, $default);
    }

    public function getTable()
    {
        $custom_table = Config::get('action_log.table');
        return $custom_table ? $custom_table : parent::getTable();
    }

    /**
     * To be overwritten
     * @return string
     */
    public function getLabel()
    {
        return sprintf('User #%s performed Action "%s" on Subject "%s"', $this->user_id, $this->action_name, class_basename($this->subject));
    }
}

