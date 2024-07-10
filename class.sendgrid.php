<?php
/**
 * @project:	ALRB Web Development
 * @access:		Fri Nov 10 10:08:00 CST 2023
 * @author:		Levi Self <levi@airlinkrb.com>
 **/

class SendGridAPI {

    public $apiurl  = "https://api.sendgrid.com/v3/mail/send";
    public $apikey  = "SG.";

    public function SendEmail($to_email, $to_name, $subject, $message, $from_email, $from_name) {

        $SimpleAPI 		= new SimpleAPI();
        $sendgrid_auth	= array(
        	"type"		=>		"token",
        	"token"		=>		$this->apikey
        );
        $SimpleAPI->Auth($sendgrid_auth);
        
        $email_dataset = array(
            "personalizations"	=>	array(
                array(
                    "to"	=>	array(
                        array(
                            "email"	=>	$to_email,
                            "name"	=>	$to_name
                        ),
                    ),
                    "subject"	=>	$subject
                ),
            ),
            "content"		=>	array(
                array(
                    "type"	=>	"text/plain",
                    "value"	=>	$message
                ),
            ),
            "from"		=>	array(
                "email"		=>	$from_email,
                "name"		=>	$from_name
            ),
            "reply_to"	=>	array(
                "email"		=>	$from_email,
                "name"		=>	$from_name
            )
        );

        $SimpleAPI->call("POST", $this->apiurl, $email_dataset);
    }
}
?>
