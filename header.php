<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php if (is_front_page() || is_home() || is_singular('post')) : ?>
        <link rel="preconnect" href="https://image.tmdb.org">
        <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <?php endif; ?>

    <?php wp_head(); ?>

    <style>
        :root {
            --av-primary: #38BDF8;
            --av-primary-hover: #0EA5E9;
            --av-primary-soft: #7DD3FC;
            --av-bg: #0F0F0F;
            --av-header: rgba(11, 13, 18, 0.96);
            --av-surface: #181A20;
            --av-surface-light: #22252D;
            --av-text: #FFFFFF;
            --av-text-muted: #A3A3A3;
            --av-border: rgba(255, 255, 255, 0.09);
        }

        .site-content::before,
        .home .site-content::before,
        .archive .site-content::before,
        .search .site-content::before,
        .single .site-content::before,
        .page .site-content::before {
            content: none !important;
            display: none !important;
        }

        /*
        =========================================================
        ANIMEVERZE HEADER
        =========================================================
        */

        #masthead {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 82px;
            padding: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            background: var(--av-header);
            border-bottom: none;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition:
                background 0.3s ease,
                border-color 0.3s ease;
        }

        #content.site-content {
            padding-top: 82px !important;
        }

        .admin-bar #masthead {
            top: 32px;
        }

        .header-container {
            position: relative;
            width: 98%;
            max-width: 1800px;
            height: 100%;
            margin: 0 auto;
            padding: 0;
            box-sizing: border-box;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            align-items: center;
        }

        /*
        =========================================================
        LOGO
        =========================================================
        */

        .site-branding {
            grid-column: 1;
            justify-self: start;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            height: 100%;
            min-width: 0;
            position: relative;
            z-index: 2;
        }

        .site-branding a {
            display: inline-flex;
            align-items: center;
            text-decoration: none !important;
            line-height: 1;
        }

        #masthead .site-branding img.animeverze-logo-image {
            height: 75px !important;
            max-height: 75px !important;
            width: auto !important;
            max-width: 480px;
            object-fit: contain;
        }

        /*
        =========================================================
        DESKTOP NAVIGATION
        =========================================================
        */

        .netflix-nav {
            grid-column: 2;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            justify-self: center;
            margin: 0;
            padding: 0;
        }

        .netflix-nav ul {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .netflix-nav ul li {
            margin: 0;
            padding: 0;
        }

        .netflix-nav ul li a {
            position: relative;
            display: inline-flex;
            align-items: center;
            padding: 29px 0;
            color: #D7D9DE;
            text-decoration: none !important;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.25s ease;
        }

        .netflix-nav ul li a:hover,
        .netflix-nav ul li a.active {
            color: var(--av-primary) !important;
        }

        .netflix-nav ul li a::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 18px;
            width: 0;
            height: 2px;
            border-radius: 100px;
            background: var(--av-primary);
            transform: translateX(-50%);
            transition: width 0.25s ease;
        }

        .netflix-nav ul li a:hover::after,
        .netflix-nav ul li a.active::after {
            width: 100%;
        }

        /*
        =========================================================
        HEADER RIGHT
        =========================================================
        */

        .header-right {
            grid-column: 3;
            justify-self: end;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .search-trigger,
        .mobile-nav-toggle {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid transparent;
            border-radius: 50%;
            background: transparent;
            color: #FFFFFF;
            font-size: 19px;
            cursor: pointer;
            transition:
                background 0.25s ease,
                color 0.25s ease,
                border-color 0.25s ease;
        }

        .search-trigger:hover,
        .mobile-nav-toggle:hover {
            color: var(--av-primary);
            background: rgba(56, 189, 248, 0.09);
            border-color: rgba(56, 189, 248, 0.14);
        }

        .mobile-nav-toggle {
            display: none;
        }

        /*
        =========================================================
        SEARCH OVERLAY
        =========================================================
        */

        #masthead #search-overlay {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 0;
            z-index: 9998;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(11, 13, 18, 0.98);
            border-top: none;
            border-bottom: none;
            box-shadow: none;
        }

        #masthead #search-overlay.active {
            height: 92px;
            border-bottom: 1px solid rgba(56, 189, 248, 0.20);
        }

        .search-form-wrap {
            position: relative;
            display: flex;
            align-items: center;
            width: 90%;
            max-width: 760px;
        }

        .search-form-wrap form {
            width: 100%;
            margin: 0;
        }

        .search-input-full {
            width: 100%;
            padding: 13px 52px 13px 22px;
            background: #191C22 !important;
            border: 1px solid #30343D !important;
            border-radius: 12px !important;
            color: #FFFFFF !important;
            font-size: 16px;
            outline: none !important;
            box-shadow: none !important;
            transition:
                border-color 0.25s ease,
                box-shadow 0.25s ease,
                background 0.25s ease;
        }

        .search-input-full::placeholder {
            color: #777C86;
        }

        .search-input-full:focus {
            background: #1C2027 !important;
            border-color: var(--av-primary) !important;
            box-shadow:
                0 0 0 3px rgba(56, 189, 248, 0.08) !important;
        }

        .close-search-btn {
            position: absolute;
            right: 13px;
            top: 50%;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateY(-50%);
            padding: 0;
            background: transparent;
            border: none;
            border-radius: 50%;
            color: #8B9099;
            font-size: 17px;
            cursor: pointer;
            transition:
                color 0.25s ease,
                background 0.25s ease;
        }

        .close-search-btn:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.07);
        }

        /*
        =========================================================
        MOBILE DROPDOWN MENU
        =========================================================
        */

        .mobile-dropdown {
            position: fixed;
            top: 82px;
            left: 0;
            width: 100%;
            z-index: 9997;
            display: none;
            background: rgba(11, 13, 18, 0.99);
            border-bottom: 2px solid var(--av-primary);
            box-shadow:
                0 18px 45px rgba(0, 0, 0, 0.48);
        }

        .admin-bar .mobile-dropdown {
            top: 114px;
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 10px 0 14px;
        }

        .mobile-nav-links a {
            position: relative;
            display: flex;
            align-items: center;
            min-height: 52px;
            padding: 0 24px;
            color: #D7D9DE;
            text-decoration: none !important;
            font-size: 16px;
            font-weight: 600;
            border-bottom:
                1px solid rgba(255, 255, 255, 0.045);
            transition:
                background 0.2s ease,
                color 0.2s ease;
        }

        .mobile-nav-links a:last-child {
            border-bottom: none;
        }

        .mobile-nav-links a:hover {
            color: #FFFFFF;
            background: rgba(56, 189, 248, 0.07);
        }

        .mobile-nav-links a.active {
            color: var(--av-primary);
            background: rgba(56, 189, 248, 0.09);
        }

        .mobile-nav-links a.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 9px;
            bottom: 9px;
            width: 3px;
            border-radius:
                0 5px 5px 0;
            background: var(--av-primary);
        }

        /*
        =========================================================
        OLD SIDEBAR SUPPORT
        =========================================================
        Current design does not use the old full-screen sidebar,
        but these are hidden to avoid conflicts with old JS/CSS.
        =========================================================
        */

        .sidebar-backdrop,
        .mobile-sidebar {
            display: none !important;
        }


        /*
        =========================================================
        HERO SLIDER FIXES
        =========================================================
        */

        .netflix-hero,
        .zeta-hero-wrap {
            position: relative !important;
            margin-bottom: 60px !important;
        }

        .swiper-pagination,
        .slick-dots,
        .owl-dots {
            position: absolute !important;
            bottom: 30px !important;
            left: 0 !important;
            width: 100% !important;
            z-index: 100 !important;
            display: flex !important;
            justify-content: center !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .swiper-container {
            padding-bottom: 0 !important;
        }

        /*
        =========================================================
        SLIDER ARROWS REMOVAL
        =========================================================
        */

        .slick-prev,
        .slick-next,
        .owl-prev,
        .owl-next,
        .tp-leftarrow,
        .tp-rightarrow,
        .flex-prev,
        .flex-next,
        .prev,
        .next,
        .nivo-directionNav,
        .carousel-control-prev,
        .carousel-control-next,
        .swiper-button-prev,
        .swiper-button-next,
        [class*="arrow-left"],
        [class*="arrow-right"],
        [id*="arrow-left"],
        [id*="arrow-right"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }


        /*
        =========================================================
        VIEW ALL BUTTON
        =========================================================
        */

        .view-all {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            float: right;
            margin-top: 5px;
            padding: 7px 15px !important;
            background: var(--av-primary) !important;
            border: 1px solid var(--av-primary) !important;
            border-radius: 6px !important;
            color: #071018 !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            text-decoration: none !important;
            transition:
                background 0.25s ease,
                color 0.25s ease,
                border-color 0.25s ease,
                transform 0.25s ease !important;
        }

        .view-all:hover {
            background: var(--av-primary-hover) !important;
            border-color: var(--av-primary-hover) !important;
            color: #FFFFFF !important;
            transform: translateY(-2px);
        }

        /*
        =========================================================
        SECTION HEADERS
        =========================================================
        */

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-left: 15px;
            border-left: 4px solid var(--av-primary);
        }

        /*
        =========================================================
        RESPONSIVE
        =========================================================
        */

        @media (max-width: 1100px) {
            #masthead {
                height: 74px;
            }

            #content.site-content {
                padding-top: 74px !important;
            }

            .header-container {
                width: 98%;
            }

            .netflix-nav {
                display: none;
            }

            .mobile-nav-toggle {
                display: inline-flex;
            }

            .mobile-dropdown {
                top: 74px;
            }

            .admin-bar .mobile-dropdown {
                top: 106px;
            }
        }

        @media (max-width: 782px) {
            .admin-bar #masthead {
                top: 46px;
            }

            .admin-bar .mobile-dropdown {
                top: 120px;
            }
        }

        @media (max-width: 600px) {
            #masthead {
                height: 72px;
            }

            #content.site-content {
                padding-top: 72px !important;
            }

            .header-container {
                width: calc(100% - 12px);
            }

            #masthead .site-branding img.animeverze-logo-image {
                height: 55px !important;
                max-height: 55px !important;
                max-width: 380px;
            }

            .header-right {
                gap: 22px;
            }

            .site-branding a {
                transform: translateY(-1px);
            }

            .search-trigger,
            .mobile-nav-toggle {
                width: 38px;
                height: 38px;
                font-size: 18px;
            }

            #masthead #search-overlay.active {
                height: 82px;
            }

            .search-form-wrap {
                width: calc(100% - 28px);
            }

            .search-input-full {
                padding:
                    11px 46px 11px 17px;
                border-radius: 10px !important;
                font-size: 15px;
            }

            .mobile-dropdown {
                top: 68px;
            }

            .admin-bar .mobile-dropdown {
                top: 114px;
            }

            .mobile-nav-links a {
                min-height: 50px;
                padding: 0 20px;
                font-size: 15px;
            }

            .netflix-hero,
            .zeta-hero-wrap {
                margin-bottom: 40px !important;
            }

            .swiper-pagination,
            .slick-dots,
            .owl-dots {
                bottom: 18px !important;
            }
        }

        @media (max-width: 350px) {
            #masthead .site-branding img.animeverze-logo-image {
                height: 48px !important;
                max-height: 48px !important;
                max-width: 320px;
            }

            .search-trigger,
            .mobile-nav-toggle {
                width: 34px;
                height: 34px;
            }
        }

        /* =========================================================
   DESKTOP: EXACT 9:16 RATIO & CENTERED COMPACT CATEGORY
========================================================= */

        /* Desktop 8 Columns Grid */
        .movie-grid {
            display: grid !important;
            grid-template-columns: repeat(8, minmax(0, 1fr)) !important;
            gap: 12px !important;
        }

        /* Desktop 9:16 Vertical Poster Shape */
        .movie-card-vertical .thumbnail-wrap {
            aspect-ratio: 9 / 16 !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .movie-card-vertical .thumbnail-wrap img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block !important;
        }

        /* Desktop Details Overlay (Centered) */
        .movie-details-overlay {
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-end !important;
            align-items: center !important;
            text-align: center !important;
            padding: 35px 8px 10px !important;
        }

        /* Desktop Title */
        .movie-card-vertical .entry-title {
            font-size: 13px !important;
            width: 100% !important;
            text-align: center !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            display: block !important;
            margin-bottom: 2px !important;
        }

        /* Desktop Category: Centered & Extra Small (8px) */
        .movie-category {
            font-size: 8px !important;
            letter-spacing: 0.5px !important;
            color: #9ea3ab !important;
            display: block !important;
            width: 100% !important;
            text-align: center !important;
            margin: 0 auto !important;
            text-transform: uppercase !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }


        /* =========================================================
   MOBILE: 9:16 RATIO, LARGER POSTERS & CENTERED CATEGORY
========================================================= */

        @media (max-width: 768px) {

            /* Mobile 3 Columns Grid */
            .movie-grid {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 8px !important;
                width: 100% !important;
            }

            .movie-card-vertical {
                width: 100% !important;
                margin-bottom: 10px !important;
                border-radius: 8px !important;
            }

            /* Mobile 9:16 Shape (පෝස්ටරය දිගට සහ ලොකුවට දිස්වේ) */
            .movie-card-vertical .thumbnail-wrap {
                width: 100% !important;
                aspect-ratio: 9 / 16 !important;
                position: relative !important;
                overflow: hidden !important;
                border-radius: 8px !important;
            }

            .movie-card-vertical .thumbnail-wrap img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                display: block !important;
            }

            /* Mobile Details Overlay (Centered) */
            .movie-details-overlay {
                position: absolute !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                padding: 35px 6px 8px !important;
                background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(0, 0, 0, 0) 100%) !important;
                min-height: 65px !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: flex-end !important;
                align-items: center !important;
                text-align: center !important;
            }

            /* Mobile Title */
            .movie-card-vertical .entry-title {
                font-size: 11.5px !important;
                font-weight: 600 !important;
                line-height: 1.25 !important;
                color: #ffffff !important;
                margin: 0 0 3px 0 !important;
                width: 100% !important;
                text-align: center !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                display: block !important;
            }

            /* Mobile Category: Centered & Extra Small (7.5px) */
            .movie-category {
                font-size: 7.5px !important;
                letter-spacing: 0.5px !important;
                color: #9ea3ab !important;
                display: block !important;
                width: 100% !important;
                text-align: center !important;
                margin: 0 auto !important;
                text-transform: uppercase !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            /* Mobile Top Badges */
            .movie-quality {
                top: 6px !important;
                left: 6px !important;
                font-size: 8px !important;
                padding: 2px 4px !important;
                border-radius: 3px !important;
            }

            .movie-rating {
                top: 6px !important;
                right: 6px !important;
                font-size: 8.5px !important;
                padding: 2px 4px !important;
                border-radius: 3px !important;
            }
        }
    </style>
