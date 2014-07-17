<?php
/* @var $this SiteController */
/* @var $model EventForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Event';
$this->breadcrumbs = array(
    'Event',
);

//Create an extension Instance
$jgoogleapi = Yii::app()->JGoogleAPI;
$client = $jgoogleapi->getClient();

try {
    if (!isset(Yii::app()->session['auth_token'])) {
        $client->authenticate();
        Yii::app()->session['auth_token'] = $client->getAccessToken();
    } else {
        $client->setAccessToken(Yii::app()->session['auth_token']);

        $cal = $jgoogleapi->getService('Calendar');
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
                <?php echo $form->labelEx($model, 'date (NOT IMPLEMENTED, NEED DATEPICKER)'); ?>
                <?php echo $form->textField($model, 'date'); ?>
                <?php echo $form->error($model, 'date'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'starttime (NOT IMPLEMENTED, NEED TIMEPICKER)'); ?>
                <?php echo $form->textField($model, 'starttime'); ?>
                <?php echo $form->error($model, 'starttime'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'endtime (NOT IMPLEMENTED, NEED TIMEPICKER)'); ?>
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
