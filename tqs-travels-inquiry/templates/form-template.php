<?php
if ( ! defined( 'ABSPATH' ) ) exit;
require_once plugin_dir_path( __FILE__ ) . '../includes/tqs-airports.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/tqs-dial-codes.php';

$airports   = tqs_get_airports();
$dial_codes = tqs_get_dial_codes();

$pax_adult  = intval( $_POST['tqs_adults']  ?? 1 );
$pax_kids   = intval( $_POST['tqs_kids']    ?? 0 );
$pax_infant = intval( $_POST['tqs_infants'] ?? 0 );

$sel_phone_code = sanitize_text_field( wp_unslash( $_POST['tqs_phone_code'] ?? '+31' ) );
$sel_wa_code    = sanitize_text_field( wp_unslash( $_POST['tqs_wa_code']    ?? '+31' ) );
$wa_same        = isset( $_POST['tqs_submit'] ) ? isset( $_POST['tqs_wa_same'] ) : true;
$sel_type       = sanitize_text_field( wp_unslash( $_POST['tqs_travel_type'] ?? 'oneway' ) );
?>

<div class="tqs-form-wrapper">
    <div class="tqs-form-header">
        <h2>Plan Your Dream Trip with TQS Travels</h2>
        <p>Fill in your travel requirements and our team will get back to you within 24 hours.</p>
    </div>

    <form method="POST" class="tqs-inquiry-form" id="tqs-main-form" novalidate>
        <?php wp_nonce_field( 'tqs_inquiry_action', 'tqs_nonce' ); ?>

        <!-- PERSONAL INFORMATION -->
        <div class="tqs-form-section">
            <h3>Personal Information</h3>
            <div class="tqs-row">
                <div class="tqs-field">
                    <label for="tqs_full_name">Full Name <span class="required">*</span></label>
                    <input type="text" id="tqs_full_name" name="tqs_full_name"
                           value="<?php echo esc_attr( $_POST['tqs_full_name'] ?? '' ); ?>"
                           placeholder="e.g. John Smith" required />
                </div>
                <div class="tqs-field">
                    <label for="tqs_email">Email Address <span class="required">*</span></label>
                    <input type="email" id="tqs_email" name="tqs_email"
                           value="<?php echo esc_attr( $_POST['tqs_email'] ?? '' ); ?>"
                           placeholder="e.g. john@email.com" required />
                </div>
            </div>

            <div class="tqs-row">
                <div class="tqs-field">
                    <label>Phone Number <span class="required">*</span></label>
                    <div class="tqs-phone-wrap">
                        <select name="tqs_phone_code" id="tqs_phone_code" class="tqs-dial-select" onchange="tqsSyncWaCode(this)">
                            <?php foreach ( $dial_codes as $dc_label => $dc_code ) :
                                $is_other = ( $dc_code === 'other' ); ?>
                            <option value="<?php echo esc_attr( $is_other ? 'other' : $dc_code ); ?>"
                                    <?php selected( $sel_phone_code, $is_other ? 'other' : $dc_code ); ?>>
                                <?php echo esc_html( $dc_label ); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="tqs_phone_code_other" id="tqs_phone_code_other"
                               class="tqs-dial-other" placeholder="+00"
                               value="<?php echo esc_attr( $_POST['tqs_phone_code_other'] ?? '' ); ?>"
                               style="<?php echo $sel_phone_code === 'other' ? 'display:flex;' : 'display:none;'; ?>"
                               maxlength="6" />
                        <input type="tel" name="tqs_phone" id="tqs_phone"
                               class="tqs-phone-number"
                               placeholder="Digits only e.g. 612345678"
                               value="<?php echo esc_attr( $_POST['tqs_phone'] ?? '' ); ?>"
                               required inputmode="numeric" pattern="[0-9]{4,15}" />
                    </div>
                    <span class="tqs-field-hint">Numbers only - no spaces, dashes or brackets</span>
                    <div class="tqs-phone-error" id="tqs-phone-error" style="display:none;">
                        Please enter digits only (e.g. 612345678)
                    </div>
                </div>
            </div>

            <div class="tqs-row">
                <div class="tqs-field">
                    <label>WhatsApp Number <span class="tqs-optional-tag">optional</span></label>
                    <label class="tqs-wa-same-label">
                        <input type="checkbox" name="tqs_wa_same" id="tqs_wa_same" value="1"
                               <?php checked( $wa_same, true ); ?>
                               onchange="tqsToggleWa(this)" />
                        Same as phone number
                    </label>
                    <div class="tqs-phone-wrap" id="tqs-wa-fields"
                         style="<?php echo $wa_same ? 'display:none;' : 'display:flex;'; ?> margin-top:10px;">
                        <select name="tqs_wa_code" id="tqs_wa_code" class="tqs-dial-select">
                            <?php foreach ( $dial_codes as $dc_label => $dc_code ) :
                                $is_other = ( $dc_code === 'other' ); ?>
                            <option value="<?php echo esc_attr( $is_other ? 'other' : $dc_code ); ?>"
                                    <?php selected( $sel_wa_code, $is_other ? 'other' : $dc_code ); ?>>
                                <?php echo esc_html( $dc_label ); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="tqs_wa_code_other" id="tqs_wa_code_other"
                               class="tqs-dial-other" placeholder="+00"
                               value="<?php echo esc_attr( $_POST['tqs_wa_code_other'] ?? '' ); ?>"
                               style="<?php echo ( isset( $_POST['tqs_wa_code'] ) && $_POST['tqs_wa_code'] === 'other' ) ? 'display:flex;' : 'display:none;'; ?>"
                               maxlength="6" />
                        <input type="tel" name="tqs_whatsapp" id="tqs_whatsapp"
                               class="tqs-phone-number"
                               placeholder="Digits only e.g. 612345678"
                               value="<?php echo esc_attr( $_POST['tqs_whatsapp'] ?? '' ); ?>"
                               inputmode="numeric" pattern="[0-9]{4,15}" />
                    </div>
                    <div class="tqs-phone-error" id="tqs-wa-error" style="display:none;">
                        Please enter digits only (e.g. 612345678)
                    </div>
                </div>
            </div>
        </div>

        <!-- TRAVEL TYPE -->
        <div class="tqs-form-section">
            <h3>Travel Type <span class="required">*</span></h3>
            <div class="tqs-travel-type-selector">
                <?php
                $types = [ 'oneway' => 'One Way', 'return' => 'Return', 'multicity' => 'Multi-City' ];
                foreach ( $types as $val => $lbl ) :
                    $active = $sel_type === $val ? 'active' : '';
                ?>
                <label class="tqs-type-card <?php echo esc_attr( $active ); ?>">
                    <input type="radio" name="tqs_travel_type" value="<?php echo esc_attr( $val ); ?>"
                           <?php checked( $sel_type, $val ); ?> />
                    <span class="tqs-type-label"><?php echo esc_html( $lbl ); ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ONE WAY -->
        <div class="tqs-form-section tqs-type-section" id="tqs-section-oneway">
            <h3>Flight Details - One Way</h3>
            <div class="tqs-row">
                <div class="tqs-field">
                    <?php tqs_render_airport_field( [
                        'label'      => 'Traveling From',
                        'name'       => 'tqs_from',
                        'id'         => 'tqs_from',
                        'airports'   => $airports,
                        'selected'   => sanitize_text_field( $_POST['tqs_from'] ?? '' ),
                        'other_val'  => sanitize_text_field( $_POST['tqs_from_other'] ?? '' ),
                        'other_name' => 'tqs_from_other',
                    ] ); ?>
                </div>
                <div class="tqs-field">
                    <?php tqs_render_airport_field( [
                        'label'      => 'Destination',
                        'name'       => 'tqs_destination',
                        'id'         => 'tqs_destination',
                        'airports'   => $airports,
                        'selected'   => sanitize_text_field( $_POST['tqs_destination'] ?? '' ),
                        'other_val'  => sanitize_text_field( $_POST['tqs_destination_other'] ?? '' ),
                        'other_name' => 'tqs_destination_other',
                        'required'   => true,
                    ] ); ?>
                </div>
            </div>
            <div class="tqs-row">
                <div class="tqs-field" style="max-width:280px;">
                    <label for="tqs_travel_date">Departure Date <span class="required">*</span></label>
                    <input type="date" id="tqs_travel_date" name="tqs_travel_date"
                           value="<?php echo esc_attr( $_POST['tqs_travel_date'] ?? '' ); ?>"
                           min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>" />
                </div>
            </div>
        </div>

        <!-- RETURN -->
        <div class="tqs-form-section tqs-type-section" id="tqs-section-return">
            <h3>Flight Details - Return</h3>
            <div class="tqs-row">
                <div class="tqs-field">
                    <?php tqs_render_airport_field( [
                        'label'      => 'Traveling From',
                        'name'       => 'tqs_from_return',
                        'id'         => 'tqs_from_return',
                        'airports'   => $airports,
                        'selected'   => sanitize_text_field( $_POST['tqs_from_return'] ?? '' ),
                        'other_val'  => sanitize_text_field( $_POST['tqs_from_return_other'] ?? '' ),
                        'other_name' => 'tqs_from_return_other',
                    ] ); ?>
                </div>
                <div class="tqs-field">
                    <?php tqs_render_airport_field( [
                        'label'      => 'Destination',
                        'name'       => 'tqs_destination_return',
                        'id'         => 'tqs_destination_return',
                        'airports'   => $airports,
                        'selected'   => sanitize_text_field( $_POST['tqs_destination_return'] ?? '' ),
                        'other_val'  => sanitize_text_field( $_POST['tqs_destination_return_other'] ?? '' ),
                        'other_name' => 'tqs_destination_return_other',
                        'required'   => true,
                    ] ); ?>
                </div>
            </div>
            <div class="tqs-row">
                <div class="tqs-field">
                    <label>Departure Date <span class="required">*</span></label>
                    <input type="date" name="tqs_travel_date_return" id="tqs_travel_date_return"
                           value="<?php echo esc_attr( $_POST['tqs_travel_date_return'] ?? '' ); ?>"
                           min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>"
                           onchange="tqsReturnDepChanged(this)" />
                </div>
                <div class="tqs-field">
                    <label>Return Date <span class="required">*</span></label>
                    <input type="date" name="tqs_return_date" id="tqs_return_date"
                           value="<?php echo esc_attr( $_POST['tqs_return_date'] ?? '' ); ?>"
                           min="<?php echo esc_attr( $_POST['tqs_travel_date_return'] ?? date( 'Y-m-d' ) ); ?>"
                           onchange="tqsReturnArrChanged(this)" />
                </div>
            </div>
            <div id="tqs-return-date-error" class="tqs-return-date-error" style="display:none;">
                Return date must be after the departure date.
            </div>
        </div>

        <!-- MULTI-CITY -->
        <div class="tqs-form-section tqs-type-section" id="tqs-section-multicity">
            <h3>Multi-City Legs</h3>
            <p class="tqs-hint">Minimum 3 legs required. Click "Add Another Leg" to add more stops.</p>
            <div id="tqs-mc-legs">
                <?php
                $mc_froms       = (array) ( $_POST['tqs_mc_from']       ?? [ '', '', '' ] );
                $mc_tos         = (array) ( $_POST['tqs_mc_to']         ?? [ '', '', '' ] );
                $mc_dep_dates   = (array) ( $_POST['tqs_mc_dep_date']   ?? [ '', '', '' ] );
                $mc_arr_dates   = (array) ( $_POST['tqs_mc_arr_date']   ?? [ '', '', '' ] );
                $mc_from_others = (array) ( $_POST['tqs_mc_from_other'] ?? [] );
                $mc_to_others   = (array) ( $_POST['tqs_mc_to_other']   ?? [] );
                $mc_leg_count   = max( count( $mc_froms ), 3 );
                for ( $i = 0; $i < $mc_leg_count; $i++ ) :
                ?>
                <div class="tqs-mc-leg" data-leg="<?php echo esc_attr( $i ); ?>">
                    <div class="tqs-mc-leg-header">
                        <span class="tqs-leg-badge">Leg <?php echo intval( $i + 1 ); ?></span>
                        <?php if ( $i >= 3 ) : ?>
                        <button type="button" class="tqs-remove-leg" onclick="tqsRemoveLeg(this)">Remove</button>
                        <?php endif; ?>
                    </div>
                    <div class="tqs-row">
                        <div class="tqs-field">
                            <?php tqs_render_airport_field( [
                                'label'      => 'Departure Airport',
                                'name'       => 'tqs_mc_from[]',
                                'id'         => 'tqs_mc_from_' . $i,
                                'airports'   => $airports,
                                'selected'   => sanitize_text_field( $mc_froms[ $i ] ?? '' ),
                                'other_val'  => sanitize_text_field( $mc_from_others[ $i ] ?? '' ),
                                'other_name' => 'tqs_mc_from_other[]',
                            ] ); ?>
                        </div>
                        <div class="tqs-field">
                            <?php tqs_render_airport_field( [
                                'label'      => 'Arrival Airport',
                                'name'       => 'tqs_mc_to[]',
                                'id'         => 'tqs_mc_to_' . $i,
                                'airports'   => $airports,
                                'selected'   => sanitize_text_field( $mc_tos[ $i ] ?? '' ),
                                'other_val'  => sanitize_text_field( $mc_to_others[ $i ] ?? '' ),
                                'other_name' => 'tqs_mc_to_other[]',
                            ] ); ?>
                        </div>
                    </div>
                    <div class="tqs-row tqs-mc-dates-row">
                        <div class="tqs-field tqs-field--date">
                            <label>Departure Date <span class="required">*</span></label>
                            <input type="date" name="tqs_mc_dep_date[]" class="tqs-mc-dep-date"
                                   value="<?php echo esc_attr( $mc_dep_dates[ $i ] ?? '' ); ?>"
                                   min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>"
                                   onchange="tqsValidateLegDates(this)" />
                        </div>
                        <div class="tqs-field tqs-field--date">
                            <label>Arrival Date <span class="required">*</span></label>
                            <input type="date" name="tqs_mc_arr_date[]" class="tqs-mc-arr-date"
                                   value="<?php echo esc_attr( $mc_arr_dates[ $i ] ?? '' ); ?>"
                                   min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>"
                                   onchange="tqsValidateLegDates(this)" />
                        </div>
                        <div class="tqs-leg-date-error" style="display:none;">
                            Arrival date must be on or after departure date.
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            <button type="button" class="tqs-add-leg-btn" onclick="tqsAddLeg()">+ Add Another Leg</button>
        </div>

        <!-- PASSENGERS -->
        <div class="tqs-form-section">
            <h3>Passengers</h3>
            <p class="tqs-hint">Maximum 9 passengers total. Infants cannot exceed the number of adults.</p>
            <div class="tqs-pax-grid">
                <?php
                $pax_cards = [
                    'adult'   => [ 'id' => 'adults',  'label' => 'Adults',   'age' => '12+ years',     'val' => $pax_adult  ],
                    'kids'    => [ 'id' => 'kids',    'label' => 'Children', 'age' => '2 - 11 years',  'val' => $pax_kids   ],
                    'infants' => [ 'id' => 'infants', 'label' => 'Infants',  'age' => 'Under 2 years', 'val' => $pax_infant ],
                ];
                foreach ( $pax_cards as $slug => $card ) : ?>
                <div class="tqs-pax-card <?php echo $card['val'] > 0 ? 'pax-active' : ''; ?>" id="pax-card-<?php echo esc_attr( $slug ); ?>">
                    <div class="tqs-pax-info">
                        <div class="tqs-pax-type"><?php echo esc_html( $card['label'] ); ?></div>
                        <div class="tqs-pax-age"><?php echo esc_html( $card['age'] ); ?></div>
                    </div>
                    <div class="tqs-pax-counter">
                        <button type="button" class="tqs-pax-btn tqs-pax-minus"
                                onclick="tqsChangePax('<?php echo esc_attr( $card['id'] ); ?>', -1)"
                                aria-label="Decrease <?php echo esc_attr( $card['label'] ); ?>">-</button>
                        <span class="tqs-pax-count" id="pax-count-<?php echo esc_attr( $card['id'] ); ?>"><?php echo intval( $card['val'] ); ?></span>
                        <input type="hidden" name="tqs_<?php echo esc_attr( $card['id'] ); ?>"
                               id="tqs_<?php echo esc_attr( $card['id'] ); ?>"
                               value="<?php echo intval( $card['val'] ); ?>" />
                        <button type="button" class="tqs-pax-btn tqs-pax-plus"
                                onclick="tqsChangePax('<?php echo esc_attr( $card['id'] ); ?>', 1)"
                                aria-label="Increase <?php echo esc_attr( $card['label'] ); ?>">+</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="tqs-pax-summary" id="tqs-pax-summary">
                <span id="tqs-pax-summary-text">
                    <?php
                    $tot = $pax_adult + $pax_kids + $pax_infant;
                    echo 'Total: <strong>' . intval( $tot ) . ' / 9</strong> passengers &nbsp;&middot;&nbsp; ';
                    echo 'Adults: <strong>' . intval( $pax_adult ) . '</strong> &nbsp;&middot;&nbsp; ';
                    echo 'Children: <strong>' . intval( $pax_kids ) . '</strong> &nbsp;&middot;&nbsp; ';
                    echo 'Infants: <strong>' . intval( $pax_infant ) . '</strong>';
                    ?>
                </span>
            </div>
            <div class="tqs-pax-error" id="tqs-pax-error" style="display:none;"></div>
        </div>

        <!-- TRIP PREFERENCES -->
        <div class="tqs-form-section">
            <h3>Trip Preferences</h3>
            <div class="tqs-row">
                <div class="tqs-field">
                    <label for="tqs_trip_type">Trip Category</label>
                    <select id="tqs_trip_type" name="tqs_trip_type">
                        <option value="">-- Select --</option>
                        <?php
                        $trip_types = [ 'Leisure / Holiday', 'Honeymoon', 'Family Trip', 'Adventure', 'Business Travel', 'Group Tour', 'Solo Travel', 'Pilgrimage' ];
                        $sel_tt     = sanitize_text_field( $_POST['tqs_trip_type'] ?? '' );
                        foreach ( $trip_types as $t ) {
                            printf( '<option value="%s"%s>%s</option>', esc_attr( $t ), selected( $sel_tt, $t, false ), esc_html( $t ) );
                        }
                        ?>
                    </select>
                </div>
                <div class="tqs-field">
                    <label for="tqs_budget">Approximate Budget (per person)</label>
                    <select id="tqs_budget" name="tqs_budget">
                        <option value="">-- Select --</option>
                        <?php
                        $budgets = [ 'Under $500', '$500 - $1,000', '$1,000 - $2,500', '$2,500 - $5,000', '$5,000 - $10,000', '$10,000+', 'Flexible' ];
                        $sel_b   = sanitize_text_field( $_POST['tqs_budget'] ?? '' );
                        foreach ( $budgets as $b ) {
                            printf( '<option value="%s"%s>%s</option>', esc_attr( $b ), selected( $sel_b, $b, false ), esc_html( $b ) );
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="tqs-field tqs-checkboxes">
                <label>Services Required</label>
                <div class="tqs-checkbox-grid">
                    <?php
                    $services_opts = [ 'Flight Booking', 'Hotel / Accommodation', 'Airport Transfer', 'Car Rental', 'Tour Guide', 'Travel Insurance', 'Visa Assistance', 'Cruise Booking', 'All-Inclusive Package' ];
                    $sel_services  = (array) ( $_POST['tqs_services'] ?? [] );
                    foreach ( $services_opts as $s ) :
                        $chk = in_array( $s, $sel_services, true ) ? 'checked' : '';
                    ?>
                    <label class="tqs-checkbox-label">
                        <input type="checkbox" name="tqs_services[]"
                               value="<?php echo esc_attr( $s ); ?>" <?php echo $chk; ?>>
                        <?php echo esc_html( $s ); ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- SPECIAL REQUESTS AND COMMENTS -->
        <div class="tqs-form-section">
            <h3>Special Requests and Comments</h3>
            <p class="tqs-hint">Let us know anything specific - we will do our best to accommodate your needs.</p>
            <div class="tqs-comments-grid">
                <div class="tqs-field tqs-checkboxes">
                    <label>Quick Requests <span class="tqs-optional-tag">optional</span></label>
                    <div class="tqs-checkbox-grid">
                        <?php
                        $quick_opts = [
                            'Vegetarian / Vegan Meal', 'Halal Meal', 'Baby / Infant Meal',
                            'Wheelchair Assistance', 'Extra Legroom Seat', 'Window Seat Preferred',
                            'Hotel Recommendation Needed', 'Airport Transfer Required',
                            'Visa Assistance Required', 'Special Occasion (Birthday / Anniversary)',
                            'Travelling with Medical Equipment', 'Travelling with Pet',
                        ];
                        $sel_qr = (array) ( $_POST['tqs_quick_requests'] ?? [] );
                        foreach ( $quick_opts as $qr ) :
                            $chk = in_array( $qr, $sel_qr, true ) ? 'checked' : '';
                        ?>
                        <label class="tqs-checkbox-label">
                            <input type="checkbox" name="tqs_quick_requests[]"
                                   value="<?php echo esc_attr( $qr ); ?>" <?php echo $chk; ?>>
                            <?php echo esc_html( $qr ); ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="tqs-field">
                    <label for="tqs_message">Additional Comments / Special Requests <span class="tqs-optional-tag">optional</span></label>
                    <textarea id="tqs_message" name="tqs_message" rows="6" class="tqs-comments-box"
                              placeholder="e.g. We are celebrating our honeymoon and would love a window seat. One passenger uses a walking aid. Please arrange hotel near the city centre..."><?php echo esc_textarea( $_POST['tqs_message'] ?? '' ); ?></textarea>
                    <div class="tqs-char-counter">
                        <span id="tqs-char-count">0</span> / 1000 characters
                    </div>
                </div>
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="tqs-submit-row">
            <button type="submit" name="tqs_submit" class="tqs-submit-btn">Send My Travel Inquiry</button>
        </div>

    </form>
</div>

<?php
function tqs_render_airport_field( $args ) {
    $label      = $args['label']      ?? 'Airport';
    $name       = $args['name']       ?? 'airport';
    $id         = $args['id']         ?? 'airport';
    $airports   = $args['airports']   ?? [];
    $selected   = $args['selected']   ?? '';
    $other_val  = $args['other_val']  ?? '';
    $other_name = $args['other_name'] ?? $name . '_other';
    $required   = ! empty( $args['required'] );
    $req_star   = $required ? '<span class="required">*</span>' : '';
    $show_other = ( $selected === 'other' );

    $priority   = [ 'Netherlands', 'Belgium', 'Pakistan' ];
    $top_sorted = [];
    foreach ( $priority as $c ) {
        if ( isset( $airports[ $c ] ) ) $top_sorted[ $c ] = $airports[ $c ];
    }
    $rest = array_diff_key( $airports, array_flip( $priority ) );
    ?>
    <label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?> <?php echo $req_star; ?></label>
    <div class="tqs-airport-wrap">
        <input type="text" class="tqs-airport-search" placeholder="Search airport or city..." aria-label="Search airports" />
        <select name="<?php echo esc_attr( $name ); ?>"
                id="<?php echo esc_attr( $id ); ?>"
                class="tqs-airport-select"
                onchange="tqsToggleOther(this)"
                <?php echo $required ? 'required' : ''; ?>>
            <option value="">-- Select Airport --</option>
            <?php foreach ( $top_sorted as $country => $ap_list ) : ?>
            <optgroup label="<?php echo esc_attr( $country ); ?>">
                <?php foreach ( $ap_list as $code => $ap_name ) : ?>
                <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $selected, $code ); ?>><?php echo esc_html( $ap_name ); ?></option>
                <?php endforeach; ?>
            </optgroup>
            <?php endforeach; ?>
            <optgroup label="Other">
                <option value="other" <?php selected( $selected, 'other' ); ?>>Other - Enter Manually</option>
            </optgroup>
            <?php foreach ( $rest as $country => $ap_list ) : ?>
            <optgroup label="<?php echo esc_attr( $country ); ?>">
                <?php foreach ( $ap_list as $code => $ap_name ) : ?>
                <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $selected, $code ); ?>><?php echo esc_html( $ap_name ); ?></option>
                <?php endforeach; ?>
            </optgroup>
            <?php endforeach; ?>
        </select>
        <input type="text"
               name="<?php echo esc_attr( $other_name ); ?>"
               class="tqs-airport-other"
               id="<?php echo esc_attr( $id ); ?>_other"
               placeholder="Enter airport name, city or IATA code"
               value="<?php echo esc_attr( $other_val ); ?>"
               style="<?php echo $show_other ? 'display:block;' : 'display:none;'; ?>"
               <?php echo ( $show_other && $required ) ? 'required' : ''; ?> />
    </div>
    <?php
}
?>
