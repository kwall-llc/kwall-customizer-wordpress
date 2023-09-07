<?php 

/**
 * Plugin Name: Kwall Customizer
 * Plugin URI: kwallco.com
 * Author: Kwall
 * Authro URI: kwallco.com
 * Description: Customizer settings for Kwall Starter Theme
 * Version: 0.1.0
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: kwallco.com
 */

//====================================================================
// Add New Color Option in Existing Colors Section in Customizer
//====================================================================
 
add_action( 'customize_register', 'kwall_customizer_add_colorPicker'  );

function kwall_customizer_add_colorPicker( $wp_customize){
     
    // Add Settings 
 
     
    // Add Settings 
    $wp_customize->add_setting( 'secondary_color', array(
        'default-color' => '#04bfbf',
    ));
 
 
    $wp_customize->add_setting( 'primary_color', array(
        'default-color' => '#45ace0',                        
    ));
 
 
    // Add Controls
	 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
        'label' => 'Primary Color',
        'section' => 'colors',
        'settings' => 'primary_color'
    )));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
        'label' => 'Secondary Color',
        'section' => 'colors',
        'settings' => 'secondary_color'
 
    )));
 
 
}
 //====================================================================
// Add New Font Section and a dropdown select for fonts
//====================================================================

 
add_action( 'customize_register', 'kwall_add_dropdown_font' );
function kwall_add_dropdown_font($wp_customize){
 
    //add section
    $wp_customize->add_section( 'kwall_dropdown_font_section', array(
 
                'title' => 'Fonts',
                'priority' => 10
    ));
 
    //add setting
    $wp_customize->add_setting( 'kwall_dropdown_font', array(
        'default' => 'Montserrat',
    ));
 
    //add control
    $wp_customize->add_control( 'kwall_dropdown_font_control', array(
                'label' => 'Select Font',
                'type'  => 'select',
                'section' => 'kwall_dropdown_font_section',
                'settings' => 'kwall_dropdown_font',
                'transport' => 'refresh',
                'choices' => array(
                    
                    "'Manuale', serif"   => __( 'Manuale' ),
                    "'Playfair Display', serif"  => __( 'Playfair Display' ),
                    "'PT Serif', serif"   => __( 'PT Serif' ),
                    "'Roboto', sans-serif"   => __( 'Roboto' ),
                    "'Roboto Condensed', sans-serif"   => __( 'Roboto Condensed' ),
                    "'Open Sans', sans-serif"   => __( 'Open Sans' ),
                    "'Jost', sans-serif"   => __( 'Jost' ),
                    "'Montserrat', sans-serif"   => __( 'Montserrat' ),
                ),
    ));
 
}



//====================================================================
// Compile new fonts and colors using scssphp
//====================================================================


if (is_customize_preview()) {
	add_action('wp_head', function() {
        
		$compiler = new ScssPhp\ScssPhp\Compiler();
 
		$source_scss = get_template_directory() . '/assets/scss/styles.scss';
		$scssContents = file_get_contents($source_scss);
		$import_path = get_template_directory() . '/assets/scss';
		$compiler->addImportPath($import_path);
		$variables = [
			'$primary-1' => get_theme_mod('primary_color', '#002F63'),
			'$secondary-1' => get_theme_mod('secondary_color', '#00234A'),
            '$font' => get_theme_mod('kwall_dropdown_font', 'Montserrat'),
		];
		$compiler->setVariables($variables);
 
		$css = $compiler->compile($scssContents);
		if (!empty($css) && is_string($css)) {
			echo '<style type="text/css">' . $css . '</style>';
		}
	});
}

add_action('customize_save_after', function() {
	$compiler = new ScssPhp\ScssPhp\Compiler();
 
    $source_scss = get_template_directory() . '/assets/scss/styles.scss';
    $scssContents = file_get_contents($source_scss);
    $import_path = get_template_directory() . '/assets/scss';
    $compiler->addImportPath($import_path);
	$target_css = get_template_directory() . '/assets/css/styles.css';
 
    $variables = [
        '$primary-1' => get_theme_mod('primary_color', '#002F63'),
        '$secondary-1' => get_theme_mod('secondary_color', '#00234A'),
        '$font' => get_theme_mod('kwall_dropdown_font', 'Montserrat'),
    ];	
	$compiler->setVariables($variables);
 
	$css = $compiler->compile($scssContents);
	if (!empty($css) && is_string($css)) {
		file_put_contents($target_css, $css);
	}
});