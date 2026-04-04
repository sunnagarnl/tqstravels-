<?php
/**
 * Plugin Name: TQS Pro Travel Form
 * Plugin URI:  https://tqstravels.com/
 * Description: Professional travel inquiry form with nonce, honeypot, rate limiting, and transient-based error handling.
 * Version:     6.6.2
 * Author:      TQS Travels
 * Text Domain: tqs-pro-travel
 * License:     GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TQS_TRAVEL_EMAIL', 'info@tqstravels.com' );

// ============================================================================
// Dialing Codes
// ============================================================================

/**
 * Returns a flat array of all supported country dialing code strings.
 *
 * @return string[]
 */
function tqs_dialing_codes_flat() {
	return [
		'+93 Afghanistan',
		'+355 Albania',
		'+213 Algeria',
		'+376 Andorra',
		'+244 Angola',
		'+1 Antigua and Barbuda',
		'+54 Argentina',
		'+374 Armenia',
		'+61 Australia',
		'+43 Austria',
		'+994 Azerbaijan',
		'+1 Bahamas',
		'+973 Bahrain',
		'+880 Bangladesh',
		'+1 Barbados',
		'+375 Belarus',
		'+32 Belgium',
		'+501 Belize',
		'+229 Benin',
		'+975 Bhutan',
		'+591 Bolivia',
		'+387 Bosnia and Herzegovina',
		'+267 Botswana',
		'+55 Brazil',
		'+673 Brunei',
		'+359 Bulgaria',
		'+226 Burkina Faso',
		'+257 Burundi',
		'+855 Cambodia',
		'+237 Cameroon',
		'+1 Canada',
		'+238 Cape Verde',
		'+236 Central African Republic',
		'+235 Chad',
		'+56 Chile',
		'+86 China',
		'+57 Colombia',
		'+269 Comoros',
		'+242 Congo',
		'+506 Costa Rica',
		'+385 Croatia',
		'+53 Cuba',
		'+357 Cyprus',
		'+420 Czech Republic',
		'+243 Democratic Republic of the Congo',
		'+45 Denmark',
		'+253 Djibouti',
		'+1 Dominica',
		'+1 Dominican Republic',
		'+593 Ecuador',
		'+20 Egypt',
		'+503 El Salvador',
		'+240 Equatorial Guinea',
		'+291 Eritrea',
		'+372 Estonia',
		'+268 Eswatini',
		'+251 Ethiopia',
		'+679 Fiji',
		'+358 Finland',
		'+33 France',
		'+241 Gabon',
		'+220 Gambia',
		'+995 Georgia',
		'+49 Germany',
		'+233 Ghana',
		'+30 Greece',
		'+1 Grenada',
		'+502 Guatemala',
		'+224 Guinea',
		'+245 Guinea-Bissau',
		'+592 Guyana',
		'+509 Haiti',
		'+504 Honduras',
		'+36 Hungary',
		'+354 Iceland',
		'+91 India',
		'+62 Indonesia',
		'+98 Iran',
		'+964 Iraq',
		'+353 Ireland',
		'+972 Israel',
		'+39 Italy',
		'+1 Jamaica',
		'+81 Japan',
		'+962 Jordan',
		'+7 Kazakhstan',
		'+254 Kenya',
		'+686 Kiribati',
		'+383 Kosovo',
		'+965 Kuwait',
		'+996 Kyrgyzstan',
		'+856 Laos',
		'+371 Latvia',
		'+961 Lebanon',
		'+266 Lesotho',
		'+231 Liberia',
		'+218 Libya',
		'+423 Liechtenstein',
		'+370 Lithuania',
		'+352 Luxembourg',
		'+261 Madagascar',
		'+265 Malawi',
		'+60 Malaysia',
		'+960 Maldives',
		'+223 Mali',
		'+356 Malta',
		'+692 Marshall Islands',
		'+222 Mauritania',
		'+230 Mauritius',
		'+52 Mexico',
		'+691 Micronesia',
		'+373 Moldova',
		'+377 Monaco',
		'+976 Mongolia',
		'+382 Montenegro',
		'+212 Morocco',
		'+258 Mozambique',
		'+95 Myanmar',
		'+264 Namibia',
		'+674 Nauru',
		'+977 Nepal',
		'+31 Netherlands',
		'+64 New Zealand',
		'+505 Nicaragua',
		'+227 Niger',
		'+234 Nigeria',
		'+389 North Macedonia',
		'+47 Norway',
		'+968 Oman',
		'+92 Pakistan',
		'+680 Palau',
		'+507 Panama',
		'+675 Papua New Guinea',
		'+595 Paraguay',
		'+51 Peru',
		'+63 Philippines',
		'+48 Poland',
		'+351 Portugal',
		'+974 Qatar',
		'+40 Romania',
		'+7 Russia',
		'+250 Rwanda',
		'+1 Saint Kitts and Nevis',
		'+1 Saint Lucia',
		'+1 Saint Vincent and the Grenadines',
		'+685 Samoa',
		'+378 San Marino',
		'+239 Sao Tome and Principe',
		'+966 Saudi Arabia',
		'+221 Senegal',
		'+381 Serbia',
		'+248 Seychelles',
		'+232 Sierra Leone',
		'+65 Singapore',
		'+421 Slovakia',
		'+386 Slovenia',
		'+677 Solomon Islands',
		'+252 Somalia',
		'+27 South Africa',
		'+211 South Sudan',
		'+34 Spain',
		'+94 Sri Lanka',
		'+249 Sudan',
		'+597 Suriname',
		'+46 Sweden',
		'+41 Switzerland',
		'+963 Syria',
		'+886 Taiwan',
		'+992 Tajikistan',
		'+255 Tanzania',
		'+66 Thailand',
		'+670 Timor-Leste',
		'+228 Togo',
		'+676 Tonga',
		'+1 Trinidad and Tobago',
		'+216 Tunisia',
		'+90 Turkey',
		'+993 Turkmenistan',
		'+688 Tuvalu',
		'+256 Uganda',
		'+380 Ukraine',
		'+971 United Arab Emirates',
		'+44 United Kingdom',
		'+1 United States',
		'+598 Uruguay',
		'+998 Uzbekistan',
		'+678 Vanuatu',
		'+379 Vatican City',
		'+58 Venezuela',
		'+84 Vietnam',
		'+967 Yemen',
		'+260 Zambia',
		'+263 Zimbabwe',
	];
}

