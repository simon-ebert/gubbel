<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->pageTitle = Yii::app()->name;

require_once Yii::app()->basePath . '/../assets/google-php/src/Google_Client.php';
require_once Yii::app()->basePath . '/../assets/google-php/src/contrib/Google_CalendarService.php';

session_start();

$client = new Google_Client();
$client->setApplicationName("Google Calendar PHP Starter Application");

// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId('333515926117-mm09b1rd1nveou3gj55bgoas3dgdek9d.apps.googleusercontent.com');
$client->setClientSecret('ohLMC7ANulRD9ogvAQhUTYDu');
$client->setRedirectUri('http://127.0.0.1:8080/site/create');
$client->setDeveloperKey('AIzaSyDHqh-a321XGF7S8GLts4_UE3etia-ZTZs');

$service = new Google_CalendarService($client);

if (isset($_GET['logout'])) {
    unset($_SESSION['token']);
}

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['token'] = $client->getAccessToken();
    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
    $client->setAccessToken($_SESSION['token']);
}

if (!$client->getAccessToken()) {
    $authUrl = $client->createAuthUrl();
    print "<a class='login' href='$authUrl'>Connect Me!</a>";
} else {
    $calList = $cal->calendarList->listCalendarList();
    print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
    $_SESSION['token'] = $client->getAccessToken();
}
?>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>assets/timePicker-master/jquery.timePicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>assets/timePicker-master/timePicker.css" />

<form id="myForm" class="form-horizontal" action="<?php echo $this->createUrl("site/save"); ?>" method="POST">
    <fieldset>

        <!-- Form Name -->
        <legend>Form Name</legend>

        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="summary">Summary</label>  
            <div class="col-md-7">
                <input id="summary" name="summary" type="text" placeholder="Unnamed event" class="form-control input-md" xrequired="">

            </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="date">Date</label>  
            <div class="col-md-4">
                <input id="start-date" name="date" type="text" placeholder="" class="form-control input-md" xrequired="">

            </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="start-time">Start time</label>  
            <div class="col-md-4">
                <input id="start-time" name="start-time" type="text" placeholder="" class="form-control input-md" xrequired="">

            </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="end-time">End time</label>  
            <div class="col-md-4">
                <input id="end-time" name="end-time" type="text" placeholder="" class="form-control input-md" xrequired="">

            </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="location">Start Location</label>  
            <div class="col-md-7">
                <input id="location" name="location" type="text" placeholder="" class="form-control input-md" xrequired="">

            </div>
        </div>

        <!-- Textarea -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="description">Description</label>
            <div class="col-md-7">                     
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
        </div>

        <!-- Textarea -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="attendees">Attendees</label>
            <div class="col-md-7">                     
                <textarea class="form-control" id="attendees" name="attendees"></textarea>
            </div>
        </div>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="create"></label>
            <div class="col-md-4">
                <button id="create" type="submit" class="btn btn-success">Create event</button>
            </div>
        </div>
    </fieldset>
</form>


<script>
    $('#start-date').datepicker();
    $('#start-time, #end-time').timePicker();
</script>

<?php
$event = new Google_Event();
$event->setSummary('Appointment');
$event->setLocation('Somewhere');
$start = new Google_EventDateTime();
$start->setDateTime('2014-07-09T10:00:00.000-07:00');
$event->setStart($start);
$end = new Google_EventDateTime();
$end->setDateTime('2014-07-09T10:25:00.000-07:00');
$event->setEnd($end);
$attendee1 = new Google_EventAttendee();
$attendee1->setEmail('simon.ebert@tum.de');
// ...
$attendees = array($attendee1,
        // ...
);
$event->attendees = $attendees;

$createdEvent = $service->events->insert('primary', $event, array("sendNotifications" => true));

echo $createdEvent->getId();
