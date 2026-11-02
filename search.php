<?php
/**
 * AnimeVerze: Search Results Template
 */

get_header();

$search_query = get_search_query();
?>

<style>
.search-results-container {
    width: 94%;
    max-width: 1800px;
    margin: 0 auto;
    padding: 55px 0 70px;
    min-height: 70vh;
}

.search-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 26px;
    padding-left: 14px;
    border-left: 4px solid #38BDF8;
}

.search-title-wrap {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.search-kicker {
    color: #38BDF8;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}

.search-header h1 {
    margin: 0;
    padding: 0;
    color: #fff;
    font-size: clamp(22px, 2.2vw, 30px);
    font-weight: 850;
    line-height: 1.2;
    letter-spacing: -0.5px;
}

.search-header h1 span {
    color: #7DD3FC;
}

.search-count {
    flex-shrink: 0;
    padding: 7px 11px;
    background: rgba(56, 189, 248, 0.08);
    border: 1px solid rgba(56, 189, 248, 0.22);
    border-radius: 7px;
    color: #7DD3FC;
    font-size: 10px;
    font-weight: 700;
}

.content-loop {
    display: grid;
    grid-template-columns: repeat(8, minmax(0, 1fr));
    gap: 15px;
    margin-top: 0;
}

.search-empty {
    grid-column: 1 / -1;
    width: 100%;
    padding: 40px 20px;
    background: #15181E;
    border: 1px dashed #292E38;
    border-radius: 10px;
    text-align: center;
}

.search-empty-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(56, 189, 248, 0.08);
    border: 1px solid rgba(56, 189, 248, 0.18);
    border-radius: 50%;
    color: #38BDF8;
    font-size: 18px;
}

.search-empty h2 {
    margin: 0 0 7px;
    color: #fff;
    font-size: 18px;
    font-weight: 800;
}

.search-empty p {
    margin: 0;
    color: #8E949F;
    font-size: 12px;
    line-height: 1.6;
}

.search-pagination {
    margin-top: 45px;
}

@media (max-width: 1400px) {
    .content-loop {
        grid-template-columns: repeat(7, minmax(0, 1fr));
    }
}

@media (max-width: 1200px) {
    .content-loop {
        grid-template-columns: repeat(6, minmax(0, 1fr));
    }
}

@media (max-width: 992px) {
    .content-loop {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .search-results-container {
        width: calc(100% - 24px);
        padding: 32px 0 50px;
    }

    .search-header {
        margin-bottom: 18px;
        padding-left: 10px;
        border-left-width: 3px;
    }

    .search-kicker {
        font-size: 8px;
    }

    .search-header h1 {
        font-size: 18px;
    }

    .search-count {
        padding: 6px 8px;
        font-size: 8px;
    }

    .content-loop {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
    }

    .search-empty {
        padding: 32px 15px;
    }

    .search-empty h2 {
        font-size: 16px;
    }

    .search-pagination {
        margin-top: 32px;
    }
}

@media (max-width: 360px) {
    .search-header {
        align-items: flex-start;
    }

    .search-count {
        display: none;
    }
}
</style>

<div id="primary" class="search-results-container full-width clear">
    <main id="main" class="site-main clear">

        <header class="search-header">
            <div class="search-title-wrap">
                <span class="search-kicker">Search Anime</span>

                <h1>
                    Search Results for:
                    <span><?php echo esc_html($search_query); ?></span>
                </h1>
            </div>

            <?php if (have_posts()) : ?>
                <span class="search-count">
                    <?php
                    global $wp_query;
                    echo esc_html($wp_query->found_posts) . ' Results';
                    ?>
                </span>
            <?php endif; ?>
        </header>

        <div id="recent-content" class="content-loop">
            <?php if (have_posts()) : ?>

                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'loop'); ?>
                <?php endwhile; ?>

            <?php else : ?>

                <div class="search-empty">
                    <div class="search-empty-icon">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </div>

                    <h2>No Anime Found</h2>

                    <p>
                        We couldn't find anything for
                        "<?php echo esc_html($search_query); ?>".
                        Try another anime title or keyword.
                    </p>
                </div>

            <?php endif; ?>
        </div>

        <?php if (have_posts()) : ?>
            <div class="search-pagination">
                <?php get_template_part('template-parts/pagination'); ?>
            </div>
        <?php endif; ?>

    </main>
</div>

<?php get_footer(); ?>