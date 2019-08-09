<?php

function generate_string($input, $strength = 16) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}

	$permitted_chars = '.0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomize_1 = generate_string($permitted_chars, 10) ;
 	$randomize_2 = generate_string($permitted_chars, 10) ;



//get the Log in Form & JS
ob_start();
include($_ENV["path_to_security"] . "security_portal_login_form.php");
$login_form = ob_get_clean();
//Minimize form
$login_form = preg_replace( '~>\s+<~', '><', $login_form );
$login_form = preg_replace('!\s+!', ' ', $login_form);


$let_me_in = FALSE;


// Security - Set Cookie defaults (using EE template functions)
if( !($this->EE->input->cookie("randomized_1")) AND !($this->EE->input->cookie("randomized_2")))
{
	$this->EE->functions->set_cookie('randomized_1', $randomize_1 , 3600 * 2 );
	$this->EE->functions->set_cookie('randomized_2' , $randomize_2 , 3600 * 2) ;
}



/* TODO
db queries
check if good cookies exist
if so the user is auth
if not regenerate random cookies, set them and send user to the key form 

use /mfa for the key url (outside of EE)

*/

/* TODO
if it is not the key
create UUIDs
create records
create new cookie var

if it is the key, validate, 
update UUIDs
update records
create new cookie var
*/

?>