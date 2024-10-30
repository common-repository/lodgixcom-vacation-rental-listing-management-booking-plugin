<?php

class LodgixWidgetRentalSearch2 extends WP_Widget {

    private static $DEFAULT_SETTINGS;

    public function __construct() {
		parent::__construct(
			'lodgix_rental_search',
            LodgixTranslate::translate('Lodgix Rental Search'),
			array('description' => LodgixTranslate::translate('Lodgix Rental Search Widget'))
		);
        self::$DEFAULT_SETTINGS = array(
            'title' => 'Rental Search',
            'button_text' => 'Display Results',
            'horizontal' => false,
            'horz_alignment' => 'horz-center',
            'min_nights' => 1,
            'arrival' => true,
            'departure' => true,
            'location' => true,
            'bedrooms' => true,
            'price' => true,
            'from_price' => 50,
            'to_price' => 1000,
            'price_increment' => 50,
            'currency_symbol' => '$',
            'pet_friendly' => false,
            'amenities' => false,
            'tags' => false,
            'name' => true,
            'formbgc' => '',
            'buttonbgc' => '',
            'buttontc' => '',
            'fieldfontsize' => '',
            'fieldfontsizeun' => 'px',
            'widgettp' => '',
            'widgettpun' => 'px',
            'widgetrp' => '',
            'widgetrpun' => 'px',
            'widgetbp' => '',
            'widgetbpun' => 'px',
            'widgetlp' => '',
            'widgetlpun' => 'px',
            'formWidth' => '',
            'formWidthun' => 'px'
        );
	}

