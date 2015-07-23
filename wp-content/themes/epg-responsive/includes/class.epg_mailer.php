<?php

/**
 * Class EPG Mailer
 */
class EPG_PHPMailer extends PHPMailer {

	/**
	 * Clean the "From" address
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	public function from_address( $string ) {
		$this->From = $string;
		$bad        = array( 'content-type', 'bcc:', 'to:', 'cc:', 'href' );

		return str_replace( $bad, '', $this->From );
	}

	/**
	 * @param array $email_array
	 *
	 * @param       $email_array
	 *
	 * @throws phpmailerException
	 */
	public function multiple_request_recipients( $email_array ) {
		foreach ( $email_array as $recipient ) {
			$this->addAnAddress( $recipient['kind'], $recipient['address'],
				$recipient['name'] );
		}
	}

	/**
	 * Creates the full email subject
	 *
	 * @param null $subject
	 * @param null $issuetype
	 * @param null $realm
	 *
	 * @return bool
	 */
	public function email_subject( $subject = null, $issuetype = null, $realm = null ) {
		$subject_line = 'Ticket: ';
		/** Default Subject / Title */
		if ( null !== $subject ) {
			$subject_line .= $subject;
		}
		/** Set the Issue Label */
		$subject_line .= $this->issue_type_label( $issuetype );
		/** Set type label */
		if ( null !== $realm ) {
			$subject_line .= ' #' . $realm;
		}
		/** If string isn't blank, prepare for title */
		if ( strlen( $subject_line ) > 1 ) {
			$this->Subject = stripslashes( $subject_line );
		}
		/** Otherwise, return failure */

		return false;
	}

	/**
	 * @param null $issuetype
	 *
	 * @return null|string
	 */
	protected function issue_type_label( $issuetype = null ) {
		/** Determines the label via $issuetype */
		if ( null === $issuetype ) {

			return null;
		}

		switch ( $issuetype ) :
			case 'Bug':

			case 'Issue':
				$label = ' #Ticket';
				break;

			case 'Enhancement':

			case 'Question':
				$label = ' #Project';
				break;

			default:
				$label = '';
				break;

		endswitch;

		return $label;
	}

	/**
	 * @param $files
	 *
	 * @throws Exception
	 * @throws phpmailerException
	 */
	public function multiple_attachments( $files ) {
		foreach ( $files['file']['tmp_name'] as $key => $val ) {
			$name = $files['file']['name'][ $key ];
			$path = $val;

			$this->AddAttachment( $path, $name );
		}
	}

	/**
	 * @param string     $file
	 * @param array|null $data
	 *
	 * @return bool|string
	 */
	public function include_html( $file, $data = null ) {
		$email_body = $this->get_include_contents( get_stylesheet_directory() . $file, $data );
		$this->msgHTML( $email_body );

		return $email_body;
	}

	/**
	 * @param       $filename
	 * @param array $data
	 *
	 * @return bool|string
	 */
	function get_include_contents( $filename, $data = array() ) {
		if ( is_array( $data ) ) {
			extract( $data );
		}
		if ( is_file( $filename ) ) {
			ob_start();
			include $filename;

			return ob_get_clean();
		}

		return false;
	}

}
