<?php
/**
 * The template for displaying all single software items
 *
 * @author  Mohamed Nurdin Mgaza <codeoba@gmail.com>
 * @country Tanzania | +687001775
 * @version 1.7.0
 * @package Nursoft
 */

get_header(); ?>

<main class="container main_content">
    <?php
    while ( have_posts() ) :
        the_post();

        // Retrieve Custom Meta Fields
        $version         = get_post_meta( get_the_ID(), '_nursoft_version', true );
        $size            = get_post_meta( get_the_ID(), '_nursoft_size', true );
        $downloads       = get_post_meta( get_the_ID(), '_nursoft_downloads', true );
        $reputation      = get_post_meta( get_the_ID(), '_nursoft_reputation', true );
        $download_url    = get_post_meta( get_the_ID(), '_nursoft_download_url', true );
        $requirements    = get_post_meta( get_the_ID(), '_nursoft_requirements', true );
        $instructions    = get_post_meta( get_the_ID(), '_nursoft_instructions', true );
        $screenshots     = get_post_meta( get_the_ID(), '_nursoft_screenshots', true );
        $badge           = get_post_meta( get_the_ID(), '_nursoft_badge', true );
        $version_history = get_post_meta( get_the_ID(), '_nursoft_version_history', true );
        $versions_list   = get_post_meta( get_the_ID(), '_nursoft_versions_list', true );

        // Formatting
        $downloads_formatted = $downloads !== '' ? number_format( intval($downloads) ) : '0';
        $reputation_value    = $reputation !== '' ? floatval($reputation) : 4.5;
        $size_display        = $size !== '' ? esc_html($size) : 'N/A';
        $version_display     = $version !== '' ? esc_html($version) : '1.0.0';

        // Platform & Category Terms
        $platforms = wp_get_post_terms( get_the_ID(), 'platform' );
        $platform_name = ! empty( $platforms ) && ! is_wp_error( $platforms ) ? $platforms[0]->name : 'Windows';
        $platform_slug = ! empty( $platforms ) && ! is_wp_error( $platforms ) ? $platforms[0]->slug : 'ms-windows';

        $categories = wp_get_post_terms( get_the_ID(), 'software_cat' );
        ?>

        <div class="software_layout">
            <!-- 1. Left / Main Column -->
            <article class="software_main_col">

                <!-- ===== CONTENT CARD: Header, Gallery, Description, Requirements ===== -->
                <div class="soft_content_card">

                <?php 
                // 3. Single Page Top Ad Slot
                nursoft_render_ad( 'nursoft_ad_single_top' ); 
                ?>

                <!-- Software Logo & Title Header -->
                <header class="soft_ident_header">
                    <div class="soft_ident_icon_wrap" style="position: relative; flex-shrink: 0;">
                        <div class="soft_ident_icon">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'thumbnail', array( 'alt' => get_the_title() ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" alt="Software logo" />
                            <?php endif; ?>
                        </div>
                        <?php 
                        $badge_html = nursoft_render_card_badge( $badge );
                        if ( ! empty( $badge_html ) && $badge !== 'none' ) : ?>
                            <div class="thumbnail_badge_wrap">
                                <?php echo $badge_html; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="soft_ident_info">
                        <h1 class="soft_ident_title">
                            <?php the_title(); ?>
                            <?php if ( $version ) : ?>
                                <span class="soft_title_version"><?php echo esc_html($version); ?></span>
                            <?php endif; ?>
                        </h1>
                        
                        <div class="soft_ident_cat_list">
                            <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                                <?php foreach ( $categories as $cat ) : ?>
                                    <a class="soft_ident_cat" href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
                                        <?php echo esc_html( $cat->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php echo nursoft_render_card_badge( $badge ); ?>
                        </div>
                    </div>
                </header>

                <!-- Technical Specs Badges Grid -->
                <div class="soft_tech_grid">
                    <!-- Version -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Version', 'nursoft'); ?></div>
                        <div class="soft_tech_value"><?php echo $version_display; ?></div>
                    </div>
                    <!-- Size -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Size', 'nursoft'); ?></div>
                        <div class="soft_tech_value"><?php echo $size_display; ?></div>
                    </div>
                    <!-- Platform -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Platform', 'nursoft'); ?></div>
                        <div class="soft_tech_value" style="text-transform: capitalize; color: var(--accent-blue);"><?php echo esc_html($platform_name); ?></div>
                    </div>
                    <!-- Downloads -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Downloads', 'nursoft'); ?></div>
                        <div class="soft_tech_value" id="download-count-value"><?php echo $downloads_formatted; ?></div>
                    </div>
                    <!-- Reputation -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Reputation', 'nursoft'); ?></div>
                        <div class="soft_tech_value stars">
                            <?php echo nursoft_render_stars( $reputation_value ); ?>
                        </div>
                    </div>
                </div>

                <!-- Premium Slideshow Screenshot Gallery Slider & Lightbox -->
                <?php
                // Fetch screenshots from both Media IDs and External URLs
                $screenshots_ids = get_post_meta( get_the_ID(), '_nursoft_screenshots_ids', true );
                $screenshots_ext = get_post_meta( get_the_ID(), '_nursoft_screenshots_ext', true );
                
                // Backwards compatibility fallback
                $old_screenshots = get_post_meta( get_the_ID(), '_nursoft_screenshots', true );
                if ( empty($screenshots_ext) && ! empty($old_screenshots) && ! is_numeric($old_screenshots) ) {
                    $screenshots_ext = $old_screenshots;
                }

                $screenshot_urls = array();

                // 1. Resolve Media Library IDs
                if ( ! empty($screenshots_ids) ) {
                    $ids_array = explode(',', $screenshots_ids);
                    foreach ( $ids_array as $img_id ) {
                        $full_url = wp_get_attachment_image_url( $img_id, 'large' );
                        if ( $full_url ) {
                            $screenshot_urls[] = $full_url;
                        }
                    }
                }

                // 2. Resolve External URLs
                if ( ! empty($screenshots_ext) ) {
                    $ext_array = array_map('trim', explode(',', $screenshots_ext));
                    foreach ( $ext_array as $url ) {
                        if ( ! empty($url) ) {
                            $screenshot_urls[] = $url;
                        }
                    }
                }

                if ( ! empty( $screenshot_urls ) ) :
                ?>
                    <section class="soft_gallery_section">
                        <h5 class="soft_section_title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;color:var(--accent-blue);"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/></svg>
                            <span><?php _e('Screenshots', 'nursoft'); ?></span>
                        </h5>
                        
                        <!-- Slider UI Container -->
                        <div class="nursoft-slider-container">
                            <div class="nursoft-slider-wrapper" id="nursoft-slider-wrapper">
                                <?php foreach ( $screenshot_urls as $index => $img_url ) : ?>
                                    <div class="nursoft-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <img src="<?php echo esc_url($img_url); ?>" class="nursoft-slider-img" alt="<?php echo esc_attr(get_the_title()); ?> screenshot <?php echo $index + 1; ?>" data-index="<?php echo $index; ?>" />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if ( count($screenshot_urls) > 1 ) : ?>
                                <!-- Controls -->
                                <button class="nursoft-slider-btn prev" id="nursoft-prev-btn">&lt;</button>
                                <button class="nursoft-slider-btn next" id="nursoft-next-btn">&gt;</button>
                                
                                <!-- Indicator Dots -->
                                <div class="nursoft-slider-dots" id="nursoft-slider-dots">
                                    <?php foreach ( $screenshot_urls as $index => $img_url ) : ?>
                                        <span class="nursoft-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <!-- Fullscreen Lightbox Modal -->
                    <div class="nursoft-lightbox" id="nursoft-lightbox">
                        <span class="lightbox-close" id="lightbox-close">&times;</span>
                        <img class="lightbox-content" id="lightbox-img" src="" alt="Zoomed screenshot" />
                        <div class="lightbox-caption" id="lightbox-caption"></div>
                        
                        <?php if ( count($screenshot_urls) > 1 ) : ?>
                            <button class="lightbox-btn prev" id="lightbox-prev-btn">&lt;</button>
                            <button class="lightbox-btn next" id="lightbox-next-btn">&gt;</button>
                        <?php endif; ?>
                    </div>

                    <!-- Custom Styles for Slider & Lightbox -->
                    <style>
                        .nursoft-slider-container {
                            position: relative;
                            width: 100%;
                            height: 380px;
                            background-color: #050608;
                            border: 1px solid var(--border-color);
                            border-radius: 12px;
                            overflow: hidden;
                            margin-bottom: 30px;
                            box-shadow: var(--shadow-card);
                        }
                        .nursoft-slider-wrapper {
                            position: relative;
                            width: 100%;
                            height: 100%;
                        }
                        .nursoft-slide {
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            opacity: 0;
                            z-index: 1;
                            transition: opacity 0.5s ease-in-out;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            padding: 10px;
                        }
                        .nursoft-slide.active {
                            opacity: 1;
                            z-index: 2;
                        }
                        .nursoft-slider-img {
                            max-width: 100%;
                            max-height: 100%;
                            object-fit: contain;
                            border-radius: 6px;
                            cursor: zoom-in;
                            transition: transform var(--transition-fast);
                        }
                        .nursoft-slider-img:hover {
                            transform: scale(1.01);
                        }
                        .nursoft-slider-btn {
                            position: absolute;
                            top: 50%;
                            transform: translateY(-50%);
                            background-color: rgba(18, 20, 28, 0.7);
                            border: 1px solid var(--border-color);
                            backdrop-filter: blur(8px);
                            color: white;
                            font-size: 20px;
                            font-weight: bold;
                            width: 44px;
                            height: 44px;
                            border-radius: 50%;
                            cursor: pointer;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            z-index: 10;
                            transition: background-color var(--transition-fast), border-color var(--transition-fast), transform var(--transition-fast);
                        }
                        .nursoft-slider-btn:hover {
                            background-color: var(--accent-blue);
                            border-color: var(--accent-blue);
                            transform: translateY(-50%) scale(1.08);
                        }
                        .nursoft-slider-btn.prev { left: 20px; }
                        .nursoft-slider-btn.next { right: 20px; }
                        
                        .nursoft-slider-dots {
                            position: absolute;
                            bottom: 15px;
                            left: 50%;
                            transform: translateX(-50%);
                            display: flex;
                            gap: 8px;
                            z-index: 10;
                        }
                        .nursoft-dot {
                            width: 8px;
                            height: 8px;
                            border-radius: 50%;
                            background-color: rgba(255, 255, 255, 0.35);
                            cursor: pointer;
                            transition: background-color var(--transition-fast), transform var(--transition-fast);
                        }
                        .nursoft-dot.active {
                            background-color: var(--accent-blue);
                            transform: scale(1.25);
                            box-shadow: 0 0 8px var(--accent-blue);
                        }

                        /* Lightbox Zoom Styles */
                        .nursoft-lightbox {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100vw;
                            height: 100vh;
                            background-color: rgba(10, 11, 13, 0.95);
                            backdrop-filter: blur(15px);
                            z-index: 10000;
                            display: none;
                            align-items: center;
                            justify-content: center;
                            flex-direction: column;
                        }
                        .lightbox-close {
                            position: absolute;
                            top: 25px;
                            right: 35px;
                            color: var(--text-secondary);
                            font-size: 40px;
                            font-weight: bold;
                            cursor: pointer;
                            transition: color var(--transition-fast);
                        }
                        .lightbox-close:hover {
                            color: white;
                        }
                        .lightbox-content {
                            max-width: 85%;
                            max-height: 75%;
                            object-fit: contain;
                            border-radius: 8px;
                            border: 1px solid var(--border-color);
                            box-shadow: 0 10px 40px rgba(0,0,0,0.8);
                        }
                        .lightbox-caption {
                            color: var(--text-secondary);
                            font-size: 14px;
                            margin-top: 15px;
                            font-weight: 500;
                        }
                        .lightbox-btn {
                            position: absolute;
                            top: 50%;
                            transform: translateY(-50%);
                            background-color: rgba(255, 255, 255, 0.05);
                            border: 1px solid rgba(255, 255, 255, 0.1);
                            color: white;
                            width: 55px;
                            height: 55px;
                            border-radius: 50%;
                            font-size: 26px;
                            cursor: pointer;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            z-index: 10001;
                            transition: background-color var(--transition-fast), border-color var(--transition-fast);
                        }
                        .lightbox-btn:hover {
                            background-color: var(--accent-blue);
                            border-color: var(--accent-blue);
                        }
                        .lightbox-btn.prev { left: 40px; }
                        .lightbox-btn.next { right: 40px; }
                    </style>

                    <!-- JavaScript for Slider & Lightbox Functionality -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const images = <?php echo json_encode($screenshot_urls); ?>;
                            let currentIndex = 0;

                            const slides = document.querySelectorAll('.nursoft-slide');
                            const dots = document.querySelectorAll('.nursoft-dot');
                            
                            const prevBtn = document.getElementById('nursoft-prev-btn');
                            const nextBtn = document.getElementById('nursoft-next-btn');

                            // Lightbox items
                            const lightbox = document.getElementById('nursoft-lightbox');
                            const lightboxImg = document.getElementById('lightbox-img');
                            const lightboxCaption = document.getElementById('lightbox-caption');
                            const lightboxClose = document.getElementById('lightbox-close');
                            const lightboxPrev = document.getElementById('lightbox-prev-btn');
                            const lightboxNext = document.getElementById('lightbox-next-btn');

                            function showSlide(index) {
                                if (index < 0) index = slides.length - 1;
                                if (index >= slides.length) index = 0;

                                slides.forEach(s => s.classList.remove('active'));
                                dots.forEach(d => d.classList.remove('active'));

                                slides[index].classList.add('active');
                                if (dots[index]) dots[index].classList.add('active');
                                
                                currentIndex = index;
                            }

                            if (nextBtn && prevBtn) {
                                nextBtn.addEventListener('click', () => showSlide(currentIndex + 1));
                                prevBtn.addEventListener('click', () => showSlide(currentIndex - 1));
                            }

                            dots.forEach(dot => {
                                dot.addEventListener('click', function() {
                                    showSlide(parseInt(this.getAttribute('data-index')));
                                });
                            });

                            // Open Lightbox
                            document.querySelectorAll('.nursoft-slider-img').forEach(img => {
                                img.addEventListener('click', function() {
                                    const idx = parseInt(this.getAttribute('data-index'));
                                    openLightbox(idx);
                                });
                            });

                            function openLightbox(idx) {
                                if (idx < 0) idx = images.length - 1;
                                if (idx >= images.length) idx = 0;

                                lightboxImg.src = images[idx];
                                lightboxCaption.textContent = `Screenshot ${idx + 1} of ${images.length}`;
                                lightbox.style.display = 'flex';
                                currentIndex = idx;
                            }

                            function closeLightbox() {
                                lightbox.style.display = 'none';
                            }

                            if (lightboxClose) {
                                lightboxClose.addEventListener('click', closeLightbox);
                            }

                            if (lightboxNext && lightboxPrev) {
                                lightboxNext.addEventListener('click', () => openLightbox(currentIndex + 1));
                                lightboxPrev.addEventListener('click', () => openLightbox(currentIndex - 1));
                            }

                            // Close lightbox on clicking backdrop
                            lightbox.addEventListener('click', function(e) {
                                if (e.target === lightbox) closeLightbox();
                            });

                            // Keyboard navigation support!
                            document.addEventListener('keydown', function(e) {
                                if (lightbox.style.display === 'flex') {
                                    if (e.key === 'ArrowRight') openLightbox(currentIndex + 1);
                                    if (e.key === 'ArrowLeft') openLightbox(currentIndex - 1);
                                    if (e.key === 'Escape') closeLightbox();
                                } else {
                                    if (e.key === 'ArrowRight') showSlide(currentIndex + 1);
                                    if (e.key === 'ArrowLeft') showSlide(currentIndex - 1);
                                }
                            });
                        });
                    </script>
                <?php endif; ?>

                <!-- Detailed Description / Editor Content -->
                <section class="soft_desc_content">
                    <h5 class="soft_section_title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;color:var(--accent-blue);"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                        <span><?php _e('Description', 'nursoft'); ?></span>
                    </h5>
                    <?php the_content(); ?>
                </section>

                <!-- System Requirements Box -->
                <?php if ( ! empty( $requirements ) ) : ?>
                    <section class="requirements_box">
                        <h5 class="requirements_title"><?php _e('System Requirements', 'nursoft'); ?></h5>
                        <div class="requirements_content"><?php echo esc_html( $requirements ); ?></div>
                    </section>
                <?php endif; ?>

                <!-- Installation Instructions Toggle Box -->
                <?php if ( ! empty( $instructions ) ) : ?>
                    <section class="instructions_toggle_box" style="margin-top: 20px;">
                        <details class="instructions_details" open>
                            <summary class="instructions_summary">
                                <span class="summary_arrow">▼</span>
                                <span><?php _e('Installation Instructions', 'nursoft'); ?></span>
                            </summary>
                            <div class="instructions_content"><?php echo esc_html( $instructions ); ?></div>
                        </details>
                    </section>
                <?php endif; ?>

                </div><!-- /.soft_content_card -->

                <?php 
                // 4. Single Page Bottom Ad Slot (Above Downloads)
                nursoft_render_ad( 'nursoft_ad_single_bottom' ); 
                ?>

                <!-- ===== DOWNLOAD BOX: standalone card ===== -->
                <?php
                // Fetch direct and torrent URLs
                $direct_download  = get_post_meta( get_the_ID(), '_nursoft_direct_download_url', true );
                $torrent_download = get_post_meta( get_the_ID(), '_nursoft_torrent_download_url', true );
                
                // Fallback to old field
                $old_url = get_post_meta( get_the_ID(), '_nursoft_download_url', true );
                if ( empty($direct_download) && ! empty($old_url) ) {
                    $direct_download = $old_url;
                }
                ?>
                <section class="download_box">
                    <div class="download_box_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -16 512 512" fill="currentColor" style="width: 48px; height: 48px;">
                            <path d="M413.492 128.91C396.2 42.145 311.844-14.172 225.078 3.121 161.618 15.77 111.996 65.36 99.308 128.813 37.79 135.903-6.34 191.52.747 253.043c6.524 56.621 54.48 99.34 111.477 99.3h80.093c8.848 0 16.02-7.171 16.02-16.019s-7.172-16.02-16.02-16.02h-80.093c-44.239-.261-79.883-36.331-79.625-80.566.261-44.238 36.332-79.886 80.57-79.625 8.164 0 15.023-6.14 15.922-14.258 8.133-70.304 71.723-120.707 142.031-112.574 59.11 6.836 105.738 53.465 112.574 112.574 1.344 8.262 8.5 14.313 16.867 14.258 44.239 0 80.098 35.86 80.098 80.098 0 44.234-35.86 80.094-80.098 80.094H320.47c-8.848 0-16.02 7.172-16.02 16.02s7.172 16.019 16.02 16.019h80.097c61.926-.387 111.817-50.903 111.434-112.828-.352-56.395-42.531-103.754-98.508-110.606m0 0"></path>
                            <path d="m313.02 385.184-40.61 40.62V224.192c0-8.847-7.172-16.02-16.015-16.02-8.848 0-16.02 7.173-16.02 16.02v201.614l-40.61-40.621c-6.144-6.368-16.288-6.543-22.652-.395-6.363 6.145-6.539 16.285-.394 22.649.133.136.261.265.394.394l67.938 67.953a16.1 16.1 0 0 0 5.176 3.461 15.83 15.83 0 0 0 12.335 0 16 16 0 0 0 5.172-3.46l67.938-67.954c6.363-6.144 6.539-16.285.394-22.648-6.148-6.364-16.289-6.54-22.652-.395-.133.129-.266.258-.394.395m0 0"></path>
                        </svg>
                    </div>
                    <h5 class="download_box_title"><?php _e('Ready to Download?', 'nursoft'); ?></h5>
                    <p class="download_box_desc"><?php _e('Choose your preferred mirror below. Downloads are fully scanned and verified safe.', 'nursoft'); ?></p>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px; align-items: center; justify-content: center; max-width: 320px; margin: 0 auto;">
                        <!-- 1. Direct Download Button -->
                        <?php if ( ! empty( $direct_download ) ) : ?>
                            <a href="<?php echo esc_url( add_query_arg( array( 'nursoft_action' => 'download_portal', 'post_id' => get_the_ID(), 'version_idx' => 0, 'type' => 'direct' ), home_url('/') ) ); ?>" class="download_btn" style="width: 100%;" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                <span><?php _e('Direct Download', 'nursoft'); ?></span>
                            </a>
                        <?php endif; ?>

                        <!-- 2. Torrent Download Button -->
                        <?php if ( ! empty( $torrent_download ) ) : ?>
                            <a href="<?php echo esc_url( add_query_arg( array( 'nursoft_action' => 'download_portal', 'post_id' => get_the_ID(), 'version_idx' => 0, 'type' => 'torrent' ), home_url('/') ) ); ?>" class="download_btn" style="width: 100%; background: linear-gradient(135deg, #00b4db, #0083b0); box-shadow: 0 4px 15px rgba(0, 180, 219, 0.3);" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill:currentColor;"><path d="M12 2C7.58 2 4 5.58 4 10v4c0 3.31 2.69 6 6 6h4c3.31 0 6-2.69 6-6v-4c0-4.42-3.58-8-8-8zm6 12c0 2.21-1.79 4-4 4h-4c-2.21 0-4-1.79-4-4v-4c0-3.31 2.69-6 6-6s6 2.69 6 6v4zM8 10h2v2H8zm6 0h2v2h-2z"/></svg>
                                <span><?php _e('Torrent Download', 'nursoft'); ?></span>
                            </a>
                        <?php endif; ?>

                        <!-- Fallback if both links are empty -->
                        <?php if ( empty( $direct_download ) && empty( $torrent_download ) ) : ?>
                            <span class="download_btn" style="width: 100%; background-color: var(--text-muted); cursor: not-allowed; box-shadow: none;">
                                <span><?php _e('Links Coming Soon', 'nursoft'); ?></span>
                            </span>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Version History Box — Compact Collapsible -->
                <?php if ( ! empty( $versions_list ) && is_array( $versions_list ) ) :
                    $total_versions = count( $versions_list );
                ?>
                    <section class="version_history_box">
                        <h5 class="soft_section_title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;color:var(--accent-blue);"><path d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89.07.14L9 12H6a7 7 0 1 1 7 7 7.07 7.07 0 0 1-6-3H4.82A9 9 0 1 0 13 3zm-1 5v5l4.25 2.52.75-1.23-3.5-2.09V8z"/></svg>
                            <span><?php _e('Version History & Download Links', 'nursoft'); ?></span>
                            <span style="margin-left:auto; font-size:12px; font-weight:500; color:var(--text-muted); font-family:var(--font-body);"><?php echo $total_versions; ?> <?php _e('releases', 'nursoft'); ?></span>
                        </h5>

                        <div class="version_compact_list" id="version-compact-list">
                            <?php foreach ( $versions_list as $index => $item ) :
                                $is_hidden = ( $index >= 5 );
                                $has_details = ! empty($item['changelog']) || ! empty($item['date']) || ! empty($item['direct_url']) || ! empty($item['torrent_url']);
                            ?>
                            <div class="vhl-row<?php echo $is_hidden ? ' vhl-extra' : ''; ?>" data-index="<?php echo $index; ?>">
                                <!-- Left: version badge + toggle trigger -->
                                <div class="vhl-left">
                                    <div class="vhl-dot"></div>
                                    <button class="vhl-badge<?php echo $index === 0 ? ' vhl-badge--latest' : ''; ?>"
                                        <?php if ( $has_details ) : ?>
                                        onclick="nursoftToggleChangelog(this, <?php echo $index; ?>)"
                                        aria-expanded="false"
                                        <?php endif; ?>>
                                        <span><?php echo esc_html($item['version']); ?></span>
                                        <?php if ( $index === 0 ) : ?>
                                            <span class="vhl-latest-tag"><?php _e('LATEST', 'nursoft'); ?></span>
                                        <?php endif; ?>
                                        <?php if ( ! empty($item['date']) ) : ?>
                                            <span class="vhl-date"><?php echo esc_html($item['date']); ?></span>
                                        <?php endif; ?>
                                        <?php if ( $has_details ) : ?>
                                            <svg class="vhl-chevron" viewBox="0 0 24 24" width="14" height="14"><path d="M6 9l6 6 6-6"/></svg>
                                        <?php endif; ?>
                                    </button>
                                </div>

                                <!-- Right: compact download buttons always visible -->
                                <div class="vhl-right">
                                    <?php if ( ! empty($item['direct_url']) ) : ?>
                                        <a href="<?php echo esc_url( add_query_arg( array( 'nursoft_action' => 'download_portal', 'post_id' => get_the_ID(), 'version_idx' => $index, 'type' => 'direct' ), home_url('/') ) ); ?>" class="vhl-dl-btn vhl-dl-btn--direct" target="_blank" title="<?php esc_attr_e('Direct Download', 'nursoft'); ?>">
                                            <svg viewBox="0 0 24 24" width="12" height="12"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                            <span><?php _e('Direct', 'nursoft'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ( ! empty($item['torrent_url']) ) : ?>
                                        <a href="<?php echo esc_url( add_query_arg( array( 'nursoft_action' => 'download_portal', 'post_id' => get_the_ID(), 'version_idx' => $index, 'type' => 'torrent' ), home_url('/') ) ); ?>" class="vhl-dl-btn vhl-dl-btn--torrent" target="_blank" title="<?php esc_attr_e('Torrent Download', 'nursoft'); ?>">
                                            <svg viewBox="0 0 24 24" width="12" height="12"><path d="M12 2C7.58 2 4 5.58 4 10v4c0 3.31 2.69 6 6 6h4c3.31 0 6-2.69 6-6v-4c0-4.42-3.58-8-8-8zm6 12c0 2.21-1.79 4-4 4h-4c-2.21 0-4-1.79-4-4v-4c0-3.31 2.69-6 6-6s6 2.69 6 6v4zM8 10h2v2H8zm6 0h2v2h-2z"/></svg>
                                            <span><?php _e('Torrent', 'nursoft'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <!-- Collapsible Changelog Panel (hidden by default) -->
                                <?php if ( $has_details ) : ?>
                                <div class="vhl-changelog" id="vhl-changelog-<?php echo $index; ?>" hidden>
                                    <?php if ( ! empty($item['changelog']) ) : ?>
                                        <div class="vhl-changelog-text"><?php echo nl2br( esc_html( $item['changelog'] ) ); ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if ( $total_versions > 5 ) : ?>
                        <!-- Show More / Less button -->
                        <button class="vhl-show-more" id="vhl-show-more-btn" onclick="nursoftToggleMoreVersions(this)">
                            <svg viewBox="0 0 24 24" width="14" height="14" id="vhl-more-chevron"><path d="M6 9l6 6 6-6"/></svg>
                            <span id="vhl-more-label"><?php echo sprintf( _n('Show %s more release', 'Show %s more releases', $total_versions - 5, 'nursoft'), $total_versions - 5 ); ?></span>
                        </button>
                        <?php endif; ?>
                    </section>

                <?php elseif ( ! empty( $version_history ) ) :
                    // Legacy plain-text version history
                    $entries = array_filter( array_map( 'trim', explode("\n", $version_history) ) );
                    $entries = array_values( $entries );
                    $total_entries = count($entries);
                ?>
                    <section class="version_history_box">
                        <h5 class="soft_section_title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;color:var(--accent-blue);"><path d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89.07.14L9 12H6a7 7 0 1 1 7 7 7.07 7.07 0 0 1-6-3H4.82A9 9 0 1 0 13 3zm-1 5v5l4.25 2.52.75-1.23-3.5-2.09V8z"/></svg>
                            <span><?php _e('Version History / Changelog', 'nursoft'); ?></span>
                            <span style="margin-left:auto; font-size:12px; font-weight:500; color:var(--text-muted); font-family:var(--font-body);"><?php echo $total_entries; ?> <?php _e('releases', 'nursoft'); ?></span>
                        </h5>
                        <div class="version_compact_list" id="version-compact-list-legacy">
                            <?php foreach ( $entries as $i => $entry ) :
                                $parts = explode('-', $entry, 2);
                                if ( count($parts) === 2 ) {
                                    $version_title = trim($parts[0]);
                                    $version_desc  = trim($parts[1]);
                                } else {
                                    $parts2 = explode(':', $entry, 2);
                                    $version_title = count($parts2) === 2 ? trim($parts2[0]) : $entry;
                                    $version_desc  = count($parts2) === 2 ? trim($parts2[1]) : '';
                                }
                                $is_hidden = ( $i >= 5 );
                            ?>
                            <div class="vhl-row<?php echo $is_hidden ? ' vhl-extra' : ''; ?>">
                                <div class="vhl-left">
                                    <div class="vhl-dot"></div>
                                    <button class="vhl-badge<?php echo $i === 0 ? ' vhl-badge--latest' : ''; ?>"
                                        <?php if ( ! empty($version_desc) ) : ?>onclick="nursoftToggleChangelog(this, 'l<?php echo $i; ?>')" aria-expanded="false"<?php endif; ?>>
                                        <span><?php echo esc_html($version_title); ?></span>
                                        <?php if ( $i === 0 ) : ?><span class="vhl-latest-tag"><?php _e('LATEST', 'nursoft'); ?></span><?php endif; ?>
                                        <?php if ( ! empty($version_desc) ) : ?><svg class="vhl-chevron" viewBox="0 0 24 24" width="14" height="14"><path d="M6 9l6 6 6-6"/></svg><?php endif; ?>
                                    </button>
                                </div>
                                <div class="vhl-right"></div>
                                <?php if ( ! empty($version_desc) ) : ?>
                                <div class="vhl-changelog" id="vhl-changelog-l<?php echo $i; ?>" hidden>
                                    <div class="vhl-changelog-text"><?php echo esc_html($version_desc); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if ( $total_entries > 5 ) : ?>
                        <button class="vhl-show-more" id="vhl-show-more-btn-legacy" onclick="nursoftToggleMoreVersions(this)">
                            <svg viewBox="0 0 24 24" width="14" height="14" id="vhl-more-chevron-legacy"><path d="M6 9l6 6 6-6"/></svg>
                            <span><?php echo sprintf( _n('Show %s more release', 'Show %s more releases', $total_entries - 5, 'nursoft'), $total_entries - 5 ); ?></span>
                        </button>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>

                <script>
                /**
                 * Toggle individual changelog panel per version row.
                 */
                function nursoftToggleChangelog(btn, idx) {
                    var panel = document.getElementById('vhl-changelog-' + idx);
                    if (!panel) return;
                    var isOpen = btn.getAttribute('aria-expanded') === 'true';
                    if (isOpen) {
                        panel.hidden = true;
                        btn.setAttribute('aria-expanded', 'false');
                        btn.classList.remove('vhl-badge--open');
                    } else {
                        panel.hidden = false;
                        btn.setAttribute('aria-expanded', 'true');
                        btn.classList.add('vhl-badge--open');
                    }
                }

                /**
                 * Toggle display of versions beyond the first 5.
                 */
                function nursoftToggleMoreVersions(btn) {
                    var extras = btn.closest('section').querySelectorAll('.vhl-extra');
                    var label  = btn.querySelector('span');
                    var chev   = btn.querySelector('svg');
                    var showing = btn.getAttribute('data-showing') === '1';

                    extras.forEach(function(el) {
                        el.style.display = showing ? 'none' : 'grid';
                    });

                    if (showing) {
                        btn.setAttribute('data-showing', '0');
                        var count = extras.length;
                        label.textContent = (count === 1)
                            ? '<?php _e("Show 1 more release", "nursoft"); ?>'
                            : '<?php echo esc_js( __("Show %s more releases", "nursoft") ); ?>'.replace('%s', count);
                        chev.style.transform = '';
                    } else {
                        btn.setAttribute('data-showing', '1');
                        label.textContent = '<?php _e("Show less", "nursoft"); ?>';
                        chev.style.transform = 'rotate(180deg)';
                    }
                }
                </script>

                <!-- Premium Comment Section -->
                <?php
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>

            </article>

            <!-- 2. Right / Sidebar Column -->
            <aside class="software_sidebar_col">
                
                <!-- Related Software Widget -->
                <div class="widget_box">
                    <h5 class="widget_title"><?php _e('Related Software', 'nursoft'); ?></h5>
                    <div class="widget_list">
                        <?php
                        // Query related products in same category
                        $cat_ids = array();
                        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                            foreach ( $categories as $cat ) {
                                $cat_ids[] = $cat->term_id;
                            }
                        }

                        $rel_args = array(
                            'post_type'      => 'software',
                            'posts_per_page' => 4,
                            'post__not_in'   => array( get_the_ID() ),
                            'tax_query'      => array(
                                'relation' => 'OR',
                                array(
                                    'taxonomy' => 'software_cat',
                                    'field'    => 'term_id',
                                    'terms'    => $cat_ids,
                                ),
                            ),
                        );
                        $rel_query = new WP_Query( $rel_args );

                        if ( $rel_query->have_posts() ) :
                            while ( $rel_query->have_posts() ) : $rel_query->the_post();
                                $rel_version = get_post_meta( get_the_ID(), '_nursoft_version', true );
                                $rel_size    = get_post_meta( get_the_ID(), '_nursoft_size', true );
                                $rel_plats   = wp_get_post_terms( get_the_ID(), 'platform' );
                                $rel_plat_name = ! empty( $rel_plats ) ? $rel_plats[0]->name : 'Windows';
                                ?>
                                <div class="widget_item">
                                    <a href="<?php the_permalink(); ?>" class="widget_thumb">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'thumbnail' ); ?>
                                        <?php else : ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" alt="logo" />
                                        <?php endif; ?>
                                    </a>
                                    <div class="widget_item_info">
                                        <a class="widget_item_title" href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?><?php if($rel_version) echo ' ' . esc_html($rel_version); ?>
                                        </a>
                                        <div class="widget_item_meta">
                                            <span style="color:var(--accent-blue); text-transform: capitalize;"><?php echo esc_html($rel_plat_name); ?></span>
                                            <span><?php echo esc_html($rel_size); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p style="font-size:13px; color:var(--text-secondary);">' . __('No related software found.', 'nursoft') . '</p>';
                        endif;
                        ?>
                    </div>
                </div>

                <!-- Popular Downloads Widget -->
                <div class="widget_box">
                    <h5 class="widget_title"><?php _e('Popular Downloads', 'nursoft'); ?></h5>
                    <div class="widget_list">
                        <?php
                        // Query popular software sorted by download counts
                        $pop_args = array(
                            'post_type'      => 'software',
                            'posts_per_page' => 4,
                            'meta_key'       => '_nursoft_downloads',
                            'orderby'        => 'meta_value_num',
                            'order'          => 'DESC',
                        );
                        $pop_query = new WP_Query( $pop_args );

                        if ( $pop_query->have_posts() ) :
                            while ( $pop_query->have_posts() ) : $pop_query->the_post();
                                $pop_version = get_post_meta( get_the_ID(), '_nursoft_version', true );
                                $pop_size    = get_post_meta( get_the_ID(), '_nursoft_size', true );
                                $pop_downloads = get_post_meta( get_the_ID(), '_nursoft_downloads', true );
                                $pop_downloads_formatted = $pop_downloads !== '' ? number_format( intval($pop_downloads) ) : '0';
                                ?>
                                <div class="widget_item">
                                    <a href="<?php the_permalink(); ?>" class="widget_thumb">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'thumbnail' ); ?>
                                        <?php else : ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" alt="logo" />
                                        <?php endif; ?>
                                    </a>
                                    <div class="widget_item_info">
                                        <a class="widget_item_title" href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?><?php if($pop_version) echo ' ' . esc_html($pop_version); ?>
                                        </a>
                                        <div class="widget_item_meta">
                                            <span style="color:var(--accent-yellow);"><?php echo $pop_downloads_formatted; ?> dls</span>
                                            <span><?php echo esc_html($pop_size); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>

            </aside>
        </div>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
