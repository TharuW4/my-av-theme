<div class="no-results" style="text-align: center; padding: 100px 20px; color: #ccc;">

    <div class="page-content">
        <i class="fa fa-search" style="font-size: 80px; color: #333; margin-bottom: 25px; display: block;"></i>
        
        <h2 style="color: #fff; margin-bottom: 15px;">Nothing Found!</h2>
        
        <p style="margin-bottom: 30px; font-size: 16px; max-width: 600px; margin-left: auto; margin-right: auto;">
            Sorry, but nothing matched your search terms. Please try again with different keywords or check back later.
        </p>

        <div class="widget" style="max-width: 500px; margin: 0 auto;">
            <?php get_search_form(); ?>
        </div>

        <div style="margin-top: 50px;">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="background: var(--primary-red); color: #fff; padding: 12px 30px; border-radius: 5px; text-decoration: none; font-weight: bold; transition: 0.3s; text-transform: uppercase; font-size: 14px;">
                Back to Home
            </a>
        </div>
    </div>

</div>