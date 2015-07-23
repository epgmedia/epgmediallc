<?php

/**
 * Abstract WP_Job_Manager_Form class.
 *
 * @abstract
 */
abstract class WP_Job_Manager_Form {

	protected $fields    = array();
	protected $action    = '';
	protected $errors    = array();
	protected $steps     = array();
	protected $step      = 0;
	public    $form_name = '';

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__ );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__ );
	}

	/**
	 * Process function. all processing code if needed - can also change view if step is complete
	 */
	public function process() {
		$keys = array_keys( $this->steps );

		if ( isset( $keys[ $this->step ] ) && is_callable( $this->steps[ $keys[ $this->step ] ]['handler'] ) ) {
			call_user_func( $this->steps[ $keys[ $this->step ] ]['handler'] );
		}
	}

	/**
	 * output function. Call the view handler.
	 */
	public function output( $atts = array() ) {
		$keys = array_keys( $this->steps );

		$this->show_errors();

		if ( isset( $keys[ $this->step ] ) && is_callable( $this->steps[ $keys[ $this->step ] ]['view'] ) ) {
			call_user_func( $this->steps[ $keys[ $this->step ] ]['view'], $atts );
		}
	}

	/**
	 * Add an error
	 * @param string $error
	 */
	public function add_error( $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Show errors
	 */
	public function show_errors() {
		foreach ( $this->errors as $error ) {
			echo '<div class="job-manager-error">' . $error . '</div>';
		}
	}

	/**
	 * Get action (URL for forms to post to).
	 * As of 1.22.2 this defaults to the current page permalink.
	 *
	 * @return string
	 */
	public function get_action() {
		return esc_url_raw( $this->action ? $this->action : wp_unslash( $_SERVER['REQUEST_URI'] ) );
	}

	/**
	 * Get step from outside of the class
	 */
	public function get_step() {
		return $this->step;
	}

	/**
	 * Increase step from outside of the class
	 */
	public function next_step() {
		$this->step ++;
	}

	/**
	 * Decrease step from outside of the class
	 */
	public function previous_step() {
		$this->step --;
	}

	/**
	 * get_fields function.
	 *
	 * @param string $key
	 * @return array
	 */
	public function get_fields( $key ) {
		if ( empty( $this->fields[ $key ] ) ) {
			return array();
		}

		$fields = $this->fields[ $key ];

		uasort( $fields, array( $this, 'sort_by_priority' ) );

		return $fields;
	}

	/**
	 * Sort array by priority value
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	protected function sort_by_priority( $a, $b ) {
	    if ( $a['priority'] == $b['priority'] ) {
	        return 0;
	    }
	    return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
	}

	/**
	 * Init form fields
	 */
	protected function init_fields() {
		$this->fields = array();
	}

	/**
	 * Get post data for fields
	 *
	 * @return array of data
	 */
	protected function get_posted_fields() {
		$this->init_fields();

		$values = array();

		foreach ( $this->fields as $group_key => $group_fields ) {
			foreach ( $group_fields as $key => $field ) {
				// Get the value
				$field_type = str_replace( '-', '_', $field['type'] );

				if ( $handler = apply_filters( "job_manager_get_posted_{$field_type}_field", false ) ) {
					$values[ $group_key ][ $key ] = call_user_func( $handler, $key, $field );
				} elseif ( method_exists( $this, "get_posted_{$field_type}_field" ) ) {
					$values[ $group_key ][ $key ] = call_user_func( array( $this, "get_posted_{$field_type}_field" ), $key, $field );
				} else {
					$values[ $group_key ][ $key ] = $this->get_posted_field( $key, $field );
				}

				// Set fields value
				$this->fields[ $group_key ][ $key ]['value'] = $values[ $group_key ][ $key ];
			}
		}

		return $values;
	}

	/**
	 * Navigates through an array and sanitizes the field.
	 *
	 * @param array|string $value The array or string to be sanitized.
	 * @return array|string $value The sanitized array (or string from the callback).
	 */
	protected function sanitize_posted_field( $value ) {
		// Decode URLs
		if ( is_string( $value ) && ( strstr( $value, 'http:' ) || strstr( $value, 'https:' ) ) ) {
			$value = urldecode( $value );
		}

		// Santize value
		$value = is_array( $value ) ? array_map( array( $this, 'sanitize_posted_field' ), $value ) : sanitize_text_field( stripslashes( trim( $value ) ) );

		return $value;
	}

	/**
	 * Get the value of a posted field
	 * @param  string $key
	 * @param  array $field
	 * @return string|array
	 */
	protected function get_posted_field( $key, $field ) {
		return isset( $_POST[ $key ] ) ? $this->sanitize_posted_field( $_POST[ $key ] ) : '';
	}

	/**
	 * Get the value of a posted multiselect field
	 * @param  string $key
	 * @param  array $field
	 * @return array
	 */
	protected function get_posted_multiselect_field( $key, $field ) {
		return isset( $_POST[ $key ] ) ? array_map( 'sanitize_text_field', $_POST[ $key ] ) : array();
	}

	/**
	 * Get the value of a posted file field
	 * @param  string $key
	 * @param  array $field
	 * @return string|array
	 */
	protected function get_posted_file_field( $key, $field ) {
		$file = $this->upload_file( $key, $field );

		if ( ! $file ) {
			$file = $this->get_posted_field( 'current_' . $key, $field );
		} elseif ( is_array( $file ) ) {
			$file = array_filter( array_merge( $file, (array) $this->get_posted_field( 'current_' . $key, $field ) ) );
		}

		return $file;
	}

	/**
	 * Get the value of a posted textarea field
	 * @param  string $key
	 * @param  array $field
	 * @return string
	 */
	protected function get_posted_textarea_field( $key, $field ) {
		return isset( $_POST[ $key ] ) ? wp_kses_post( trim( stripslashes( $_POST[ $key ] ) ) ) : '';
	}

	/**
	 * Get the value of a posted textarea field
	 * @param  string $key
	 * @param  array $field
	 * @return string
	 */
	protected function get_posted_wp_editor_field( $key, $field ) {
		return $this->get_posted_textarea_field( $key, $field );
	}

	/**
	 * Get posted terms for the taxonomy
	 * @param  string $key
	 * @param  array $field
	 * @return array
	 */
	protected function get_posted_term_checklist_field( $key, $field ) {
		if ( isset( $_POST[ 'tax_input' ] ) && isset( $_POST[ 'tax_input' ][ $field['taxonomy'] ] ) ) {
			return array_map( 'absint', $_POST[ 'tax_input' ][ $field['taxonomy'] ] );
		} else {
			return array();
		}
	}

	/**
	 * Get posted terms for the taxonomy
	 * @param  string $key
	 * @param  array $field
	 * @return int
	 */
	protected function get_posted_term_multiselect_field( $key, $field ) {
		return isset( $_POST[ $key ] ) ? array_map( 'absint', $_POST[ $key ] ) : array();
	}

	/**
	 * Get posted terms for the taxonomy
	 * @param  string $key
	 * @param  array $field
	 * @return int
	 */
	protected function get_posted_term_select_field( $key, $field ) {
		return ! empty( $_POST[ $key ] ) && $_POST[ $key ] > 0 ? absint( $_POST[ $key ] ) : '';
	}

	/**
	 * Upload a file
	 * @return  string or array
	 */
	protected function upload_file( $field_key, $field ) {
		if ( isset( $_FILES[ $field_key ] ) && ! empty( $_FILES[ $field_key ] ) && ! empty( $_FILES[ $field_key ]['name'] ) ) {
			if ( ! empty( $field['allowed_mime_types'] ) ) {
				$allowed_mime_types = $field['allowed_mime_types'];
			} else {
				$allowed_mime_types = get_allowed_mime_types();
			}

			$file_urls       = array();
			$files_to_upload = job_manager_prepare_uploaded_files( $_FILES[ $field_key ] );

			foreach ( $files_to_upload as $file_to_upload ) {
				$uploaded_file = job_manager_upload_file( $file_to_upload, array( 'file_key' => $field_key ) );

				if ( is_wp_error( $uploaded_file ) ) {
					throw new Exception( $uploaded_file->get_error_message() );
				} else {
					$file_urls[] = $uploaded_file->url;
				}
			}

			if ( ! empty( $field['multiple'] ) ) {
				return $file_urls;
			} else {
				return current( $file_urls );
			}
		}
	}
}