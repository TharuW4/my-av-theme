<div id="site-bottom" class="clear">

	<?php 
		if ( has_nav_menu( 'footer' ) ) {
			wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'footer-menu', 'menu_class' => 'footer-nav' ) );
		}
	?>	

	<div class="site-info" style="color: #666; font-size: 13px;">

		&copy; <?php echo esc_html( date("Y") ); ?> 
		<strong style="color: #fff;"><?php echo esc_html( get_bloginfo('name') ); ?></strong>. 
		All Rights Reserved.

	</div></div>```