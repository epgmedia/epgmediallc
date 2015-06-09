<?php

include_once( ABSPATH . '/wp-includes/class-phpmailer.php' );

class epg_phpmailer extends phpmailer {

	/** Clean the "From" address */
	public function from_address( $string ) {
		$this->From = $string;
		$bad        = array( "content-type", "bcc:", "to:", "cc:", "href" );

		return str_replace( $bad, "", $this->From );
	}

	/**
	 * @param array $email_array
	 */
	public function it_request_recipients( $email_array ) {
		foreach ( $email_array as $recipient ) {
			$this->addAnAddress( $recipient['kind'], $recipient['address'],
			                     $recipient['name'] );
		}
	}

	/** Creates the full email subject */
	public function email_subject( $subject = NULL, $issuetype = NULL, $realm = NULL ) {
		$subject_line = 'Ticket: ';
		/** Default Subject / Title */
		if ( NULL !== $subject ) {
			$subject_line .= $subject;
		}
		/** Set the Issue Label */
		$subject_line .= $this->issue_type_label( $issuetype );
		/** Set type label */
		if ( NULL !== $realm ) {
			$subject_line .= ' #' . $realm;
		}
		/** If string isn't blank, prepare for title */
		if ( strlen( $subject_line ) > 1 ) {
			$this->Subject = stripslashes( $subject_line );
		}

		/** Otherwise, return failure */

		return FALSE;
	}

	protected function issue_type_label( $issuetype = NULL ) {
		/** Determines the label via $issuetype */
		if ( NULL === $issuetype ) {

			return NULL;
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


	public function multiple_attachments( $files ) {
		foreach ( $files['file']['tmp_name'] as $key => $val ) {
			$name = $files['file']['name'][ $key ];
			$path = $val;

			$this->AddAttachment( $path, $name );
		}
	}
}

function get_include_contents( $filename, $data = array() ) {
	extract( $data );
	if ( is_file( $filename ) ) {
		ob_start();
		include $filename;

		return ob_get_clean();
	}

	return FALSE;
}