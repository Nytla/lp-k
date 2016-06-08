<?php
/**
 * Mailer.php
 *
 * This is file with Mailer class
 *
 * @category	classes
 * @copyright	2012
 * @author		Igor Zhabskiy <Zhabskiy.Igor@gmail.com>
 */
/**
 * Mailer
 *
 * This class sent email.
 *
 * @version 1.0
 */
final class Mailer {
	/**
	 * _to_email
	 *
	 * @var string
	 */
	private $_to_email = '';
	/**
	 * _from_email
	 *
	 * @var string
	 */
	private $_from_email = '';
	/**
	 * _from_name
	 *
	 * @var string
	 */
	private $_from_name = '';
	/**
	 * _subject
	 *
	 * @var string
	 */
	private $_subject = '';
	/**
	 * _mime_version
	 *
	 * @var string
	 */
	private $_mime_version = '1.0';
	
	/**
	 * _encoding
	 *
	 * @var string	It can be: UTF-8, ISO-8859-1, Windows-1251, KOI8-r, cp866
	 */
	// private $_encoding = 'iso-8859-1';
	private $_encoding = 'utf-8';
	/**
	 * _content_type
	 *
	 * @var string	It can be: "text/plain;", "text/html;", "image/png;", "image/gif;", "video/mpeg;", "text/css;", and "audio/basic;"
	 */
	private $_content_type = 'text/html;';
	/**
	 * _headers
	 *
	 * @var null
	 */
	private $_headers;
	/**
	 * emailSent
	 * 
	 * This function sent email 
	 * 
	 * @param string 	$body
	 * @return boolean	$mailer
	 */
	public function emailSent($to_email, $from_email, $from_name, $subject, $body) {
		/**
		 * Send email
		 */	
		$this -> _to_email = $to_email;
		
		$this -> _from_email = $from_email;
		
		$this -> _from_name = $from_name;
		
		$this -> _subject = $subject;
		
		$mailer = ($this -> emailValidate()) ? mail($to_email, $subject, $body, $this -> addHeader()) : false;
		
		return $mailer;
	}
	/**
	 * emailValidate
	 *
	 * This function send email
	 *
	 * @return boolean
	 */
	private function emailValidate() {
		$find_coma = ',';
		$search = strpos($this -> _to_email, $find_coma);
		if ($search === false) {
			
			if (filter_var($this -> _to_email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_PATH_REQUIRED)) {
				
				return true;
			} else {
				return false;
			}
		} else {
			
			$email_to_array = explode(',', $this -> _to_email);
			
			foreach ($email_to_array as $value) {
				
				if (filter_var(trim($value), FILTER_VALIDATE_EMAIL, FILTER_FLAG_PATH_REQUIRED)) {
					
					$flag = true;
				} else {
					
					$flag = false;
					
					break;
				}
			}
			
			return $flag;
		}
	}
	/**
	 * addHeader
	 *
	 * This function create headers for our letter
	 *
	 * @return string $this -> headers
	 */
	private function addHeader() {
		$this -> _headers  = "MIME-Version: " . $this -> _mime_version . "\r\n";
		$this -> _headers .= "Content-Type: " . $this -> _content_type . " charset=" . $this -> _encoding . "\r\n";
		$this -> _headers .= "From: " . $this -> _from_name . " <" . $this -> _from_email . ">\r\n";
		$this -> _headers .= "Reply-To: " . $this -> _from_email . "\r\n";			
		$this -> _headers .= "X-Mailer: PHP/" . phpversion();
		return $this -> _headers;
	}
	/**
	 * Destructor
	 *
	 * This function is destructor
	 */
	public function __destruct() {}
}

/**
 * Create copy of Mailer object
 */
$object = new Mailer();

/**
 * Set variable of our letter
 */
$name = $_POST['name'];
$email = $_POST['email'];
$text = $_POST['text'];

$to_email = 'info@krearo.com';
$from_email = 'krearo@mail.com';
$from_name = $name;
$suject = 'Обратная связь на krearo.com';
$body = '
<html>
	<head>
	</head>
	<body>
		<p><strong>Имя:</strong> '. $name .'</p>
		<p><strong>Email:</strong> '. $email .'</p>
		<p><strong>Сообщение:</strong> '. $text .'</p>
	</body>
</html>
';

/**
 * Send email
 */
$send_email = $object -> emailSent($to_email, $from_email, $from_name, $suject, $body);

/**
 * Prepare request
 */
print json_encode(array(
		"flag" => ($send_email) ? true : false
	));
?>