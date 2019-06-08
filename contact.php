<?php

// email that will be in the form field of the email.
$from = 'Demo newsletter sign-up form <demo@domain.com>';

// email address that will receive form submission
$sendTo = 'Demo Newsletter Sign-up Form <demo@domain.com>';

// subject of the email
$subject = 'New Newsletter Sign-Up Form';

// text to appear in the email
$fields = array('name' => 'Name', 'surname' => 'Surname', 'email' => 'Email'); 

// success message 
$okMessage = 'Thank you for subscribing to our newsletter!';

// error message
$errorMessage = 'There was an error while submitting the form. Please try again later';

// error reporting
error_reporting(E_ALL & ~E_NOTICE);

try
{

    if(count($_POST) == 0) throw new \Exception('Form is empty');
            
    $emailText = "Someone subscribed to our newsletter\n=============================\n";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email 
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }

    // headers for the email
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    );
    
    // Send email
    mail($sendTo, $subject, $emailText, implode("\n", $headers));

    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}


// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['email'];
}
