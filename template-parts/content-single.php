<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="entry-content">
        <?php
            /* අපි දැනටමත් single.php එකේ title එක සහ player එක හදලා තියෙන නිසා, 
               මෙතනදී පෙන්වන්නේ පෝස්ට් එකේ content එක පමණයි.
            */
            the_content();

            wp_link_pages( array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'videoclub' ),
                'after'  => '</div>',
            ) );
        ?>
    </div><?php if (has_tag()) : ?>
        <div class="entry-tags" style="margin-top: 30px; padding: 20px 0; border-top: 1px solid #222;">
            <span class="tag-links" style="color: #666; font-size: 13px;">
                <i class="fa fa-tags" style="margin-right: 10px;"></i>
                <?php the_tags('', ' '); ?>
            </span>
        </div>
    <?php endif; ?>

</article>```