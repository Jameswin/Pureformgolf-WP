<?php
if( !function_exists( "modal_theme_optin_to_win" ) ) {
	function modal_theme_optin_to_win( $atts, $content = null ){
		/**
		 * Define Variables
		 */
		global $cp_form_vars;

		$style_id = $settings_encoded = '';
		extract(shortcode_atts(array(
			'style_id'			=> '',
			'settings_encoded'	=> '',
	    ), $atts));

		$settings = base64_decode( $settings_encoded );
		$style_settings = unserialize( $settings );

		foreach( $style_settings as $key => $setting ) {
			$style_settings[$key] = apply_filters('smile_render_setting',$setting);;
		}
		
		unset($style_settings['style_id']); 

		//	Generate UID
		$uid		= uniqid();
		$uid_class	= 'content-'.$uid;

		$individual_vars = array(
			"uid"       	=> $uid,
			"uid_class" 	=> $uid_class,
			"style_class"	=> "cp-optin-to-win"
		);		

		/**
		 * Merge short code variables arrays
		 *
		 * @array 	$individual_vars		Individual style EXTRA short-code variables
		 * @array 	$cp_form_vars			CP Form global short-code variables
		 * @array 	$style_settings			Individual style short-code variables
		 * @array 	$atts					short-code attributes
		 */
		$all = array_merge(
			$individual_vars,
			$cp_form_vars,
			$style_settings,
			$atts
		);

		/**
		 *	Extract short-code variables
		 *
		 *	@array 		$all 		      All merged arrays
		 *	@array 		$style_settings   Its required as per WP. Merged $style_settings in $all.
		 */

		$a = shortcode_atts( $all , $style_settings );

		/** = Style - individual options
		 *-----------------------------------------------------------*/
		//	Variables
		$imgclass 			= ( $a['image_position'] == 0 ) ? 'cp-right-contain' : '';
		$imageStyle		 	= cp_add_css( 'left', $a['image_horizontal_position'], 'px');
		$imageStyle		   .= cp_add_css( 'top', $a['image_vertical_position'], 'px');
		$imageStyle		   .= cp_add_css( 'max-width', $a['image_size'], 'px');
		
		//	Filters & Actions
		$modal_image 			= apply_filters( 'cp_get_modal_image_url', $a['modal_image'] );

		
		/** = Before filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array( 'cp_modal_global_before', array( $a ) );
?>
		<!-- BEFORE CONTENTS -->
		<div class="cp-row cp-columns-equalized">
			<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 cp-column-equalized-center cp-text-container <?php echo esc_attr($imgclass); ?>" >
	        	<div class="cp-title-container <?php if( trim( $a['modal_title1'] ) == '' ) { echo 'cp-empty'; } ?>" >
	            	<h2 class="cp-title cp_responsive" style="color: <?php echo esc_attr( $a['modal_title_color'] ); ?>;"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_title1'] ) ) ); ?></h2>
	           	</div>
	           	<div class="cp-sec-title-container <?php if( trim( $a['modal_sec_title'] ) == '' ) { echo 'cp-empty'; } ?>" >
	            	<div class="cp-sec-title cp_responsive" style="color: <?php echo esc_attr( $a['modal_sec_title_color'] ); ?>;"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_sec_title'] ) ) ); ?></div>
	           	</div>
	            <div class="cp-desc-container <?php if( trim( $a['modal_short_desc1'] ) == '' ) { echo 'cp-empty'; } ?>">
		        	<div class="cp-description cp_responsive" style="color: <?php echo esc_attr( $a['modal_desc_color'] ); ?>;"><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_short_desc1'] ) ) ); ?></div>
				</div>
				<div class="cp-form-container cp-vertical-form-container">
	          		<?php
	             		/**
						 * Embed CP Form
						 */
						apply_filters_ref_array('cp_get_form', array( $a ) );
					?>
				</div>

                <div class="cp-info-container cp_responsive <?php if( trim( $a['modal_confidential'] ) == '' ) { echo 'cp-empty'; } ?>" style="color: <?php echo esc_attr( $a['tip_color'] ); ?>;">
                	<?php echo do_shortcode( html_entity_decode( stripslashes ( $a['modal_confidential'] ) ) ); ?>
                </div>
            </div><!-- .cp-text-container -->
		  	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 cp-column-equalized-center">
		    	<div class="cp-image-container"> 
		        	<img style="<?php echo esc_attr($imageStyle); ?>" src="<?php echo esc_attr( $modal_image ); ?>" class="cp-image"> 
		      	</div>
		   	</div><!-- .col-lg-5 -->
		</div>

		<!-- AFTER CONTENTS -->
<?php
		/** = After filter
		 *-----------------------------------------------------------*/
		apply_filters_ref_array('cp_modal_global_after', array( $a ) );

	   	return ob_get_clean();
	}
}
