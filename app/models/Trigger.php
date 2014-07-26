<?php


class Trigger extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'triggers';

    protected $fillable = [
        'name', 'streamId', 'check_field', 'check_operator', 'check_value', 'filter_value', 'filter_field', 'action', 'action_details', 'push_subject', 'push_message', 'variable_name', 'variable_value'
    ];


    public function getActionDetailsAttribute($value)
    {
        $actionDetails = json_decode($value);
        if (is_object($actionDetails))
        {
            return $actionDetails;
        }
        else
        {
            return new stdClass();
        }
    }

    public function setActionDetailsAttribute($value)
    {
        if (is_object($value))
        {
            $this->attributes['action_details'] = json_encode($value);
        }
        else
        {
            $this->attributes['action_details'] = json_encode([]);
        }
    }

    public function getPushSubjectAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->push_subject))
        {
            return $this->action_details->push_subject;
        }
        else
        {
            return null;
        }
    }

    public function getPushMessageAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->push_message))
        {
            return $this->action_details->push_message;
        }
        else
        {
            return null;
        }
    }

    public function setPushSubjectAttribute($value)
    {
        $actionDetails = $this->action_details;
        $actionDetails->push_subject = $value;
        $this->action_details = $actionDetails;
    }

    public function setPushMessageAttribute($value)
    {
        $actionDetails = $this->action_details;
        $actionDetails->push_message = $value;
        $this->action_details = $actionDetails;
    }
}
