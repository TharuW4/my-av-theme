<?php

/**
 * AnimeVerze - Single Anime Template
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();

        $post_id = get_the_ID();

        $content_type = get_post_meta($post_id, '_content_type', true);
        $movie_url = trim((string) get_post_meta($post_id, '_movie_url', true));
        $episode_text = (string) get_post_meta($post_id, '_episode_links', true);

        $rating = get_post_meta($post_id, '_rating', true);
        $quality = get_post_meta($post_id, '_quality', true);
        $year = get_post_meta($post_id, '_release_year', true);
        $director = get_post_meta($post_id, '_director', true);

        $cast_list = json_decode(get_post_meta($post_id, '_cast_json', true), true);
        $screenshots = json_decode(get_post_meta($post_id, '_movie_screenshots', true), true);

        $direct_text = (string) get_post_meta($post_id, '_direct_download_url', true);
        $telegram_text = (string) get_post_meta($post_id, '_telegram_download_url', true);

        $movie_subtitles_raw =
            get_post_meta(
                $post_id,
                '_movie_subtitles',
                true
            );

        if ($content_type !== 'series') {
            $content_type = 'movie';
        }

        $is_series = ($content_type === 'series');
        $type_label = $is_series ? 'Anime Series' : 'Anime Movie';

        $episode_links = array();
        $direct_links = array();
        $telegram_links = array();

        if ($episode_text !== '') {
            $episode_links = array_map(
                'trim',
                explode("\n", str_replace("\r", '', $episode_text))
            );
        }

        if ($is_series && $direct_text !== '') {
            $direct_links = array_map(
                'trim',
                explode("\n", str_replace("\r", '', $direct_text))
            );
        }

        if ($is_series && $telegram_text !== '') {
            $telegram_links = array_map(
                'trim',
                explode("\n", str_replace("\r", '', $telegram_text))
            );
        }


        /*
|--------------------------------------------------------------------------
| Multi Season Data
|--------------------------------------------------------------------------
*/

        $anime_seasons = $is_series
            ? get_post_meta($post_id, '_anime_seasons', true)
            : array();


        /*
|--------------------------------------------------------------------------
| Backward Compatibility
|--------------------------------------------------------------------------
| පරණ post එකකට _anime_seasons නැත්නම් old episode fields
| Season 1 විදිහට භාවිතා කරනවා.
|--------------------------------------------------------------------------
*/

        if (
            $is_series &&
            (!is_array($anime_seasons) || empty($anime_seasons))
        ) {

            $legacy_count = max(
                count($episode_links),
                count($direct_links),
                count($telegram_links)
            );

            $legacy_episodes = array();


            for ($i = 0; $i < $legacy_count; $i++) {

                $watch = isset($episode_links[$i])
                    ? trim($episode_links[$i])
                    : '';

                $direct = isset($direct_links[$i])
                    ? trim($direct_links[$i])
                    : '';

                $telegram = isset($telegram_links[$i])
                    ? trim($telegram_links[$i])
                    : '';


                if (
                    $watch === '' &&
                    $direct === '' &&
                    $telegram === ''
                ) {
                    continue;
                }


                $legacy_episodes[] = array(
                    'watch' => $watch,
                    'direct' => $direct,
                    'telegram' => $telegram
                );
            }


            if (!empty($legacy_episodes)) {

                $anime_seasons = array(
                    array(
                        'episodes' => $legacy_episodes
                    )
                );
            } else {

                $anime_seasons = array();
            }
        }


        /*
|--------------------------------------------------------------------------
| Remove invalid / empty seasons
|--------------------------------------------------------------------------
*/

        if (!is_array($anime_seasons)) {
            $anime_seasons = array();
        }

        $anime_seasons = array_values(
            array_filter(
                $anime_seasons,
                function ($season) {

                    return (
                        isset($season['episodes']) &&
                        is_array($season['episodes']) &&
                        !empty($season['episodes'])
                    );
                }
            )
        );

        $season_count = count($anime_seasons);

        $movie_direct = !$is_series ? trim($direct_text) : '';
        $movie_telegram = !$is_series ? trim($telegram_text) : '';

        $is_movie_mp4 = !$is_series && $movie_url !== '' && stripos($movie_url, '.mp4') !== false;

        $download_page = get_posts(array(
            'post_type'   => 'page',
            'numberposts' => 1,
            'meta_key'    => '_wp_page_template',
            'meta_value'  => 'page-download.php'
        ));

        $landing_page = !empty($download_page)
            ? get_permalink($download_page[0]->ID)
            : home_url('/download/');

        $anime_name = get_the_title();

        function animeverze_download_page_url($landing_page, $direct_link, $telegram_link, $name, $subtitle_tracks = array())
        {
            if (!$direct_link && !$telegram_link && empty($subtitle_tracks)) {
                return '';
            }

            return add_query_arg(
                array(
                    'direct'       => $direct_link ? base64_encode($direct_link) : '',
                    'telegram'     => $telegram_link ? base64_encode($telegram_link) : '',
                    'av_name'      => $name,
                    'av_subtitles' => !empty($subtitle_tracks)
                        ? base64_encode(wp_json_encode($subtitle_tracks))
                        : '',
                ),
                $landing_page
            );
        }

        if (!function_exists('animeverze_prepare_subtitle_tracks')) {

            function animeverze_prepare_subtitle_tracks($raw_tracks)
            {
                if (!is_array($raw_tracks)) {
                    return array();
                }


                $clean_tracks = array();


                foreach ($raw_tracks as $track) {

                    if (!is_array($track)) {
                        continue;
                    }


                    $lang =
                        isset($track['lang'])
                        ? sanitize_key(
                            (string) $track['lang']
                        )
                        : 'si';


                    $url =
                        isset($track['url'])
                        ? trim(
                            (string) $track['url']
                        )
                        : '';


                    if ($url === '') {
                        continue;
                    }


                    $label_map = array(
                        'si'      => 'Sinhala',
                        'sinhala' => 'Sinhala',
                        'en'      => 'English',
                        'english' => 'English',
                        'ja'      => 'Japanese'
                    );

                    $label = isset($label_map[$lang])
                        ? $label_map[$lang]
                        : (function_exists('animeverze_subtitle_label') ? animeverze_subtitle_label($lang) : strtoupper($lang));


                    $clean_tracks[] = array(
                        'lang' =>
                        $lang !== ''
                            ? $lang
                            : 'si',

                        'label' => $label,

                        'url' =>
                        esc_url_raw($url)
                    );
                }


                return $clean_tracks;
            }
        }


        $movie_subtitles =
            !$is_series
            ? animeverze_prepare_subtitle_tracks(
                $movie_subtitles_raw
            )
            : array();

        $movie_download_page_url = !$is_series
            ? animeverze_download_page_url(
                $landing_page,
                $movie_direct,
                $movie_telegram,
                $anime_name,
                $movie_subtitles
            )
            : '';
