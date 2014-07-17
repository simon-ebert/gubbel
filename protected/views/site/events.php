<?php
/* @var $this SiteController */
/* @var $model EventForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Events';
$this->breadcrumbs = array(
    'Events',
);

try {
    if (!isset(Yii::app()->session['auth_token'])) {
        $this->client->authenticate();
        Yii::app()->session['auth_token'] = $this->client->getAccessToken();
    } else {
        $this->client->setAccessToken(Yii::app()->session['auth_token']);
        $calList = $this->service->getService('Calendar')->calendarList->listCalendarList();
        //Check the api documentation to see other ways to interact with api

        foreach ($calList->items as $item) {
            if ($item->summary == 'Gubbel') {
                $id = $item->id;
                break;
            }
        }

        if ($id) {
            $events = $this->service->getService('Calendar')->events->listEvents($id);
            $i = 1
            ?>

        <h1>Events</h1>
        
        <?php if (count($events->getItems())>0) { ?>
        
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
                    <td>Update</td>
                </tr>
                <?php foreach ($events->getItems() as $event) { /* var_dump($event); */ ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $event->summary; ?></td>
                        <td><?php echo $event->start->dateTime; ?></td>
                        <td><?php echo $event->start->dateTime; ?></td>
                        <td><?php echo $event->end->dateTime; ?></td>
                        <td><?php echo $event->location; ?></td>
                        <td><?php echo $event->description; ?></td>
                        <td><?php
                            if (count($event->attendees) > 0) {
                                foreach ($event->attendees as $attendee) {
                                    echo $attendee->email . ' (' . $attendee->responseStatus . ')<br/>';
                                }
                            }
                            ?></td>
                        <td><a href="#">Update</a></td>
                    </tr>
            <?php } ?>
            </table>
            <?php
        }else{
            echo '<p>There are currently no events</p>';
        }
        }
        // We're not done yet. Remember to update the cached access token.
        // Remember to replace $_SESSION with a real database or memcached.
        Yii::app()->session['auth_token'] = $this->client->getAccessToken();
    }
} catch (Exception $exc) {
    //Becarefull because the Exception you catch may not be from invalid token
    Yii::app()->session['auth_token'] = null;
    throw $exc;
}
?>