    public function form($instance) {
        global $wpdb;

        $tableProperties = $wpdb->prefix . 'lodgix_properties';

        $title = self::$DEFAULT_SETTINGS['title'];
        $button_text = self::$DEFAULT_SETTINGS['button_text'];
        $horizontal = self::$DEFAULT_SETTINGS['horizontal'];
        $horzAlign = self::$DEFAULT_SETTINGS['horz_alignment'];
        $minNights = self::$DEFAULT_SETTINGS['min_nights'];
        $departure = self::$DEFAULT_SETTINGS['departure'];
        $arrival = self::$DEFAULT_SETTINGS['arrival'];
        $location = self::$DEFAULT_SETTINGS['location'];
        $bedrooms = self::$DEFAULT_SETTINGS['bedrooms'];
        $price = self::$DEFAULT_SETTINGS['price'];
        $fromPrice = self::$DEFAULT_SETTINGS['from_price'];
        $toPrice = self::$DEFAULT_SETTINGS['to_price'];
        $priceIncrement = self::$DEFAULT_SETTINGS['price_increment'];
        $properties = $wpdb->get_results("SELECT currency_symbol FROM $tableProperties LIMIT 1");
        if ($properties) {
            $currencySymbol = $properties[0]->currency_symbol;
        } else {
            $currencySymbol = self::$DEFAULT_SETTINGS['currency_symbol'];
        }
        $petFriendly = self::$DEFAULT_SETTINGS['pet_friendly'];
        $amenities = self::$DEFAULT_SETTINGS['amenities'];
        $tags = self::$DEFAULT_SETTINGS['tags'];
        $name = self::$DEFAULT_SETTINGS['name'];
        $formbgc = self::$DEFAULT_SETTINGS['formbgc'];
        $buttonbgc = self::$DEFAULT_SETTINGS['buttonbgc'];
        $buttontc = self::$DEFAULT_SETTINGS['buttontc'];
        $widgettp = self::$DEFAULT_SETTINGS['widgettp'];
        $widgettpun = self::$DEFAULT_SETTINGS['widgettpun'];
        $widgetrp = self::$DEFAULT_SETTINGS['widgetrp'];
        $widgetrpun = self::$DEFAULT_SETTINGS['widgetrpun'];
        $widgetbp = self::$DEFAULT_SETTINGS['widgetbp'];
        $widgetbpun = self::$DEFAULT_SETTINGS['widgetbpun'];
        $widgetlp = self::$DEFAULT_SETTINGS['widgetlp'];
        $widgetlpun = self::$DEFAULT_SETTINGS['widgetlpun'];
        $formWidth = self::$DEFAULT_SETTINGS['formWidth'];
        $formWidthun = self::$DEFAULT_SETTINGS['formWidthun'];

		if ($instance) {
            if (array_key_exists('title', $instance)) {
                $title = esc_attr($instance['title']);
            }
            if (array_key_exists('button_text', $instance)) {
                $button_text = esc_attr($instance['button_text']);
            }
            if (array_key_exists('horizontal', $instance)) {
                $horizontal = esc_attr($instance['horizontal']);
            }
            if (array_key_exists('horz_alignment', $instance)) {
                $horzAlign = esc_attr($instance['horz_alignment']);
            }
            if (array_key_exists('min_nights', $instance)) {
                $minNights = esc_attr($instance['min_nights']);
            }
            if (array_key_exists('departure', $instance)) {
                $departure = esc_attr($instance['departure']);
            }
            if (array_key_exists('arrival', $instance)) {
                $arrival = esc_attr($instance['arrival']);
            }
            if (array_key_exists('location', $instance)) {
                $location = esc_attr($instance['location']);
            }
            if (array_key_exists('bedrooms', $instance)) {
                $bedrooms = esc_attr($instance['bedrooms']);
            }
            if (array_key_exists('price', $instance)) {
                $price = esc_attr($instance['price']);
            }
            if (array_key_exists('from_price', $instance)) {
                $fromPrice = esc_attr($instance['from_price']);
            }
            if (array_key_exists('to_price', $instance)) {
                $toPrice = esc_attr($instance['to_price']);
            }
            if (array_key_exists('price_increment', $instance)) {
                $priceIncrement = esc_attr($instance['price_increment']);
            }
            if (array_key_exists('currency_symbol', $instance)) {
                $currencySymbol = esc_attr($instance['currency_symbol']);
            }
            if (array_key_exists('pet_friendly', $instance)) {
			    $petFriendly = esc_attr($instance['pet_friendly']);
            }
            if (array_key_exists('amenities', $instance)) {
			    $amenities = esc_attr($instance['amenities']);
            }
            if (array_key_exists('tags', $instance)) {
			    $tags = esc_attr($instance['tags']);
            }
            if (array_key_exists('name', $instance)) {
                $name = esc_attr($instance['name']);
            }
            if (array_key_exists('formbgc', $instance)) {
                $formbgc = esc_attr($instance['formbgc']);
            }
            if (array_key_exists('buttonbgc', $instance)) {
                $buttonbgc = esc_attr($instance['buttonbgc']);
            }
            if (array_key_exists('buttontc', $instance)) {
                $buttontc = esc_attr($instance['buttontc']);
            }
            if (array_key_exists('widgettp', $instance)) {
                $widgettp = esc_attr($instance['widgettp']);
            }
            if (array_key_exists('widgettpun', $instance)) {
                $widgettpun = esc_attr($instance['widgettpun']);
            }
            if (array_key_exists('widgetrp', $instance)) {
                $widgetrp = esc_attr($instance['widgetrp']);
            }
            if (array_key_exists('widgetrpun', $instance)) {
                $widgetrpun = esc_attr($instance['widgetrpun']);
            }
            if (array_key_exists('widgetbp', $instance)) {
                $widgetbp = esc_attr($instance['widgetbp']);
            }
            if (array_key_exists('widgetbpun', $instance)) {
                $widgetbpun = esc_attr($instance['widgetbpun']);
            }
            if (array_key_exists('widgetlp', $instance)) {
                $widgetlp = esc_attr($instance['widgetlp']);
            }
            if (array_key_exists('widgetlpun', $instance)) {
                $widgetlpun = esc_attr($instance['widgetlpun']);
            }
            if (array_key_exists('formWidth', $instance)) {
                $formWidth = esc_attr($instance['formWidth']);
            }
            if (array_key_exists('formWidthun', $instance)) {
                $formWidthun = esc_attr($instance['formWidthun']);
            }

		}
		?>
			<p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo LodgixTranslate::translate('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"><br>
            </p>
			<p>
                <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php echo LodgixTranslate::translate('Button Text:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo $button_text; ?>"><br>
            </p>
            <p>
                <input id="<?php echo $this->get_field_id('horizontal'); ?>" name="<?php echo $this->get_field_name('horizontal'); ?>" type="checkbox" <?php checked(true, $horizontal); ?>>
                <label for="<?php echo $this->get_field_id('horizontal'); ?>"><?php echo LodgixTranslate::translate('Horizontal Layout'); ?></label>
			</p>

            <p>
                <div id="ldxHorizontalAlign">
                    <label for="<?php echo $this->get_field_id('horz_alignment'); ?>"><?php echo LodgixTranslate::translate('Horizontal Form Alignment:'); ?></label>
                    <select class="widefat" id="<?php echo $this->get_field_id('horz_alignment'); ?>" name="<?php echo $this->get_field_name('horz_alignment'); ?>">
                        <option value="horz-center" <?php echo ($horzAlign == 'horz-center' ? 'selected' : ''); ?>>Center</option>
                        <option value="horz-left" <?php echo ($horzAlign == 'horz-left' ? 'selected' : ''); ?>>Left</option>
                        <option value="horz-right" <?php echo ($horzAlign == 'horz-right' ? 'selected' : ''); ?>>Right</option>
                    </select>
                </div>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('min_nights'); ?>"><?php echo LodgixTranslate::translate('Minimum Nights:'); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id('min_nights'); ?>" name="<?php echo $this->get_field_name('min_nights'); ?>">
                    <?php
                        for ($i = 1; $i < 100; $i++) {
                            $selected = $minNights == $i ? 'selected' : '';
                            echo "<option value='$i' $selected>$i " . LodgixTranslate::translate($i > 1 ? 'nights' : 'night') . "</option>";
                        }
                    ?>
                </select><br>
            </p>
            <p>
                <input id="<?php echo $this->get_field_id('departure'); ?>" name="<?php echo $this->get_field_name('departure'); ?>" type="checkbox" <?php checked(true, $departure); ?>>
                <label for="<?php echo $this->get_field_id('departure'); ?>"><?php echo LodgixTranslate::translate('Search by Departure'); ?></label>
			</p>
            <p>
                <input id="<?php echo $this->get_field_id('arrival'); ?>" name="<?php echo $this->get_field_name('arrival'); ?>" type="checkbox" <?php checked(true, $arrival); ?>>
                <label for="<?php echo $this->get_field_id('arrival'); ?>"><?php echo LodgixTranslate::translate('Search by Arrival'); ?></label>
			</p>
            <p>
                <input id="<?php echo $this->get_field_id('location'); ?>" name="<?php echo $this->get_field_name('location'); ?>" type="checkbox" <?php checked(true, $location); ?>>
                <label for="<?php echo $this->get_field_id('location'); ?>"><?php echo LodgixTranslate::translate('Search by Location'); ?></label>
			</p>
            <p>
                <input id="<?php echo $this->get_field_id('bedrooms'); ?>" name="<?php echo $this->get_field_name('bedrooms'); ?>" type="checkbox" <?php checked(true, $bedrooms); ?>>
                <label for="<?php echo $this->get_field_id('bedrooms'); ?>"><?php echo LodgixTranslate::translate('Search by Bedrooms'); ?></label>
			</p>
            <p>
                <input id="<?php echo $this->get_field_id('price'); ?>" name="<?php echo $this->get_field_name('price'); ?>" type="checkbox" <?php checked(true, $price); ?> onclick="document.getElementById('<?php echo $this->get_field_id('price_details'); ?>').style.display=this.checked?'block':'none'">
                <label for="<?php echo $this->get_field_id('price'); ?>"><?php echo LodgixTranslate::translate('Search by Price'); ?></label>
			</p>
            <div id="<?php echo $this->get_field_id('price_details'); ?>" <?php if (!$price) echo 'style="display:none"' ?>>
                <p>
                    <label for="<?php echo $this->get_field_id('from_price'); ?>"><?php echo LodgixTranslate::translate('From Price:'); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('from_price'); ?>" name="<?php echo $this->get_field_name('from_price'); ?>" type="text" value="<?php echo $fromPrice; ?>"><br>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('to_price'); ?>"><?php echo LodgixTranslate::translate('To Price:'); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('to_price'); ?>" name="<?php echo $this->get_field_name('to_price'); ?>" type="text" value="<?php echo $toPrice; ?>"><br>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('price_increment'); ?>"><?php echo LodgixTranslate::translate('Price Increment:'); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('price_increment'); ?>" name="<?php echo $this->get_field_name('price_increment'); ?>" type="text" value="<?php echo $priceIncrement; ?>"><br>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('currency_symbol'); ?>"><?php echo LodgixTranslate::translate('Currency Symbol:'); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('currency_symbol'); ?>" name="<?php echo $this->get_field_name('currency_symbol'); ?>" type="text" value="<?php echo $currencySymbol; ?>"><br>
                </p>
            </div>
            <p>
                <input id="<?php echo $this->get_field_id('pet_friendly'); ?>" name="<?php echo $this->get_field_name('pet_friendly'); ?>" type="checkbox" <?php checked(true, $petFriendly); ?>>
                <label for="<?php echo $this->get_field_id('pet_friendly'); ?>"><?php echo LodgixTranslate::translate('Search by Pet Friendly'); ?></label>
			</p>
            <p>
                <input id="<?php echo $this->get_field_id('amenities'); ?>" name="<?php echo $this->get_field_name('amenities'); ?>" type="checkbox" <?php checked(true, $amenities); ?>>
                <label for="<?php echo $this->get_field_id('amenities'); ?>"><?php echo LodgixTranslate::translate('Search by Amenities'); ?></label>
			</p>
            <p>
                <input id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="checkbox" <?php checked(true, $tags); ?>>
                <label for="<?php echo $this->get_field_id('tags'); ?>"><?php echo LodgixTranslate::translate('Search by Tags'); ?></label>
			</p>
            <p>
                <input id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="checkbox" <?php checked(true, $name); ?>>
                <label for="<?php echo $this->get_field_id('name'); ?>"><?php echo LodgixTranslate::translate('Search by Name or ID'); ?></label>
			</p>

            <p><strong>Styling Options:</strong></p>
            <p>
                <label for="<?php echo $this->get_field_id('formbgc'); ?>"><?php echo LodgixTranslate::translate('Form Background Color'); ?></label><br>
                <input class="color-picker" id="<?php echo $this->get_field_id( 'formbgc' ); ?>" type="text" data-alpha="true" name="<?php echo $this->get_field_name( 'formbgc' ); ?>" value="<?php echo esc_attr( $formbgc ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('buttonbgc'); ?>"><?php echo LodgixTranslate::translate('Button Background Color'); ?></label><br>
                <input class="color-picker" id="<?php echo $this->get_field_id( 'buttonbgc' ); ?>" type="text" data-alpha="true" name="<?php echo $this->get_field_name( 'buttonbgc' ); ?>" value="<?php echo esc_attr( $buttonbgc ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('buttontc'); ?>"><?php echo LodgixTranslate::translate('Button Text Color'); ?></label><br>
                <input class="color-picker" id="<?php echo $this->get_field_id( 'buttontc' ); ?>" type="text" data-alpha="true" name="<?php echo $this->get_field_name( 'buttontc' ); ?>" value="<?php echo esc_attr( $buttontc ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('widgettp'); ?>"><?php echo LodgixTranslate::translate('Form Top Padding'); ?></label><br>
                <input id="<?php echo $this->get_field_id('widgettp'); ?>" name="<?php echo $this->get_field_name('widgettp'); ?>" type="text" value="<?php echo $widgettp; ?>" class="valuestyle" style="position: relative; top: 2px;">
                <select id="<?php echo $this->get_field_id('widgettpun'); ?>" name="<?php echo $this->get_field_name('widgettpun'); ?>" class="unit-select">
                    <option value="px" <?php echo ($widgettpun == 'px' ? 'selected' : ''); ?>>px</option>
                    <option value="rem" <?php echo ($widgettpun == 'rem' ? 'selected' : ''); ?>>rem</option>
                    <option value="per" <?php echo ($widgettpun == 'per' ? 'selected' : ''); ?>>%</option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('widgetrp'); ?>"><?php echo LodgixTranslate::translate('Form Right Padding'); ?></label><br>
                <input id="<?php echo $this->get_field_id('widgetrp'); ?>" name="<?php echo $this->get_field_name('widgetrp'); ?>" type="text" value="<?php echo $widgetrp; ?>" class="valuestyle" style="position: relative; top: 2px;">
                <select id="<?php echo $this->get_field_id('widgetrpun'); ?>" name="<?php echo $this->get_field_name('widgetrpun'); ?>" class="unit-select">
                    <option value="px" <?php echo ($widgetrpun == 'px' ? 'selected' : ''); ?>>px</option>
                    <option value="rem" <?php echo ($widgetrpun == 'rem' ? 'selected' : ''); ?>>rem</option>
                    <option value="per" <?php echo ($widgetrpun == 'per' ? 'selected' : ''); ?>>%</option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('widgetbp'); ?>"><?php echo LodgixTranslate::translate('Form Bottom Padding'); ?></label><br>
                <input id="<?php echo $this->get_field_id('widgetbp'); ?>" name="<?php echo $this->get_field_name('widgetbp'); ?>" type="text" value="<?php echo $widgetbp; ?>" class="valuestyle" style="position: relative; top: 2px;">
                <select id="<?php echo $this->get_field_id('widgetbpun'); ?>" name="<?php echo $this->get_field_name('widgetbpun'); ?>" class="unit-select">
                    <option value="px" <?php echo ($widgetbpun == 'px' ? 'selected' : ''); ?>>px</option>
                    <option value="rem" <?php echo ($widgetbpun == 'rem' ? 'selected' : ''); ?>>rem</option>
                    <option value="per" <?php echo ($widgetbpun == 'per' ? 'selected' : ''); ?>>%</option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('widgetlp'); ?>"><?php echo LodgixTranslate::translate('Form Left Padding'); ?></label><br>
                <input id="<?php echo $this->get_field_id('widgetlp'); ?>" name="<?php echo $this->get_field_name('widgetlp'); ?>" type="text" value="<?php echo $widgetlp; ?>" class="valuestyle" style="position: relative; top: 2px;">
                <select id="<?php echo $this->get_field_id('widgetlpun'); ?>" name="<?php echo $this->get_field_name('widgetlpun'); ?>" class="unit-select">
                    <option value="px" <?php echo ($widgetlpun == 'px' ? 'selected' : ''); ?>>px</option>
                    <option value="rem" <?php echo ($widgetlpun == 'rem' ? 'selected' : ''); ?>>rem</option>
                    <option value="per" <?php echo ($widgetlpun == 'per' ? 'selected' : ''); ?>>%</option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('formWidth'); ?>"><?php echo LodgixTranslate::translate('Form Width'); ?></label><br>
                <input id="<?php echo $this->get_field_id('formWidth'); ?>" name="<?php echo $this->get_field_name('formWidth'); ?>" type="text" value="<?php echo $formWidth; ?>" class="valuestyle" style="position: relative; top: 2px;">
                <select id="<?php echo $this->get_field_id('formWidthun'); ?>" name="<?php echo $this->get_field_name('formWidthun'); ?>" class="unit-select">
                    <option value="px" <?php echo ($formWidthun == 'px' ? 'selected' : ''); ?>>px</option>
                    <option value="rem" <?php echo ($formWidthun == 'rem' ? 'selected' : ''); ?>>rem</option>
                    <option value="per" <?php echo ($formWidthun == 'per' ? 'selected' : ''); ?>>%</option>
                </select>
            </p>

            <p><a class="button-secondary" href="#" id="<?php echo $this->get_field_id('reset'); ?>" title="<?php esc_attr_e( 'Reset Styles' ); ?>"><?php esc_attr_e( 'Reset Styles' ); ?></a></p>

            <script type="text/javascript">
                jQuery(document).ready(function(){

                    if ( typeof FLBuilder == 'undefined' ) {
                        jQuery( '.color-picker' ).wpColorPicker();
                    }

                    if(jQuery('#<?php echo $this->get_field_id('horizontal'); ?>').is(":checked")){
                        console.log('it is!');
                        jQuery('#ldxHorizontalAlign *').show();
                    } else {
                        jQuery('#ldxHorizontalAlign *').hide();
                    }
                    jQuery('#<?php echo $this->get_field_id('horizontal'); ?>').change(function () {

                        if(jQuery('#<?php echo $this->get_field_id('horizontal'); ?>').is(":checked")){
                            console.log('still is!');
                            jQuery('#ldxHorizontalAlign *').show();
                        } else {
                            jQuery('#ldxHorizontalAlign *').hide();
                        }
                    });

                    function media_upload(button_selector) {
                        var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                        jQuery('body').on('click', button_selector, function () {
                            var button_id = $(this).attr('id');
                            wp.media.editor.send.attachment = function (props, attachment) {
                                if (_custom_media) {
                                    jQuery('.' + button_id + '_img').attr('src', attachment.url);
                                    jQuery('.' + button_id + '_url').val(attachment.url);
                                } else {
                                    return _orig_send_attachment.apply($('#' + button_id), [props, attachment]);
                                }
                            }
                            wp.media.editor.open($('#' + button_id));
                            return false;
                        });
                    }
                    media_upload('.js_custom_upload_media');

                    jQuery('.js_custom_remove_media').on('click', function () {
                        var button_id = $(this).attr('id'),
                            field_id = button_id.slice(0, -7);
                        jQuery('.' + field_id + '_img').attr('src', '');
                        jQuery('.' + field_id + '_url').val('');
                    });

                });

                jQuery( '#<?php echo $this->get_field_id('reset'); ?>' ).click(function( event ) {
                    event.preventDefault();
                    jQuery( ".color-picker" ).val( "" );
                    jQuery( ".color-alpha" ).css( "background", "" );
                    jQuery( ".color-alpha" ).val( "" );
                    jQuery( ".wp-color-result" ).css( "background-color", "" );
                    jQuery( ".unit-select" ).val( "px" );
                    jQuery( ".valuestyle" ).val( "" );
                    jQuery("#<?php echo $this->get_field_id('savewidget'); ?>").val('Save').removeAttr('disabled');
                });
            </script>

		<?php
	}

    public function update($new_instance, $old_instance) {
        $instance = Array();
        $instance['title'] = strip_tags($new_instance['title']);
		$instance['button_text'] = strip_tags($new_instance['button_text']);
        $instance['horizontal'] = (isset($new_instance['horizontal']) && $new_instance['horizontal'] == 'on');
        $instance['horz_alignment'] = strip_tags($new_instance['horz_alignment']);
        $instance['min_nights'] = strip_tags($new_instance['min_nights']);
        $instance['departure'] = ($new_instance['departure'] == 'on');
        $instance['arrival'] = ($new_instance['arrival'] == 'on');
        $instance['location'] = ($new_instance['location'] == 'on');
        $instance['bedrooms'] = ($new_instance['bedrooms'] == 'on');
        $instance['price'] = ($new_instance['price'] == 'on');
        $instance['from_price'] = strip_tags($new_instance['from_price']);
        $instance['to_price'] = strip_tags($new_instance['to_price']);
        $instance['price_increment'] = strip_tags($new_instance['price_increment']);
        $instance['currency_symbol'] = strip_tags($new_instance['currency_symbol']);
        $instance['pet_friendly'] = (isset($new_instance['pet_friendly']) && $new_instance['pet_friendly'] == 'on');
        $instance['amenities'] = (isset($new_instance['amenities']) && $new_instance['amenities'] == 'on');
        $instance['tags'] = (isset($new_instance['tags']) && $new_instance['tags'] == 'on');
        $instance['name'] = ($new_instance['name'] == 'on');
        $instance['name'] = ($new_instance['name'] == 'on');
        
        $instance['widgettc']        = (isset($new_instance['widgettc'])) ? strip_tags($new_instance['widgettc']) : '';
        $instance['formbgc']         = (isset($new_instance['formbgc'])) ? strip_tags($new_instance['formbgc']) : '';
        $instance['buttonbgc']       = (isset($new_instance['buttonbgc'])) ? strip_tags($new_instance['buttonbgc']) : '';
        $instance['buttontc']        = (isset($new_instance['buttontc'])) ? strip_tags($new_instance['buttontc']) : '';
        $instance['fieldfontsize']   = (isset($new_instance['fieldfontsize'])) ? strip_tags($new_instance['fieldfontsize']) : '';
        $instance['fieldfontsizeun'] = (isset($new_instance['fieldfontsizeun'])) ? strip_tags($new_instance['fieldfontsizeun']) : '';
        $instance['widgettp']        = (isset($new_instance['widgettp'])) ? strip_tags($new_instance['widgettp']) : '';
        $instance['widgettpun']      = (isset($new_instance['widgettpun'])) ? strip_tags($new_instance['widgettpun']) : '';
        $instance['widgetrp']        = (isset($new_instance['widgetrp'])) ? strip_tags($new_instance['widgetrp']) : '';
        $instance['widgetrpun']      = (isset($new_instance['widgetrpun'])) ? strip_tags($new_instance['widgetrpun']) : '';
        $instance['widgetbp']        = (isset($new_instance['widgetbp'])) ? strip_tags($new_instance['widgetbp']) : '';
        $instance['widgetbpun']      = (isset($new_instance['widgetbpun'])) ? strip_tags($new_instance['widgetbpun']) : '';
        $instance['widgetlp']        = (isset($new_instance['widgetlp'])) ? strip_tags($new_instance['widgetlp']) : '';
        $instance['widgetlpun']      = (isset($new_instance['widgetlpun'])) ? strip_tags($new_instance['widgetlpun']) : '';
        $instance['formWidth']       = (isset($new_instance['formWidth'])) ? strip_tags($new_instance['formWidth']) : '';
        $instance['formWidthun']     = (isset($new_instance['formWidthun'])) ? strip_tags($new_instance['formWidthun']) : '';

		return $instance;
	}

    public function measurementCheck($un){
        $un = esc_html($un);
        if($un == 'per'){
            $un = '%';
        }
        return $un;
    }

    public function widget($args, $instance) {
		global $wpdb, $sitepress;

        $pluginPath = plugin_dir_url(plugin_basename(__FILE__));
        $currentLanguage = $sitepress ? $sitepress->get_current_language() : 'en';
        $datepickerLanguage = $currentLanguage;
        if ($datepickerLanguage == 'zh-hans') {
            $datepickerLanguage = 'zh-CN';
        }

        $tableProperties = $wpdb->prefix . 'lodgix_properties';
        $tableAmenities = $wpdb->prefix . 'lodgix_searchable_amenities';
        $tableTags = $wpdb->prefix . 'lodgix_tags';

		extract($args);

		$lodgixSettings = get_option('p_lodgix_options');

        $limitBookingDaysAdvance = (int)$wpdb->get_var("SELECT MAX(limit_booking_days_advance) FROM $tableProperties");

        $dateFormat = $lodgixSettings['p_lodgix_date_format'];
        if ($dateFormat == '%m/%d/%Y') {
            $dateFormat = 'mm/dd/yy';
        } else if ($dateFormat == '%d/%m/%Y') {
            $dateFormat = 'dd/mm/yy';
        } else if ($dateFormat == '%m-%d-%Y') {
            $dateFormat = 'mm-dd-yy';
        } else if ($dateFormat == '%d-%m-%Y') {
            $dateFormat = 'dd-mm-yy';
        } else if ($dateFormat == '%d %b %Y') {
            $dateFormat = 'dd M yy';
        } else if ($dateFormat == '%a, %d %b %Y') {
            $dateFormat = 'dd M yy';
        }

        $title = apply_filters('widget_title', empty($instance['title']) ? self::$DEFAULT_SETTINGS['title'] : esc_html($instance['title']));
        $button_text = apply_filters('button_text', empty($instance['button_text']) ? self::$DEFAULT_SETTINGS['button_text'] : esc_html($instance['button_text']));
        $horizontal = array_key_exists('horizontal', $instance) ? $instance['horizontal'] : self::$DEFAULT_SETTINGS['horizontal'];
        $horzAlign = array_key_exists('horz_alignment', $instance) ? $instance['horz_alignment'] : self::$DEFAULT_SETTINGS['horz_alignment'];
        $minNights = apply_filters('min_nights', empty($instance['min_nights']) ? self::$DEFAULT_SETTINGS['min_nights'] : intval($instance['min_nights']));
        if ($minNights < 1) {
            $minNights = 1;
        }
        $showDeparture = array_key_exists('departure', $instance) ? $instance['departure'] : self::$DEFAULT_SETTINGS['departure'];
        $showArrival = array_key_exists('arrival', $instance) ? $instance['arrival'] : self::$DEFAULT_SETTINGS['arrival'];
        $showLocation = array_key_exists('location', $instance) ? $instance['location'] : self::$DEFAULT_SETTINGS['location'];
        $showBedrooms = array_key_exists('bedrooms', $instance) ? $instance['bedrooms'] : self::$DEFAULT_SETTINGS['bedrooms'];
        $showPrice = array_key_exists('price', $instance) ? $instance['price'] : self::$DEFAULT_SETTINGS['price'];
        $fromPrice = apply_filters('from_price', empty($instance['from_price']) ? self::$DEFAULT_SETTINGS['from_price'] : esc_html($instance['from_price']));
        $toPrice = apply_filters('to_price', empty($instance['to_price']) ? self::$DEFAULT_SETTINGS['to_price'] : esc_html($instance['to_price']));
        $priceIncrement = apply_filters('price_increment', empty($instance['price_increment']) ? self::$DEFAULT_SETTINGS['price_increment'] : esc_html($instance['price_increment']));
        if (!empty($instance['currency_symbol'])) {
            $currencySymbol = esc_html($instance['currency_symbol']);
        } else {
            $properties = $wpdb->get_results("SELECT currency_symbol FROM $tableProperties LIMIT 1");
            if ($properties) {
                $currencySymbol = esc_html($properties[0]->currency_symbol);
            } else {
                $currencySymbol = self::$DEFAULT_SETTINGS['currency_symbol'];
            }
        }
        $currencySymbol = apply_filters('currency_symbol', $currencySymbol);
        $showPetFriendly = array_key_exists('pet_friendly', $instance) ? $instance['pet_friendly'] : self::$DEFAULT_SETTINGS['pet_friendly'];
        $showAmenities = array_key_exists('amenities', $instance) ? $instance['amenities'] : self::$DEFAULT_SETTINGS['amenities'];
        $showTags = array_key_exists('tags', $instance) ? $instance['tags'] : self::$DEFAULT_SETTINGS['tags'];
        $showName = array_key_exists('name', $instance) ? $instance['name'] : self::$DEFAULT_SETTINGS['name'];

        echo $before_widget;

        if (!$horizontal) {
            echo $before_title . LodgixTranslate::translate($title) . $after_title;
        }

        if ($datepickerLanguage != 'en') {
            echo '<script src="' . $pluginPath . 'js/i18n/datepicker-' . $datepickerLanguage. '.js"></script>';
        }

        $arrival = LodgixInventory::searchParam('lodgix-custom-search-arrival');
        $nights = LodgixInventory::searchParam('lodgix-custom-search-nights');
        $nights = (int)$nights;
        $categoryId = LodgixInventory::searchParam('lodgix-custom-search-area');
        $bedrooms = LodgixInventory::searchParam('lodgix-custom-search-bedrooms');
        $priceFrom = LodgixInventory::searchParam('lodgix-custom-search-daily-price-from');
        $priceFrom = (int)$priceFrom;
        $priceTo = LodgixInventory::searchParam('lodgix-custom-search-daily-price-to');
        $priceTo = (int)$priceTo;
        $petFriendly = LodgixInventory::searchParam('lodgix-custom-search-pet-friendly');
        $amenities = LodgixInventory::searchParamArr('lodgix-custom-search-amenity');
        if (!$amenities) {
            $amenities = array();
        }
        $amenities = array_map('stripslashes', $amenities);
        $tags = LodgixInventory::searchParamArr('lodgix-custom-search-tag');
        if (!$tags) {
            $tags = array();
        }
        $tags = array_map('stripslashes', $tags);
        $id = LodgixInventory::searchParam('lodgix-custom-search-id');
        $formbgc = apply_filters('formbgc', empty($instance['formbgc']) ? self::$DEFAULT_SETTINGS['formbgc'] : esc_html($instance['formbgc']));
        $buttonbgc = apply_filters('buttonbgc', empty($instance['buttonbgc']) ? self::$DEFAULT_SETTINGS['buttonbgc'] : esc_html($instance['buttonbgc']));
        $buttontc = apply_filters('buttontc', empty($instance['buttontc']) ? self::$DEFAULT_SETTINGS['buttontc'] : esc_html($instance['buttontc']));
        $widgettp = apply_filters('widgettp', empty($instance['widgettp']) ? self::$DEFAULT_SETTINGS['widgettp'] : esc_html($instance['widgettp']));
        $widgetrp = apply_filters('widgetrp', empty($instance['widgetrp']) ? self::$DEFAULT_SETTINGS['widgetrp'] : esc_html($instance['widgetrp']));
        $widgetbp = apply_filters('widgetbp', empty($instance['widgetbp']) ? self::$DEFAULT_SETTINGS['widgetbp'] : esc_html($instance['widgetbp']));
        $widgetlp = apply_filters('widgetlp', empty($instance['widgetlp']) ? self::$DEFAULT_SETTINGS['widgetlp'] : esc_html($instance['widgetlp']));
        $formWidth = apply_filters('formWidth', empty($instance['formWidth']) ? self::$DEFAULT_SETTINGS['formWidth'] : esc_html($instance['formWidth']));
        
        $widgettpun = apply_filters('widgettpun', empty($instance['widgettpun']) ? 'px' : $this->measurementCheck($instance['widgettpun'])); 
        $widgetbpun = apply_filters('widgetbpun', empty($instance['widgetbpun']) ? 'px' : $this->measurementCheck($instance['widgetbpun'])); 
        $widgetlpun = apply_filters('widgetlpun', empty($instance['widgetlpun']) ? 'px' : $this->measurementCheck($instance['widgetlpun'])); 
        $widgetrpun = apply_filters('widgetrpun', empty($instance['widgetrpun']) ? 'px' : $this->measurementCheck($instance['widgetrpun'])); 
        $formWidthun = apply_filters('formWidthun', empty($instance['formWidthun']) ? 'px' : $this->measurementCheck($instance['formWidthun'])); 


        ?>

        <style>
        
            .ldgxRentalSearchForm{
                <?php
                echo ($widgettp != '' ? 'padding-top: ' . $widgettp . $widgettpun . ';' : '');
                echo ($widgetrp != '' ? 'padding-right: ' . $widgetrp . $widgetrpun . ';' : '');
                echo ($widgetbp != '' ? 'padding-bottom: ' . $widgetbp . $widgetbpun . ';' : '');
                echo ($widgetlp != '' ? 'padding-left: ' . $widgetlp . $widgetlpun . ';' : '');
                echo ($formbgc != '' ? 'background-color: ' . $formbgc . ';\n' : '');
                echo ($formWidth != '' ? 'width: 100%; max-width: ' . $formWidth . $formWidthun . ';' : '');
                ?>
            }

            <?php if($buttonbgc || $buttontc){ ?>
            .ldgxRentalSearchWidget .ldgxRentalSearchDivSubmit button{
                <?php
                    echo ($buttonbgc != '' ? 'background-color: ' . $buttonbgc . ';' : '');
                    echo ($buttontc != '' ? 'color: ' . $buttontc . ';' : '');
                ?>
            }
            <?php } ?>
        </style>
            <div class="ldgxRentalSearchWidget <?php echo $horizontal ? 'ldgxRentalSearchWidgetHorizontal' : 'ldgxRentalSearchWidgetVertical'; ?> <?php echo $horzAlign; ?>">
                <form class="ldgxRentalSearchForm" method="post" action="<?php echo get_permalink((int)$lodgixSettings['p_lodgix_search_rentals_page_' . $currentLanguage]); ?>">
                    <div class="ldgxRentalSearchContainer">
                        <?php if ($showArrival) { ?>
                            <div class="ldgxRentalSearchDiv ldgxRentalSearchDivArrival">
                                <input type="text" class="ldgxRentalSearchDatepicker" name="lodgix-custom-search-datepicker" value="<?php echo htmlspecialchars(isset($_POST['lodgix-custom-search-datepicker']) ? $_POST['lodgix-custom-search-datepicker'] : '', ENT_QUOTES); ?>" placeholder="<?php echo LodgixTranslate::translate('Arriving'); ?>" title="<?php echo LodgixTranslate::translate('Arriving'); ?>" onchange="lodgixRentalSearch(this)" readonly>
            					<input type="hidden" class="ldgxRentalSearchArrival" name="lodgix-custom-search-arrival" value="<?php echo $arrival; ?>">
                            </div>
                        <?php } ?>
                        <?php if ($showDeparture) { ?>
                            <div class="ldgxRentalSearchDiv ldgxRentalSearchDivNights">
                                <select class="ldgxRentalSearchNights" name="lodgix-custom-search-nights" title="<?php echo LodgixTranslate::translate('Nights'); ?>" onchange="lodgixRentalSearch(this)">
                                    <?php
                                        if ($nights) {
                                            for ($i = $minNights; $i < 100; $i++) {
                                                $selected = $nights == $i ? 'selected' : '';
                                                echo "<option value='$i' $selected>$i " . LodgixTranslate::translate($i > 1 ? 'nights' : 'night') . "</option>";
                                            }
                                        } else {
                                            echo '<option value="" selected>' . LodgixTranslate::translate('Nights') . '</option>';
                                            for ($i = $minNights; $i < 100; $i++) {
                                                echo "<option value='$i'>$i " . LodgixTranslate::translate($i > 1 ? 'nights' : 'night') . "</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        <?php } ?>
                        <?php
                            if ($showLocation) {
                                ?>
                                <div class="ldgxRentalSearchDiv ldgxRentalSearchDivArea">
                                    <select class="ldgxRentalSearchArea" name="lodgix-custom-search-area" title="<?php echo LodgixTranslate::translate('Location'); ?>" onchange="lodgixRentalSearch(this)">
                                        <?php
                                            if ($categoryId == '') {
                                                echo '<option value="" selected>' . LodgixTranslate::translate('Location') . '</option>';
                                            }
                                            if (strtoupper($categoryId) == 'ALL_AREAS') {
                                                echo '<option value="ALL_AREAS" selected>' . LodgixTranslate::translate('All Areas') . '</option>';
                                            } else {
                                                echo '<option value="ALL_AREAS">' . LodgixTranslate::translate('All Areas') . '</option>';
                                            }
                                            $categories = (new LodgixServiceCategories())->getAll();
                                            foreach ($categories as $category) {
                                                $selected = $categoryId == $category->category_id ? 'selected' : '';
                                                echo "<option value='$category->category_id' $selected>$category->category_title_long</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <?php
                            }
                            if ($showBedrooms) {
                                ?>
                                <div class="ldgxRentalSearchDiv ldgxRentalSearchDivBedrooms">
                                    <select class="ldgxRentalSearchBedrooms" name="lodgix-custom-search-bedrooms" title="<?php echo LodgixTranslate::translate('Bedrooms'); ?>" onchange="lodgixRentalSearch(this)">
                                        <?php
                                            $minRooms = (int)$wpdb->get_var("SELECT MIN(bedrooms) FROM $tableProperties");
                                            $maxRooms = (int)$wpdb->get_var("SELECT MAX(bedrooms) FROM $tableProperties");
                                            if ($bedrooms !== null) {
                                                if (strtoupper($bedrooms) == 'ANY') {
                                                    echo '<option value="ANY" selected>' . LodgixTranslate::translate('Any Bedrooms') . '</option>';
                                                    if ($minRooms == 0) {
                                                        echo '<option value="0">' . LodgixTranslate::translate('Studio') . '</option>';
                                                    }
                                                    for ($i = 1; $i <= $maxRooms; $i++) {
                                                        echo "<option value='$i'>$i " . LodgixTranslate::translate($i > 1 ? 'bedrooms' : 'bedroom') . "</option>";
                                                    }
                                                } else {
                                                    echo '<option value="ANY">' . LodgixTranslate::translate('Any Bedrooms') . '</option>';
                                                    if ($minRooms == 0) {
                                                        if ($bedrooms == '0') {
                                                            echo '<option value="0" selected>' . LodgixTranslate::translate('Studio') . '</option>';
                                                        } else {
                                                            echo '<option value="0">' . LodgixTranslate::translate('Studio') . '</option>';
                                                        }
                                                    }
                                                    $value = (int)$bedrooms;
                                                    for ($i = 1; $i <= $maxRooms; $i++) {
                                                        $selected = $value == $i ? 'selected' : '';
                                                        echo "<option value='$i' $selected>$i " . LodgixTranslate::translate($i > 1 ? 'bedrooms' : 'bedroom') . "</option>";
                                                    }
                                                }
                                            } else {
                                                echo '<option value="" selected>' . LodgixTranslate::translate('Bedrooms') . '</option>';
                                                echo '<option value="ANY">' . LodgixTranslate::translate('Any Bedrooms') . '</option>';
                                                if ($minRooms == 0) {
                                                    echo '<option value="0">' . LodgixTranslate::translate('Studio') . '</option>';
                                                }
                                                for ($i = 1; $i <= $maxRooms; $i++) {
                                                    echo "<option value='$i'>$i " . LodgixTranslate::translate($i > 1 ? 'bedrooms' : 'bedroom') . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <?php
                            }
                            if ($showPrice) {
                                ?>
                                <div class="ldgxRentalSearchDiv ldgxRentalSearchDivDailyPriceFrom">
                                    <select class="ldgxRentalSearchDailyPriceFrom" name="lodgix-custom-search-daily-price-from" title="<?php echo LodgixTranslate::translate('Daily Price From'); ?>" onchange="lodgixRentalSearch(this)">
                                        <?php
                                            if ($priceFrom) {
                                                echo '<option value="0" ' . ($priceFrom == 0 ? 'selected' : '')
                                                    . '>' . LodgixTranslate::translate('From Any Price') . '</option>';
                                                for ($i = $fromPrice; $i <= $toPrice; $i += $priceIncrement) {
                                                    echo '<option value="' . $i . '" '
                                                        . ($priceFrom == $i ? 'selected' : '') . '>' . LodgixTranslate::translate('From')
                                                        . ' ' . $currencySymbol . $i . ' ' . LodgixTranslate::translate('per nt')
                                                        . '</option>';
                                                }
                                            } else {
                                                echo '<option value="" selected>' . LodgixTranslate::translate('Daily Price From')
                                                    . '</option>';
                                                echo '<option value="0">' . LodgixTranslate::translate('From Any Price') . '</option>';
                                                for ($i = $fromPrice; $i <= $toPrice; $i += $priceIncrement) {
                                                    echo '<option value="' . $i . '">' . LodgixTranslate::translate('From') . ' '
                                                        . $currencySymbol . $i . ' ' . LodgixTranslate::translate('per nt') . '</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="ldgxRentalSearchDiv ldgxRentalSearchDivDailyPriceTo">
                                    <select class="ldgxRentalSearchDailyPriceTo" name="lodgix-custom-search-daily-price-to" title="<?php echo LodgixTranslate::translate('Daily Price To'); ?>" onchange="lodgixRentalSearch(this)">
                                        <?php
                                            if ($priceTo) {
                                                echo '<option value="0" ' . ($priceTo == 0 ? 'selected' : '')
                                                    . '>' . LodgixTranslate::translate('To Any Price') . '</option>';
                                                for ($i = $fromPrice; $i <= $toPrice; $i += $priceIncrement) {
                                                    echo '<option value="' . $i . '" '
                                                        . ($priceTo == $i ? 'selected' : '') . '>' . LodgixTranslate::translate('To') . ' '
                                                        . $currencySymbol . $i . ' ' . LodgixTranslate::translate('per nt') . '</option>';
                                                }
                                            } else {
                                                echo '<option value="" selected>' . LodgixTranslate::translate('Daily Price To') . '</option>';
                                                echo '<option value="0">' . LodgixTranslate::translate('To Any Price') . '</option>';
                                                for ($i = $fromPrice; $i <= $toPrice; $i += $priceIncrement) {
                                                    echo '<option value="' . $i . '">' . LodgixTranslate::translate('To') . ' '
                                                        . $currencySymbol . $i . ' ' . LodgixTranslate::translate('per nt') . '</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <?php
                            }
                            if ($showPetFriendly || $showAmenities || $showTags) {
                                echo '<div class="ldgxRentalSearchDiv ldgxRentalSearchDivAmenities">';
                                if ($showPetFriendly) {
                                    if ($petFriendly == 'on') {
                                        $checked = 'checked';
                                    } else {
                                        $checked = '';
                                    }
                                    ?>
                                    <div class="ldgxRentalSearchAmenity">
                                        <label>
                                            <input type="checkbox"
                                                   class="ldgxRentalSearchPetFriendly"
                                                   name="lodgix-custom-search-pet-friendly"
                                                   <?php echo $checked; ?>
                                                   onclick='lodgixRentalSearch(this)'>
                                           <?php echo LodgixTranslate::translate('Pet Friendly'); ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                                if ($showAmenities) {
                                    $dbAmenities = $wpdb->get_results("SELECT DISTINCT * FROM $tableAmenities");
                                    $i = 0;
                                    foreach ($dbAmenities as $amenity) {
                                        $amenityName = trim($amenity->description);
                                        $amenityNameTranslated = LodgixTranslate::translateTerm($amenityName, $currentLanguage);
                                        $checked = in_array($amenityName, $amenities) ? 'checked' : '';
                                        $amenityName = htmlspecialchars($amenityName, ENT_QUOTES);
                                        echo "
                                            <div class='ldgxRentalSearchAmenity'>
                                                <label>
                                                    <input type='checkbox'
                                                           class='ldgxRentalSearchAmenity$i'
                                                           name='lodgix-custom-search-amenity[]'
                                                           value='$amenityName'
                                                           $checked
                                                           onclick='lodgixRentalSearch(this)'>
                                                    $amenityNameTranslated
                                                </label>
                                            </div>
                                        ";
                                        $i++;
                                    }
                                }
                                if ($showTags) {
                                    $dbTags = $wpdb->get_results("SELECT * FROM $tableTags ORDER BY tag");
                                    $i = 0;
                                    foreach ($dbTags as $tag) {
                                        $tagName = trim($tag->tag);
                                        $tagNameTranslated = LodgixTranslate::translate(ucwords($tagName), $currentLanguage);
                                        $checked = in_array($tagName, $tags) ? 'checked' : '';
                                        $tagName = htmlspecialchars($tagName, ENT_QUOTES);
                                        echo "
                                            <div class='ldgxRentalSearchAmenity'>
                                                <label>
                                                    <input type='checkbox'
                                                           class='ldgxRentalSearchTag$i'
                                                           name='lodgix-custom-search-tag[]'
                                                           value='$tagName'
                                                           $checked
                                                           onclick='lodgixRentalSearch(this)'>
                                                    $tagNameTranslated
                                                </label>
                                            </div>
                                        ";
                                        $i++;
                                    }
                                }
                                echo '</div>';
                            }
                            if ($showName) {
                                ?>
                                <div class="ldgxRentalSearchDiv ldgxRentalSearchDivId">
                                    <input type="text"
                                           class="ldgxRentalSearchId"
                                           name="lodgix-custom-search-id"
                                           placeholder="<?php echo LodgixTranslate::translate('Property Name or ID'); ?>"
                                           onkeyup="lodgixRentalSearch(this)"
                                           value="<?php echo $id; ?>">
                                </div>
                                <?php
                            }
                        ?>
                        <div class="ldgxRentalSearchDiv ldgxRentalSearchDivSubmit">
                            <button class="ldgxRentalSearchSubmit"><?php echo LodgixTranslate::translate($button_text); ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                (function($) {
                    'use strict';

                    $.fn.isOnScreen = function(){
                        var win = $(window);
                        var viewport = {
                            top : win.scrollTop(),
                            left : win.scrollLeft()
                        };
                        viewport.right = viewport.left + win.width();
                        viewport.bottom = viewport.top + win.height();
                        var bounds = this.offset();
                        bounds.right = bounds.left + this.outerWidth();
                        bounds.bottom = bounds.top + this.outerHeight();
                        return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
                    };

                    window.lodgixRentalSearch = function(el) {
                        var arrivalVal = $('.ldgxRentalSearchArrival').val();
                        if (arrivalVal) {
                            // With 'T12:00:00' date will be parsed as local time instead of UTC
                            jQueryLodgix('.ldgxRentalSearchDatepicker').datepicker('setDate', new Date(arrivalVal + 'T12:00:00'));
                        } else {
                            var tomorrow = new Date();
                            tomorrow.setDate(tomorrow.getDate() + 1);
                            jQueryLodgix('.ldgxRentalSearchDatepicker').datepicker('setDate', tomorrow);
                        }
                        $('.ldgxRentalSearchNights option[value=""]').remove();
                        $('.ldgxRentalSearchArea option[value=""]').remove();
                        $('.ldgxRentalSearchBedrooms option[value=""]').remove();
                        $('.ldgxRentalSearchDailyPriceFrom option[value=""]').remove();
                        $('.ldgxRentalSearchDailyPriceTo option[value=""]').remove();
                        if (parseInt($('.ldgxRentalSearchDailyPriceFrom').val()) > parseInt($('.ldgxRentalSearchDailyPriceTo').val())) {
                            $('.ldgxRentalSearchDailyPriceTo option[value="0"]').prop('selected', true);
                        }

                        // Sync multiple forms on the same page
                        el = $(el);
                        var els = $('.' + el.attr('class'));
                        els.val(el.val());
                        els.prop('checked', el.prop('checked'));

                        $('.ldgxRentalSearchSubmit').addClass('ldgxRainbowAnimation');
                        $('.ldgxRentalSearchForm').ajaxSubmit({
                            url: '<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php',
                            data: {
                                'action': 'p_lodgix_custom_search'
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response) {
                                    $('.ldgxRentalSearchSubmit').text('Display ' + (response.num_results || 0) + ' Results');
                                    var minNights = <?php echo $minNights ?>;
                                    var selectbox = $('.ldgxRentalSearchNights');
                                    var selected = parseInt(selectbox.val());
                                    if (isNaN(selected) || selected < minNights) {
                                        selected = minNights;
                                    }
                                    var options = [];
                                    for (var i = minNights; i < 100; i++) {
                                        options.push('<option value="');
                                        options.push(i);
                                        options.push('">');
                                        options.push(i);
                                        if (i > 1) {
                                            options.push(' <?php echo LodgixTranslate::translate('nights'); ?>');
                                        } else {
                                            options.push(' <?php echo LodgixTranslate::translate('night'); ?>');
                                        }
                                        options.push('</option>');
                                    }
                                    selectbox.empty().append(options.join(''));
                                    $('.ldgxRentalSearchNights option[value="' + selected + '"]').prop('selected', true);
                                }
                            },
                            complete: function() {
                                $('.ldgxRentalSearchSubmit').removeClass('ldgxRainbowAnimation');
                            }
                        });
                    };

                    var maxDate = null;
                    var limitDays = parseInt('<?php echo $limitBookingDaysAdvance; ?>');
                    if (!isNaN(limitDays) && limitDays > 0) {
                        maxDate = new Date();
                        maxDate.setDate(maxDate.getDate() + limitDays);
                    }

                    $(document).ready(function() {
                        var dateFormat = '<?php echo $dateFormat; ?>';
                        var datepickerEl = jQueryLodgix('.ldgxRentalSearchDatepicker');
                        datepickerEl.datepicker({
                                showOn: 'both',
                                buttonImage: '<?php echo $pluginPath; ?>images/calendar.png',
                                buttonImageOnly: true,
                                buttonText: '',
                                dateFormat: dateFormat,
                                altField: '.ldgxRentalSearchArrival',
                                altFormat: 'yy-mm-dd',
                                minDate: 0,
                                maxDate: maxDate,
                                beforeShow: function() {
                                    setTimeout(function() {
                                        jQueryLodgix('.lodgix-datepicker').css('z-index', 99999999999999);
                                    }, 0);
                                }
                            }<?php if ($datepickerLanguage != 'en') { echo ', jQueryLodgix.datepicker.regional["' . $datepickerLanguage. '"]'; } ?>)
                            .next('.lodgix-datepicker-trigger')
                            .addClass('ldgxRentalSearchDatepickerTrigger')
                            .attr('alt', 'Datepicker');

                        if (maxDate) {
                            var date = jQueryLodgix.datepicker.parseDate(dateFormat, datepickerEl.val());
                            if (date > maxDate) {
                                jQueryLodgix('.ldgxRentalSearchDatepicker').datepicker('setDate', maxDate);
                            }
                        }
                        if (!datepickerEl.val()) {
                            var arrivalVal = $('.ldgxRentalSearchArrival').val();
                            if (arrivalVal) {
                                // With 'T12:00:00' date will be parsed as local time instead of UTC
                                jQueryLodgix('.ldgxRentalSearchDatepicker').datepicker('setDate', new Date(arrivalVal + 'T12:00:00'));
                            }
                        }
                    });

                }(jQLodgix));
            </script>
        <?php

		echo $after_widget;
	}
}

function lodgixRegisterWidgetRentalSearch2() {
    register_widget('LodgixWidgetRentalSearch2');
}

add_action('widgets_init', 'lodgixRegisterWidgetRentalSearch2');
