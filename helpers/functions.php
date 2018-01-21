<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function emailToUser($data = array()){
    
    $view = new EmailView();
    $view->setView('userNotification.php');
    $view->setParam('title', $data['title']);
    $view->setParam('messageText', $data['messageText']);
    $view->setParam('user', $data['user']);    
    $view->setParam('plan', $data['plan']);

    $body = $view->render(false);

    sendEmail(EMAIL_INFO, EMAIL_INFO_NAME, $data['user']['email'], $data['user']['first_name'] . ' ' . $data['user']['last_name'], EMAIL_REPLY, EMAIL_REPLY_NAME, $data['title'], $body);
}

function sendEmail($fromEmail, $fromName, $targetEmail, $targetName, $replyToEmail, $replyToName, $subject, $body, $attachment = null, $altbody = null)
{
	require_once 'helpers/vendor/autoload.php';
    $mail = new PHPMailer;

    if (USE_SMTP) {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST; 
        $mail->SMTPAuth = true;       
        $mail->Username = SMTP_USERNAME;          
        $mail->Password = SMTP_PASSWORD;                      
        $mail->SMTPSecure = SMTP_SECURE;                
        $mail->Port = SMTP_PORT;                          
        $mail->SMTPDebug = PHPMAILER_DEBUG;
    }else{
        $mail->Host = localhost;
    }

    $mail->CharSet = 'UTF-8';
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($targetEmail, $targetName);
    $mail->addReplyTo($replyToEmail, $replyToName);

    $mail->addCC(EMAIL_INFO);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;
    if ($altbody === null)
    {
        $mail->AltBody = $body;
    }
    else
    {
        $mail->AltBody = $altbody;
    }

    if ($attachment !== null){
    	if (is_array($attachment)){
    		if (isAssoc($attachment)){
    			foreach ($attachment as $key => $value) {
    				$mail->addAttachment($key, $value);
    			}
    		}else{
    			foreach ($attachment as $attach) {
    				$mail->addAttachment($attach);
    			}
    		}
    	}
    }

    if(!$mail->send()) {
        $errorInfo = $mail->ErrorInfo;
        var_dump($mail->ErrorInfo);
        exit;
        $error = array('error' => true, 'errorInfo' => $errorInfo);
        return $error;
    }
    else
    {
        return true;
    }
}
