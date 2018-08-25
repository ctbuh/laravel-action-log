<?php

namespace ctbuh\ActionLog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ActionEvent extends Model
{

    protected $guarded = array();
    protected $fillable = array('action_name');

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
            $this->user_id = Auth::user()->id;
        }

        $this->action_name = $action_name;
        $this->meta_key = $meta_key;
        $this->meta_value = $meta_value;
        $this->extra = $extra;
        $this->save();
    }

    public function getMetaValue($key = null)
    {
        // TODO
    }

    public function getExtra($key = null, $default = null)
    {
        // TODO
    }

    // TODO: Config::get('action_log.table')
    public function getTable()
    {
        return parent::getTable();
    }

    public function getLabel()
    {
        return sprintf('[user] performed [action] on [subject]');
    }
}

