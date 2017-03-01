<?php namespace elipa\email;
use Exception;
class Email{

    public function sendEmail($to , $from ,$cc, $subject , $msg){
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        $headers .= 'From: <'.$from.'>' . "\r\n";
        $headers .= 'Cc: '.$cc.'' . "\r\n";
        $send = mail($to,$subject,$msg,$headers);

        if ( !empty($to) && !empty($from) && !empty($subject) && !empty($msg))://if these variables are not empty go ahead and send mail
            if($send)://if email was sent
                return 'Mail sent succesfully';
            else://if there was an error in sending
                return 'error while sending mail';
            endif;
        else:
        return 'Error: some variables are missing';
        endif;
    }

    public function generate_email($vid, $mername, $username, $password){

        $email_text = '<html>
<head>
<title></title>
</head>
<body>
<div style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;width:100%!important;min-height:100%;line-height:1.6;background:#f6f6f6;margin:0;padding:0">

<table style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;width:100%;background:#f6f6f6;margin:0;padding:0">
<tbody><tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:0" valign="top"></td>
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;display:block!important;max-width:600px!important;clear:both!important;margin:0 auto;padding:0" valign="top" width="600">
<div style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;max-width:600px;display:block;margin:0 auto;padding:20px">
<table style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;border-radius:3px;background:#fff;margin:0;padding:0;border:1px solid #e9e9e9" width="100%" cellpadding="0" cellspacing="0">
<tbody><tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;text-align:center;margin:0;padding:20px" valign="top" align="center">


<table style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0" width="100%" cellpadding="0" cellspacing="0">
<tbody><tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:0 0 0px" valign="top">
<h1 style="font-family:\'Helvetica Neue\',Helvetica,Arial,\'Lucida Grande\',sans-serif;font-size:32px;color:#000;line-height:1.2;font-weight:800;margin:20px 0 0;padding:0">iPay Registration Details</h1>
</td>
</tr>
<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:0 0 0px" valign="top">
<h3 style="font-family:\'Helvetica Neue\',Helvetica,Arial,\'Lucida Grande\',sans-serif;border-bottom-color:#333;border-bottom-width:1px;border-bottom-style:solid;font-size:20px;color:#000;line-height:1.2;font-weight:300;margin:20px 0 0;padding:0">Merchant ID '.$vid.'</h3>
</td>
</tr>

<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:0 0 0px" valign="top">
<h4 style="font-family:\'Helvetica Neue\',Helvetica,Arial,\'Lucida Grande\',sans-serif;font-weight:300;margin:20px 0 0;padding:0">Thank you for registering with iPay below are your account details</h4>
</td>
</tr>
<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:0 0 10px" valign="top">
<table style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;text-align:left;width:80%;margin:40px auto;padding:0">
<tbody>


<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:5px 0" valign="top">
<table style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;width:100%;margin:0;padding:0" cellpadding="0" cellspacing="0">
<tbody><tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td colspan="2" style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;border-top-width:1px;border-top-color:#eee;background:#348eda;color:#fff;border-top-style:solid;margin:0;padding:5px 0" valign="top"><b> Account Details</b></td>

</tr>
<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0" valign="top">VENDOR ID:</td>
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;text-align:right;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0" valign="top" align="right">'.$vid.'</td>
</tr>
<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0" valign="top">Merchant Name:</td>
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;text-align:right;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0" valign="top" align="right">'.$mername.'</td>
</tr>





</tbody></table>
</td>
</tr>





<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:5px 0" valign="top">
<table style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;width:100%;margin:0;padding:0" cellpadding="0" cellspacing="0">
<tbody><tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td colspan="2" style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;border-top-width:1px;border-top-color:#eee;background:#F29208;color:#fff;border-top-style:solid;margin:0;padding:5px 0" valign="top"><b> Dashboard Details</b></td>

</tr>
<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0" valign="top">Username:</td>
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;text-align:right;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0" valign="top" align="right">'.$username.'</td>
</tr>
<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0" valign="top">Password:</td>
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;text-align:right;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0" valign="top" align="right">'.$password.'</td>
</tr>


<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:10px;vertical-align:top;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0;font-style: italic;" valign="top">Please change your username and password as soon as you login</td>
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;text-align:right;border-top-width:1px;border-top-color:#eee;border-top-style:solid;margin:0;padding:5px 0" valign="top" align="right"></td>
</tr>


</tbody></table>
</td>
</tr>



</tbody></table>
</td>
</tr>
<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:0 0 20px" valign="top">
<a href="https://dashboard.ipayafrica.com" style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;color:#fff;text-decoration:none;line-height:2;font-weight:bold;text-align:center;display:inline-block;border-radius:5px;text-transform:capitalize;background:#348eda;margin:0;padding:0;border-color:#348eda;border-style:solid;border-width:10px 20px" target="_blank">Login to your dashboard</a>
</td>
</tr>
<tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:0 0 20px" valign="top">
<a href="https://ipayafrica.com/help/" style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:10px;color:#fff;text-decoration:none;line-height:2;font-weight:bold;text-align:center;display:inline-block;border-radius:5px;text-transform:capitalize;background:#348eda;margin:0;padding:0;border-color:#348eda;border-style:solid;border-width:10px 20px" target="_blank">Help</a>
</td>
</tr>

</tbody></table>
</td>
</tr>
</tbody></table>
<div style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;width:100%;clear:both;color:#999;margin:0;padding:20px">
<table style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0" width="100%">
<tbody><tr style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;margin:0;padding:0">
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:12px;vertical-align:top;text-align:center;margin:0;padding:0 0 20px" valign="top" align="center">This is an auto generated email if you have any questions, please send email <a style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:12px;color:#999;text-decoration:underline;margin:0;padding:0">info@ipayafrica.com</a></td>
</tr>
</tbody></table>
</div></div>
</td>
<td style="font-family:\'Helvetica Neue\',\'Helvetica\',Helvetica,Arial,sans-serif;font-size:14px;vertical-align:top;margin:0;padding:0" valign="top"></td>
</tr>
</tbody></table><div class="yj6qo"></div><div class="adL">

</div></div>

</body></html>';

        return $email_text;
    }


}
?>
