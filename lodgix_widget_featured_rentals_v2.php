<?php

class Lodgix_Featured_Rentals_Widget_v2 extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'ldx_frw_v2', // Base ID
            'Lodgix Featured Rentals', // Name
            array( 'description' => __( 'Displays Featured Rental Properties', 'text_domain' ), ) // Args
        );
    }

    public function getProperties( $args, $instance ){

        global $wpdb;

        extract($args);

        $properties_table = $wpdb->prefix . "lodgix_properties";
        $lang_pages_table = $wpdb->prefix . "lodgix_lang_pages";
		$pages_table = $wpdb->prefix . "lodgix_pages";

		// Each widget can store its own options. We keep strings here.
		$loptions = get_option('p_lodgix_options');

        $sql = 'SELECT ' . $properties_table . '.id AS id,property_id,description,enabled,featured,main_image_thumb,
                bedrooms,bathrooms,sleeps,proptype,city,state_code,post_id FROM ' . $properties_table . '
                LEFT JOIN ' . $pages_table .  ' ON ' . $properties_table . '.id = ' . $pages_table .  '.property_id';

        if (!$loptions['p_lodgix_featured_select_all']) {
            // Show only featured properties
            $sql .= ' WHERE featured=1';
        }

        if (!empty($instance['rotate'])) {
            // Rotate
            $sql .= ' ORDER BY rand()';
        } else {
            $sql .= ' ORDER BY id';
        }

        $limit = $instance['display_properties'];
        if (!isset($limit) || !is_numeric($limit)) {
            $limit = 3;
        }
        if ($limit > 0) {
            // Limit number of displayed properties
            $sql .= ' LIMIT ' . $limit;
        }

        $propertiesResults = $wpdb->get_results($sql);

        //reset keys, this is for future use of the plugin
        foreach($propertiesResults as $property){
            if(isset($property->post_id)){
                if (isset($_REQUEST['lang']) && $_REQUEST['lang'] == "de") {
    				$page_id = $wpdb->get_var("SELECT page_id FROM " . $lang_pages_table . " WHERE property_id=" . $property->id);
    				$permalink = get_permalink($page_id);
                    $property->link = $permalink;
    			} else {
                    $property->property_id = $property->post_id;
                    $propertyLink = get_permalink($property->property_id);
                    $property->link = $propertyLink;
                }
                unset($property->post_id);
            }
            if(isset($property->main_image_thumb)){
                $property->image_url = $property->main_image_thumb;
                unset($property->main_image_thumb);
            }
            if(isset($property->description)){
                $property->title = $property->description;
                unset($property->description);
            }
            if(isset($property->proptype)){
                if ($property->proptype == 'Room type') {
                    $property->property_type = '';
                } else {
                    $property->property_type = $property->proptype;
                }
                unset($property->proptype);
            }
            if(isset($property->bedrooms)){
                if ($property->bedrooms == 0) {
    				$property->details['bedrooms']['amount'] = LodgixTranslate::translate('Studio') . ', ';
    			} else {
                    $property->details['bedrooms']['amount'] = $property->bedrooms;
                    $property->details['bedrooms']['mobileText'] = LodgixTranslate::translate('Beds');
                }
                unset($property->bedrooms);
            }
            if(isset($property->bathrooms)){
                $property->details['bathrooms']['amount'] = $property->bathrooms;
                $property->details['bathrooms']['mobileText'] = LodgixTranslate::translate('Baths');
                unset($property->bathrooms);
            }
            if(isset($property->sleeps)){
                $property->details['sleeps']['amount'] = $property->sleeps;
                unset($property->sleeps);
            }
            if(isset($property->city)){
                $property->location = $property->city . ', ' . $property->state_code;
                unset($property->city);
                unset($property->state_code);
            }
        }

        return $propertiesResults;
    }

    public function widgetStyles( $args, $instance ){
        $styles = '';
        $widgetStyles = array();

        //widget text alignment
        if(!empty($instance['w_align'])){
            if($instance['w_align'] == 0){
                $alignment = 'left';
            } else if($instance['w_align'] == 2){
                $alignment = 'right';
            } else {
                $alignment = 'center';
            }
            $widgetStyles['widget']['text-align'] = $alignment;
        }

        //widget text color
        if(!empty($instance['widgettextc'])){
            $widgetStyles['widget']['color'] = strip_tags($instance['widgettextc']);
        }

        //header Color
        if(!empty($instance['widgetheaderc'])){
            $widgetStyles['header']['color'] = strip_tags($instance['widgetheaderc']);
        }

        //compile #featured-properties-widget-container
        if(!empty($widgetStyles['widget'])){
            $widgetStylesComp = '';
            foreach($widgetStyles['widget'] as $styleProperty => $value){
                $widgetStylesComp .= $styleProperty . ': ' . $value . ';';
            }
            $styles .= '#featured-properties-widget-container{' . $widgetStylesComp . '}';
        }

        //compile #featured-properties-widget-container h2.widgettitle
        if(!empty($widgetStyles['header'])){
            $widgetHeaderStylesComp = '';
            foreach($widgetStyles['header'] as $styleProperty => $value){
                $widgetHeaderStylesComp .= $styleProperty . ': ' . $value . ';';
            }
            $styles .= '#featured-properties-widget-container h2.widgettitle{' . $widgetHeaderStylesComp . '}';
        }

        return $styles;
    }

    public function propertyStyles( $args, $instance ){

        $styles = '';
        $propertyStyles = array();

        //widget text alignment
        if(!empty($instance['p_align'])){
            if($instance['p_align'] == 0){
                $alignment = 'left';
            } else if($instance['p_align'] == 2){
                $alignment = 'right';
            } else {
                $alignment = 'center';
            }
            $propertyStyles['property']['text-align'] = $alignment;
        }

        //property background color
        if($instance['proprtybgc']){
            $propertyStyles['property']['background-color'] = strip_tags($instance['proprtybgc']);
        }

        //property border
        if($instance['pbordersize']){
            if(!$instance['propertyborderc']){
                $borderColor = '#777777';
            } else {
                $borderColor = strip_tags($instance['propertyborderc']);
            }
            $propertyStyles['property']['border'] = strip_tags($instance['pbordersize']) . strip_tags($instance['pbordersizeun']) . ' solid ' . $borderColor;
        }


        //property text color
        if(!empty($instance['propertytextc'])){
            $propertyStyles['property']['color'] = strip_tags($instance['propertytextc']);
            $propertyStyles['svg']['fill'] = strip_tags($instance['propertytextc']);
        }

        //property link color
        if(!empty($instance['propertylinkc'])){
            $propertyStyles['link']['color'] = strip_tags($instance['propertylinkc']);
        }

        //property image Padding
        if($instance['pimgpadding']){
            $propertyStyles['imgcont']['padding'] = strip_tags($instance['pimgpadding']) . strip_tags($instance['pimgpaddingun']);
        }

        //property content padding
        if($instance['pconpadding']){
            $propertyStyles['content']['padding'] = strip_tags($instance['pconpadding']) . strip_tags($instance['pconpaddingun']);
        }

        //compile .ldx-featured-property styles
        if(!empty($propertyStyles['property'])){
            $propertyStylesComp = '';
            foreach($propertyStyles['property'] as $styleProperty => $value){
                $propertyStylesComp .= $styleProperty . ': ' . $value . ';';
            }
            $styles .= '.ldx-featured-property-inner{' . $propertyStylesComp . '}';
        }

        //compile .ldx-featured-property a styles
        if(!empty($propertyStyles['link'])){
            $propertyLinkStylesComp = '';
            foreach($propertyStyles['link'] as $styleProperty => $value){
                $propertyLinkStylesComp .= $styleProperty . ': ' . $value . ';';
            }
            $styles .= '.ldx-featured-property a{' . $propertyLinkStylesComp . '}';
        }


        //compile .ldx-featured-property-upper styles
        if(!empty($propertyStyles['imgcont'])){
            $propertyImgContStylesComp = '';
            foreach($propertyStyles['imgcont'] as $styleProperty => $value){
                $propertyImgContStylesComp .= $styleProperty . ': ' . $value . ';';
            }
            $styles .= '.ldx-featured-property-upper{' . $propertyImgContStylesComp . '}';
        }

        //compile .ldx-featured-property-content styles
        if(!empty($propertyStyles['content'])){
            $propertyContentStylesComp = '';
            foreach($propertyStyles['content'] as $styleProperty => $value){
                $propertyContentStylesComp .= $styleProperty . ': ' . $value . ';';
            }
            $styles .= '.ldx-featured-property-content{' . $propertyContentStylesComp . '}';
        }

        //compile .ldx-featured-property-content .inline-icon styles
        if(!empty($propertyStyles['svg'])){
            $propertyContentSVGComp = '';
            foreach($propertyStyles['svg'] as $styleProperty => $value){
                $propertyContentSVGComp .= $styleProperty . ': ' . $value . ';';
            }
            $styles .= '.ldx-featured-property-content .inline-icon svg path{' . $propertyContentSVGComp . '}';
        }

        // //compile .ldx-featured-property-banner styles
        if(!empty($propertyStyles['banner'])){
            $propertyBannerStylesComp = '';
            foreach($propertyStyles['banner'] as $styleProperty => $value){
                $propertyBannerStylesComp .= $styleProperty . ': ' . $value . ';';
            }
            $styles .= '.ldx-featured-property-banner{' . $propertyBannerStylesComp . '}';
        }

        //output compiled property styles
        return $styles;
    }

    public function widget( $args, $instance ) {

        // outputs the content of the widget

        $widgetStyles = $this->widgetStyles($args, $instance);
        $propertyStyles = $this->propertyStyles($args, $instance);

        if(!empty($widgetStyles || $propertyStyles)){
            echo '<style>';
            if(!empty($widgetStyles)){
                echo $widgetStyles;
            }
            if(!empty($propertyStyles)){
                echo $propertyStyles;
            }
            echo '</style>';
        }

        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        // get the property data
        $properties = $this->getProperties( $args, $instance );
        $layoutValue = strip_tags($instance['layout']);
        $layout = 'ldx-featured';
        $columns = '';

        if($instance['columns']){
            $columns = 'ldx-featured-columns-' . $instance['columns'];
        } else {
            $columns = 'ldx-featured-columns-4';
        }

        if($layoutValue == "0"){
            $layout .= '-vertical';
            $columns = '';
        } elseif ($layoutValue == "1") {
            $layout .= '-horizontal';
        } elseif ($layoutValue == "2") {
            $layout .= '-horizontal-left';
        } elseif ($layoutValue == "3") {
            $layout .= '-horizontal-right';
        }

        echo $before_widget;

        echo '<div id="featured-properties-widget-container">';

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        if($instance['w_before']){
            $w_before = apply_filters( 'w_before', $instance['w_before'] );
            echo '<p class="ldx-featured-content-before">' . $w_before . '</p>';
        }

        if($properties){
            echo '<div id="featured-properties-container" class="' . $layout . ' ' . $columns . '">';
            foreach($properties as $property){
                $property = (array) $property;
                // get the property markup
                echo $this->widgetProperty( $args, $instance, $property);
            }
            echo '</div>';
        }

        echo '</div>';

        echo $after_widget;
    }

    public function widgetProperty( $args, $instance, $property = [
        'id' => 'demo',
        'link' => '#',
        'image_url' => 'https://placehold.it/474x355&text=placeholder',
        'title' => 'Property Title',
        'property_type' => 'Apartment',
        'details' => array(),
        'location' => 'Elkin, NC'
    ] ){

        if(!$instance['show_bedrooms']){
            unset($property['details']['bedrooms']);
        }
        if(!$instance['show_bathrooms']){
            unset($property['details']['bathrooms']);
        }
        if(!$instance['show_guests']){
            unset($property['details']['sleeps']);
        }

        ob_start();

        extract($property);
        ?>
        <div id="ldx-ft-p-<?php echo $id ?>" class="ldx-featured-property">
            <div class="ldx-featured-property-inner">
                <?php /*if($instance['display_banner']){ ?>
                    <div class="ldx-featured-property-banner"><span><?php _e('Featured'); ?></span></div>
                <?php } */?>
                <div class="ldx-featured-property-upper">
                    <?php if($image_url){ ?>
                        <a href="<?php echo esc_html($link); ?>">
                            <div class="ldx-featured-property-img" style="background-image: url(<?php echo $image_url; ?>);">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 474 355" style="enable-background:new 0 0 474 355;" xml:space="preserve"></svg>
                            </div>
                        </a>
                    <?php } ?>
                    <!--
                        <div class="ldx-featured-property-heart">Heart</div>
                    -->
                </div>
                <div class="ldx-featured-property-content">

                    <h3 class="ldx-featured-property-title">
                        <a href="<?php echo esc_html($link); ?>">
                            <?php _e($title); ?>
                        </a>
                    </h3>

                    <?php if($instance['show_location']){ ?>
                        <p class="ldx-featured-property-location inline-icon">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <style type="text/css">
                            	.st0{fill:#939598;}
                            </style>
                            <g>
                            	<g>
                            		<path class="st0" d="M256,0C165,0,91,74,91,165c0,117.2,88.9,264.1,153.5,341.6c6,7.2,17.1,7.2,23.1,0
                            			C323.4,439.5,421,289,421,165C421,74,347,0,256,0z M256,472.8C221.4,427.5,121,285.5,121,165c0-74.4,60.6-135,135-135
                            			s135,60.6,135,135C391,267.2,317.5,392.4,256,472.8z"/>
                            	</g>
                            </g>
                            <g>
                            	<g>
                            		<path class="st0" d="M256,100c-35.8,0-65,29.2-65,65s29.2,65,65,65s65-29.2,65-65S291.8,100,256,100z M256,200
                            			c-19.3,0-35-15.7-35-35s15.7-35,35-35s35,15.7,35,35S275.3,200,256,200z"/>
                            	</g>
                            </g>
                            </svg>
                            <span class="location"><?php _e($location); ?></span>
                        </p>
                    <?php } ?>

                    <?php if($instance['display_proptype']){ ?>
                        <p class="ldx-featured-property-prop-type inline-icon">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <style type="text/css">
                            	.st0{fill:#939598;}
                            </style>
                            <g id="Apartment-2">
                            	<path class="st0" d="M496,472h-8c-4.4,0-8,3.6-8,8s3.6,8,8,8h8c4.4,0,8-3.6,8-8S500.4,472,496,472z"/>
                            	<path class="st0" d="M152,320c4.4,0,8-3.6,8-8v-48c0-4.4-3.6-8-8-8h-48c-4.4,0-8,3.6-8,8v48c0,4.4,3.6,8,8,8H152z M112,272h32v32
                            		h-32V272z"/>
                            	<path class="st0" d="M184,320h48c4.4,0,8-3.6,8-8v-48c0-4.4-3.6-8-8-8h-48c-4.4,0-8,3.6-8,8v48C176,316.4,179.6,320,184,320z
                            		 M192,272h32v32h-32V272z"/>
                            	<path class="st0" d="M104,240h48c4.4,0,8-3.6,8-8v-48c0-4.4-3.6-8-8-8h-48c-4.4,0-8,3.6-8,8v48C96,236.4,99.6,240,104,240z
                            		 M112,192h32v32h-32V192z"/>
                            	<path class="st0" d="M184,240h48c4.4,0,8-3.6,8-8v-48c0-4.4-3.6-8-8-8h-48c-4.4,0-8,3.6-8,8v48C176,236.4,179.6,240,184,240z
                            		 M192,192h32v32h-32V192z"/>
                            	<path class="st0" d="M104,160h48c4.4,0,8-3.6,8-8v-48c0-4.4-3.6-8-8-8h-48c-4.4,0-8,3.6-8,8v48C96,156.4,99.6,160,104,160z
                            		 M112,112h32v32h-32V112z"/>
                            	<path class="st0" d="M184,160h48c4.4,0,8-3.6,8-8v-48c0-4.4-3.6-8-8-8h-48c-4.4,0-8,3.6-8,8v48C176,156.4,179.6,160,184,160z
                            		 M192,112h32v32h-32V112z"/>
                            	<path class="st0" d="M344,376c4.4,0,8-3.6,8-8v-8c0-4.4-3.6-8-8-8s-8,3.6-8,8v8C336,372.4,339.6,376,344,376z"/>
                            	<path class="st0" d="M488,416c0-10.4-3.4-20.4-9.7-28.7c4-13.5,0.6-28.2-9.1-38.5c1.8-4,2.7-8.4,2.7-12.8c0-17.7-14.3-32-32-32
                            		s-32,14.3-32,32c0,4.4,0.9,8.8,2.7,12.8c-9.7,10.3-13.1,24.9-9.1,38.5c-15.9,21.1-11.8,51,9.3,67c6.2,4.7,13.4,7.8,21.1,9v8.7h-80
                            		v-40.7c26.1-4.3,43.7-28.9,39.4-55c-1.3-7.6-4.4-14.9-9-21c4-13.5,0.6-28.2-9.1-38.5c1.8-4,2.7-8.4,2.7-12.8c0-17.7-14.3-32-32-32
                            		s-32,14.3-32,32c0,4.4,0.9,8.8,2.7,12.8c-9.7,10.3-13.1,24.9-9.1,38.5c-15.9,21.1-11.8,51,9.3,67c6.2,4.7,13.4,7.8,21.1,9v9.8
                            		l-11.6-7.7c-3.7-2.5-8.6-1.5-11.1,2.2s-1.5,8.6,2.2,11.1l0,0l20.4,13.6V472h-32v-24c0-4.4-3.6-8-8-8h-24V72h24c4.4,0,8-3.6,8-8V32
                            		c0-4.4-3.6-8-8-8H40c-4.4,0-8,3.6-8,8v32c0,4.4,3.6,8,8,8h24v368H40c-4.4,0-8,3.6-8,8v24H16c-4.4,0-8,3.6-8,8s3.6,8,8,8h448
                            		c4.4,0,8-3.6,8-8s-3.6-8-8-8h-16v-8.7C471.1,459.4,488,439.4,488,416z M312,384c0-8.1,3.1-15.8,8.6-21.7c2.2-2.3,2.7-5.8,1.4-8.7
                            		c-4.6-10.2-1.4-22.2,7.7-28.8c3.5-2.7,4.3-7.7,1.6-11.2l0,0c-2.1-2.8-3.3-6.2-3.3-9.7c0-8.8,7.2-16,16-16s16,7.2,16,16
                            		c0,3.5-1.2,6.9-3.3,9.6c-2.7,3.5-1.9,8.5,1.6,11.2l0,0c9,6.6,12.2,18.6,7.6,28.8c-1.3,2.9-0.7,6.3,1.5,8.7
                            		c12,12.8,11.4,32.9-1.3,44.9c-4,3.7-8.8,6.4-14.1,7.7V392c0-4.4-3.6-8-8-8s-8,3.6-8,8v22.9C321.9,411.2,312.1,398.5,312,384z
                            		 M48,40h240v16H48V40z M80,72h176v368h-48v-88c0-4.4-3.6-8-8-8h-64c-4.4,0-8,3.6-8,8v88H80V72z M192,440h-48v-80h48V440z M48,472
                            		v-16h240v16H48z M408,416c0-8.1,3.1-15.8,8.6-21.7c2.2-2.3,2.7-5.8,1.4-8.7c-4.6-10.2-1.4-22.2,7.7-28.8c3.5-2.7,4.3-7.7,1.6-11.2
                            		l0,0c-2.1-2.8-3.3-6.2-3.3-9.7c0-8.8,7.2-16,16-16s16,7.2,16,16c0,3.5-1.2,6.9-3.3,9.6c-2.7,3.5-1.9,8.5,1.6,11.2l0,0
                            		c9,6.6,12.2,18.6,7.6,28.8c-1.3,2.9-0.7,6.3,1.5,8.7c12,12.8,11.4,32.9-1.3,44.9c-4,3.7-8.8,6.4-14.1,7.7V424c0-4.4-3.6-8-8-8
                            		s-8,3.6-8,8v22.9C417.9,443.2,408.1,430.5,408,416z"/>
                            	<path class="st0" d="M344,216h56c1.4,0,2.8-0.4,4-1.2c14.8-4.4,23.3-20,18.9-34.8c-3.2-10.8-12.6-18.6-23.8-19.8
                            		c-4.3-17.1-21.6-27.5-38.7-23.2c-8.5,2.1-15.7,7.6-20,15.3c-17.6,2-30.1,17.9-28.1,35.5C314.1,203.8,327.8,216,344,216z M343.7,168
                            		l1.2,0.1c3.6,0.3,6.9-1.9,8.1-5.4c2.8-8.3,11.9-12.8,20.2-9.9c6.4,2.2,10.7,8.1,10.8,14.8c0,0.2,0,0.4,0,0.5
                            		c-0.1,4.4,3.4,8.1,7.8,8.2c0.6,0,1.2,0,1.8-0.2c6.6-1.2,13,3.2,14.2,9.8c0.1,0.6,0.2,1.3,0.2,2c0,5.7-4,10.6-9.6,11.8
                            		c-0.3,0.1-0.6,0.1-0.9,0.2H344c-8.8,0.1-16.1-7-16.1-15.9C327.8,175.3,334.9,168.1,343.7,168z"/>
                            </g>
                            </svg>
                            <?php _e($property_type); ?>
                        </p>
                    <?php } ?>

                    <?php if($details){ ?>
                        <ul class="ldx-featured-property-details has-icon">
                            <?php foreach( $details as $detail => $amount){ ?>
                                <li class="<?php echo strtolower(str_replace(' ', '-', $detail)); ?>">
                                    <?php if(isset($amount['mobileText'])){ ?>
                                        <?php echo $amount['amount']; ?>
                                        <span class="desktopText"> <?php echo ucwords(strtolower($detail)); ?></span>
                                        <span class="mobileText"><?php _e($amount['mobileText']); ?></span>
                                    <?php } else { ?>
                                        <?php echo $amount['amount'] . ' ' . ucwords(strtolower($detail)); ?>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public function form( $instance ) {


        // outputs the options form in the admin
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'Featured Rentals', 'text_domain' );
        }

        if ( isset( $instance[ 'layout' ] ) ) {
            $layout = $instance[ 'layout' ];
        } else {
            $layout = 2;
        }

        if ( isset( $instance[ 'display_properties' ] ) ) {
            $display_properties = $instance[ 'display_properties' ];
        } else {
            $display_properties = 3;
        }

        if ( isset( $instance[ 'columns' ] ) ) {
            $columns = $instance[ 'columns' ];
        } else {
            $columns = 3;
        }

        if ( isset( $instance[ 'rotate' ] ) ) {
            $rotate = $instance[ 'rotate' ];
        } else {
            $rotate = false;
        }

        if ( isset( $instance[ 'display_proptype' ] ) ) {
            $display_proptype = $instance[ 'display_proptype' ];
        } else {
            $display_proptype = true;
        }

        if ( isset( $instance[ 'display_banner' ] ) ) {
            $display_banner = $instance[ 'display_banner' ];
        } else {
            $display_banner = true;
        }

        if ( isset( $instance[ 'show_location' ] ) ) {
            $show_location = $instance[ 'show_location' ];
        } else {
            $show_location = true;
        }

        if ( isset( $instance[ 'show_bedrooms' ] ) ) {
            $show_bedrooms = $instance[ 'show_bedrooms' ];
        } else {
            $show_bedrooms = true;
        }

        if ( isset( $instance[ 'show_bathrooms' ] ) ) {
            $show_bathrooms = $instance[ 'show_bathrooms' ];
        } else {
            $show_bathrooms = true;
        }

        if ( isset( $instance[ 'show_guests' ] ) ) {
            $show_guests = $instance[ 'show_guests' ];
        } else {
            $show_guests = true;
        }

        if ( isset( $instance[ 'w_before' ] ) ) {
            $w_before = $instance[ 'w_before' ];
        } else {
            $w_before = '';
        }

        if ( isset( $instance[ 'w_align' ] ) ) {
            $w_align = $instance[ 'w_align' ];
        } else {
            $w_align = 0;
        }

        if ( isset( $instance[ 'widgetheaderc' ] ) ) {
            $widgetheaderc = $instance[ 'widgetheaderc' ];
        } else {
            $widgetheaderc = '';
        }

        if ( isset( $instance[ 'widgettextc' ] ) ) {
            $widgettextc = $instance[ 'widgettextc' ];
        } else {
            $widgettextc = '';
        }

        if ( isset( $instance[ 'proprtybgc' ] ) ) {
            $proprtybgc = $instance[ 'proprtybgc' ];
        } else {
            $proprtybgc = '';
        }

        if ( isset( $instance[ 'pbordersize' ] ) ) {
            $pbordersize = $instance[ 'pbordersize' ];
        } else {
            $pbordersize = '';
        }

        if ( isset( $instance[ 'pbordersizeun' ] ) ) {
            $pbordersizeun = $instance[ 'pbordersizeun' ];
        } else {
            $pbordersizeun = 'px';
        }

        if ( isset( $instance[ 'propertyborderc' ] ) ) {
            $propertyborderc = $instance[ 'propertyborderc' ];
        } else {
            $propertyborderc = '';
        }

        if ( isset( $instance[ 'p_align' ] ) ) {
            $p_align = $instance[ 'p_align' ];
        } else {
            $p_align = 0;
        }

        if ( isset( $instance[ 'propertytextc' ] ) ) {
            $propertytextc = $instance[ 'propertytextc' ];
        } else {
            $propertytextc = '';
        }

        if ( isset( $instance[ 'propertylinkc' ] ) ) {
            $propertylinkc = $instance[ 'propertylinkc' ];
        } else {
            $propertylinkc = '';
        }

        if ( isset( $instance[ 'pimgpadding' ] ) ) {
            $pimgpadding = $instance[ 'pimgpadding' ];
        } else {
            $pimgpadding = '';
        }

        if ( isset( $instance[ 'pimgpaddingun' ] ) ) {
            $pimgpaddingun = $instance[ 'pimgpaddingun' ];
        } else {
            $pimgpaddingun = 'px';
        }

        if ( isset( $instance[ 'pconpadding' ] ) ) {
            $pconpadding = $instance[ 'pconpadding' ];
        } else {
            $pconpadding = '';
        }

        if ( isset( $instance[ 'pconpaddingun' ] ) ) {
            $pconpaddingun = $instance[ 'pconpaddingun' ];
        } else {
            $pconpaddingun = 'px';
        }

        if ( isset( $instance[ 'propertyfbbc' ] ) ) {
            $propertyfbbc = $instance[ 'propertyfbbc' ];
        } else {
            $propertyfbbc = '';
        }

        ?>

        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('layout'); ?>"><?php echo __('Layout:'); ?></label>
            <select class='widefat' id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
                <option value="0"<?php echo $layout == 0 ? 'selected' : ''; ?>>Vertical</option>
                <option value="1"<?php echo $layout == 1 ? 'selected' : ''; ?>>Horizontal</option>
                <option value="2"<?php echo $layout == 2 ? 'selected' : ''; ?>>Horizontal Left</option>
                <option value="3"<?php echo $layout == 3 ? 'selected' : ''; ?>>Horizontal Right</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('display_properties'); ?>"><?php echo __('Properties to Display:'); ?></label>
            <select class='widefat' id="<?php echo $this->get_field_id('display_properties'); ?>" name="<?php echo $this->get_field_name('display_properties'); ?>">
                <option value="0"<?php echo $display_properties == 0 ? 'selected' : ''; ?>>All</option>
                <option value="1"<?php echo $display_properties == 1 ? 'selected' : ''; ?>>1</option>
                <option value="2"<?php echo $display_properties == 2 ? 'selected' : ''; ?>>2</option>
                <option value="3"<?php echo $display_properties == 3 ? 'selected' : ''; ?>>3</option>
                <option value="4"<?php echo $display_properties == 4 ? 'selected' : ''; ?>>4</option>
                <option value="5"<?php echo $display_properties == 5 ? 'selected' : ''; ?>>5</option>
                <option value="6"<?php echo $display_properties == 6 ? 'selected' : ''; ?>>6</option>
                <option value="7"<?php echo $display_properties == 7 ? 'selected' : ''; ?>>7</option>
                <option value="8"<?php echo $display_properties == 8 ? 'selected' : ''; ?>>8</option>
                <option value="9"<?php echo $display_properties == 9 ? 'selected' : ''; ?>>9</option>
                <option value="10"<?php echo $display_properties == 10 ? 'selected' : ''; ?>>10</option>
                <option value="11"<?php echo $display_properties == 11 ? 'selected' : ''; ?>>11</option>
                <option value="12"<?php echo $display_properties == 12 ? 'selected' : ''; ?>>12</option>
                <option value="13"<?php echo $display_properties == 13 ? 'selected' : ''; ?>>13</option>
                <option value="14"<?php echo $display_properties == 14 ? 'selected' : ''; ?>>14</option>
                <option value="15"<?php echo $display_properties == 15 ? 'selected' : ''; ?>>15</option>
                <option value="16"<?php echo $display_properties == 16 ? 'selected' : ''; ?>>16</option>
                <option value="17"<?php echo $display_properties == 17 ? 'selected' : ''; ?>>17</option>
                <option value="18"<?php echo $display_properties == 18 ? 'selected' : ''; ?>>18</option>
                <option value="19"<?php echo $display_properties == 19 ? 'selected' : ''; ?>>19</option>
                <option value="20"<?php echo $display_properties == 20 ? 'selected' : ''; ?>>20</option>
            </select>
        </p>

        <p>
            <div id="ldxHorizontalColumnsSelect">
                <label for="<?php echo $this->get_field_id('columns'); ?>"><?php echo __('Horizontal Columns:'); ?></label><br>
                <small style="line-height: 1.2;margin-bottom: .5rem;display: block;"><?php _e( 'Number of properties displayed in a single row when using a horizontal layout' ); ?></em></small>
                <select class='widefat' id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>">
                    <option value="1"<?php echo $columns == 1 ? 'selected' : ''; ?>>1</option>
                    <option value="2"<?php echo $columns == 2 ? 'selected' : ''; ?>>2</option>
                    <option value="3"<?php echo $columns == 3 ? 'selected' : ''; ?>>3</option>
                    <option value="4"<?php echo $columns == 4 ? 'selected' : ''; ?>>4</option>
                    <option value="5"<?php echo $columns == 5 ? 'selected' : ''; ?>>5</option>
                </select>
            </div>
        </p>

        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('rotate'); ?>" name="<?php echo $this->get_field_name('rotate'); ?>" <?php checked(true, $rotate); ?>>
            <label for="<?php echo $this->get_field_id('rotate'); ?>"><?php echo __('Rotate Properties'); ?></label><br>
        </p>

        <h2><?php _e('Display Features'); ?></h2>

        <ul style="columns: 2; -webkit-columns: 2; -moz-columns: 2; list-style: none; margin-left: 0;">
            <li>
                <input type="checkbox" id="<?php echo $this->get_field_id('display_proptype'); ?>" name="<?php echo $this->get_field_name('display_proptype'); ?>" <?php checked(true, $display_proptype); ?> style="margin: -.25rem .25rem 0 0 !important;">
                <label for="<?php echo $this->get_field_id('display_proptype'); ?>"><?php echo __('Show Property Type'); ?></label>
            </li>
            <li>
                <input type="checkbox" id="<?php echo $this->get_field_id('display_banner'); ?>" name="<?php echo $this->get_field_name('display_banner'); ?>" <?php checked(true, $display_banner); ?> style="margin: -.25rem .25rem 0 0 !important;">
                <label for="<?php echo $this->get_field_id('display_banner'); ?>"><?php echo __('Show Featured Banner'); ?></label>
            </li>
            <li>
                <input type="checkbox" id="<?php echo $this->get_field_id('show_location'); ?>" name="<?php echo $this->get_field_name('show_location'); ?>" <?php checked(true, $show_location); ?> style="margin: -.25rem .25rem 0 0 !important;">
                <label for="<?php echo $this->get_field_id('show_location'); ?>"><?php echo __('Show Location'); ?></label>
            </li>
            <li>
                <input type="checkbox" id="<?php echo $this->get_field_id('show_bedrooms'); ?>" name="<?php echo $this->get_field_name('show_bedrooms'); ?>" <?php checked(true, $show_bedrooms); ?> style="margin: -.25rem .25rem 0 0 !important;">
                <label for="<?php echo $this->get_field_id('show_bedrooms'); ?>"><?php echo __('Show Bedrooms'); ?></label>
            </li>
            <li>
                <input type="checkbox" id="<?php echo $this->get_field_id('show_bathrooms'); ?>" name="<?php echo $this->get_field_name('show_bathrooms'); ?>" <?php checked(true, $show_bathrooms); ?> style="margin: -.25rem .25rem 0 0 !important;">
                <label for="<?php echo $this->get_field_id('show_bathrooms'); ?>"><?php echo __('Show Bathrooms'); ?></label>
            </li>
            <li>
                <input type="checkbox" id="<?php echo $this->get_field_id('show_guests'); ?>" name="<?php echo $this->get_field_name('show_guests'); ?>" <?php checked(true, $show_bathrooms); ?> style="margin: -.25rem .25rem 0 0 !important;">
                <label for="<?php echo $this->get_field_id('show_guests'); ?>"><?php echo __('Show Guests'); ?></label>
            </li>
        </ul>

        <h2><?php _e('Additional Content'); ?></h2>

        <p>
            <label for="<?php echo $this->get_field_name( 'w_before' ); ?>">
                <?php _e( 'Content Before:' ); ?><br>
                <small style="line-height: 1.2; margin-bottom: .5rem; display: block;"><?php _e( 'Displays Content beneath title and above lisings:' ); ?></em></small>
            </label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'w_before' ); ?>" name="<?php echo $this->get_field_name( 'w_before' ); ?>" type="text" rows="6"><?php echo esc_attr( $w_before ); ?></textarea>
        </p>

        <h2><?php _e('Widget Styles'); ?></h2>

        <p>
            <label for="<?php echo $this->get_field_id('w_align'); ?>"><?php echo __('Text Alignment:'); ?></label>
            <select class='widefat textalignment' id="<?php echo $this->get_field_id('w_align'); ?>" name="<?php echo $this->get_field_name('w_align'); ?>">
                <option value="0"<?php echo $w_align == 0 ? 'selected' : ''; ?>>Left</option>
                <option value="1"<?php echo $w_align == 1 ? 'selected' : ''; ?>>Center</option>
                <option value="2"<?php echo $w_align == 2 ? 'selected' : ''; ?>>Right</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('widgetheaderc'); ?>"><?php echo LodgixTranslate::translate('Widget Header Color'); ?></label><br>
            <input class="color-picker" id="<?php echo $this->get_field_id( 'widgetheaderc' ); ?>" type="text" data-alpha="true" name="<?php echo $this->get_field_name( 'widgetheaderc' ); ?>" value="<?php echo esc_attr( $widgetheaderc ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('widgettextc'); ?>"><?php echo LodgixTranslate::translate('Widget Text Color'); ?></label><br>
            <input class="color-picker" id="<?php echo $this->get_field_id( 'widgettextc' ); ?>" type="text" data-alpha="true" name="<?php echo $this->get_field_name( 'widgettextc' ); ?>" value="<?php echo esc_attr( $widgettextc ); ?>" />
        </p>
 
        <h2><?php _e('Property Styles'); ?></h2>

        <p>
            <label for="<?php echo $this->get_field_id('proprtybgc'); ?>"><?php echo LodgixTranslate::translate('Property Background Color'); ?></label><br>
            <input class="color-picker" id="<?php echo $this->get_field_id( 'proprtybgc' ); ?>" type="text" data-alpha="true" name="<?php echo $this->get_field_name( 'proprtybgc' ); ?>" value="<?php echo esc_attr( $proprtybgc ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('pbordersize'); ?>"><?php echo LodgixTranslate::translate('Property Border Size'); ?></label><br>
            <input id="<?php echo $this->get_field_id('pbordersize'); ?>" name="<?php echo $this->get_field_name('pbordersize'); ?>" type="text" value="<?php echo $pbordersize; ?>" style="position: relative; top: 2px;" class="valuestyle">
            <select id="<?php echo $this->get_field_id('pbordersizeun'); ?>" name="<?php echo $this->get_field_name('pbordersizeun'); ?>" class="unit-select">
                <option value="px" <?php echo ($pbordersizeun == 'px' ? 'selected' : ''); ?>>px</option>
                <option value="em" <?php echo ($pbordersizeun == 'rem' ? 'selected' : ''); ?>>rem</option>
                <option value="per" <?php echo ($pbordersizeun == 'per' ? 'selected' : ''); ?>>%</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('propertyborderc'); ?>"><?php echo LodgixTranslate::translate('Property Border Color'); ?></label><br>
            <input class="color-picker" id="<?php echo $this->get_field_id( 'propertyborderc' ); ?>" type="text" data-alpha="true" name="<?php echo $this->get_field_name( 'propertyborderc' ); ?>" value="<?php echo esc_attr( $propertyborderc ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('p_align'); ?>"><?php echo __('Text Alignment:'); ?></label>
            <select class='widefat textalignment' id="<?php echo $this->get_field_id('p_align'); ?>" name="<?php echo $this->get_field_name('p_align'); ?>">
                <option value="0"<?php echo $p_align == 0 ? 'selected' : ''; ?>>Left</option>
                <option value="1"<?php echo $p_align == 1 ? 'selected' : ''; ?>>Center</option>
                <option value="2"<?php echo $p_align == 2 ? 'selected' : ''; ?>>Right</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('propertylinkc'); ?>"><?php echo LodgixTranslate::translate('Property Title Color'); ?></label><br>
            <input class="color-picker" id="<?php echo $this->get_field_id( 'propertylinkc' ); ?>" type="text" data-alpha="true" name="<?php echo $this->get_field_name( 'propertylinkc' ); ?>" value="<?php echo esc_attr( $propertylinkc ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('propertytextc'); ?>"><?php echo LodgixTranslate::translate('Property Text Color'); ?></label><br>
            <input class="color-picker" id="<?php echo $this->get_field_id( 'propertytextc' ); ?>" type="text" data-alpha="true" name="<?php echo $this->get_field_name( 'propertytextc' ); ?>" value="<?php echo esc_attr( $propertytextc ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('pimgpadding'); ?>"><?php echo LodgixTranslate::translate('Property Image Padding'); ?></label><br>
            <input id="<?php echo $this->get_field_id('pimgpadding'); ?>" name="<?php echo $this->get_field_name('pimgpadding'); ?>" type="text" value="<?php echo $pimgpadding; ?>" style="position: relative; top: 2px;" class="valuestyle">
            <select id="<?php echo $this->get_field_id('pimgpaddingun'); ?>" name="<?php echo $this->get_field_name('pimgpaddingun'); ?>" class="unit-select">
                <option value="px" <?php echo ($pimgpaddingun == 'px' ? 'selected' : ''); ?>>px</option>
                <option value="em" <?php echo ($pimgpaddingun == 'rem' ? 'selected' : ''); ?>>rem</option>
                <option value="per" <?php echo ($pimgpaddingun == 'per' ? 'selected' : ''); ?>>%</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('pconpadding'); ?>"><?php echo LodgixTranslate::translate('Property Content Padding'); ?></label><br>
            <input id="<?php echo $this->get_field_id('pconpadding'); ?>" name="<?php echo $this->get_field_name('pconpadding'); ?>" type="text" value="<?php echo $pconpadding; ?>" style="position: relative; top: 2px;" class="valuestyle">
            <select id="<?php echo $this->get_field_id('pconpaddingun'); ?>" name="<?php echo $this->get_field_name('pconpaddingun'); ?>" class="unit-select">
                <option value="px" <?php echo ($pconpaddingun == 'px' ? 'selected' : ''); ?>>px</option>
                <option value="em" <?php echo ($pconpaddingun == 'rem' ? 'selected' : ''); ?>>rem</option>
                <option value="per" <?php echo ($pconpaddingun == 'per' ? 'selected' : ''); ?>>%</option>
            </select>
        </p>

        <p><a class="button-secondary" href="#" id="<?php echo $this->get_field_id('reset'); ?>" title="<?php esc_attr_e( 'Reset Styles' ); ?>"><?php esc_attr_e( 'Reset Styles' ); ?></a></p>

        <script>
            jQuery(document).ready(function(){

                if ( typeof FLBuilder == 'undefined' ) {
            		jQuery( '.color-picker' ).wpColorPicker();
                }

                var layout = jQuery('#<?php echo $this->get_field_id('layout'); ?>').val();
                if(layout != "0"){
                    jQuery('#ldxHorizontalColumnsSelect *').show();
                } else {
                    jQuery('#ldxHorizontalColumnsSelect *').hide();
                }
                jQuery('#<?php echo $this->get_field_id('layout'); ?>').change(function () {
                    var layout =  jQuery(this).val()

                    if(layout != "0"){
                        jQuery('#ldxHorizontalColumnsSelect *').show();
                    } else {
                        jQuery('#ldxHorizontalColumnsSelect *').hide();
                    }
                });

                jQuery( '#<?php echo $this->get_field_id('reset'); ?>' ).click(function( event ) {
                    event.preventDefault();
                    jQuery( ".textalignment" ).val( "0" );
                    jQuery( ".color-picker" ).val( "" );
                    jQuery( ".color-alpha" ).css( "background", "" );
                    jQuery( ".wp-color-result" ).css( "background-color", "" );
                    jQuery( ".unit-select" ).val( "px" );
                    jQuery( ".valuestyle" ).val( "" );
                    jQuery( ".color-alpha" ).val( "" );
                    jQuery("#<?php echo $this->get_field_id('savewidget'); ?>").val('Save').removeAttr('disabled');
                });

            });
        </script>

    <?php
    }

    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['display_properties'] = strip_tags($new_instance['display_properties']);
        $instance['layout'] = strip_tags($new_instance['layout']);
        $instance['columns'] = strip_tags($new_instance['columns']);
        $instance['rotate'] = isset($new_instance['rotate']) && $new_instance['rotate'] == 'on' ? true : false;
        $instance['display_proptype'] = isset($new_instance['display_proptype']) && $new_instance['display_proptype'] == 'on' ? true : false;
        $instance['display_banner'] = isset($new_instance['display_banner']) && $new_instance['display_banner'] == 'on' ? true : false;
        $instance['show_location'] = isset($new_instance['show_location']) && $new_instance['show_location'] == 'on' ? true : false;
        $instance['show_bedrooms'] = isset($new_instance['show_bedrooms']) && $new_instance['show_bedrooms'] == 'on' ? true : false;
        $instance['show_bathrooms'] = isset($new_instance['show_bathrooms']) && $new_instance['show_bathrooms'] == 'on' ? true : false;
        $instance['show_guests'] = isset($new_instance['show_guests']) && $new_instance['show_guests'] == 'on' ? true : false;
        $instance['w_before'] = ( !empty( $new_instance['w_before'] ) ) ? strip_tags( $new_instance['w_before'] ) : '';
        $instance['w_align'] = strip_tags($new_instance['w_align']);
        $instance['widgetheaderc'] = strip_tags($new_instance['widgetheaderc']);
        $instance['widgettextc'] = strip_tags($new_instance['widgettextc']);
        $instance['proprtybgc'] = strip_tags($new_instance['proprtybgc']);
        $instance['pbordersize'] = strip_tags($new_instance['pbordersize']);
        $instance['pbordersizeun'] = strip_tags($new_instance['pbordersizeun']);
        $instance['propertyborderc'] = strip_tags($new_instance['propertyborderc']);
        $instance['p_align'] = strip_tags($new_instance['p_align']);
        $instance['propertytextc'] = strip_tags($new_instance['propertytextc']);
        $instance['propertylinkc'] = strip_tags($new_instance['propertylinkc']);
        $instance['pimgpadding'] = strip_tags($new_instance['pimgpadding']);
        $instance['pimgpaddingun'] = strip_tags($new_instance['pimgpaddingun']);
        $instance['pconpadding'] = strip_tags($new_instance['pconpadding']);
        $instance['pconpaddingun'] = strip_tags($new_instance['pconpaddingun']);

        return $instance;
    }
}

function lodgixRegisterWidgetFeaturedv2() {
	register_widget('Lodgix_Featured_Rentals_Widget_v2');
}

add_action('widgets_init', 'lodgixRegisterWidgetFeaturedv2');


?>
