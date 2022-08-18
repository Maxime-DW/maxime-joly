<?php
/*
 *  CONFIGURE EVERYTHING HERE
 */

// an email address that will be in the From field of the email.
$from = 'Contact';

// an email address that will receive the email with the output of the form
$sendTo = '<contact@maxime-joly.fr>';

// subject of the email
$subject = 'Nouveau message de contact';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array('nom' => 'Nom', 'email' => 'Email', 'objet' => 'Objet', 'message' => 'Message');

// message that will be displayed when everything is OK :)
$okMessage = 'Votre message a bien été envoyé !';

// If something goes wrong, we will display this message.
$errorMessage = 'Il y a eu une erreur lors de la saisie du formulaire de contact. Veuillez réessayez ultérieurement .';

/*
 *  LET'S DO THE SENDING
 */

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);

try {

    if (count($_POST) == 0) throw new \Exception('Form is empty');

    $emailText = "Vous avez un nouveau message de contact du site maxime-joly.fr\n";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email 
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }

    // All the necessary headers for the email.
    $headers = array(
        'Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $_POST['email'],
        'Return-Path: ' . $from,
    );

    // Send email
    mail($sendTo, $subject, $emailText, implode("\n", $headers));

    $responseArray = array('type' => 'success', 'message' => $okMessage);
} catch (\Exception $e) {
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
    echo $responseArray['message'];
}
