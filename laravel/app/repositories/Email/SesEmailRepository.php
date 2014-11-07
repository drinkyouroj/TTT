<?php namespace AppStorage\Email;

use Aws\Ses\SesClient, 
	Config,
	Log
	;

class SesEmailRepository implements EmailRepository {

	public function __construct()
	{
		//Makes an aws instance.
		$this->ses = SesClient::factory(Config::get('aws.ses'));
		$this->default = Config::get('email');//gets default values for data initilization
	}

	public function instance() {
		//grabs the ses instance.
		
	}

	/*
	* Sends an e-mail.
	*/
	public function create($data, $override = false) {

		//system level block on whether to send email or not.
		if( Config::get('app.email_send') || $override ) {
			$data = self::dataInit($data);
			try {
				$result = $this->ses->sendEmail(array(
						'Source' => $data['from'],
						'Destination' => array(
							'ToAddresses' => $data['to'],
							'CcAddresses' => $data['cc'],
		        			'BccAddresses' => $data['bcc']
							),//end destination

						'Message' => array(

							'Subject' => array(
								'Data' => $data['subject']
								),//end subject

							'Body' => array(
								'Text' => array(
									'Data' => $data['plaintext']
									),
								'Html' => array(
									'Data' => $data['html']
									),
								),//end body
							),
						'ReplyToAddresses' => $data['reply'],
						'ReturnPath' => $data['complaint']
					));
			} catch (\Aws\Ses\Exception\SesException $e) {
				//Log the deamn exception
				Log::error($data['to']);
				Log::error($e);
			}
		}
	}

		//Below ensures that many of the trivial fields get filled in.
		private function dataInit($data) {

			//Default From
			$data['from']		= empty($data['from']) ? $this->default['from'] : $data['from'];
			$data['complaint']	= empty($data['complaint']) ? $this->default['returnPath'] : $data['complaint'];
			$data['reply']		= empty($data['reply']) ? array($this->default['returnPath']) : $data['reply'];
			$data['cc']			= empty($data['cc']) ? array() : $data['cc'];
			$data['bcc']		= empty($data['bcc']) ? array() : $data['bcc'];
			return $data;
		}


	public function test($data) {

		// $data['subject'] = 'test';
		// $data['plaintext'] = 'test';
		$data['html'].= '<p>TEST</p>';

		$data['to'] = $this->default['testEmails'];
		unset($data['cc']);
		unset($data['bcc']);
		$data = self::dataInit($data);

		$result = self::create($data, true);
	}

}