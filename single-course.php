<?php
/**
 * The template for displaying all single course items
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
        $instructor      = get_post_meta( get_the_ID(), '_nursoft_course_instructor', true );
        $duration        = get_post_meta( get_the_ID(), '_nursoft_course_duration', true );
        $lessons         = get_post_meta( get_the_ID(), '_nursoft_course_lessons', true );
        $level           = get_post_meta( get_the_ID(), '_nursoft_course_level', true );
        $language        = get_post_meta( get_the_ID(), '_nursoft_course_language', true );
        
        $size            = get_post_meta( get_the_ID(), '_nursoft_size', true );
        $downloads       = get_post_meta( get_the_ID(), '_nursoft_downloads', true );
        $reputation      = get_post_meta( get_the_ID(), '_nursoft_reputation', true );
        $badge           = get_post_meta( get_the_ID(), '_nursoft_badge', true );

        // Formatting
        $downloads_formatted = $downloads !== '' ? number_format( intval($downloads) ) : '0';
        $reputation_value    = $reputation !== '' ? floatval($reputation) : 4.5;
        $size_display        = $size !== '' ? esc_html($size) : 'N/A';

        // Platform & Category Terms
        $platforms = wp_get_post_terms( get_the_ID(), 'platform' );
        $platform_name = ! empty( $platforms ) && ! is_wp_error( $platforms ) ? $platforms[0]->name : 'Courses';
        
        $categories = wp_get_post_terms( get_the_ID(), 'course_cat' );
        ?>

        <div class="software_layout">
            <!-- 1. Left / Main Column -->
            <article class="software_main_col">

                <!-- ===== CONTENT CARD: Header, Gallery, Description ===== -->
                <div class="soft_content_card">

                <?php 
                // Single Page Top Ad Slot
                nursoft_render_ad( 'nursoft_ad_single_top' ); 
                ?>

                <!-- Course Banner & Title Header -->
                <header class="soft_ident_header">
                    <div class="soft_ident_icon_wrap" style="position: relative; flex-shrink: 0;">
                        <div class="soft_ident_icon" style="border-radius: 6px; overflow: hidden; width: 90px; height: 90px;">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'thumbnail', array( 'alt' => get_the_title(), 'style' => 'object-fit: cover; width: 100%; height: 100%;' ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" alt="Course banner" style="object-fit: cover; width: 100%; height: 100%;" />
                            <?php endif; ?>
                        </div>
                        <?php 
                        $badge_html = nursoft_render_card_badge( $badge );
                        if ( ! empty( $badge_html ) && $badge !== 'none' ) : ?>
                            <div class="thumbnail_badge_wrap" style="position: absolute; top: -8px; right: -8px; z-index: 10;">
                                <?php echo $badge_html; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="soft_ident_info" style="margin-left: 20px; flex-grow: 1;">
                        <h1 class="soft_ident_title" style="font-size: 24px; line-height: 1.3; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; gap: 15px; width: 100%;">
                            <span><?php the_title(); ?></span>
                            <button class="fav-toggle-btn" data-post-id="<?php the_ID(); ?>" data-version="<?php echo esc_attr($duration); ?>" title="<?php esc_attr_e('Add to Bookmarks', 'nursoft'); ?>" style="flex-shrink:0;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </button>
                        </h1>
                        
                        <div class="soft_ident_cat_list">
                            <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                                <?php foreach ( $categories as $cat ) : ?>
                                    <a class="soft_ident_cat" href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
                                        <?php echo esc_html( $cat->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if ( $instructor ) : ?>
                                <span class="soft_ident_cat" style="background-color: rgba(43, 203, 186, 0.1); color: #2bcbba; border: 1px solid rgba(43, 203, 186, 0.2);">
                                    <?php printf( __('Instructor: %s', 'nursoft'), esc_html($instructor) ); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

                <!-- Technical Specs Badges Grid -->
                <div class="soft_tech_grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 10px; margin: 25px 0;">
                    <!-- Instructor -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Instructor', 'nursoft'); ?></div>
                        <div class="soft_tech_value" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo esc_attr($instructor); ?>"><?php echo $instructor ? esc_html($instructor) : 'N/A'; ?></div>
                    </div>
                    <!-- Duration -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Duration', 'nursoft'); ?></div>
                        <div class="soft_tech_value"><?php echo $duration ? esc_html($duration) : 'N/A'; ?></div>
                    </div>
                    <!-- Lessons -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Lessons', 'nursoft'); ?></div>
                        <div class="soft_tech_value"><?php echo $lessons ? esc_html($lessons) : 'N/A'; ?></div>
                    </div>
                    <!-- Level -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Level', 'nursoft'); ?></div>
                        <div class="soft_tech_value" style="text-transform: capitalize;"><?php echo $level ? esc_html($level) : 'All Levels'; ?></div>
                    </div>
                    <!-- Size -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Size', 'nursoft'); ?></div>
                        <div class="soft_tech_value"><?php echo $size_display; ?></div>
                    </div>
                    <!-- Downloads -->
                    <div class="soft_tech_card">
                        <div class="soft_tech_label"><?php _e('Downloads', 'nursoft'); ?></div>
                        <div class="soft_tech_value" id="download-count-value"><?php echo $downloads_formatted; ?></div>
                    </div>
                </div>

                <!-- Detailed Description / Editor Content -->
                <section class="soft_desc_content">
                    <h5 class="soft_section_title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;color:var(--accent-blue);"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                        <span><?php _e('Course Overview', 'nursoft'); ?></span>
                    </h5>
                    <?php the_content(); ?>
                </section>

                <!-- Course Metadata Box -->
                <section class="requirements_box" style="margin-top: 30px;">
                    <h5 class="requirements_title"><?php _e('Course Details', 'nursoft'); ?></h5>
                    <div class="requirements_content" style="line-height: 1.8;">
                        <strong><?php _e('Course Title:', 'nursoft'); ?></strong> <?php the_title(); ?><br/>
                        <?php if($instructor) : ?><strong><?php _e('Instructor:', 'nursoft'); ?></strong> <?php echo esc_html($instructor); ?><br/><?php endif; ?>
                        <?php if($lessons) : ?><strong><?php _e('Number of Lessons:', 'nursoft'); ?></strong> <?php echo esc_html($lessons); ?> lessons<br/><?php endif; ?>
                        <?php if($duration) : ?><strong><?php _e('Total Duration:', 'nursoft'); ?></strong> <?php echo esc_html($duration); ?><br/><?php endif; ?>
                        <?php if($level) : ?><strong><?php _e('Target Level:', 'nursoft'); ?></strong> <?php echo esc_html($level); ?><br/><?php endif; ?>
                        <?php if($language) : ?><strong><?php _e('Language:', 'nursoft'); ?></strong> <?php echo esc_html($language); ?><br/><?php endif; ?>
                        <?php if($size) : ?><strong><?php _e('Course Size:', 'nursoft'); ?></strong> <?php echo esc_html($size); ?><br/><?php endif; ?>
                    </div>
                </section>

                </div><!-- /.soft_content_card -->

                <?php 
                // Single Page Bottom Ad Slot (Above Downloads)
                nursoft_render_ad( 'nursoft_ad_single_bottom' ); 
                ?>

                <!-- ===== DOWNLOAD BOX ===== -->
                <?php
                $direct_download  = get_post_meta( get_the_ID(), '_nursoft_direct_download_url', true );
                if ( empty( $direct_download ) ) {
                    $direct_download = get_post_meta( get_the_ID(), '_nursoft_download_url', true );
                }
                $torrent_download = get_post_meta( get_the_ID(), '_nursoft_torrent_download_url', true );
                ?>
                <section class="download_box">
                    <div class="download_box_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -16 512 512" fill="currentColor" style="width: 48px; height: 48px;">
                            <path d="M413.492 128.91C396.2 42.145 311.844-14.172 225.078 3.121 161.618 15.77 111.996 65.36 99.308 128.813 37.79 135.903-6.34 191.52.747 253.043c6.524 56.621 54.48 99.34 111.477 99.3h80.093c8.848 0 16.02-7.171 16.02-16.019s-7.172-16.02-16.02-16.02h-80.093c-44.239-.261-79.883-36.331-79.625-80.566.261-44.238 36.332-79.886 80.57-79.625 8.164 0 15.023-6.14 15.922-14.258 8.133-70.304 71.723-120.707 142.031-112.574 59.11 6.836 105.738 53.465 112.574 112.574 1.344 8.262 8.5 14.313 16.867 14.258 44.239 0 80.098 35.86 80.098 80.098 0 44.234-35.86 80.094-80.098 80.094H320.47c-8.848 0-16.02 7.172-16.02 16.02s7.172 16.019 16.02 16.019h80.097c61.926-.387 111.817-50.903 111.434-112.828-.352-56.395-42.531-103.754-98.508-110.606m0 0"></path>
                            <path d="m313.02 385.184-40.61 40.62V224.192c0-8.847-7.172-16.02-16.015-16.02-8.848 0-16.02 7.173-16.02 16.02v201.614l-40.61-40.621c-6.144-6.368-16.288-6.543-22.652-.395-6.363 6.145-6.539 16.285-.394 22.649.133.136.261.265.394.394l67.938 67.953a16.1 16.1 0 0 0 5.176 3.461 15.83 15.83 0 0 0 12.335 0 16 16 0 0 0 5.172-3.46l67.938-67.954c6.363-6.144 6.539-16.285.394-22.648-6.148-6.364-16.289-6.54-22.652-.395-.133.129-.266.258-.394.395m0 0"></path>
                        </svg>
                    </div>
                    <h5 class="download_box_title"><?php _e('Ready to Download Course?', 'nursoft'); ?></h5>
                    <p class="download_box_desc"><?php _e('High speed direct and torrent mirrors are ready. Click down to start.', 'nursoft'); ?></p>
                    
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

                <!-- Premium Comment Section -->
                <?php
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>

            </article>

            <!-- 2. Right / Sidebar Column -->
            <aside class="software_sidebar_col">
                
                <!-- Related Courses Widget -->
                <div class="widget_box">
                    <h5 class="widget_title"><?php _e('Related Courses', 'nursoft'); ?></h5>
                    <div class="widget_list">
                        <?php
                        $cat_ids = array();
                        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                            foreach ( $categories as $cat ) {
                                $cat_ids[] = $cat->term_id;
                            }
                        }

                        $rel_args = array(
                            'post_type'      => 'course',
                            'posts_per_page' => 4,
                            'post__not_in'   => array( get_the_ID() ),
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'course_cat',
                                    'field'    => 'term_id',
                                    'terms'    => $cat_ids,
                                ),
                            ),
                        );
                        $rel_query = new WP_Query( $rel_args );

                        if ( $rel_query->have_posts() ) :
                            while ( $rel_query->have_posts() ) : $rel_query->the_post();
                                $rel_instructor = get_post_meta( get_the_ID(), '_nursoft_course_instructor', true );
                                $rel_size   = get_post_meta( get_the_ID(), '_nursoft_size', true );
                                $rel_duration = get_post_meta( get_the_ID(), '_nursoft_course_duration', true );
                                ?>
                                <div class="widget_item">
                                    <a href="<?php the_permalink(); ?>" class="widget_thumb" style="border-radius: 6px; width: 45px; height: 45px;">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'thumbnail', array('style' => 'object-fit: cover; width:100%; height:100%;') ); ?>
                                        <?php else : ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" alt="logo" style="object-fit: cover; width:100%; height:100%;" />
                                        <?php endif; ?>
                                    </a>
                                    <div class="widget_item_info" style="margin-left: 12px;">
                                        <a class="widget_item_title" href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                        <div class="widget_item_meta">
                                            <span style="color:var(--accent-blue);"><?php echo $rel_instructor ? esc_html($rel_instructor) : 'Instructor'; ?></span>
                                            <span><?php echo esc_html($rel_size); ?> (<?php echo esc_html($rel_duration); ?>)</span>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p style="font-size:13px; color:var(--text-secondary);">' . __('No related courses found.', 'nursoft') . '</p>';
                        endif;
                        ?>
                    </div>
                </div>

                <!-- Popular Courses Widget -->
                <div class="widget_box">
                    <h5 class="widget_title"><?php _e('Popular Courses', 'nursoft'); ?></h5>
                    <div class="widget_list">
                        <?php
                        $pop_args = array(
                            'post_type'      => 'course',
                            'posts_per_page' => 4,
                            'meta_key'       => '_nursoft_downloads',
                            'orderby'        => 'meta_value_num',
                            'order'          => 'DESC',
                        );
                        $pop_query = new WP_Query( $pop_args );

                        if ( $pop_query->have_posts() ) :
                            while ( $pop_query->have_posts() ) : $pop_query->the_post();
                                $pop_instructor = get_post_meta( get_the_ID(), '_nursoft_course_instructor', true );
                                $pop_size   = get_post_meta( get_the_ID(), '_nursoft_size', true );
                                $pop_downloads = get_post_meta( get_the_ID(), '_nursoft_downloads', true );
                                $pop_downloads_formatted = $pop_downloads !== '' ? number_format( intval($pop_downloads) ) : '0';
                                ?>
                                <div class="widget_item">
                                    <a href="<?php the_permalink(); ?>" class="widget_thumb" style="border-radius: 6px; width: 45px; height: 45px;">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'thumbnail', array('style' => 'object-fit: cover; width:100%; height:100%;') ); ?>
                                        <?php else : ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" alt="logo" style="object-fit: cover; width:100%; height:100%;" />
                                        <?php endif; ?>
                                    </a>
                                    <div class="widget_item_info" style="margin-left: 12px;">
                                        <a class="widget_item_title" href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
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
