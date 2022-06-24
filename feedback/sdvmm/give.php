<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form1')
{
   $mailto = 'feedback@labs.ryanwalpole.com';
   $mailfrom = 'feedback@labs.ryanwalpole.com';
   $mailname = 'RWE Labs Feedback';
   $subject = 'Feedback for Stardew Valley Mod Manager';
   $message = 'Values submitted from web site form:';
   $success_url = './success.php';
   $error_url = './success.php';
   $eol = "\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response", "h-captcha-response");
   $boundary = md5(uniqid(time()));
   $header  = 'From: '.$mailname.' <'.$mailfrom.'>'.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;

   try
   {
      if (!ValidateEmail($mailfrom))
      {
         $error .= "The specified email address (" . $mailfrom . ") is invalid!\n<br>";
         throw new Exception($error);
      }
      $message .= $eol;
      $message .= "IP Address : ";
      $message .= $_SERVER['REMOTE_ADDR'];
      $message .= $eol;
      foreach ($_POST as $key => $value)
      {
         if (!in_array(strtolower($key), $internalfields))
         {
            if (is_array($value))
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
            }
            else
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
            }
         }
      }
      $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
      $body .= '--'.$boundary.$eol;
      $body .= 'Content-Type: text/plain; charset=ISO-8859-1'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
      if (!empty($_FILES))
      {
         foreach ($_FILES as $key => $value)
         {
             if ($_FILES[$key]['error'] == 0)
             {
                $body .= '--'.$boundary.$eol;
                $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
                $body .= 'Content-Transfer-Encoding: base64'.$eol;
                $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
                $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
             }
         }
      }
      $body .= '--'.$boundary.'--'.$eol;
      if ($mailto != '')
      {
         mail($mailto, $subject, $body, $header);
      }
      header('Location: '.$success_url);
   }
   catch (Exception $e)
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $e->getMessage(), $errorcode);
      echo $errorcode;
   }
   exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Give Feedback | Stardew Valley Mod Manager</title>
<meta name="generator" content="WYSIWYG Web Builder 16 - https://www.wysiwygwebbuilder.com">
<link href="feedback.css" rel="stylesheet">
<link href="give.css" rel="stylesheet">
<script src="jquery-1.12.4.min.js"></script>
<script src="wwb16.min.js"></script>
<script>
$(document).ready(function()
{
   LoadValue('Combobox1', 'session', 2);
   LoadValue('Combobox2', 'session', 2);
   LoadValue('Combobox3', 'session', 2);
   LoadValue('Combobox4', 'session', 2);
   LoadValue('TextArea1', 'session', 0);
   LoadValue('TextArea2', 'session', 0);
   $("#Form1").submit(function(event)
   {
      StoreValue('Combobox1', 'session', 2);
      StoreValue('Combobox2', 'session', 2);
      StoreValue('Combobox3', 'session', 2);
      StoreValue('Combobox4', 'session', 2);
      StoreValue('TextArea1', 'session', 0);
      StoreValue('TextArea2', 'session', 0);
      return true;
   });
});
</script>
</head>
<body>
<div id="space"><br></div>
<div id="container">
<div id="wb_Form1" style="position:absolute;left:0px;top:0px;width:437px;height:613px;z-index:13;">
<form name="Feedback_for_Stardew_Valley_Mod_Manager" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" id="Form1">
<input type="hidden" name="formid" value="form1">
<select name="LikesSDVMM" size="1" id="Combobox1" style="position:absolute;left:12px;top:39px;width:406px;height:28px;z-index:0;" required>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
<select name="UIRating" size="1" id="Combobox2" style="position:absolute;left:12px;top:100px;width:406px;height:28px;z-index:1;" required>
<option value="Excellent">Excellent</option>
<option value="Very Good">Very Good</option>
<option value="Good">Good</option>
<option value="Average">Average</option>
<option value="Not Good">Not Good</option>
<option value="Bad">Bad</option>
<option value="Awful">Awful</option>
</select>
<select name="UXRating" size="1" id="Combobox3" style="position:absolute;left:12px;top:169px;width:406px;height:28px;z-index:2;" required>
<option value="Excellent">Excellent</option>
<option value="Very Good">Very Good</option>
<option value="Good">Good</option>
<option value="Average">Average</option>
<option value="Not Good">Not Good</option>
<option value="Bad">Bad</option>
<option value="Awful">Awful</option>
</select>
<div id="wb_Text1" style="position:absolute;left:12px;top:19px;width:329px;height:15px;z-index:3;">
<span style="color:#000000;font-family:Arial;font-size:13px;">Do you enjoy using Stardew Valley Mod Manager?</span></div>
<div id="wb_Text2" style="position:absolute;left:12px;top:80px;width:329px;height:15px;z-index:4;">
<span style="color:#000000;font-family:Arial;font-size:13px;">How would you describe the user interface?</span></div>
<div id="wb_Text3" style="position:absolute;left:12px;top:149px;width:329px;height:15px;z-index:5;">
<span style="color:#000000;font-family:Arial;font-size:13px;">How would you describe the user experience?</span></div>
<div id="wb_Text4" style="position:absolute;left:12px;top:216px;width:406px;height:15px;z-index:6;">
<span style="color:#000000;font-family:Arial;font-size:13px;">Do you think it is easy to set up Stardew Valley Mod Manager?</span></div>
<select name="LikesSDVMM" size="1" id="Combobox4" style="position:absolute;left:12px;top:236px;width:406px;height:28px;z-index:7;" required>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
<div id="wb_Text5" style="position:absolute;left:12px;top:284px;width:406px;height:15px;z-index:8;">
<span style="color:#000000;font-family:Arial;font-size:13px;">What features do you find yourself enjoying the most?</span></div>
<textarea name="FeaturesEnjoyed" id="TextArea1" style="position:absolute;left:12px;top:309px;width:396px;height:90px;z-index:9;" rows="5" cols="47" spellcheck="false"></textarea>
<div id="wb_Text6" style="position:absolute;left:12px;top:425px;width:406px;height:15px;z-index:10;">
<span style="color:#000000;font-family:Arial;font-size:13px;">What features would you like to see us implement or work on?</span></div>
<textarea name="FeaturesWanted" id="TextArea2" style="position:absolute;left:12px;top:450px;width:396px;height:90px;z-index:11;" rows="5" cols="47" spellcheck="false"></textarea>
<input type="submit" id="Button1" name="" value="Submit" style="position:absolute;left:322px;top:565px;width:96px;height:25px;z-index:12;">
</form>
</div>
</div>
</body>
</html>