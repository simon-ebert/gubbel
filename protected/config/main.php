<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Web Application',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
// uncomment the following to enable the Gii tool
    /*
      'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'Enter Your Password Here',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array('127.0.0.1','::1'),
      ),
     */
    ),
    // application components
    'components' => array(
        'JGoogleAPI' => array(
            'class' => 'ext.JGoogleAPI.JGoogleAPI',
            //Default authentication type to be used by the extension
            'defaultAuthenticationType' => 'webappAPI',
            //Account type Authentication data
//            'serviceAPI' => array(
//                'clientId' => '333515926117-mm09b1rd1nveou3gj55bgoas3dgdek9d.apps.googleusercontent.com',
//                'clientEmail' => 'YOUR_SERVICE_ACCOUNT_CLIENT_EMAIL',
//                'keyFilePath' => 'THE_PATH_TO_YOUR_KEY_FILE',
//            ),
            //You can define one of the authentication types or both (for a Service Account or Web Application Account)
            'webappAPI' => array(
                'clientId' => '333515926117-mm09b1rd1nveou3gj55bgoas3dgdek9d.apps.googleusercontent.com',
                'clientEmail' => '333515926117-mm09b1rd1nveou3gj55bgoas3dgdek9d@developer.gserviceaccount.com',
                'clientSecret' => 'ohLMC7ANulRD9ogvAQhUTYDu',
                'redirectUri' => 'http://localhost/gubbel-google/index.php?r=site/events',
                'javascriptOrigins' => 'https://asp-gubbel.appspot.com',
            ),
            'simpleApiKey' => 'AIzaSyDHqh-a321XGF7S8GLts4_UE3etia-ZTZs',
            //Scopes needed to access the API data defined by authentication type
            'scopes' => array(
                'serviceAPI' => array(
                    'drive' => array(
                        'https://www.googleapis.com/auth/drive.file',
                    ),
                ),
                'webappAPI' => array(
                    'calendar' => array(
                        'https://www.googleapis.com/auth/calendar',
                    ),
                ),
            ),
            //Use objects when retriving data from api if true or an array if false
            'useObjects' => true,
        ),
        'user' => array(
// enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        
//          'urlManager'=>array(
//          'urlFormat'=>'path',
//          'rules'=>array(
//          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
//          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
//          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
//          ),
//          ),
         
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        /*
          'db'=>array(
          'connectionString' => 'mysql:host=localhost;dbname=testdrive',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          ),
         */
        'errorHandler' => array(
// use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
// using Yii::app()->params['paramName']
    'params' => array(
// this is used in contact page
        'adminEmail' => 'webmaster@example.com',
    ),
);
