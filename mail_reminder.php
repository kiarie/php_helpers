<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

$connection_array = array('dbname'=> 'micstation_conf_ipay_ke' , 'host' => 'localhost' , 'pass' => 'admin' , 'user' => 'root');
$database = new \elipa\database\ORM_Model($connection_array);
$email = new \elipa\email\Email();
/**************************/
// Create connection
$conn = new mysqli($connection_array['host'], $connection_array['user'], $connection_array['pass'], $connection_array['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/**************************/

//do a select
$date_fifteen_minutes_ago = date('Y-m-d H:i:s',time() - 15 * 60);
$select = $database->select('online_sessions_ke',array('email','phone','vid','oid','sid','amount'))
->where('vid','demo')
->where('sessdatetime',$date_fifteen_minutes_ago, '<')
->getAll();

foreach($select as $session_data):
  //check if exists in the online ke
  $online_data = $database->select('online_ke',array('id'))
  ->where('tid',$session_data['tid'])->get();
  if(!$online_data):
      /*sends the email */
      $to = $session_data['email'];
      $from = 'no-reply@jumia.co.ke';
      $cc = 'technical@ipayafrica.com';
      $subject = 'Pending Payment for '.$session_data['oid'];
      $msg = '';
      $email->sendEmail($to , $from ,$cc, $subject , $msg);
    /***************************************************************/
    /* updates the table */
    $sql = "UPDATE online_sessions_ke SET email_reminder ='1' WHERE id = ".$session_data['id'];

    if ($conn->query($sql) === TRUE) {
        echo " Record updated successfully ";
    } else {
        echo " Error updating record: " . $conn->error;
    }
    /***************************************************************/



  endif;

endforeach;/* end for each */
$conn->close();//close the db connection
?>
