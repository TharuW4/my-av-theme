<?php
/**
 * AnimeVerze: Archive & Category Template
 */

get_header();

$archive_title = get_the_archive_title();

if (is_category('anime-movies')) {
    $archive_title = 'Anime Movies';
} elseif (is_category('anime-series')) {
    $archive_title = 'Anime Series';
}
?>

<style>
.archive-container {
    width: 94%;
    max-width: 1800px;
    margin: 0 auto;
    padding: 55px 0 70px;
    min-height: 70vh;
}

.archive-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 26px;
    padding-left: 14px;
    border-left: 4px solid #38BDF8;
}

.archive-title-wrap {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.archive-kicker {
    color: #38BDF8;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}

.archive-header h1 {
    margin: 0;
    padding: 0;
    color: #fff;
    font-size: clamp(22px, 2.2vw, 30px);
    font-weight: 850;
    line-height: 1.15;
    letter-spacing: -0.5px;
    text-transform: capitalize;
}

.archive-count {
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

.archive-empty {
    grid-column: 1 / -1;
    padding: 35px 20px;
    background: #15181E;
    border: 1px dashed #292E38;
    border-radius: 10px;
    color: #8E949F;
    font-size: 13px;
    text-align: center;
}

.archive-pagination {
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
    .archive-container {
        width: calc(100% - 24px);
        padding: 32px 0 50px;
    }

    .archive-header {
        margin-bottom: 18px;
        padding-left: 10px;
        border-left-width: 3px;
    }

    .archive-kicker {
        font-size: 8px;
    }

    .archive-header h1 {
        font-size: 19px;
    }

    .archive-count {
        padding: 6px 8px;
        font-size: 8px;
    }

    .content-loop {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
    }

    .archive-pagination {
        margin-top: 32px;
    }
}

@media (max-width: 360px) {
    .archive-header {
        align-items: flex-end;
    }

    .archive-count {
        display: none;
    }
}
</style>

<div id="primary" class="archive-container full-width clear">
    <main id="main" class="site-main clear">
        <header class="archive-header">
            <div class="archive-title-wrap">
                <span class="archive-kicker">Browse Anime</span>
                <h1><?php echo esc_html($archive_title); ?></h1>
            </div>

            <?php if (have_posts()) : ?>
                <span class="archive-count">
                    <?php
                    global $wp_query;
                    echo esc_html($wp_query->found_posts) . ' Titles';
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
                <div class="archive-empty">No anime found in this section.</div>
            <?php endif; ?>
        </div>

        <div class="archive-pagination">
            <?php get_template_part('template-parts/pagination'); ?>
        </div>
    </main>
</div>

<?php get_footer(); ?>
