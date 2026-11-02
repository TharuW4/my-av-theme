<?php
/**
 * Block Styles
 */

if ( function_exists( 'register_block_style' ) ) {
	/**
	 * Register block styles.
	 */
	function videoclub_register_block_styles() {
		// Image: Borders.
		register_block_style(
			'core/image',
			array(
				'name'  => 'videoclub-border',
				'label' => esc_html__( 'Borders', 'videoclub' ),
			)
		);
	}
	add_action( 'init', 'videoclub_register_block_styles' );
}