// ============================================================================
// Airport Helpers
// ============================================================================

/**
 * Returns the grouped airport list used to populate the autocomplete.
 *
 * @return array<string, string[]>
 */
function tqs_airport_groups() {
	return [
		'UK & Ireland'              => [
			'London Heathrow (LHR)',
			'London Gatwick (LGW)',
			'London Stansted (STN)',
			'London Luton (LTN)',
			'London City (LCY)',
			'Manchester (MAN)',
			'Birmingham (BHX)',
			'Edinburgh (EDI)',
			'Glasgow (GLA)',
			'Bristol (BRS)',
			'Leeds Bradford (LBA)',
			'Newcastle (NCL)',
			'Liverpool (LPL)',
			'Belfast International (BFS)',
			'Cardiff (CWL)',
			'Dublin (DUB)',
			'Other',
		],
		'Europe'                    => [
			'Amsterdam Schiphol (AMS)',
			'Athens Eleftherios Venizelos (ATH)',
			'Barcelona El Prat (BCN)',
			'Brussels (BRU)',
			'Copenhagen (CPH)',
			'Frankfurt (FRA)',
			'Helsinki (HEL)',
			'Istanbul (IST)',
			'Lisbon (LIS)',
			'Madrid Barajas (MAD)',
			'Milan Malpensa (MXP)',
			'Munich (MUC)',
			'Nice (NCE)',
			'Oslo Gardermoen (OSL)',
			'Paris Charles de Gaulle (CDG)',
			'Paris Orly (ORY)',
			'Prague (PRG)',
			'Rome Fiumicino (FCO)',
			'Stockholm Arlanda (ARN)',
			'Vienna (VIE)',
			'Warsaw Chopin (WAW)',
			'Zurich (ZRH)',
			'Other',
		],
		'Middle East'               => [
			'Abu Dhabi (AUH)',
			'Amman (AMM)',
			'Bahrain (BAH)',
			'Beirut (BEY)',
			'Cairo (CAI)',
			'Doha (DOH)',
			'Dubai (DXB)',
			'Kuwait City (KWI)',
			'Muscat (MCT)',
			'Riyadh (RUH)',
			'Tel Aviv (TLV)',
			'Other',
		],
		'Asia'                      => [
			'Bangkok Suvarnabhumi (BKK)',
			'Beijing Capital (PEK)',
			'Colombo (CMB)',
			'Delhi Indira Gandhi (DEL)',
			'Dhaka (DAC)',
			'Hong Kong (HKG)',
			'Islamabad (ISB)',
			'Jakarta Soekarno-Hatta (CGK)',
			'Karachi (KHI)',
			'Kuala Lumpur (KUL)',
			'Lahore (LHE)',
			'Manila (MNL)',
			'Mumbai (BOM)',
			'Osaka Kansai (KIX)',
			'Seoul Incheon (ICN)',
			'Shanghai Pudong (PVG)',
			'Singapore Changi (SIN)',
			'Tokyo Narita (NRT)',
			'Tokyo Haneda (HND)',
			'Other',
		],
		'North America'             => [
			'Atlanta (ATL)',
			'Boston (BOS)',
			'Calgary (YYC)',
			"Chicago O'Hare (ORD)",
			'Dallas/Fort Worth (DFW)',
			'Houston George Bush (IAH)',
			'Las Vegas (LAS)',
			'Los Angeles (LAX)',
			'Miami (MIA)',
			'Montreal (YUL)',
			'New York JFK (JFK)',
			'New York Newark (EWR)',
			'Orlando (MCO)',
			'San Francisco (SFO)',
			'Seattle (SEA)',
			'Toronto Pearson (YYZ)',
			'Vancouver (YVR)',
			'Washington Dulles (IAD)',
			'Other',
		],
		'Caribbean & Latin America' => [
			'Cancun (CUN)',
			'Mexico City (MEX)',
			'Havana (HAV)',
			'Kingston (KIN)',
			'Nassau (NAS)',
			'Punta Cana (PUJ)',
			'Buenos Aires Ezeiza (EZE)',
			'Sao Paulo Guarulhos (GRU)',
			'Lima (LIM)',
			'Bogota (BOG)',
			'Other',
		],
		'Africa'                    => [
			'Accra (ACC)',
			'Addis Ababa (ADD)',
			'Cape Town (CPT)',
			'Casablanca (CMN)',
			'Dar es Salaam (DAR)',
			'Johannesburg (JNB)',
			'Lagos (LOS)',
			'Nairobi (NBO)',
			'Other',
		],
		'Australia & Pacific'       => [
			'Auckland (AKL)',
			'Brisbane (BNE)',
			'Melbourne (MEL)',
			'Perth (PER)',
			'Sydney (SYD)',
			'Other',
		],
	];
}

