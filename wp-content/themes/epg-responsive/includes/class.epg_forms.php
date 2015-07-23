<?php
/**
 * Class EPG_Forms
 *
 * @author Chris W. Gerber
 */
class EPG_Forms {

	/**
	 * @var array $supervisors Big array of our supervisors and email address info.
	 */
	public static $supervisors = array(
		array(
			'first_name'   => 'Chris',
			'last_name'    => 'Budzik',
			'email_domain' => 'specialtyim.com'
		),
		array(
			'first_name'   => 'Amy',
			'last_name'    => 'Collins',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Robin',
			'last_name'    => 'Cooper',
			'email_domain' => 'specialtyim.com'
		),
		array(
			'first_name'   => 'Andrew',
			'last_name'    => 'Esham',
			'email_domain' => 'specialtyim.com'
		),
		array(
			'first_name'   => 'Kathy',
			'last_name'    => 'Johnson',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Joanne',
			'last_name'    => 'Juda',
			'email_domain' => 'specialtyim.com'
		),
		array(
			'first_name'   => 'Dave',
			'last_name'    => 'McMahon',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Marion',
			'last_name'    => 'Minor',
			'email_domain' => 'specialtyim.com'
		),
		array(
			'first_name'   => 'Cherri',
			'last_name'    => 'Perschmann',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'John',
			'last_name'    => 'Prusak',
			'email_domain' => 'snowgoer.com'
		),
		array(
			'first_name'   => 'Mark',
			'last_name'    => 'Rosacker',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Terry',
			'last_name'    => 'Roorda',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Angela',
			'last_name'    => 'Schmieg',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Stuart',
			'last_name'    => 'Sutherland',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Jonathan',
			'last_name'    => 'Sweet',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Mark',
			'last_name'    => 'Tuttle',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Dodi',
			'last_name'    => 'Vessels',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'David',
			'last_name'    => 'Voll',
			'email_domain' => 'epgmediallc.com'
		),
		array(
			'first_name'   => 'Gerald',
			'last_name'    => 'Winkel',
			'email_domain' => 'specialtyim.com'
		),
		array(
			'first_name'   => 'Bernadette',
			'last_name'    => 'Wohlman',
			'email_domain' => 'epgmediallc.com'
		)
	);

	public static function epg_form_options( $select_string, $options = null ) {

		if ( $options == null ) {
			$options = self::$supervisors;
		}

		echo '<option selected value="none">' . $select_string . '</option>';

		foreach ( $options as $supervisor ) {
			echo '<option value="' . self::supervisor_email( $supervisor ) . '">' . self::supervisor_name( $supervisor ) . '</option>';
		}
	}

	public static function supervisor_email( $supervisor_array ) {
		return strtolower( $supervisor_array['first_name'][0] . $supervisor_array['last_name'] . '@' . $supervisor_array['email_domain'] );
	}

	public static function supervisor_name( $supervisor_array ) {
		return $supervisor_array['first_name'] . ' ' . $supervisor_array['last_name'];
	}

	public static function died( $error ) {
		// your error code can go here ?>
		<p>
			We are very sorry, but there were error(s) found with the form you submitted. These errors appear below.
		</p>
		<p>
			<?php echo $error; ?>
		</p>
		<p>
			Please go back and fix these errors.
		</p>
		<?php
		die();
	}

}