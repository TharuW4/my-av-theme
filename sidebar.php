<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package videoclub
 */


?>

<aside id="secondary" class="widget-area sidebar">

<?php if ( is_active_sidebar( 'sidebar-post' ) && is_single() ) { ?>

	<?php dynamic_sidebar( 'sidebar-post' ); ?>

<?php } else { ?>

	<?php dynamic_sidebar( 'sidebar-1' ); ?>

<?php } ?>

</aside><!-- #secondary -->

