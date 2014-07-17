<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class EventForm extends CFormModel {

    public $summary;
    public $date;
    public $starttime;
    public $endtime;
    public $location;
    public $description;
    public $attendees;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('summary', 'required'),
            array('date', 'default'),
            array('starttime, endtime', 'default'),
            array('location, description', 'default'),
            array('attendees', 'default')
        );
    }

}
