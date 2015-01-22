<?php
$handlers = array(
    'user_enrolled' => array(
        'handlerfile' => '/local/enrollmentnotifications/lib.php',
        'handlerfunction' => 'local_enrollmentnotifications_user_enrolled',
        'schedule' => 'instant',
        'internal' => 1,
    ),
    'user_enrolled_bulk' => array(
        'handlerfile' => '/local/enrollmentnotifications/lib.php',
        'handlerfunction' => 'local_enrollmentnotifications_user_enrolled',
        'schedule' => 'instant',
        'internal' => 1,
    ),

);