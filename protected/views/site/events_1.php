<?php
/* @var $this SiteController */
/* @var $model EventForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Events';
$this->breadcrumbs = array(
    'Events',
);

session_start();

$client = new Google_Client();
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
    print "<h1><a class='login' href='$authUrl'>Login via Google</a></h1>";
} else {
    $_SESSION['token'] = $client->getAccessToken();

    $calList = $service->calendarList->listCalendarList();
    foreach ($calList->items as $item) {
        if ($item->summary == 'Gubbel') {
            $id = $item->id;
            break;
        }
    }

    if ($id) {
        $events = $service->events->listEvents($id);
        $i = 0
        ?>

        <table>
            <tr>
                <td>#</td>
                <td>Summary</td>
                <td>Date</td>
                <td>Starttime</td>
                <td>Endtime</td>
                <td>Location</td>
                <td>Description</td>
                <td>Attendees</td>
            </tr>
            <?php foreach ($events->getItems() as $event) { /* var_dump($event); */ ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $event->summary; ?></td>
                    <td><?php echo $event->start->dateTime; ?></td>
                    <td><?php echo $event->start->dateTime; ?></td>
                    <td><?php echo $event->end->dateTime; ?></td>
                    <td><?php echo $event->location; ?></td>
                    <td><?php echo $event->location; ?></td>
                    <td><?php
                        foreach ($event->attendees as $attendee) {
                            echo $attendee->email . ' (' . $attendee->responseStatus . ')<br/>';
                        }
                        ?></td>
                </tr>
            <?php } ?>
        </table>

        <?php
//        $pageToken = $events->getNextPageToken();
//        if ($pageToken) {
//            $optParams = array('pageToken' => $pageToken);
//            $events = $service->events->listEvents($id, $optParams);
//        } else {
//            break;
//        }
//    }
    }
}
?>
