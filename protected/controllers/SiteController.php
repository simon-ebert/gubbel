<?php

class SiteController extends Controller {

    public $client;
    public $service;

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'

        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }

        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }

        $this->render('login', array('model' => $model));
    }

    public function actionCreateEvent() {
        //Create an extension Instance
        $this->service = Yii::app()->JGoogleAPI;
        $this->client = Yii::app()->JGoogleAPI->getClient();

        $model = new EventForm;
        if (isset($_POST['EventForm'])) {
            $model->attributes = $_POST['EventForm'];
            if ($model->validate()) {

                $this->client->setAccessToken(Yii::app()->session['auth_token']);

//                 look for calendar 'asp-gubbel'
                $calList = $this->service->getService('Calendar')->calendarList->listCalendarList();
                foreach ($calList->items as $item) {
                    if ($item->summary == 'Gubbel') {
                        $id = $item->id;
                        break;
                    }
                }

//                // create calendar 'asp-gubbel' if necessary
                if (!$id) {
                    $calendar = $this->service->getObject('Calendar', $this->service);
                    $calendar->setLocation('Germany');
                    $calendar->setSummary('Gubbel');
                    $calendar->setTimeZone('Europe/Berlin');

                    $createdCalendar = $this->service->calendars->insert($calendar);

                    $id = $createdCalendar->getId();
                    echo $id;
                }

//                // create event
                $event = $this->service->getObject('Event', $this->service);
                $event->setSummary($model->summary);
                $event->setLocation($model->location);
                $start = new Google_EventDateTime();
                $start->setDateTime('2014-07-18T10:00:00.000-07:00');
                $event->setStart($start);
                $end = new Google_EventDateTime();
                $end->setDateTime('2014-07-18T10:25:00.000-07:00');
                $event->setEnd($end);
                $event->setDescription($model->description);
                $attendee1 = new Google_EventAttendee();
                $attendee1->setEmail($model->attendees);
// ...
                $attendees = array($attendee1,
                        // ...
                );
                $event->attendees = $attendees;

                $createdEvent = $this->service->getService('Calendar')->events->insert($id, $event, array("sendNotifications" => true));
                echo $createdEvent->getId();
            }
        }

        $this->render('event'
                , array('model' => $model)
        );
    }

    public function updateEvent() {
        
        $this->render('event'
//                , array('model' => $model)
        );
    }
    
    public function actionEvents() {
        //Create an extension Instance
        $this->service = Yii::app()->JGoogleAPI;
        $this->client = Yii::app()->JGoogleAPI->getClient();

        $this->render('events');
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionGoogleLogin() {
        $identity = new GUserIdentity('test', 'test');
        $identity->authenticate();
        if ($identity->errorCode === GUserIdentity::ERROR_NONE) {
            Yii::app()->user->login($identity, $duration = 0);
            $this->redirect('/');
        } else {
            $this->redirect('/site/login');
        }
    }

    public function actionTest() {
        echo '<pre>';
        echo "is_file('gs://yii-assets/dir/file.txt');";
        var_dump(is_file('gs://yii-assets/dir/file.txt'));

        echo "is_dir('gs://yii-assets/dir');";
        var_dump(is_dir('gs://yii-assets/dir'));

        echo "is_file('gs://yii-assets/nodir/nofile.txt');";
        var_dump(is_file('gs://yii-assets/nodir/nofile.txt'));

        echo "is_dir('gs://yii-assets/nodir');";
        var_dump(is_dir('gs://yii-assets/nodir'));

        echo "filemtime('gs://yii-assets/dir/file.txt');";
        var_dump(filemtime('gs://yii-assets/dir/file.txt'));
        echo '</pre>';
    }

}
