<?php

$to      = 'milder.lisondra@yahoo.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: charles.miller@coherent.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);