</head>

<body <?php body_class(); ?>>

    <?php
    if (function_exists('wp_body_open')) {
        wp_body_open();
    }

    /*
=========================================================
CURRENT CATEGORY LINKS
=========================================================

For now the old WordPress category names are kept internally
so existing posts and URLs do not break.

Frontend:
Cartoon Movies  -> Anime Movies
Cartoon Series  -> Anime Series

Later we can rename/rebuild the categories properly.
=========================================================
*/

    $anime_movies_id = get_cat_ID('Anime Movies');
    $anime_series_id = get_cat_ID('Anime Series');
    $anime_movies_url = $anime_movies_id
        ? get_category_link($anime_movies_id)
        : home_url('/');
    $anime_series_url = $anime_series_id
        ? get_category_link($anime_series_id)
        : home_url('/');
    $about_page = get_page_by_path('about-us');
    $contact_page = get_page_by_path('contact-us');
    $about_url = $about_page
        ? get_permalink($about_page->ID)
        : home_url('/about-us/');
    $contact_url = $contact_page
        ? get_permalink($contact_page->ID)
        : home_url('/contact-us/');
    ?>

    <div id="page" class="site">
        <header id="masthead" class="site-header">
            <div class="header-container">

                <!-- =================================================
                 BRANDING
            ================================================== -->

                <div class="site-branding">
                    <a
                        href="<?php echo esc_url(home_url('/')); ?>"
                        aria-label="AnimeVerze Home">
                        <img
                            class="animeverze-logo-image"
                            src="https://animeverze.com/wp-content/uploads/2026/09/ChatGPT_Image_Aug_15__2026__08_37_57_PM-removebg-preview.png"
                            alt="AnimeVerze Logo"
                            loading="eager"
                            decoding="async" />
                    </a>
                </div>

                <!-- =================================================
                 DESKTOP NAVIGATION
            ================================================== -->

                <nav
                    id="primary-nav"
                    class="netflix-nav"
                    aria-label="Primary Navigation">
                    <ul>
                        <li>
                            <a
                                href="<?php echo esc_url(home_url('/')); ?>"
                                class="<?php echo is_front_page() ? 'active' : ''; ?>">
                                Home
                            </a>
                        </li>

                        <li>
                            <a
                                href="<?php echo esc_url($anime_series_url); ?>"
                                class="<?php echo is_category('anime-series') ? 'active' : ''; ?>">
                                Anime Series
                            </a>
                        </li>

                        <li>
                            <a
                                href="<?php echo esc_url($anime_movies_url); ?>"
                                class="<?php echo is_category('anime-movies') ? 'active' : ''; ?>">
                                Anime Movies
                            </a>
                        </li>

                        <li>
                            <a
                                href="<?php echo esc_url($about_url); ?>"
                                class="<?php echo is_page('about-us') ? 'active' : ''; ?>">
                                About
                            </a>
                        </li>

                        <li>
                            <a
                                href="<?php echo esc_url($contact_url); ?>"
                                class="<?php echo is_page('contact-us') ? 'active' : ''; ?>">
                                Contact
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- =================================================
                 HEADER ACTIONS
            ================================================== -->

                <div class="header-right">
                    <button
                        type="button"
                        class="search-trigger"
                        onclick="toggleSearch()"
                        aria-label="Search Anime"
                        aria-controls="search-overlay">
                        <i
                            class="fa fa-search"
                            aria-hidden="true"></i>
                    </button>

                    <button
                        type="button"
                        class="mobile-nav-toggle"
                        onclick="toggleMobileDropdown()"
                        aria-label="Open Menu"
                        aria-controls="dropdown-menu">
                        <i
                            id="sw-menu-icon-open"
                            class="fa fa-bars"
                            aria-hidden="true"></i>

                        <i
                            id="sw-menu-icon-close"
                            class="fa fa-times"
                            aria-hidden="true"
                            style="display:none;"></i>
                    </button>
                </div>
            </div>

            <!-- =====================================================
             SEARCH
        ====================================================== -->

            <div
                id="search-overlay"
                aria-hidden="true">
                <div class="search-form-wrap">
                    <form
                        role="search"
                        method="get"
                        action="<?php echo esc_url(home_url('/')); ?>">
                        <input
                            type="search"
                            class="search-input-full"
                            placeholder="Search anime..."
                            name="s"
                            id="full-search-input"
                            autocomplete="off"
                            value="<?php echo esc_attr(get_search_query()); ?>">
                    </form>

                    <button
                        type="button"
                        class="close-search-btn"
                        onclick="toggleSearch()"
                        aria-label="Close Search">
                        <i
                            class="fa fa-times"
                            aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- =====================================================
             MOBILE MENU
        ====================================================== -->

            <div
                id="dropdown-menu"
                class="mobile-dropdown"
                aria-hidden="true">
                <nav
                    class="mobile-nav-links"
                    aria-label="Mobile Navigation">
                    <a
                        href="<?php echo esc_url(home_url('/')); ?>"
                        class="<?php echo is_front_page() ? 'active' : ''; ?>">
                        Home
                    </a>

                    <a
                        href="<?php echo esc_url($anime_series_url); ?>"
                        class="<?php echo is_category('anime-series') ? 'active' : ''; ?>">
                        Anime Series
                    </a>

                    <a
                        href="<?php echo esc_url($anime_movies_url); ?>"
                        class="<?php echo is_category('anime-movies') ? 'active' : ''; ?>">
                        Anime Movies
                    </a>

                    <a
                        href="<?php echo esc_url($about_url); ?>"
                        class="<?php echo is_page('about-us') ? 'active' : ''; ?>">
                        About
                    </a>

                    <a
                        href="<?php echo esc_url($contact_url); ?>"
                        class="<?php echo is_page('contact-us') ? 'active' : ''; ?>">
                        Contact
                    </a>
                </nav>
            </div>
        </header>

        <script>
            function closeMobileDropdown() {
                const menu = document.getElementById('dropdown-menu');
                const openIcon = document.getElementById('sw-menu-icon-open');
                const closeIcon = document.getElementById('sw-menu-icon-close');

                if (!menu) {
                    return;
                }
                menu.style.display = 'none';
                menu.setAttribute('aria-hidden', 'true');

                if (openIcon) {
                    openIcon.style.display = 'inline-block';
                }
                if (closeIcon) {
                    closeIcon.style.display = 'none';
                }
                document.body.style.overflow = '';
            }


            function toggleMobileDropdown() {
                const menu = document.getElementById('dropdown-menu');
                const openIcon = document.getElementById('sw-menu-icon-open');
                const closeIcon = document.getElementById('sw-menu-icon-close');

                if (!menu) {
                    return;
                }

                const isOpen = menu.style.display === 'block';

                if (isOpen) {
                    closeMobileDropdown();
                } else {
                    /*
                    Close search first if search is open.
                    */
                    const searchOverlay = document.getElementById('search-overlay');
                    if (
                        searchOverlay &&
                        searchOverlay.classList.contains('active')
                    ) {
                        searchOverlay.classList.remove('active');
                        searchOverlay.setAttribute('aria-hidden', 'true');
                    }

                    menu.style.display = 'block';
                    menu.setAttribute('aria-hidden', 'false');

                    if (openIcon) {
                        openIcon.style.display = 'none';
                    }
                    if (closeIcon) {
                        closeIcon.style.display = 'inline-block';
                    }

                    document.body.style.overflow = 'hidden';
                }
            }

            function toggleSearch() {
                const overlay = document.getElementById('search-overlay');
                const input = document.getElementById('full-search-input');
                if (!overlay) {
                    return;
                }
                const willOpen = !overlay.classList.contains('active');

                /*
                Close mobile navigation before opening search.
                */

                closeMobileDropdown();
                if (willOpen) {
                    overlay.classList.add('active');
                    overlay.setAttribute('aria-hidden', 'false');

                    setTimeout(function() {
                        if (input) {
                            input.focus();
                        }

                    }, 180);
                } else {
                    overlay.classList.remove('active');
                    overlay.setAttribute('aria-hidden', 'true');
                }
            }
            /*
            ESC closes mobile menu and search.
            */
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeMobileDropdown();
                    const overlay = document.getElementById('search-overlay');
                    if (overlay) {
                        overlay.classList.remove('active');
                        overlay.setAttribute('aria-hidden', 'true');
                    }
                }
            });
            /*
            If desktop width is restored while the mobile
            menu is open, automatically close the menu.
            */
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1100) {
                    closeMobileDropdown();
                }
            });
        </script>
        <div id="content" class="site-content">