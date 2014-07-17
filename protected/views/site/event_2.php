<?php
/* @var $this SiteController */
/* @var $model EventForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Event';
$this->breadcrumbs = array(
    'Event',
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
    ?>

    <h1>Create event</h1>

    <p>Please fill out the following form with your event information:</p>

    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'event-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
        ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <div class="row">
            <?php echo $form->labelEx($model, 'summary'); ?>
            <?php echo $form->textField($model, 'summary'); ?>
            <?php echo $form->error($model, 'summary'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'date'); ?>
            <?php echo $form->textField($model, 'date'); ?>
            <?php echo $form->error($model, 'date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'starttime'); ?>
            <?php echo $form->textField($model, 'starttime'); ?>
            <?php echo $form->error($model, 'starttime'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'endtime'); ?>
            <?php echo $form->textField($model, 'endtime'); ?>
            <?php echo $form->error($model, 'endtime'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'location'); ?>
            <?php echo $form->textField($model, 'location'); ?>
            <?php echo $form->error($model, 'location'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textField($model, 'description'); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'attendees'); ?>
            <?php echo $form->textField($model, 'attendees'); ?>
            <?php echo $form->error($model, 'attendees'); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Create event'); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->
    <?php
}
?>

