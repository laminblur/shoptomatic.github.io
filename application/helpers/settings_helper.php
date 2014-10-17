<?php
$CI =& get_instance();
$CI->load->helper('ultils');
$CI->load->helper('file');
function getSettings(){
	return object_2_array(json_decode(read_file(SETTINGS_FILE, true)));
}

$settings = array();
$settings = getSettings();
if(!$settings){ 
	//$settings['currency']='USD';

	/*Email settings*/
	$settings['useragent']        = 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
	$settings['protocol']         = 'smtp';                   // 'mail', 'sendmail', or 'smtp'
	$settings['smtp_timeout']     = 5;                        // (in seconds)
	$settings['smtp_crypto']      = '';                       // '' or 'tls' or 'ssl'
	$settings['smtp_debug']       = 0;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data
	$settings['wordwrap']         = true;
	$settings['wrapchars']        = 76;
	$settings['mailtype']         = 'html';                   // 'text' or 'html'
	$settings['charset']          = 'utf-8';
	$settings['validate']         = true;
	$settings['priority']         = 3;                        // 1, 2, 3, 4, 5
	$settings['crlf']             = "\n";                     // "\r\n" or "\n" or "\r"
	$settings['newline']          = "\n";                     // "\r\n" or "\n" or "\r"
	$settings['bcc_batch_mode']   = false;
	$settings['bcc_batch_size']   = 200;
	$settings['smtp_host']        = 'ssl://smtp.googlemail.com';
	$settings['smtp_user']        = '';
	$settings['smtp_pass']        = '';
	$settings['smtp_port']        = 465;
	$settings['from_email']       = '';
	$settings['from_user'] = 'DroidMarketCMS';
	$settings['mailpath']         = '';
	/*end email settings*/

	$data = array();
	$json = json_encode($settings);
	write_file(SETTINGS_FILE, $json);
}

function resetEmailSettings(){
	$settings=getSettings();
	$settings['useragent']        = 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
	$settings['protocol']         = 'smtp';                   // 'mail', 'sendmail', or 'smtp'
	$settings['smtp_timeout']     = 5;                        // (in seconds)
	$settings['smtp_crypto']      = '';                       // '' or 'tls' or 'ssl'
	$settings['smtp_debug']       = 0;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data
	$settings['wordwrap']         = true;
	$settings['wrapchars']        = 76;
	$settings['mailtype']         = 'html';                   // 'text' or 'html'
	$settings['charset']          = 'utf-8';
	$settings['validate']         = true;
	$settings['priority']         = 3;                        // 1, 2, 3, 4, 5
	$settings['crlf']             = "\n";                     // "\r\n" or "\n" or "\r"
	$settings['newline']          = "\n";                     // "\r\n" or "\n" or "\r"
	$settings['bcc_batch_mode']   = false;
	$settings['bcc_batch_size']   = 200;
	$settings['smtp_host']        = 'ssl://smtp.googlemail.com';
	$settings['smtp_user']        = '';
	$settings['smtp_pass']        = '';
	$settings['smtp_port']        = 465;
	$settings['from_email']       = '';
	$settings['from_user'] =   'DroidMarketCMS';
	$settings['mailpath']         = '';
	$data = array();
	$json = json_encode($settings);
	write_file(SETTINGS_FILE, $json);
}

function setSettings($settings){
	$json=json_encode($settings);
	write_file(SETTINGS_FILE,$json);
}
?>