/**
 * Normalise an airport value, substituting the custom "Other" entry when needed.
 *
 * @param string $value The selected airport value (may be 'Other').
 * @param string $other The custom airport text entered when 'Other' is selected.
 * @return string
 */
function tqs_normalize_airport_with_other( $value, $other ) {
	if ( $value === 'Other' ) {
		return sanitize_text_field( $other );
	}
	return $value;
}

// ============================================================================
// Error Token
// ============================================================================

/**
 * Initialises the per-visitor error-token cookie used to scope transient errors.
 */
function tqs_init_error_token() {
	if ( ! isset( $_COOKIE['tqs_err_token'] ) ) {
		$token = bin2hex( random_bytes( 16 ) );
		setcookie( 'tqs_err_token', $token, time() + 3600, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
	}
}

// ============================================================================
// Form Submission Handler
// ============================================================================

/**
 * Handles the travel inquiry form submission.
 */
function tqs_handle_submission() {
	if ( ! isset( $_POST['tqs_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tqs_nonce'] ) ), 'tqs_travel_form' ) ) {
		wp_die( 'Invalid request.' );
	}

	// Honeypot
	if ( ! empty( $_POST['website'] ) ) {
		wp_die( 'Invalid request.' );
	}

	// Hoist token early for rate limiting and error key
	$token     = sanitize_text_field( $_COOKIE['tqs_err_token'] ?? '' );
	$error_key = 'tqs_form_error_' . md5( $token );

	// Rate limit: max 5 submissions per session token per 10 minutes
	$rate_key    = 'tqs_rate_' . md5( $token );
	$submissions = (int) get_transient( $rate_key );
	if ( $submissions >= 5 ) {
		wp_die( 'Too many requests. Please wait a few minutes before trying again.', 'Too Many Requests', [ 'response' => 429 ] );
	}
	set_transient( $rate_key, $submissions + 1, 10 * MINUTE_IN_SECONDS );

	$fail = function( $msg ) use ( $error_key ) {
		set_transient( $error_key, $msg, 300 );
		wp_safe_redirect( wp_get_referer() ?: home_url() );
		exit;
	};

	// ---- Travel type -------------------------------------------------------
	$travel = sanitize_text_field( $_POST['travel_type'] ?? '' );
	if ( ! in_array( $travel, [ 'Return', 'One Way', 'Multi City' ], true ) ) {
		$fail( 'Invalid travel type selected.' );
	}

	// ---- Airports (Return / One Way) ---------------------------------------
	$from_raw       = sanitize_text_field( $_POST['from'] ?? '' );
	$from_other_raw = sanitize_text_field( $_POST['from_other'] ?? '' );
	$to_raw         = sanitize_text_field( $_POST['to'] ?? '' );
	$to_other_raw   = sanitize_text_field( $_POST['to_other'] ?? '' );

	if ( $travel !== 'Multi City' ) {
		if ( $from_raw === '' ) $fail( 'Please enter a departure airport.' );
		if ( $to_raw === '' )   $fail( 'Please enter a destination airport.' );
		if ( $from_raw === 'Other' && $from_other_raw === '' ) $fail( 'Please specify your departure airport.' );
		if ( $to_raw === 'Other' && $to_other_raw === '' )     $fail( 'Please specify your destination airport.' );
	}

	$from = tqs_normalize_airport_with_other( $from_raw, $from_other_raw );
	$to   = tqs_normalize_airport_with_other( $to_raw,   $to_other_raw );

	// ---- Dates (Return / One Way) ------------------------------------------
	$depart_raw = sanitize_text_field( $_POST['depart_date'] ?? '' );
	$return_raw = sanitize_text_field( $_POST['return_date'] ?? '' );

	if ( $travel !== 'Multi City' ) {
		if ( $depart_raw === '' ) $fail( 'Please select a departure date.' );
		$depart_ts = strtotime( $depart_raw );
		if ( ! $depart_ts || $depart_ts < strtotime( 'today' ) ) $fail( 'Departure date must be today or in the future.' );

		if ( $travel === 'Return' ) {
			if ( $return_raw === '' ) $fail( 'Please select a return date.' );
			$return_ts = strtotime( $return_raw );
			if ( ! $return_ts || $return_ts <= $depart_ts ) $fail( 'Return date must be after the departure date.' );
		}
	}

	// ---- Flexibility -------------------------------------------------------
	$flexibility = sanitize_text_field( $_POST['flexibility'] ?? '' );
	if ( $travel !== 'Multi City' ) {
		if ( ! in_array( $flexibility, [ 'Fixed Dates', 'Flexible +/- 1 Day', 'Flexible +/- 3 Days', 'Flexible +/- 7 Days' ], true ) ) {
			$fail( 'Please select a date flexibility option.' );
		}
	}

	// ---- Passengers --------------------------------------------------------
	$adults   = (int) ( $_POST['adults']   ?? 0 );
	$children = (int) ( $_POST['children'] ?? 0 );
	$infants  = (int) ( $_POST['infants']  ?? 0 );

	if ( $adults < 1 )                            $fail( 'At least one adult passenger is required.' );
	if ( $infants > $adults )                     $fail( 'Number of infants cannot exceed the number of adults.' );
	if ( ( $adults + $children + $infants ) > 9 ) $fail( 'Total passengers cannot exceed 9.' );

	// ---- Multi City --------------------------------------------------------
	$mc_froms = [];
	$mc_tos   = [];
	$mc_dates = [];

	if ( $travel === 'Multi City' ) {
		$mc_from_raw = isset( $_POST['mc_from'] ) && is_array( $_POST['mc_from'] ) ? $_POST['mc_from'] : [];
		$mc_to_raw   = isset( $_POST['mc_to'] )   && is_array( $_POST['mc_to'] )   ? $_POST['mc_to']   : [];
		$mc_date_raw = isset( $_POST['mc_date'] ) && is_array( $_POST['mc_date'] ) ? $_POST['mc_date'] : [];

		$mc_from_raw = array_values( array_filter( array_map( 'sanitize_text_field', $mc_from_raw ) ) );
		$mc_to_raw   = array_values( array_filter( array_map( 'sanitize_text_field', $mc_to_raw ) ) );
		$mc_date_raw = array_values( array_filter( array_map( 'sanitize_text_field', $mc_date_raw ) ) );

		if ( count( $mc_from_raw ) < 2 ) $fail( 'Multi City trips require at least 2 routes.' );
		if ( count( $mc_from_raw ) !== count( $mc_to_raw ) || count( $mc_from_raw ) !== count( $mc_date_raw ) ) {
			$fail( 'Incomplete Multi City route data.' );
		}

		$prev_ts = 0;
		foreach ( $mc_from_raw as $i => $mc_from ) {
			if ( $mc_from === 'Other' ) $fail( 'Multi City routes do not support custom airports. Please select from the list.' );
			$mc_to = $mc_to_raw[ $i ];
			if ( $mc_to === 'Other' )   $fail( 'Multi City routes do not support custom airports. Please select from the list.' );

			$mc_date = $mc_date_raw[ $i ];
			$mc_ts   = strtotime( $mc_date );
			if ( ! $mc_ts ) $fail( 'Invalid date for route ' . ( $i + 1 ) . '.' );
			if ( $i === 0 && $mc_ts < strtotime( 'today' ) ) $fail( 'The first route date must be today or in the future.' );
			if ( $mc_ts <= $prev_ts ) $fail( 'Multi City dates must be in chronological order.' );
			$prev_ts = $mc_ts;

			$mc_froms[] = tqs_normalize_airport_with_other( $mc_from, '' );
			$mc_tos[]   = tqs_normalize_airport_with_other( $mc_to,   '' );
			$mc_dates[] = $mc_date;
		}
	}

	// ---- Contact details ---------------------------------------------------
	$name  = sanitize_text_field( $_POST['full_name'] ?? '' );
	$email = sanitize_email( $_POST['email'] ?? '' );
	$phone = sanitize_text_field( $_POST['phone'] ?? '' );

	$country_code_raw = sanitize_text_field( $_POST['country_code'] ?? '' );
	if ( $country_code_raw !== '' && ! in_array( $country_code_raw, tqs_dialing_codes_flat(), true ) ) {
		$fail( 'Invalid country code selected.' );
	}
	if ( $country_code_raw === '' ) $fail( 'Please select a country code.' );

	if ( strlen( $name )  > 100 ) $fail( 'Name is too long.' );
	if ( strlen( $email ) > 100 ) $fail( 'Email address is too long.' );
	if ( strlen( $phone ) > 20  ) $fail( 'Phone number is too long.' );

	if ( $name === '' )          $fail( 'Please enter your full name.' );
	if ( ! is_email( $email ) ) $fail( 'Please enter a valid email address.' );
	if ( $phone === '' )         $fail( 'Please enter your phone number.' );

	preg_match( '/^\+(\d+)\s/', $country_code_raw, $dial_match );
	$country_digits = $dial_match[1] ?? '';

	$notes = sanitize_textarea_field( $_POST['notes'] ?? '' );
	if ( strlen( $notes ) > 1000 ) $fail( 'Special requests text is too long.' );

	// ---- Cabin class -------------------------------------------------------
	$cabin_class = sanitize_text_field( $_POST['cabin_class'] ?? 'Economy' );
	if ( ! in_array( $cabin_class, [ 'Economy', 'Premium Economy', 'Business', 'First' ], true ) ) {
		$cabin_class = 'Economy';
	}

	// ---- Build email -------------------------------------------------------
	if ( $travel === 'Multi City' ) {
		$routes_text = '';
		foreach ( $mc_froms as $i => $mc_from ) {
			$routes_text .= 'Route ' . ( $i + 1 ) . ': ' . $mc_from . ' to ' . $mc_tos[ $i ] . ' on ' . $mc_dates[ $i ] . "\n";
		}
		$trip_details = "Trip Type: Multi City\n\nRoutes:\n" . $routes_text;
	} else {
		$trip_details  = "Trip Type: {$travel}\n";
		$trip_details .= "From: {$from}\n";
		$trip_details .= "To: {$to}\n";
		$trip_details .= "Departure: {$depart_raw}\n";
		if ( $travel === 'Return' ) {
			$trip_details .= "Return: {$return_raw}\n";
		}
		$trip_details .= "Flexibility: {$flexibility}\n";
	}

	$pax_details  = "Adults: {$adults}\n";
	$pax_details .= "Children: {$children}\n";
	$pax_details .= "Infants: {$infants}\n";
	$pax_details .= "Cabin Class: {$cabin_class}\n";

	$contact_details  = "Name: {$name}\n";
	$contact_details .= "Email: {$email}\n";
	$contact_details .= "Phone: +{$country_digits} {$phone}\n";

	$message  = "New Travel Inquiry\n";
	$message .= "==================\n\n";
	$message .= $trip_details . "\n";
	$message .= "Passengers\n";
	$message .= "----------\n";
	$message .= $pax_details . "\n";
	$message .= "Contact\n";
	$message .= "-------\n";
	$message .= $contact_details;

	if ( $notes ) {
		$message .= "\nSpecial Requests\n";
		$message .= "----------------\n";
		$message .= $notes . "\n";
	}

	$subject = "Travel Inquiry from {$name} ({$travel})";
	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	];

	// ---- Send --------------------------------------------------------------
	$sent = wp_mail( TQS_TRAVEL_EMAIL, $subject, $message, $headers );
	if ( ! $sent ) {
		error_log( 'TQS Travel Form: wp_mail failed for inquiry from ' . $email . ' at ' . current_time( 'mysql' ) );
		$fail( 'We could not send your request right now. Please try again later.' );
	}

	// Success
	delete_transient( $error_key );
	wp_safe_redirect( add_query_arg( 'tqs_sent', '1', wp_get_referer() ?: home_url() ) );
	exit;
}

