<?php
/**
 * AnimeVerze Page Template
 */

get_header();
?>

<div id="primary" class="content-area full-width clear">
    <main id="main" class="site-main main-content">
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content', 'page'); ?>
        <?php endwhile; ?>
    </main>
</div>

<?php get_footer(); ?>