?>

        <script src="https://cdn.jsdelivr.net/npm/artplayer/dist/artplayer.js"></script>
        <style>
            :root {
                --av-blue: #38BDF8;
                --av-blue-hover: #0EA5E9;
                --av-soft: #7DD3FC;
                --av-bg: #0F0F0F;
                --av-card: #15181E;
                --av-card-hover: #1B2028;
                --av-border: #292E38;
                --av-text: #FFFFFF;
                --av-muted: #A3A8B2;
            }

            body {
                background: var(--av-bg);
            }

            .av-single {
                width: 93%;
                max-width: 1800px;
                margin: 0 auto;
                padding-bottom: 70px;
            }

            .av-player-section {
                margin-top: 30px;
            }

            .av-player-section.is-series {
                display: none;
            }

            .av-player-container {
                position: relative;
                width: 100%;
                aspect-ratio: 16/9;
                overflow: hidden;
                background: #000;
                border: 1px solid #20242C;
                border-radius: 12px;
                box-shadow: 0 15px 45px rgba(0, 0, 0, .35);
            }

            .av-player-container video,
            .av-player-container iframe {
                display: block;
                width: 100%;
                height: 100%;
                border: 0;
                background: #000;
            }

            #mainPlayer {
                position: relative;
                z-index: 20;
            }

            .av-player-mount {
                position: relative;
                z-index: 20;
                width: 100%;
                height: 100%;
            }

            .av-player-mount .video-js {
                width: 100%;
                height: 100%;
            }

            .av-player-mount iframe {
                width: 100%;
                height: 100%;
                border: 0;
            }

            .video-js .vjs-play-progress,
            .video-js .vjs-volume-level {
                background: #38BDF8;
            }

            .video-js .vjs-big-play-button {
                width: 82px;
                height: 82px;

                top: 50%;
                left: 50%;

                margin: 0;

                transform: translate(-50%, -50%);

                border: 0;
                border-radius: 50%;

                background: #38BDF8;

                color: #FFFFFF;

                font-size: 34px;
                line-height: 82px;

                box-shadow:
                    0 0 0 12px rgba(56, 189, 248, .08),
                    0 10px 35px rgba(0, 0, 0, .55);

                transition:
                    transform .18s ease,
                    background .18s ease;
            }


            .video-js:hover .vjs-big-play-button,
            .video-js .vjs-big-play-button:focus {
                background: #0EA5E9;
            }


            .video-js .vjs-big-play-button:hover {
                transform:
                    translate(-50%, -50%) scale(1.05);
            }


            /* Play triangle center */
            .video-js .vjs-big-play-button .vjs-icon-placeholder:before {
                line-height: 82px;
            }

            /* =====================================================
   Video.js Controls - Final
===================================================== */

            .video-js .vjs-control-bar {
                height: 4.2em;
                align-items: center;
                z-index: 10;
                background: rgba(8, 10, 14, .94);
            }


            /* Buttons */
            .video-js .vjs-button {
                height: 4.2em;
            }

            .video-js .vjs-button>.vjs-icon-placeholder:before {
                line-height: 2.33;
            }


            /* Time */
            .video-js .vjs-time-control {
                height: 4.2em;
                line-height: 4.2em;
                color: #fff;
            }


            /* Progress area */
            .video-js .vjs-progress-control {
                height: 4.2em;
            }


            /* Fix white seek-time popup */
            .video-js .vjs-time-tooltip,
            .video-js .vjs-mouse-display .vjs-time-tooltip,
            .video-js .vjs-play-progress .vjs-time-tooltip {
                width: auto !important;
                height: auto !important;
                padding: 5px 7px !important;
                background: #15181E !important;
                border: 1px solid #292E38 !important;
                border-radius: 4px !important;
                color: #FFFFFF !important;
                line-height: 1.3 !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .45);
            }


            /* Mouse position marker on progress bar */
            .video-js .vjs-mouse-display {
                width: 1px !important;
                background: rgba(56, 189, 248, .55) !important;
            }


            /* Progress + Volume color */
            .video-js .vjs-play-progress,
            .video-js .vjs-volume-level {
                background: #38BDF8;
            }


            /* Menus */
            .video-js .vjs-menu {
                z-index: 20;
            }

            .video-js .vjs-menu-content {
                background: #15181E;
                border: 1px solid #292E38;
                border-radius: 5px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, .55);
            }


            /* Normal menu item */
            .video-js .vjs-menu li {
                color: #FFFFFF;
            }


            /* Hover */
            .video-js .vjs-menu li:hover,
            .video-js .vjs-menu li:focus {
                background: #1B2028;
                color: #FFFFFF;
            }


            /* Selected subtitle / speed */
            .video-js .vjs-menu li.vjs-selected,
            .video-js .vjs-menu li.vjs-selected:hover,
            .video-js .vjs-menu li.vjs-selected:focus {
                background: #38BDF8;
                color: #06131B;
                font-weight: 700;
            }


            /* Remove "Captions Settings" */
            .video-js .vjs-texttrack-settings {
                display: none !important;
            }


            /* No subtitle = keep CC visible but disabled */
            .video-js .vjs-subs-caps-button {
                display: block !important;
            }

            .video-js .vjs-subs-caps-button[disabled],
            .video-js .vjs-subs-caps-button.vjs-disabled {
                opacity: .30;
                cursor: not-allowed;
                pointer-events: none;
            }


            /* Keep controls visible while paused */
            .video-js.vjs-paused .vjs-control-bar {
                display: flex !important;
                opacity: 1 !important;
                visibility: visible !important;
            }


            /* Fullscreen stability */
            .video-js.vjs-fullscreen {
                width: 100% !important;
                height: 100% !important;
            }

            .video-js.vjs-fullscreen.vjs-user-active .vjs-control-bar,
            .video-js.vjs-fullscreen.vjs-paused .vjs-control-bar {
                display: flex !important;
                opacity: 1 !important;
                visibility: visible !important;
            }

            #player-ad-layer:not([style*="display: none"])+.av-player-mount .vjs-big-play-button {
                display: none !important;
            }

            #player-ad-layer {
                position: absolute;
                inset: 0;
                z-index: 50;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, .05);
                cursor: pointer;
            }

            .av-play-pulse {
                position: absolute;
                width: 82px;
                height: 82px;
                border-radius: 50%;
                background: rgba(56, 189, 248, .32);
                animation: avPulse 2s infinite;
            }

            .av-play-button {
                position: relative;
                z-index: 2;
                width: 82px;
                height: 82px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--av-blue);
                border-radius: 50%;
                color: #06131B;
                font-size: 34px;
                box-shadow: 0 10px 35px rgba(0, 0, 0, .55);
            }

            .av-play-button i {
                margin-left: 5px;
            }

            @keyframes avPulse {
                from {
                    transform: scale(1);
                    opacity: .8;
                }

                to {
                    transform: scale(1.65);
                    opacity: 0;
                }
            }

            .av-player-notice {
                margin-top: 14px;
                padding: 13px 15px;
                background: rgba(250, 204, 21, .045);
                border: 1px solid rgba(250, 204, 21, .16);
                border-radius: 7px;
                color: #B8BBC2;
                font-size: 20px;
                line-height: 1.6;
                text-align: center;
            }

            .av-player-notice strong {
                color: #FACC15;
            }

            .av-player-downloads {
                display: flex;
                flex-direction: column;
                gap: 14px;
                margin-top: 45px;
                margin-bottom: 45px;
            }

            .av-player-downloads.series-downloads {
                display: none;
            }

            .av-download-btn {
                min-height: 90px;
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 15px 16px;
                background: var(--av-card);
                border: 1px solid var(--av-border);
                border-radius: 11px;
                color: #fff !important;
                text-decoration: none !important;
                transition: .2s ease;
            }

            .av-download-btn:hover {
                background: var(--av-card-hover);
                border-color: rgba(56, 189, 248, .5);
                transform: translateY(-1px);
            }

            .av-download-icon {
                width: 52px;
                height: 52px;
                flex: 0 0 46px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(56, 189, 248, .1);
                border-radius: 10px;
                color: var(--av-blue);
                font-size: 20px;
            }

            .av-download-text {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
                gap: 3px;
                text-align: left;
            }

            .av-download-text strong {
                color: #fff;
                font-size: 22px;
            }

            .av-download-text span {
                color: #858B96;
                font-size: 16px;
            }

            .av-main-content {
                margin-top: 32px;
            }

            .av-details {
                display: flex;
                align-items: stretch;
                gap: 26px;
                padding: 22px;
                background: linear-gradient(145deg, #16191F, #111318);
                border: 1px solid var(--av-border);
                border-radius: 13px;
            }

            .av-poster {
                flex: 0 0 210px;
            }

            .av-poster img {
                display: block;
                width: 100%;
                aspect-ratio: 2/3;
                object-fit: cover;
                border: 1px solid rgba(255, 255, 255, .07);
                border-radius: 9px;
            }

            .av-info {
                flex: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .av-type {
                width: fit-content;
                margin-bottom: 9px;
                padding: 4px 8px;
                background: rgba(56, 189, 248, .09);
                border: 1px solid rgba(56, 189, 248, .23);
                border-radius: 5px;
                color: var(--av-blue);
                font-size: 9px;
                font-weight: 800;
                letter-spacing: .6px;
                text-transform: uppercase;
            }

            .av-title {
                margin: 0 0 13px;
                color: #fff;
                font-size: clamp(28px, 3vw, 44px);
                font-weight: 900;
                line-height: 1.08;
                letter-spacing: -.8px;
            }

            .av-badges {
                display: flex;
                flex-wrap: wrap;
                gap: 7px;
                margin-bottom: 14px;
            }

            .av-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 5px 9px;
                background: rgba(255, 255, 255, .045);
                border: 1px solid rgba(255, 255, 255, .1);
                border-radius: 5px;
                color: #D8DBE1;
                font-size: 10px;
                font-weight: 700;
            }

            .av-badge.rating i {
                color: #FACC15;
            }

            .av-badge.quality {
                background: var(--av-blue);
                border-color: var(--av-blue);
                color: #06131B;
                font-weight: 900;
            }

            .av-details-list {
                list-style: none;
                margin: 0;
                padding: 0;
                color: var(--av-muted);
                font-size: 12px;
            }

            .av-details-list li {
                margin-bottom: 5px;
                line-height: 1.5;
            }

            .av-details-list b {
                margin-right: 4px;
                color: #fff;
            }

            .av-section-title {
                margin: 44px 0 18px;
                padding-left: 11px;
                border-left: 4px solid var(--av-blue);
                color: #fff;
                font-size: 19px;
                font-weight: 800;
                line-height: 1.2;
            }

            .av-description {
                color: #ADB2BC;
                font-size: 14px;
                line-height: 1.8;
            }

            .av-description p {
                margin-bottom: 14px;
            }

            .av-description a {
                color: var(--av-blue);
            }

            .av-cast {
                display: flex;
                gap: 16px;
                overflow-x: auto;
                padding: 3px 0 12px;
            }

            .av-cast-card {
                flex: 0 0 95px;
                text-align: center;
            }

            .av-cast-card img {
                display: block;
                width: 80px;
                height: 80px;
                margin: 0 auto;
                object-fit: cover;
                border: 2px solid var(--av-border);
                border-radius: 50%;
            }

            .av-cast-name {
                display: block;
                margin-top: 6px;
                color: #fff;
                font-size: 11px;
                font-weight: 700;
                line-height: 1.3;
            }

            .av-cast-char {
                display: block;
                margin-top: 2px;
                color: #858B96;
                font-size: 9px;
                line-height: 1.3;
            }

            .av-info-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 10px;
                margin-top: 36px;
            }

            .av-info-card {
                padding: 14px 10px;
                background: var(--av-card);
                border: 1px solid var(--av-border);
                border-radius: 8px;
                text-align: center;
            }

            .av-info-label {
                margin-bottom: 5px;
                color: #777E89;
                font-size: 8px;
                font-weight: 800;
                letter-spacing: .8px;
                text-transform: uppercase;
            }

            .av-info-value {
                overflow: hidden;
                color: #fff;
                font-size: 13px;
                font-weight: 800;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .av-info-value.rating {
                color: #FACC15;
            }

            .av-episode-list {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .av-seasons-accordion {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .av-season-group {
                overflow: hidden;
                background: var(--av-card);
                border: 1px solid var(--av-border);
                border-radius: 9px;
            }

            .av-season-toggle {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                padding: 18px 20px;
                min-height: 62px;
                background: transparent;
                border: 0;
                color: #fff;
                cursor: pointer;
                text-align: left;
                font-family: inherit;
            }

            .av-season-toggle:hover {
                background: var(--av-card-hover);
            }

            .av-season-toggle-text {
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 16px;
                font-weight: 800;
            }

            .av-season-count {
                color: #9AA3AF;
                font-size: 12px;
                font-weight: 600;
            }

            .av-season-arrow {
                color: var(--av-blue);
                font-size: 15px;
                transition: transform .2s ease;
            }

            .av-season-panel {
                display: none;
                padding: 0 14px 14px;
            }

            .av-season-group.open .av-season-panel {
                display: block;
            }

            .av-season-group.open .av-season-arrow {
                transform: rotate(180deg);
            }

            .av-season-panel .av-episode {
                background: #111318;
            }

            .av-episode {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 16px 18px;
                min-height: 58px;
                background: var(--av-card);
                border: 1px solid var(--av-border);
                border-radius: 10px;
                cursor: pointer;
                transition: .2s ease;
            }

            .av-episode:hover {
                background: var(--av-card-hover);
                border-color: rgba(56, 189, 248, .5);
                transform: translateX(2px);
            }

            .av-episode.active {
                background: rgba(56, 189, 248, .09);
                border-color: var(--av-blue);
            }

            .av-episode-number {
                flex: 0 0 45px;
                color: var(--av-soft);
                font-size: 13px;
                font-weight: 900;
            }

            .av-episode-title {
                flex: 1;
                color: #F2F3F5;
                font-size: 13px;
                font-weight: 650;
            }

            .av-episode-play {
                width: 30px;
                height: 30px;
                flex: 0 0 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(56, 189, 248, .08);
                border-radius: 50%;
                color: var(--av-blue);
                font-size: 11px;
            }

            .av-episode.active .av-episode-play {
                background: var(--av-blue);
                color: #06131B;
            }

            .av-screenshot-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 10px;
            }

            .av-screenshot-item {
                overflow: hidden;
                background: var(--av-card);
                border: 1px solid var(--av-border);
                border-radius: 8px;
            }

            .av-screenshot-item img {
                display: block;
                width: 100%;
                aspect-ratio: 16/9;
                object-fit: cover;
            }

            #comments {
                margin-top: 50px;
                padding: 24px;
                background: var(--av-card);
                border: 1px solid var(--av-border);
                border-radius: 12px;
            }

            #comments .comments-title,
            #comments .comment-reply-title {
                margin: 0 0 22px;
                padding-left: 11px;
                border-left: 4px solid var(--av-blue);
                color: #fff;
                font-size: 19px;
                font-weight: 800;
            }

            #comments .comment-list {
                margin: 0 0 28px;
                padding: 0;
                list-style: none;
            }

            #comments .comment-body {
                margin-bottom: 12px;
                padding: 16px;
                background: #111318;
                border: 1px solid var(--av-border);
                border-radius: 9px;
            }

            #comments .comment-author {
                color: #fff;
                font-weight: 700;
            }

            #comments .comment-author img {
                border-radius: 50%;
            }

            #comments .comment-meta,
            #comments .comment-meta a {
                color: #7F8691;
                font-size: 11px;
                text-decoration: none;
            }

            #comments .comment-content {
                margin-top: 10px;
                color: #B8BDC6;
                font-size: 13px;
                line-height: 1.7;
            }

            #comments .reply a {
                display: inline-block;
                margin-top: 8px;
                padding: 5px 10px;
                border: 1px solid rgba(56, 189, 248, .35);
                border-radius: 6px;
                color: var(--av-blue);
                font-size: 10px;
                font-weight: 800;
                text-decoration: none;
            }

            #comments .reply a:hover {
                background: rgba(56, 189, 248, .1);
            }

            #comments label {
                display: block;
                margin-bottom: 6px;
                color: #9299A5;
                font-size: 11px;
                font-weight: 600;
            }

            #comments input[type="text"],
            #comments input[type="email"],
            #comments input[type="url"],
            #comments textarea {
                width: 100%;
                box-sizing: border-box;
                padding: 13px !important;
                background: #111318 !important;
                border: 1px solid var(--av-border) !important;
                border-radius: 8px !important;
                color: #fff !important;
                outline: none;
            }

            #comments textarea {
                min-height: 130px;
                resize: vertical;
            }

            #comments input:focus,
            #comments textarea:focus {
                border-color: var(--av-blue) !important;
                box-shadow: 0 0 0 3px rgba(56, 189, 248, .07);
            }

            #comments .submit {
                min-height: 42px;
                padding: 0 22px !important;
                background: var(--av-blue) !important;
                border: 0 !important;
                border-radius: 7px !important;
                color: #06131B !important;
                font-size: 11px !important;
                font-weight: 900 !important;
                text-transform: uppercase;
                cursor: pointer;
            }

            #comments .submit:hover {
                background: var(--av-blue-hover) !important;
                color: #fff !important;
            }

            #comments .comment-form-cookies-consent,
            #comments .logged-in-as,
            #comments .logged-in-as a {
                color: #858B96;
                font-size: 11px;
            }

            #comments .children {
                margin-left: 22px;
                padding-left: 14px;
                border-left: 1px solid var(--av-border);
            }

            @media (max-width:768px) {
                #comments {
                    margin-top: 35px;
                    padding: 16px;
                    border-radius: 9px;
                }

                #comments .comments-title,
                #comments .comment-reply-title {
                    font-size: 16px;
                }

                #comments .comment-body {
                    padding: 13px;
                }

                #comments .children {
                    margin-left: 10px;
                    padding-left: 10px;
                }
            }

            @media (max-width:768px) {
                .av-single {
                    width: calc(100% - 20px);
                    padding-bottom: 45px;
                }

                .av-player-section {
                    margin-top: 15px;
                }

                .av-player-container {
                    border-radius: 7px;
                }

                .av-play-button,
                .av-play-pulse {
                    width: 70px;
                    height: 70px;
                }

                .av-play-button {
                    font-size: 30px;
                }

                /* Sinhala Cartoons Notice Box Size */
                .av-player-notice {
                    padding: 12px !important;
                    font-size: 13px !important;
                }

                .av-player-downloads {
                    gap: 8px;
                    margin-top: 24px;
                }

                .av-download-btn {
                    min-height: 78px;
                    padding: 14px 16px;
                }

                .av-download-icon {
                    width: 44px;
                    height: 44px;
                    flex-basis: 44px;
                }

                /* Sinhala Cartoons Download Button Text Size */
                .av-download-text strong {
                    font-size: 15px !important;
                }

                .av-download-text span {
                    font-size: 12px !important;
                }

                .av-main-content {
                    margin-top: 20px;
                }

                .av-details {
                    gap: 12px;
                    padding: 15px;
                    border-radius: 9px;
                }

                .av-poster {
                    flex: 0 0 135px;
                }

                .av-poster img {
                    border-radius: 7px;
                }

                .av-type {
                    margin-bottom: 6px;
                    padding: 3px 6px;
                    font-size: 9px;
                }

                /* Sinhala Cartoons Title Size (1.7rem) */
                .av-title {
                    margin-bottom: 10px;
                    font-size: 1.7rem !important;
                    line-height: 1.2;
                    letter-spacing: normal;
                }

                .av-badges {
                    gap: 5px;
                    margin-bottom: 10px;
                }

                .av-badge {
                    padding: 4px 7px;
                    font-size: 10px;
                }

                /* Sinhala Cartoons Details List Text Size (14px) */
                .av-details-list {
                    font-size: 14px !important;
                }

                .av-details-list li {
                    margin-bottom: 6px;
                    font-size: 14px !important;
                    color: #fff;
                }

                /* Sinhala Cartoons Headings Size (1.2rem) */
                .av-section-title {
                    margin: 40px 0 20px !important;
                    padding-left: 12px;
                    border-left-width: 4px;
                    font-size: 1.2rem !important;
                }

                /* Sinhala Cartoons Description Text Size (15px) */
                .av-description {
                    font-size: 15px !important;
                    line-height: 1.8;
                }

                .av-cast {
                    gap: 12px;
                }

                .av-cast-card {
                    flex-basis: 90px;
                }

                .av-cast-card img {
                    width: 75px;
                    height: 75px;
                }

                /* Sinhala Cartoons Cast Details */
                .av-cast-name {
                    font-size: 13px !important;
                }

                .av-cast-char {
                    font-size: 11px !important;
                }

                .av-info-grid {
                    gap: 8px;
                    margin-top: 26px;
                }

                .av-info-card {
                    padding: 12px 6px;
                }

                .av-info-label {
                    font-size: 9px !important;
                }

                .av-info-value {
                    font-size: 13px !important;
                }

                /* Sinhala Cartoons Episode Row Text Sizes */
                .av-episode {
                    gap: 10px;
                    padding: 16px 18px;
                }

                .av-episode:hover {
                    transform: none;
                }

                .av-episode-number {
                    flex-basis: 42px;
                    font-size: 16px !important;
                    font-weight: 800;
                }

                .av-episode-title {
                    font-size: 15px !important;
                }

                .av-episode-play {
                    width: 30px;
                    height: 30px;
                    flex-basis: 30px;
                    font-size: 12px;
                }

                .av-screenshot-grid {
                    grid-template-columns: 1fr;
                    gap: 12px;
                }
            }

            @media (max-width:420px) {
                .av-poster {
                    flex-basis: 120px;
                }

                /* කුඩා තිර වලදීත් අකුරු ඕනවට වඩා කුඩා වීම වැළැක්වීම */
                .av-title {
                    font-size: 1.5rem !important;
                }

                .av-details-list li {
                    font-size: 13px !important;
                }
            }

            @media (min-width:769px) {

                .av-download-btn {
                    width: 40%;
                    margin: 0 auto;
                    justify-content: center;
                }

                .av-download-text {
                    text-align: center;
                    align-items: center;
                }

                .av-download-text strong {
                    font-size: 16px;
                }

                .av-download-text span {
                    font-size: 11px;
                }

            }

            @media (max-width:768px) {

                .av-download-btn {
                    justify-content: center;
                }

                .av-download-text {
                    align-items: center;
                    text-align: center;
                }

            }

            /* Windows Taskbar එක Fullscreen වලදී මතුවීම වැළැක්වීම */
            .art-video-player.art-fullscreen {
                z-index: 2147483647 !important;
            }

            .art-video-player:-webkit-full-screen {
                width: 100vw !important;
                height: 100vh !important;
            }

            .art-video-player:fullscreen {
                width: 100vw !important;
                height: 100vh !important;
            }

            /* Subtitles Native Auto-Lift Restored */
            .art-video-player .art-subtitle {
                margin-bottom: 14px !important;
                transition: bottom 0.2s ease, transform 0.2s ease !important;
            }

            @media (max-width: 768px) {
                .art-video-player .art-subtitle {
                    margin-bottom: 4px !important;
                    line-height: 1.25 !important;
                    padding: 0 8px !important;
                }
            }

            @media (max-width: 480px) {
                .art-video-player .art-subtitle {
                    margin-bottom: 2px !important;
                }
            }

            @media (max-width: 768px) and (orientation: portrait) {
                .art-video-player:not(.art-fullscreen):not(.art-fullscreen-web) .art-subtitle {
                    --av-portrait-subtitle-size: 12px;
                }
            }

            /* Hide ALL Artplayer hover tooltip bubbles */
            .art-video-player [class*="hint--"]::before,
            .art-video-player [class*="hint--"]::after {
                display: none !important;
                content: none !important;
                opacity: 0 !important;
                visibility: hidden !important;
            }
        </style>

        <div class="av-single">

            <section
                id="playerArea"
                class="av-player-section <?php echo $is_series ? 'is-series' : ''; ?>">

                <div
                    id="videoWrapper"
                    class="av-player-container">

                    <div
                        id="player-ad-layer"
                        <?php echo $is_series ? 'style="display:none;"' : ''; ?>>
                        <div class="av-play-pulse"></div>

                        <div class="av-play-button">
                            <i class="fa fa-play" aria-hidden="true"></i>
                        </div>
                    </div>

                    <div id="playerMount" class="av-player-mount">
                        <?php if (!$is_series) : ?>
                            <?php if ($is_movie_mp4) : ?>
                                <div id="artplayer" style="width:100%;height:100%;"></div>
                            <?php else : ?>
                                <iframe id="mainPlayer" src="<?php echo esc_url($movie_url); ?>" allowfullscreen></iframe>
                            <?php endif; ?>
                        <?php else : ?>
                            <div id="artplayer" style="width:100%;height:100%;"></div>
                            <iframe id="mainPlayer" style="display:none;width:100%;height:100%;border:0;" allowfullscreen></iframe>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="av-player-notice">
                    Click <strong>Play</strong> to start watching. If <strong>redirect </strong> to advertice, close it and return to <strong>AnimeVerze</strong>
                </div>

                <?php if (!$is_series && $movie_download_page_url) : ?>

                    <div class="av-player-downloads">

                        <a
                            href="<?php echo esc_url($movie_download_page_url); ?>"
                            class="av-download-btn"
                            target="_self">
                            <div class="av-download-icon">
                                <i class="fa fa-arrow-down"></i>
                            </div>

                            <div class="av-download-text">
                                <strong>Direct &amp; Telegram Download Links</strong>
                                <span>View download options</span>
                            </div>
                        </a>
                    </div>

                <?php elseif ($is_series) : ?>

                    <div id="seriesDownloads" class="av-player-downloads series-downloads">

                        <a
                            id="seriesDownloadLinks"
                            href="#"
                            class="av-download-btn"
                            target="_self"
                            style="display:none;">
                            <div class="av-download-icon">
                                <i class="fa fa-arrow-down"></i>
                            </div>

                            <div class="av-download-text">
                                <strong>Direct &amp; Telegram Download Links</strong>
                                <span id="seriesDownloadText">Download selected episode</span>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

            </section>
            <main class="av-main-content">
                <section class="av-details">
                    <div class="av-poster">
                        <?php if (has_post_thumbnail()) : ?>

                            <?php
                            the_post_thumbnail(
                                'medium',
                                array(
                                    'alt'      => esc_attr(get_the_title()),
                                    'decoding' => 'async',
                                    'sizes'    => '(max-width: 420px) 120px, (max-width: 768px) 135px, 210px',
                                )
                            );
                            ?>
                        <?php else : ?>
                            <img
                                src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/no-poster.jpg'); ?>"
                                alt="<?php echo esc_attr(get_the_title()); ?>">
                        <?php endif; ?>
                    </div>

                    <div class="av-info">
                        <span class="av-type">
                            <?php echo esc_html($type_label); ?>
                        </span>

                        <h1 class="av-title">
                            <?php the_title(); ?>
                        </h1>

                        <div class="av-badges">

                            <?php if ($rating) : ?>
                                <span class="av-badge rating">
                                    <i class="fa fa-star"></i>
                                    <?php echo esc_html($rating); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($year) : ?>
                                <span class="av-badge">
                                    <?php echo esc_html($year); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($quality) : ?>
                                <span class="av-badge quality">
                                    <?php echo esc_html($quality); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <ul class="av-details-list">

                            <?php if ($director) : ?>
                                <li>
                                    <b>Director:</b>
                                    <?php echo esc_html($director); ?>
                                </li>
                            <?php endif; ?>

                            <?php if ($year) : ?>
                                <li>
                                    <b>Release Year:</b>
                                    <?php echo esc_html($year); ?>
                                </li>
                            <?php endif; ?>

                            <?php if ($rating) : ?>
                                <li>
                                    <b>Rating:</b>
                                    <?php echo esc_html($rating); ?>
                                </li>
                            <?php endif; ?>

                            <?php if ($quality) : ?>
                                <li>
                                    <b>Quality:</b>
                                    <?php echo esc_html($quality); ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </section>


                <h2 class="av-section-title">Description</h2>

                <div class="av-description">
                    <?php the_content(); ?>
                </div>

                <?php if (is_array($cast_list) && !empty($cast_list)) : ?>
                    <h2 class="av-section-title">Cast</h2>
                    <div class="av-cast">
                        <?php foreach ($cast_list as $actor) : ?>

                            <?php
                            $actor_image = !empty($actor['img'])
                                ? $actor['img']
                                : get_template_directory_uri() . '/assets/img/no-poster.jpg';
                            ?>

                            <div class="av-cast-card">

                                <img
                                    src="<?php echo esc_url(str_replace('/t/p/original/', '/t/p/w185/', $actor_image)); ?>"
                                    alt="<?php echo esc_attr($actor['name'] ?? ''); ?>"
                                    loading="lazy"
                                    decoding="async">

                                <span class="av-cast-name">
                                    <?php echo esc_html($actor['name'] ?? ''); ?>
                                </span>

                                <?php if (!empty($actor['character'])) : ?>

                                    <span class="av-cast-char">
                                        <?php echo esc_html($actor['character']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


                <div class="av-info-grid">
                    <div class="av-info-card">
                        <div class="av-info-label">Quality</div>

                        <div class="av-info-value">
                            <?php echo $quality ? esc_html($quality) : 'N/A'; ?>
                        </div>
                    </div>

                    <div class="av-info-card">
                        <div class="av-info-label">Year</div>

                        <div class="av-info-value">
                            <?php echo $year ? esc_html($year) : 'N/A'; ?>
                        </div>
                    </div>

                    <div class="av-info-card">
                        <div class="av-info-label">Rating</div>

                        <div class="av-info-value rating">
                            <?php echo $rating ? '★ ' . esc_html($rating) : 'N/A'; ?>
                        </div>
                    </div>
                </div>

                <?php if ($is_series && !empty($anime_seasons)) : ?>
                    <section id="episode-section">
                        <h2 class="av-section-title">Episodes</h2>

                        <?php if ($season_count > 1) : ?>

                            <!-- =====================================================
                    MULTIPLE SEASONS
                ====================================================== -->

                            <div class="av-seasons-accordion">
                                <?php foreach ($anime_seasons as $season_index => $season) : ?>
                                    <?php
                                    $season_number = $season_index + 1;
                                    $season_episodes =
                                        isset($season['episodes']) &&
                                        is_array($season['episodes'])
                                        ? $season['episodes']
                                        : array();

                                    if (empty($season_episodes)) {
                                        continue;
                                    }

                                    ?>

                                    <div class="av-season-group">
                                        <button
                                            type="button"
                                            class="av-season-toggle"
                                            aria-expanded="false"
                                            onclick="animeverzeToggleSeason(this)">

                                            <span class="av-season-toggle-text">
                                                Season <?php echo esc_html($season_number); ?>
                                                <span class="av-season-count">
                                                    <?php echo esc_html(count($season_episodes)); ?>
                                                    Episodes
                                                </span>
                                            </span>

                                            <span class="av-season-arrow">
                                                <i class="fa fa-chevron-down"></i>
                                            </span>
                                        </button>

                                        <div class="av-season-panel">
                                            <div class="av-episode-list">
                                                <?php foreach ($season_episodes as $episode_index => $episode) : ?>

                                                    <?php

                                                    $episode_number = $episode_index + 1;

                                                    $episode_watch = isset($episode['watch'])
                                                        ? trim($episode['watch'])
                                                        : '';

                                                    $episode_direct = isset($episode['direct'])
                                                        ? trim($episode['direct'])
                                                        : '';

                                                    $episode_telegram = isset($episode['telegram'])
                                                        ? trim($episode['telegram'])
                                                        : '';

                                                    $episode_subtitle_tracks =
                                                        animeverze_prepare_subtitle_tracks(
                                                            isset($episode['subtitles'])
                                                                ? $episode['subtitles']
                                                                : array()
                                                        );

                                                    $episode_label =
                                                        'Season ' .
                                                        $season_number .
                                                        ' Episode ' .
                                                        $episode_number;


                                                    $episode_download_page_url =
                                                        animeverze_download_page_url(
                                                            $landing_page,
                                                            $episode_direct,
                                                            $episode_telegram,
                                                            $anime_name . ' ' . $episode_label,
                                                            $episode_subtitle_tracks
                                                        );

                                                    ?>

                                                    <div
                                                        class="av-episode"
                                                        data-watch="<?php echo esc_url($episode_watch); ?>"
                                                        data-download="<?php echo esc_url($episode_download_page_url); ?>"
                                                        data-subtitles="<?php echo esc_attr(wp_json_encode($episode_subtitle_tracks)); ?>"
                                                        data-episode="<?php echo esc_attr($episode_number); ?>"
                                                        data-label="<?php echo esc_attr($episode_label); ?>"
                                                        onclick="animeverzePlayEpisode(this)">

                                                        <div class="av-episode-number">
                                                            <?php echo esc_html(sprintf('%02d', $episode_number)); ?>
                                                        </div>

                                                        <div class="av-episode-title">
                                                            Episode <?php echo esc_html(sprintf('%02d', $episode_number)); ?>
                                                        </div>

                                                        <div class="av-episode-play">
                                                            <i class="fa fa-play"></i>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>


                        <?php else : ?>

                            <!-- =====================================================
                    ONLY ONE SEASON
                    Season 1 label එක frontend එකේ පෙන්නන්නේ නැහැ
                ====================================================== -->

                            <?php

                            $season_episodes =
                                isset($anime_seasons[0]['episodes']) &&
                                is_array($anime_seasons[0]['episodes'])
                                ? $anime_seasons[0]['episodes']
                                : array();

                            ?>

                            <div class="av-episode-list">

                                <?php foreach ($season_episodes as $episode_index => $episode) : ?>

                                    <?php

                                    $episode_number = $episode_index + 1;

                                    $episode_watch = isset($episode['watch'])
                                        ? trim($episode['watch'])
                                        : '';

                                    $episode_direct = isset($episode['direct'])
                                        ? trim($episode['direct'])
                                        : '';

                                    $episode_telegram = isset($episode['telegram'])
                                        ? trim($episode['telegram'])
                                        : '';

                                    $episode_subtitle_tracks =
                                        animeverze_prepare_subtitle_tracks(
                                            isset($episode['subtitles'])
                                                ? $episode['subtitles']
                                                : array()
                                        );

                                    $episode_download_page_url =
                                        animeverze_download_page_url(
                                            $landing_page,
                                            $episode_direct,
                                            $episode_telegram,
                                            $anime_name . ' Episode ' . $episode_number,
                                            $episode_subtitle_tracks
                                        );
                                    ?>

                                    <div
                                        class="av-episode"
                                        data-watch="<?php echo esc_url($episode_watch); ?>"
                                        data-download="<?php echo esc_url($episode_download_page_url); ?>"
                                        data-subtitles="<?php echo esc_attr(wp_json_encode($episode_subtitle_tracks)); ?>"
                                        data-episode="<?php echo esc_attr($episode_number); ?>"
                                        data-label="<?php echo esc_attr('Episode ' . $episode_number); ?>"
                                        onclick="animeverzePlayEpisode(this)">
                                        <div class="av-episode-number">
                                            <?php echo esc_html(sprintf('%02d', $episode_number)); ?>
                                        </div>

                                        <div class="av-episode-title">
                                            Episode <?php echo esc_html(sprintf('%02d', $episode_number)); ?>
                                        </div>
                                        <div class="av-episode-play">
                                            <i class="fa fa-play"></i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>


                <?php if (is_array($screenshots) && !empty($screenshots)) : ?>

                    <h2 class="av-section-title">Screenshots</h2>

                    <div class="av-screenshot-grid">

                        <?php foreach ($screenshots as $image_url) : ?>

                            <div class="av-screenshot-item">

                                <img
                                    src="<?php echo esc_url(str_replace('/t/p/original/', '/t/p/w780/', $image_url)); ?>"
                                    alt="<?php echo esc_attr(get_the_title() . ' Screenshot'); ?>"
                                    loading="lazy"
                                    decoding="async">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <?php comments_template(); ?>
                <?php endif; ?>

            </main>
        </div>

        <script>
            let animeverzeArtPlayer = null;
            let animeverzeExternalAudioCleanup = null;
            let animeverzeExternalAudioKick = null;

            function animeverzeInitArtplayer(targetSelector, videoUrl, subtitleTracks) {
                if (typeof Artplayer === 'undefined') return;

                if (animeverzeExternalAudioCleanup) {
                    animeverzeExternalAudioCleanup();
                    animeverzeExternalAudioCleanup = null;
                    animeverzeExternalAudioKick = null;
                }

                if (animeverzeArtPlayer && animeverzeArtPlayer.destroy) {
                    animeverzeArtPlayer.destroy(false);
                    animeverzeArtPlayer = null;
                }

                let defaultSub = '';
                let defaultSubName = '';
                let subSelector = [];

                if (Array.isArray(subtitleTracks) && subtitleTracks.length > 0) {
                    defaultSub = subtitleTracks[0].url || '';
                    defaultSubName = subtitleTracks[0].label || 'Sinhala';

                    subSelector = subtitleTracks.map((sub, index) => ({
                        default: index === 0,
                        html: sub.label || (sub.lang === 'si' ? 'Sinhala' : (sub.lang === 'en' ? 'English' : sub.lang.toUpperCase())),
                        url: sub.url
                    }));

                    subSelector.push({
                        html: 'Off',
                        url: ''
                    });
                }

                // Subtitle switch function
                function switchSubtitle(item) {
                    if (item.url) {
                        animeverzeArtPlayer.subtitle.show = true;
                        animeverzeArtPlayer.subtitle.url = item.url;
                        if (typeof animeverzeArtPlayer.subtitle.switch === 'function') {
                            animeverzeArtPlayer.subtitle.switch(item.url, {
                                name: item.html,
                                type: 'vtt',
                                escape: false
                            });
                        }
                    } else {
                        animeverzeArtPlayer.subtitle.show = false;
                    }
                    return item.html;
                }

                let playerSettings = [];

                if (subSelector.length > 0) {
                    // 1. Subtitles තේරීමේ මෙනුව
                    playerSettings.push({
                        html: 'Subtitles',
                        width: 250,
                        tooltip: subSelector[0].html,
                        selector: subSelector,
                        onSelect: function(item) {
                            return switchSubtitle(item);
                        }
                    });

                    // Screen size අනුව Subtitle Size එක auto-detect කරගැනීම
                    const isMobileScreen = window.innerWidth <= 768;
                    const isTabletScreen = window.innerWidth > 768 && window.innerWidth <= 1200;
                    const isLargeScreen = window.innerWidth >= 1920;

                    const defaultDesktopSize =
                        isTabletScreen ? '20px' :
                        (isLargeScreen ? '32px' : '26px');

                    // 2. Subtitle Size - Mobile & Desktop Sizes
                    playerSettings.push({
                        html: 'Subtitle Size',
                        width: 230,
                        tooltip: isMobileScreen
                            ? 'Auto (Portrait / Fullscreen)'
                            : (
                                isTabletScreen
                                    ? 'Small (20px)'
                                    : (isLargeScreen ? 'Large (32px)' : 'Medium (26px)')
                            ),
                        selector: isMobileScreen ? [{
                                default: true,
                                html: 'Auto (Portrait / Fullscreen)',
                                value: 'var(--av-portrait-subtitle-size, 16px)'
                            },
                            {
                                html: 'Extra Small (12px)',
                                value: '12px'
                            },
                            {
                                html: 'Small (14px)',
                                value: '14px'
                            },
                            {
                                html: 'Normal (16px)',
                                value: '16px'
                            },
                            {
                                html: 'Large (18px)',
                                value: '18px'
                            },
                            {
                                html: 'Extra Large (20px)',
                                value: '20px'
                            }
                        ] : [{
                                default: isTabletScreen,
                                html: 'Small (20px)',
                                value: '20px'
                            },
                            {
                                default: !isTabletScreen && !isLargeScreen,
                                html: 'Medium (26px)',
                                value: '26px'
                            },
                            {
                                default: isLargeScreen,
                                html: 'Large (32px)',
                                value: '32px'
                            },
                            {
                                html: 'Extra Large (38px)',
                                value: '38px'
                            }
                        ],
                        onSelect: function(item) {
                            animeverzeArtPlayer.subtitle.style({
                                fontSize: item.value
                            });
                            return item.html;
                        }
                    });
                }

                const isMobileScreen = window.innerWidth <= 768;
                const isTabletScreen = window.innerWidth > 768 && window.innerWidth <= 1200;
                const isLargeScreen = window.innerWidth >= 1920;

                const defaultDesktopSize =
                    isTabletScreen ? '20px' :
                    (isLargeScreen ? '32px' : '26px');

                const isPhoneDevice =
                    (navigator.userAgentData && navigator.userAgentData.mobile === true) ||
                    /iPhone|iPod|Android.*Mobile|Windows Phone/i.test(navigator.userAgent);

                animeverzeArtPlayer = new Artplayer({
                    container: targetSelector,
                    url: videoUrl,
                    type: 'mp4',
                    theme: '#38BDF8',
                    volume: 0.8,
                    autoplay: false,

                    moreVideoAttr: {
                        crossOrigin: 'anonymous',
                        playsInline: true,
                        preload: 'metadata'
                    },

                    pip: !isPhoneDevice && Boolean(document.pictureInPictureEnabled),
                    autoMini: false,
                    screenshot: false,
                    setting: true,
                    loop: false,
                    flip: false,
                    playbackRate: true,
                    aspectRatio: false,
                    fullscreen: true,
                    fullscreenWeb: false,
                    miniProgressBar: true,
                    subtitleOffset: true,
                    subtitle: defaultSub ? {
                        url: defaultSub,
                        name: defaultSubName,
                        type: 'vtt',

                        // Allow VTT formatting tags such as <i>
                        escape: false,

                        style: {
                            color: '#FFFFFF',
                            fontSize: 'var(--av-portrait-subtitle-size, ' + (isMobileScreen ? '16px' : defaultDesktopSize) + ')',
                            textShadow: '0 2px 4px rgba(0,0,0,0.95), 0 0 2px #000',
                        },

                        encoding: 'utf-8',
                    } : {},
                    // Control Bar shortcuts
                    controls: [

                        // Subtitle CC shortcut
                        ...(subSelector.length > 0 ? [{
                            name: 'subtitle-shortcut',
                            index: 20,
                            position: 'right',

                            html:
                                '<span style="' +
                                'font-weight:bold;' +
                                'font-size:13px;' +
                                'cursor:pointer;' +
                                'padding:0 6px;' +
                                '">CC</span>',

                            tooltip: 'Subtitles',

                            selector: subSelector,

                            onSelect: function(item) {
                                return switchSubtitle(item);
                            }
                        }] : []),

                        // Audio shortcut
                        // මුලින් disabled/gray.
                        // Dual-audio metadata හම්බුණාම පහළ code එකෙන් enable කරනවා.
                        {
                            name: 'audio-track-shortcut',
                            index: 21,
                            position: 'right',

                            html:
                                '<span style="' +
                                'display:flex;' +
                                'align-items:center;' +
                                'justify-content:center;' +
                                'font-size:17px;' +
                                'padding:0 5px;' +
                                '">' +
                                '<i class="fa fa-language" aria-hidden="true"></i>' +
                                '</span>',

                            tooltip: 'Single Audio',

                            style: {
                                opacity: '0.35',
                                cursor: 'not-allowed',
                                pointerEvents: 'none'
                            }
                        }
                    ],
                    // Settings මෙනුව
                    settings: playerSettings
                });

                // =====================================================
                // External Dual Audio System
                // =====================================================
                animeverzeArtPlayer.on('ready', async function() {

                    const art = animeverzeArtPlayer;
                    const videoEl = art.video;

                    if (!videoEl) {
                        return;
                    }

                    function addSingleAudioSetting() {
                        try {
                            art.setting.add({
                                html: '<span style="opacity:.35;cursor:not-allowed;">Audio Track</span>',
                                tooltip: 'Single Audio'
                            });
                        } catch (error) {}
                    }

                    let absoluteVideoUrl;

                    try {
                        absoluteVideoUrl = new URL(
                            videoUrl,
                            window.location.href
                        );
                    } catch (error) {
                        addSingleAudioSetting();
                        return;
                    }

                    const metadataUrl = new URL(
                        'audio_tracks.json',
                        absoluteVideoUrl
                    ).href;

                    let audioData;

                    try {
                        const response = await fetch(
                            metadataUrl,
                            {
                                cache: 'no-store'
                            }
                        );

                        if (!response.ok) {
                            addSingleAudioSetting();
                            return;
                        }

                        audioData = await response.json();

                    } catch (error) {
                        addSingleAudioSetting();
                        return;
                    }

                    if (art !== animeverzeArtPlayer) return;

                    // Dual audio metadata නැත්නම් normal MP4 audio use කරනවා
                    if (
                        !audioData ||
                        audioData.dual_audio !== true ||
                        !Array.isArray(audioData.tracks) ||
                        audioData.tracks.length < 2
                    ) {
                        addSingleAudioSetting();
                        return;
                    }

                    // =====================================================
                    // External Audio Objects
                    // =====================================================

                    const tracks = audioData.tracks.map(
                        function(track, index) {

                            const audioUrl = new URL(
                                track.file,
                                absoluteVideoUrl
                            ).href;

                            const audio = new Audio();

                            audio.crossOrigin = 'anonymous';
                            audio.src = audioUrl;

                            return {
                                index: index,

                                name:
                                    track.name ||
                                    track.language ||
                                    ('Audio ' + (index + 1)),

                                language:
                                    String(
                                        track.language || ''
                                    ).toLowerCase(),

                                startTime:
                                    Number(
                                        track.original_start_time || 0
                                    ),

                                audio: audio,

                                pendingSync: false
                            };
                        }
                    );

                    // =====================================================
                    // English Default
                    // =====================================================

                    let activeIndex = tracks.findIndex(
                        function(track) {

                            return (
                                track.language === 'eng' ||
                                track.language === 'en' ||
                                track.name.toLowerCase().includes('english')
                            );
                        }
                    );

                    if (activeIndex < 0) {
                        activeIndex = 0;
                    }

                    tracks.forEach(
                        function(track, index) {

                            track.audio.preload =
                                index === activeIndex
                                    ? 'auto'
                                    : 'metadata';

                            track.audio.load();
                        }
                    );

                    // =====================================================
                    // Silence Original Embedded MP4 Audio
                    //
                    // WebAudio එකෙන් original audio output = 0 කරනවා.
                    // Artplayer volume / mute UI normal විදිහට තියාගන්නවා.
                    // =====================================================

                    let audioContext = null;
                    let sourceNode = null;
                    let gainNode = null;
                    let hardMuteFallback = false;

                    try {

                        const AudioContextClass =
                            window.AudioContext ||
                            window.webkitAudioContext;

                        if (!AudioContextClass) {
                            throw new Error(
                                'WebAudio not supported'
                            );
                        }

                        audioContext =
                            new AudioContextClass();

                        sourceNode =
                            audioContext.createMediaElementSource(
                                videoEl
                            );

                        gainNode =
                            audioContext.createGain();

                        gainNode.gain.value = 0;

                        sourceNode.connect(
                            gainNode
                        );

                        gainNode.connect(
                            audioContext.destination
                        );

                    } catch (error) {

                        // Browser WebAudio route support නැත්නම්
                        // old safe method එක use කරනවා.
                        hardMuteFallback = true;
                        videoEl.muted = true;
                    }

                    function pauseAllExternalAudio() {

                        tracks.forEach(
                            function(track) {
                                track.audio.pause();
                            }
                        );
                    }

                    function syncVolume() {

                        const volume = Math.max(
                            0,
                            Math.min(
                                1,
                                videoEl.volume
                            )
                        );

                        tracks.forEach(
                            function(track) {

                                track.audio.volume =
                                    volume;

                                if (!hardMuteFallback) {
                                    track.audio.muted =
                                        videoEl.muted;
                                } else {
                                    track.audio.muted =
                                        false;
                                }
                            }
                        );
                    }

                    function getTrackStart(track) {
                        return Math.max(
                            0,
                            Number(track.startTime) || 0
                        );
                    }

                    function getTargetTime(track) {
                        return Math.max(
                            0,
                            videoEl.currentTime,
                            getTrackStart(track)
                        );
                    }

                    // One controller manages video and external audio.
                    let wantPlay = !videoEl.paused;
                    let resumeAfterSeek = false;
                    let phase = 'held';
                    let disposed = false;
                    let generation = 0;
                    let internalVideoPauses = 0;
                    let internalVideoPlays = 0;
                    let lastDriftRecoveryTime = -Infinity;
                    let internalAudioPauses = new WeakMap();
                    let failed = false;
                    let syncTimer = null;
                    const mediaListeners = [];

                    window.animeverzeSyncState = function() {
                        function inspect(media) {
                            const ranges = [];

                            for (let i = 0; i < media.buffered.length; i++) {
                                ranges.push([
                                    media.buffered.start(i),
                                    media.buffered.end(i)
                                ]);
                            }

                            return {
                                time: media.currentTime,
                                paused: media.paused,
                                seeking: media.seeking,
                                readyState: media.readyState,
                                networkState: media.networkState,
                                buffered: ranges,
                                error: media.error ? {
                                    code: media.error.code,
                                    message: media.error.message
                                } : null
                            };
                        }

                        return JSON.stringify({
                            wantPlay: wantPlay,
                            phase: phase,
                            failed: failed,
                            internalVideoPauses: internalVideoPauses,
                            internalVideoPlays: internalVideoPlays,
                            track: tracks[activeIndex].name,
                            audioContext: audioContext ? audioContext.state : null,
                            video: inspect(videoEl),
                            audio: inspect(activeAudio())
                        }, null, 2);
                    };

                    videoEl.loop = false;

                    tracks.forEach(function(track) {
                        track.audio.loop = false;
                        track.audio.preservesPitch = true;
                    });

                    function activeAudio() {
                        return tracks[activeIndex].audio;
                    }

                    function notice(message) {
                        if (art.notice) art.notice.show = message;
                    }

                    function pauseVideoInternally() {
                        if (!videoEl.paused) {
                            internalVideoPauses++;
                            videoEl.pause();
                        }
                    }

                    function pauseAudioInternally(audio) {
                        if (!audio.paused) {
                            internalAudioPauses.set(
                                audio,
                                (internalAudioPauses.get(audio) || 0) + 1
                            );
                            audio.pause();
                        }
                    }

                    function setSyncLoading(show) {
                        try {
                            art.loading.show = !!show;
                        } catch (error) {}
                    }

                    function holdPair(pauseVideo) {
                        generation++;
                        phase = 'held';

                        // Playback අවශ්‍ය වෙලා audio/video sync වෙන්න wait කරනවා නම්
                        // normal pause එකක් වගේ නොපෙන්වා loading spinner එක පෙන්වන්න.
                        setSyncLoading(wantPlay);

                        if (pauseVideo !== false) {
                            pauseVideoInternally();
                        }

                        tracks.forEach(function(track) {
                            pauseAudioInternally(track.audio);
                        });

                        activeAudio().playbackRate = videoEl.playbackRate;
                    }

                    function readyAt(media, minimum) {
                        if (media.error || media.seeking || media.readyState < 3) {
                            return false;
                        }

                        // External audio සඳහා actual buffered data check කරනවා.
                        const time = media.currentTime;
                        const remaining = Number.isFinite(media.duration)
                            ? media.duration - time
                            : Infinity;

                        const needed = Math.min(
                            minimum,
                            Math.max(0, remaining - 0.04)
                        );

                        for (let i = 0; i < media.buffered.length; i++) {
                            if (
                                media.buffered.start(i) <= time + 0.04 &&
                                media.buffered.end(i) - time >= needed
                            ) {
                                return true;
                            }
                        }

                        return false;
                    }

                    function stopWithError(message) {
                        wantPlay = false;
                        failed = true;
                        holdPair(false);
                        resumePair();
                    }

                    function alignHeldAudio() {
                        const audio = activeAudio();

                        if (
                            videoEl.seeking ||
                            audio.seeking ||
                            audio.readyState < 1
                        ) {
                            return false;
                        }

                        const target = getTargetTime(tracks[activeIndex]);

                        if (
                            Number.isFinite(audio.duration) &&
                            target >= audio.duration - 0.04
                        ) {
                            stopWithError(
                                'The selected audio track has ended. Select another track.'
                            );
                            return false;
                        }

                        // Seek only when alignment is actually needed.
                        if (Math.abs(audio.currentTime - target) > 0.08) {
                            try {
                                audio.currentTime = target;
                            } catch (error) {
                                stopWithError(
                                    'Unable to seek the selected audio track.'
                                );
                            }

                            // Wait for seek completion and buffered data.
                            return false;
                        }

                        return true;
                    }

                    function resumePair() {
                        if (
                            disposed ||
                            failed ||
                            !wantPlay ||
                            videoEl.ended ||
                            phase === 'starting' ||
                            phase === 'running' ||
                            phase === 'lead-in'
                        ) {
                            return;
                        }

                        if (
                            videoEl.seeking ||
                            !alignHeldAudio() ||
                            !readyAt(activeAudio(), 0.1)
                        ) {
                            return;
                        }

                        const audio = activeAudio();
                        const token = ++generation;

                        const leading = videoEl.currentTime <
                            getTrackStart(tracks[activeIndex]);

                        phase = leading ? 'lead-in' : 'starting';

                        audio.playbackRate = videoEl.playbackRate;
                        syncVolume();

                        if (audioContext && audioContext.state === 'suspended') {
                            audioContext.resume().catch(function() {});
                        }

                        const wasVideoPaused = videoEl.paused;

                        if (wasVideoPaused) {
                            internalVideoPlays++;
                        }

                        const videoPromise = videoEl.play();

                        if (wasVideoPaused) {
                            videoPromise.catch(function() {
                                if (internalVideoPlays > 0) {
                                    internalVideoPlays--;
                                }
                            });
                        }

                        if (leading) {
                            videoPromise.catch(function(error) {
                                if (disposed || token !== generation) return;

                                stopWithError(
                                    'Unable to start video. Press Play to retry.'
                                );
                            });

                            setSyncLoading(false);
                            return;
                        }

                        Promise.all([videoPromise, audio.play()])
                            .then(function() {
                                if (
                                    disposed ||
                                    token !== generation ||
                                    audio !== activeAudio()
                                ) {
                                    return;
                                }

                                if (
                                    !wantPlay ||
                                    videoEl.paused ||
                                    audio.paused ||
                                    !readyAt(audio, 0.1)
                                ) {
                                    holdPair();
                                    return;
                                }

                                phase = 'running';
                                setSyncLoading(false);
                            })
                            .catch(function(error) {
                                if (disposed || token !== generation) return;

                                stopWithError(
                                    error.name === 'NotAllowedError'
                                        ? 'Playback was blocked. Press Play to start video and audio together.'
                                        : 'Unable to play the selected audio. Select another track or retry Play.'
                                );
                            });
                    }

                    function requestPlay() {
                        if (disposed) return;

                        failed = false;
                        lastDriftRecoveryTime = -Infinity;
                        wantPlay = true;

                        videoEl.preload = 'auto';
                        activeAudio().preload = 'auto';

                        if (audioContext && audioContext.state === 'suspended') {
                            audioContext.resume().catch(function() {});
                        }

                        if (
                            phase !== 'running' &&
                            phase !== 'starting' &&
                            phase !== 'lead-in'
                        ) {
                            holdPair();
                            resumePair();
                        }
                    }

                    function switchAudioTrack(index) {
                        index = Number(index);

                        if (!Number.isInteger(index) || !tracks[index]) {
                            return '';
                        }

                        holdPair();

                        lastDriftRecoveryTime = -Infinity;
                        activeIndex = index;
                        failed = false;

                        activeAudio().preload = 'auto';

                        try {
                            activeAudio().load();
                        } catch (error) {}

                        syncVolume();
                        alignHeldAudio();
                        resumePair();

                        return tracks[index].name;
                    }

                    function listen(media, event, handler) {
                        media.addEventListener(event, handler);
                        mediaListeners.push([media, event, handler]);
                    }

                    listen(videoEl, 'play', function() {
                        if (internalVideoPlays > 0) {
                            internalVideoPlays--;
                            return;
                        }

                        resumeAfterSeek = false;

                        requestPlay();
                    });

                    listen(videoEl, 'pause', function() {
                        if (internalVideoPauses > 0) {
                            internalVideoPauses--;
                            return;
                        }

                        // Seek එක නිසා browser / Artplayer pause කළා නම්
                        // user pause එකක් විදිහට treat කරන්න එපා.
                        if (resumeAfterSeek) {
                            return;
                        }

                        wantPlay = false;
                        holdPair();
                    });

                    listen(videoEl, 'seeking', function() {
                        lastDriftRecoveryTime = -Infinity;

                        resumeAfterSeek = wantPlay;

                        // Browser එකට video seek/buffer naturally කරන්න දෙන්න.
                        // External audio විතරක් hold කරනවා.
                        holdPair(false);
                    });

                    listen(videoEl, 'seeked', function() {
                        if (disposed) return;

                        const shouldResume = resumeAfterSeek;
                        resumeAfterSeek = false;

                        if (shouldResume) {
                            wantPlay = true;
                        }

                        alignHeldAudio();
                        resumePair();
                    });

                    listen(videoEl, 'waiting', function() {
                        // Video already buffering. Force-pause කරන්න අවශ්‍ය නැහැ.
                        // Audio විතරක් pause කරලා wait කරනවා.
                        holdPair(false);
                    });

                    listen(videoEl, 'ended', function() {
                        wantPlay = false;
                        holdPair();
                    });

                    listen(videoEl, 'error', function() {
                        stopWithError(
                            'Unable to load the video. Please reload the player.'
                        );
                    });

                    listen(videoEl, 'ratechange', function() {
                        activeAudio().playbackRate = videoEl.playbackRate;
                    });

                    listen(videoEl, 'volumechange', syncVolume);

                    tracks.forEach(function(track) {
                        const audio = track.audio;

                        listen(audio, 'waiting', function() {
                            if (audio === activeAudio()) holdPair();
                        });

                        listen(audio, 'seeking', function() {
                            if (audio === activeAudio() && phase !== 'held') {
                                holdPair();
                            }
                        });

                        listen(audio, 'pause', function() {
                            const count = internalAudioPauses.get(audio) || 0;

                            if (count > 0) {
                                internalAudioPauses.set(audio, count - 1);
                            } else if (
                                audio === activeAudio() &&
                                phase !== 'held'
                            ) {
                                wantPlay = false;
                                holdPair();
                            }
                        });

                        listen(audio, 'ended', function() {
                            if (audio !== activeAudio()) return;

                            wantPlay = false;
                            holdPair();

                            if (
                                !videoEl.ended &&
                                videoEl.duration - videoEl.currentTime > 0.5
                            ) {
                                notice(
                                    'The selected audio track ended before the video. Select another track.'
                                );
                            }
                        });

                        listen(audio, 'error', function() {
                            if (audio === activeAudio()) {
                                stopWithError(
                                    'Unable to load the selected audio track. Select another track.'
                                );
                            }
                        });

                        [
                            'loadedmetadata',
                            'loadeddata',
                            'canplay',
                            'progress',
                            'seeked'
                        ].forEach(function(event) {
                            listen(audio, event, function() {
                                if (audio === activeAudio()) resumePair();
                            });
                        });

                        listen(audio, 'stalled', function() {
                            if (
                                audio === activeAudio() &&
                                !readyAt(audio, 0.1)
                            ) {
                                holdPair();
                            }
                        });
                    });

                    ['loadeddata', 'canplay', 'progress'].forEach(function(event) {
                        listen(videoEl, event, resumePair);
                    });

                    listen(videoEl, 'stalled', function() {
                        if (!readyAt(videoEl, 0.1)) {
                            holdPair(false);
                        }
                    });

                    function checkPair() {
                        if (disposed || failed || !wantPlay) return;

                        const audio = activeAudio();

                        if (phase === 'held') {

                            // Sync කරන වෙලාවේ target timestamp එක fixed තියාගන්න.
                            // Video buffered-range check එකෙන් මේක block කරන්න එපා.
                            if (
                                !videoEl.paused &&
                                !videoEl.seeking
                            ) {
                                holdPair();
                            }

                            resumePair();
                            return;
                        }

                        if (videoEl.seeking) {
                            holdPair(false);
                            return;
                        }

                        if (
                            audio.seeking ||
                            !readyAt(audio, 0.1)
                        ) {
                            // Audio genuinely buffer වෙනවා නම් pair එක hold කරන්න.
                            holdPair();
                            return;
                        }

                        if (phase === 'lead-in') {
                            if (videoEl.paused) {
                                holdPair();
                                return;
                            }

                            const start = getTrackStart(tracks[activeIndex]);

                            if (videoEl.currentTime >= start) {
                                holdPair();
                                alignHeldAudio();
                                resumePair();
                            }

                            return;
                        }

                        if (phase !== 'running') return;

                        if (videoEl.paused || audio.paused) {
                            holdPair();
                            return;
                        }

                        const drift =
                            audio.currentTime - getTargetTime(tracks[activeIndex]);

                        if (Math.abs(drift) > 0.35) {
                            // Prevent repeated rewinds when the video clock is stuck.
                            if (
                                videoEl.currentTime <= lastDriftRecoveryTime + 0.1
                            ) {
                                stopWithError(
                                    'Video playback stalled. Press Play to retry.'
                                );
                                return;
                            }

                            lastDriftRecoveryTime = videoEl.currentTime;

                            holdPair();
                            alignHeldAudio();
                            return;
                        }

                        // Keep audio speed exactly the same as the video.
                        // Large drift is handled by the recovery block above.
                        audio.playbackRate = videoEl.playbackRate;
                    }

                    syncTimer = setInterval(checkPair, 100);

                    holdPair();
                    syncVolume();
                    resumePair();

                    // =====================================================
                    // Audio Track Settings Menu
                    // =====================================================

                    // =====================================================
                    // Audio Track - Settings + Bottom Shortcut
                    // =====================================================

                    function buildAudioSelector() {

                        return tracks.map(
                            function(track, index) {

                                return {
                                    default:
                                        index === activeIndex,

                                    html:
                                        track.name,

                                    value:
                                        index
                                };
                            }
                        );
                    }


                    function refreshAudioMenus() {

                        const selectedName =
                            tracks[activeIndex].name;

                        const freshSelector =
                            buildAudioSelector();


                        // Settings menu update
                        try {

                            art.setting.update({

                                name:
                                    'audio-track-setting',

                                html:
                                    'Audio Track',

                                width:
                                    250,

                                tooltip:
                                    selectedName,

                                selector:
                                    freshSelector,

                                onSelect:
                                    handleAudioSelect
                            });

                        } catch (error) {}


                        // Bottom headphones shortcut update
                        try {

                            art.controls.update({

                                name:
                                    'audio-track-shortcut',

                                index:
                                    21,

                                position:
                                    'right',

                                html:
                                    '<span style="' +
                                    'display:flex;' +
                                    'align-items:center;' +
                                    'justify-content:center;' +
                                    'gap:5px;' +
                                    'font-size:13px;' +
                                    'font-weight:600;' +
                                    'padding:0 5px;' +
                                    'white-space:nowrap;' +
                                    '">' +
                                    '<i class="fa fa-language" aria-hidden="true" style="font-size:16px;"></i>' +
                                    '</span>',

                                tooltip:
                                    selectedName,

                                selector:
                                    freshSelector,

                                onSelect:
                                    handleAudioSelect,

                                style: {
                                    opacity: '1',
                                    cursor: 'pointer',
                                    pointerEvents: 'auto'
                                }
                            });

                        } catch (error) {}
                    }


                    function handleAudioSelect(item) {

                        const selectedName =
                            switchAudioTrack(
                                item.value
                            );

                        // Settings + shortcut දෙකේම
                        // selected state එක sync කරගන්නවා.
                        setTimeout(
                            refreshAudioMenus,
                            0
                        );

                        return selectedName;
                    }


                    // Settings menu Audio Track
                    art.setting.add({

                        name:
                            'audio-track-setting',

                        html:
                            'Audio Track',

                        width:
                            250,

                        tooltip:
                            tracks[activeIndex].name,

                        selector:
                            buildAudioSelector(),

                        onSelect:
                            handleAudioSelect
                    });


                    // Bottom control Audio shortcut enable කරන්න
                    art.controls.update({

                        name:
                            'audio-track-shortcut',

                        index:
                            21,

                        position:
                            'right',

                        html:
                            '<span style="' +
                            'display:flex;' +
                            'align-items:center;' +
                            'justify-content:center;' +
                            'gap:5px;' +
                            'font-size:13px;' +
                            'font-weight:600;' +
                            'padding:0 5px;' +
                            'white-space:nowrap;' +
                            '">' +
                            '<i class="fa fa-language" aria-hidden="true" style="font-size:16px;"></i>' +
                            '</span>',

                        tooltip:
                            tracks[activeIndex].name,

                        selector:
                            buildAudioSelector(),

                        onSelect:
                            handleAudioSelect,

                        style: {
                            opacity: '1',
                            cursor: 'pointer',
                            pointerEvents: 'auto'
                        }
                    });

                    // The Play helper requests paired playback.
                    animeverzeExternalAudioKick = requestPlay;

                    animeverzeExternalAudioCleanup = function() {
                        disposed = true;
                        wantPlay = false;
                        generation++;

                        clearInterval(syncTimer);

                        mediaListeners.forEach(function(binding) {
                            binding[0].removeEventListener(
                                binding[1],
                                binding[2]
                            );
                        });

                        pauseAllExternalAudio();

                        tracks.forEach(function(track) {
                            track.audio.removeAttribute('src');
                            track.audio.load();
                        });

                        try {
                            if (sourceNode) sourceNode.disconnect();
                            if (gainNode) gainNode.disconnect();

                            if (audioContext) {
                                audioContext.close().catch(function() {});
                            }
                        } catch (error) {}

                        animeverzeExternalAudioKick = null;
                    };

                });

                return animeverzeArtPlayer;
            }



            document.addEventListener('DOMContentLoaded', function() {

                <?php if (!$is_series && $is_movie_mp4) : ?>
                    animeverzeInitArtplayer('#artplayer', '<?php echo esc_url($movie_url); ?>', <?php echo wp_json_encode($movie_subtitles); ?>);
                <?php endif; ?>


                const adLayer =
                    document.getElementById(
                        'player-ad-layer'
                    );


                if (!adLayer) {
                    return;
                }


                adLayer.addEventListener(
                    'click',
                    function() {

                        window.open(
                            'https://omg10.com/4/10807681',
                            '_blank'
                        );


                        adLayer.style.display =
                            'none';


                        if (animeverzeArtPlayer) {

                            const promise =
                                animeverzeArtPlayer.play();

                            if (
                                typeof animeverzeExternalAudioKick ===
                                'function'
                            ) {
                                animeverzeExternalAudioKick();
                            }

                            if (
                                promise &&
                                typeof promise.catch ===
                                    'function'
                            ) {
                                promise.catch(
                                    function() {}
                                );
                            }

                            return;
                        }


                        const nativePlayer =
                            document.querySelector(
                                '#playerMount video'
                            );


                        if (nativePlayer) {

                            const promise =
                                nativePlayer.play();


                            if (
                                promise &&
                                typeof promise.catch ===
                                'function'
                            ) {

                                promise.catch(
                                    function() {}
                                );
                            }
                        }
                    }
                );
            });


            function animeverzeToggleSeason(button) {

                if (!button) {
                    return;
                }

                const group =
                    button.closest('.av-season-group');

                if (!group) {
                    return;
                }


                const isOpen =
                    group.classList.contains('open');


                if (isOpen) {

                    group.classList.remove('open');

                    button.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                } else {

                    group.classList.add('open');

                    button.setAttribute(
                        'aria-expanded',
                        'true'
                    );

                }
            }

            function animeverzePlayEpisode(row) {

                if (!row) {
                    return;
                }


                const watchUrl =
                    row.getAttribute('data-watch');


                const downloadUrl =
                    row.getAttribute('data-download');


                const subtitleData =
                    row.getAttribute('data-subtitles') ||
                    '[]';


                const episode =
                    row.getAttribute('data-episode');


                const episodeLabel =
                    row.getAttribute('data-label') ||
                    ('Episode ' + episode);


                let subtitleTracks = [];


                try {

                    subtitleTracks =
                        JSON.parse(subtitleData);


                    if (!Array.isArray(subtitleTracks)) {
                        subtitleTracks = [];
                    }

                } catch (error) {

                    subtitleTracks = [];
                }


                if (!watchUrl) {
                    return;
                }


                const playerArea =
                    document.getElementById(
                        'playerArea'
                    );


                const playerMount =
                    document.getElementById(
                        'playerMount'
                    );


                const adLayer =
                    document.getElementById(
                        'player-ad-layer'
                    );


                const downloads =
                    document.getElementById(
                        'seriesDownloads'
                    );


                const downloadButton =
                    document.getElementById(
                        'seriesDownloadLinks'
                    );


                const downloadText =
                    document.getElementById(
                        'seriesDownloadText'
                    );


                if (
                    !playerArea ||
                    !playerMount
                ) {
                    return;
                }

                if (animeverzeExternalAudioCleanup) {
                    animeverzeExternalAudioCleanup();
                    animeverzeExternalAudioCleanup = null;
                    animeverzeExternalAudioKick = null;
                }

                if (animeverzeArtPlayer && animeverzeArtPlayer.destroy) {
                    animeverzeArtPlayer.destroy(false);
                    animeverzeArtPlayer = null;
                }

                const artContainer = document.getElementById('artplayer');
                const iframePlayer = document.getElementById('mainPlayer');
                const cleanUrl = watchUrl.toLowerCase();

                if (cleanUrl.includes('.mp4')) {
                    if (iframePlayer) iframePlayer.style.display = 'none';
                    if (artContainer) artContainer.style.display = 'block';
                    animeverzeInitArtplayer('#artplayer', watchUrl, subtitleTracks);
                } else {
                    if (artContainer) artContainer.style.display = 'none';
                    if (iframePlayer) {
                        iframePlayer.src = watchUrl;
                        iframePlayer.style.display = 'block';
                    }
                }


                playerArea.style.display =
                    'block';


                if (adLayer) {

                    adLayer.style.display =
                        'flex';
                }


                /*
                |--------------------------------------------------------------------------
                | Active Episode
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll('.av-episode')
                    .forEach(
                        function(item) {

                            item.classList.remove(
                                'active'
                            );
                        }
                    );


                row.classList.add(
                    'active'
                );


                /*
                |--------------------------------------------------------------------------
                | Download Button
                |--------------------------------------------------------------------------
                */

                if (
                    downloads &&
                    downloadButton
                ) {

                    if (downloadUrl) {

                        downloads.style.display =
                            'flex';


                        downloadButton.href =
                            downloadUrl;


                        downloadButton.style.display =
                            'flex';


                        if (downloadText) {

                            downloadText.textContent =
                                episodeLabel +
                                ' download options';
                        }

                    } else {

                        downloads.style.display =
                            'none';


                        downloadButton.style.display =
                            'none';


                        downloadButton.removeAttribute(
                            'href'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Scroll To Player
                |--------------------------------------------------------------------------
                */

                const headerOffset =
                    90;


                const playerTop =
                    playerArea
                    .getBoundingClientRect()
                    .top +
                    window.pageYOffset -
                    headerOffset;


                window.scrollTo({
                    top: playerTop,
                    behavior: 'smooth'
                });
            }
        </script>

<?php
    endwhile;
endif;

get_footer();
?>