// ============================================================================
// Form Renderer
// ============================================================================

/**
 * Renders the travel inquiry form shortcode output.
 *
 * @return string HTML output.
 */
function tqs_render_form() {
	tqs_init_error_token();

	$token     = sanitize_text_field( $_COOKIE['tqs_err_token'] ?? '' );
	$error_key = 'tqs_form_error_' . md5( $token );
	$error_msg = get_transient( $error_key );
	if ( $error_msg ) {
		delete_transient( $error_key );
	}

	$sent = isset( $_GET['tqs_sent'] ) && $_GET['tqs_sent'] === '1';

	$airport_groups_json = wp_json_encode( tqs_airport_groups() );
	$dial_codes_json     = wp_json_encode( tqs_dialing_codes_flat() );

	$action = esc_url( get_permalink() ?: home_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ) ) ) );

	ob_start();
	?>
	<div class="tqs-travel-form-wrap">
		<?php if ( $sent ) : ?>
		<div class="tqs-notice tqs-notice--success">
			<p>Thank you! Your travel inquiry has been sent. We'll be in touch shortly.</p>
		</div>
		<?php endif; ?>
		<?php if ( $error_msg ) : ?>
		<div class="tqs-notice tqs-notice--error">
			<p><?php echo esc_html( $error_msg ); ?></p>
		</div>
		<?php endif; ?>

		<form id="tqs-travel-form" method="post" action="<?php echo $action; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already esc_url'd above ?>">
			<?php wp_nonce_field( 'tqs_travel_form', 'tqs_nonce' ); ?>

			<!-- Honeypot -->
			<div style="display:none;" aria-hidden="true">
				<label for="tqs-website">Website</label>
				<input type="text" id="tqs-website" name="website" tabindex="-1" autocomplete="off">
			</div>

			<!-- Trip Type -->
			<fieldset class="tqs-field-group tqs-trip-type">
				<legend>Trip Type</legend>
				<label><input type="radio" name="travel_type" value="Return" checked> Return</label>
				<label><input type="radio" name="travel_type" value="One Way"> One Way</label>
				<label><input type="radio" name="travel_type" value="Multi City"> Multi City</label>
			</fieldset>

			<!-- Airports -->
			<div class="tqs-field-group tqs-airports" id="tqs-airports">
				<div class="tqs-field">
					<label for="tqs-from">Flying From</label>
					<input type="text" id="tqs-from" name="from" placeholder="e.g. London Heathrow (LHR)" maxlength="100" autocomplete="off" required>
					<input type="text" id="tqs-from-other" name="from_other" placeholder="Enter airport name" maxlength="100" style="display:none;">
				</div>
				<div class="tqs-field">
					<label for="tqs-to">Flying To</label>
					<input type="text" id="tqs-to" name="to" placeholder="e.g. Dubai (DXB)" maxlength="100" autocomplete="off" required>
					<input type="text" id="tqs-to-other" name="to_other" placeholder="Enter airport name" maxlength="100" style="display:none;">
				</div>
			</div>

			<!-- Dates -->
			<div class="tqs-field-group tqs-dates" id="tqs-dates">
				<div class="tqs-field">
					<label for="tqs-depart">Departure Date</label>
					<input type="date" id="tqs-depart" name="depart_date" required>
				</div>
				<div class="tqs-field" id="tqs-return-wrap">
					<label for="tqs-return">Return Date</label>
					<input type="date" id="tqs-return" name="return_date">
				</div>
			</div>

			<!-- Flexibility -->
			<fieldset class="tqs-field-group tqs-flexibility" id="tqs-flexibility">
				<legend>Date Flexibility</legend>
				<label><input type="radio" name="flexibility" value="Fixed Dates" checked> Fixed Dates</label>
				<label><input type="radio" name="flexibility" value="Flexible +/- 1 Day"> Flexible +/- 1 Day</label>
				<label><input type="radio" name="flexibility" value="Flexible +/- 3 Days"> Flexible +/- 3 Days</label>
				<label><input type="radio" name="flexibility" value="Flexible +/- 7 Days"> Flexible +/- 7 Days</label>
			</fieldset>

			<!-- Multi City Routes -->
			<div class="tqs-field-group" id="tqs-multicity" style="display:none;">
				<h3>Routes</h3>
				<div id="tqs-routes">
					<div class="tqs-route" data-index="0">
						<span class="tqs-route-label">Route 1</span>
						<div class="tqs-field">
							<label>From</label>
							<input type="text" name="mc_from[]" placeholder="Departure airport" maxlength="100" autocomplete="off">
						</div>
						<div class="tqs-field">
							<label>To</label>
							<input type="text" name="mc_to[]" placeholder="Destination airport" maxlength="100" autocomplete="off">
						</div>
						<div class="tqs-field">
							<label>Date</label>
							<input type="date" name="mc_date[]">
						</div>
					</div>
					<div class="tqs-route" data-index="1">
						<span class="tqs-route-label">Route 2</span>
						<div class="tqs-field">
							<label>From</label>
							<input type="text" name="mc_from[]" placeholder="Departure airport" maxlength="100" autocomplete="off">
						</div>
						<div class="tqs-field">
							<label>To</label>
							<input type="text" name="mc_to[]" placeholder="Destination airport" maxlength="100" autocomplete="off">
						</div>
						<div class="tqs-field">
							<label>Date</label>
							<input type="date" name="mc_date[]">
						</div>
					</div>
				</div>
				<button type="button" id="tqs-add-route">+ Add Another Route</button>
			</div>

			<!-- Passengers -->
			<div class="tqs-field-group tqs-passengers">
				<div class="tqs-field">
					<label for="tqs-adults">Adults</label>
					<input type="number" id="tqs-adults" name="adults" value="1" min="1" max="9" required>
				</div>
				<div class="tqs-field">
					<label for="tqs-children">Children (2–11)</label>
					<input type="number" id="tqs-children" name="children" value="0" min="0" max="8">
				</div>
				<div class="tqs-field">
					<label for="tqs-infants">Infants (under 2)</label>
					<input type="number" id="tqs-infants" name="infants" value="0" min="0" max="9">
				</div>
			</div>

			<!-- Cabin Class -->
			<div class="tqs-field-group">
				<div class="tqs-field">
					<label for="tqs-cabin">Cabin Class</label>
					<select id="tqs-cabin" name="cabin_class">
						<option value="Economy" selected>Economy</option>
						<option value="Premium Economy">Premium Economy</option>
						<option value="Business">Business</option>
						<option value="First">First</option>
					</select>
				</div>
			</div>

			<!-- Contact -->
			<div class="tqs-field-group tqs-contact">
				<div class="tqs-field">
					<label for="tqs-name">Full Name</label>
					<input type="text" id="tqs-name" name="full_name" maxlength="100" required>
				</div>
				<div class="tqs-field">
					<label for="tqs-email">Email Address</label>
					<input type="email" id="tqs-email" name="email" maxlength="100" required>
				</div>
				<div class="tqs-field tqs-phone-wrap">
					<label for="tqs-country-code-input">Country Code</label>
					<div class="tqs-country-code-wrap">
						<input type="text" id="tqs-country-code-input" placeholder="Search country..." autocomplete="off" maxlength="60">
						<input type="hidden" id="tqs-country-code" name="country_code">
						<ul id="tqs-country-code-list" role="listbox" style="display:none;"></ul>
					</div>
					<label for="tqs-phone">Phone Number</label>
					<input type="tel" id="tqs-phone" name="phone" maxlength="20" required>
				</div>
			</div>

			<!-- Notes -->
			<div class="tqs-field-group">
				<div class="tqs-field">
					<label for="tqs-notes">Special Requests (optional)</label>
					<textarea id="tqs-notes" name="notes" rows="4" maxlength="1000"></textarea>
				</div>
			</div>

			<div class="tqs-buttons">
				<button type="submit" id="tqs-submit">Send Inquiry</button>
				<button type="reset">Reset Form</button>
			</div>
		</form>
	</div>

	<script>
	(function(){
		var airportGroups = <?php echo $airport_groups_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded ?>;
		var dialCodes     = <?php echo $dial_codes_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded ?>;
	</script>
	<?php
	return ob_get_clean();
}

// ============================================================================
// JS Assets
// ============================================================================

/**
 * Outputs the inline JavaScript for the travel form.
 */
function tqs_assets_js() {
	if ( ! is_page() && ! is_singular() ) return;
	global $post;
	if ( ! $post || ! has_shortcode( $post->post_content, 'tqs_travel_form' ) ) return;
	?>
	<script>
	(function () {
		'use strict';

		var form = document.getElementById('tqs-travel-form');
		if ( ! form ) return;

		var today = new Date().toISOString().split('T')[0];

		// Set min date on all date inputs
		form.querySelectorAll('input[type="date"]').forEach(function (inp) {
			inp.setAttribute('min', today);
		});

		// ----------------------------------------------------------------
		// Trip UI
		// ----------------------------------------------------------------
		function updateTripUI() {
			var travel = form.querySelector('input[name="travel_type"]:checked');
			if ( ! travel ) return;
			var type = travel.value;

			var returnWrap   = document.getElementById('tqs-return-wrap');
			var flexFieldset = document.getElementById('tqs-flexibility');
			var airports     = document.getElementById('tqs-airports');
			var dates        = document.getElementById('tqs-dates');
			var multicity    = document.getElementById('tqs-multicity');

			var isReturn    = type === 'Return';
			var isOneWay    = type === 'One Way';
			var isMultiCity = type === 'Multi City';

			airports.style.display    = isMultiCity ? 'none' : '';
			dates.style.display       = isMultiCity ? 'none' : '';
			multicity.style.display   = isMultiCity ? '' : 'none';
			flexFieldset.style.display = isMultiCity ? 'none' : '';

			if ( returnWrap ) {
				returnWrap.style.display = isReturn ? '' : 'none';
				var returnInp = document.getElementById('tqs-return');
				if ( returnInp ) returnInp.required = isReturn;
			}

			var fromInp   = document.getElementById('tqs-from');
			var toInp     = document.getElementById('tqs-to');
			var departInp = document.getElementById('tqs-depart');
			if ( fromInp )   fromInp.required   = ! isMultiCity;
			if ( toInp )     toInp.required     = ! isMultiCity;
			if ( departInp ) departInp.required = ! isMultiCity;

			// Flexibility radios required for Return / One Way only
			form.querySelectorAll('input[name="flexibility"]').forEach(function (r) {
				r.required = ! isMultiCity;
			});
		}

		form.querySelectorAll('input[name="travel_type"]').forEach(function (r) {
			r.addEventListener('change', updateTripUI);
		});
		updateTripUI();

		// ----------------------------------------------------------------
		// Airport Autocomplete
		// ----------------------------------------------------------------
		function buildAirportList() {
			var all = [];
			if ( typeof airportGroups !== 'undefined' ) {
				Object.keys(airportGroups).forEach(function (group) {
					airportGroups[group].forEach(function (airport) {
						all.push({ group: group, value: airport });
					});
				});
			}
			return all;
		}

		var allAirports = buildAirportList();

		function toggleOtherAirport(inp) {
			var otherId = inp.id + '-other';
			var otherInp = document.getElementById(otherId);
			if ( ! otherInp ) return;
			var isOther = inp.value.toLowerCase() === 'other';
			otherInp.style.display = isOther ? '' : 'none';
			otherInp.required      = isOther;
		}

		function appendAirportItem(list, airport, inp) {
			var li = document.createElement('li');
			li.textContent = airport.value;
			li.setAttribute('role', 'option');
			li.setAttribute('tabindex', '-1');
			li.dataset.value = airport.value;
			li.addEventListener('mousedown', function (e) {
				e.preventDefault();
				inp.value = airport.value;
				list.style.display = 'none';
				toggleOtherAirport(inp);
				inp.dispatchEvent(new Event('change', { bubbles: true }));
			});
			list.appendChild(li);
		}

		function setupAirportAutocomplete(inp) {
			var list = document.createElement('ul');
			list.className  = 'tqs-airport-list';
			list.style.display = 'none';
			list.setAttribute('role', 'listbox');
			inp.parentNode.insertBefore(list, inp.nextSibling);

			inp.addEventListener('input', function () {
				var q = inp.value.toLowerCase().trim();
				list.innerHTML = '';
				if ( q.length < 1 ) { list.style.display = 'none'; return; }

				var matches = allAirports.filter(function (a) {
					return a.value.toLowerCase().indexOf(q) !== -1;
				});
				if ( ! matches.length ) { list.style.display = 'none'; return; }

				var lastGroup = '';
				matches.slice(0, 20).forEach(function (airport) {
					if ( airport.group !== lastGroup ) {
						var lbl = document.createElement('li');
						lbl.className   = 'tqs-airport-group';
						lbl.textContent = airport.group;
						lbl.setAttribute('aria-disabled', 'true');
						list.appendChild(lbl);
						lastGroup = airport.group;
					}
					appendAirportItem(list, airport, inp);
				});
				list.style.display = '';
			});

			inp.addEventListener('change', function () {
				toggleOtherAirport(inp);
			});

			inp.addEventListener('keydown', function (e) {
				if ( e.key === 'Escape' ) {
					list.style.display = 'none';
				}
			});

			inp.addEventListener('blur', function () {
				setTimeout(function () { list.style.display = 'none'; }, 150);
			});
		}

		['tqs-from', 'tqs-to'].forEach(function (id) {
			var inp = document.getElementById(id);
			if ( inp ) setupAirportAutocomplete(inp);
		});

		// ----------------------------------------------------------------
		// Multi City Airport Autocomplete
		// ----------------------------------------------------------------
		function setupMultiCityAutocomplete(route) {
			route.querySelectorAll('input[name="mc_from[]"], input[name="mc_to[]"]').forEach(function (inp) {
				setupAirportAutocomplete(inp);
			});
		}

		document.querySelectorAll('.tqs-route').forEach(setupMultiCityAutocomplete);

		var addRouteBtn = document.getElementById('tqs-add-route');
		if ( addRouteBtn ) {
			addRouteBtn.addEventListener('click', function () {
				var routes    = document.getElementById('tqs-routes');
				var existing  = routes.querySelectorAll('.tqs-route');
				var idx       = existing.length;
				var tpl       = existing[0].cloneNode(true);
				tpl.dataset.index = idx;
				tpl.querySelector('.tqs-route-label').textContent = 'Route ' + ( idx + 1 );
				tpl.querySelectorAll('input').forEach(function (inp) { inp.value = ''; });
				// Remove any leftover autocomplete lists
				tpl.querySelectorAll('.tqs-airport-list').forEach(function (l) { l.remove(); });
				routes.appendChild(tpl);
				setupMultiCityAutocomplete(tpl);
			});
		}

		// ----------------------------------------------------------------
		// Country Code Searchable Dropdown
		// ----------------------------------------------------------------
		var ccInput  = document.getElementById('tqs-country-code-input');
		var ccHidden = document.getElementById('tqs-country-code');
		var ccList   = document.getElementById('tqs-country-code-list');

		if ( ccInput && ccHidden && ccList ) {
			ccInput.addEventListener('input', function () {
				var q = ccInput.value.toLowerCase().trim();
				ccList.innerHTML = '';
				if ( ! q ) { ccList.style.display = 'none'; return; }

				var matches = (typeof dialCodes !== 'undefined' ? dialCodes : []).filter(function (c) {
					return c.toLowerCase().indexOf(q) !== -1;
				});
				if ( ! matches.length ) { ccList.style.display = 'none'; return; }

				matches.slice(0, 15).forEach(function (code) {
					var li = document.createElement('li');
					li.textContent = code;
					li.setAttribute('role', 'option');
					li.setAttribute('tabindex', '-1');
					li.addEventListener('mousedown', function (e) {
						e.preventDefault();
						ccInput.value  = code;
						ccHidden.value = code;
						ccList.style.display = 'none';
					});
					ccList.appendChild(li);
				});
				ccList.style.display = '';
			});

			ccInput.addEventListener('keydown', function (e) {
				if ( e.key === 'Escape' ) ccList.style.display = 'none';
			});

			ccInput.addEventListener('blur', function () {
				setTimeout(function () { ccList.style.display = 'none'; }, 150);
			});
		}

		// ----------------------------------------------------------------
		// Form Submit — disable button to prevent double-submit
		// ----------------------------------------------------------------
		form.addEventListener('submit', function () {
			var btn = document.getElementById('tqs-submit');
			if ( btn ) btn.disabled = true;
		});

		// Re-enable on back-navigation (bfcache)
		window.addEventListener('pageshow', function (e) {
			if ( e.persisted ) {
				var btn = document.getElementById('tqs-submit');
				if ( btn ) btn.disabled = false;
			}
		});

		// ----------------------------------------------------------------
		// Reset handler
		// ----------------------------------------------------------------
		form.addEventListener('reset', function () {
			setTimeout(function () {
				updateTripUI();
				const defaultFlex = form.querySelector('input[name="flexibility"][value="Fixed Dates"]');
				if (defaultFlex) defaultFlex.checked = true;
			}, 0);
		});

	}());
	</script>
	<?php
}

// ============================================================================
// Hooks
// ============================================================================

add_action( 'init', 'tqs_init_error_token' );

add_action( 'template_redirect', function () {
	if ( isset( $_POST['tqs_nonce'] ) ) {
		tqs_handle_submission();
	}
} );

add_shortcode( 'tqs_travel_form', 'tqs_render_form' );

add_action( 'wp_footer', 'tqs_assets_js' );
