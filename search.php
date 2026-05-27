<?php
/**
 * The template for displaying search results pages
 *
 * @author  Mohamed Nurdin Mgaza <codeoba@gmail.com>
 * @country Tanzania | +687001775
 * @version 1.7.0
 * @package Nursoft
 */

get_header(); ?>

<main class="container main_content">
    <div class="software_layout">
        <div class="software_main_col">
            <section class="products_section">
        <!-- Search Header Title -->
        <div class="section-header">
            <h4><?php echo sprintf( __('Search Results for: %s', 'nursoft'), esc_html( get_search_query() ) ); ?></h4>
            <?php
            global $wp_query;
            $count = $wp_query->found_posts;
            ?>
            <span style="font-size: 13px; color: var(--text-secondary);"><?php echo $count; ?> <?php _e('Software items matched', 'nursoft'); ?></span>
        </div>

        <?php if ( have_posts() ) : ?>
            <!-- Product Grid -->
            <div class="product-grid" style="margin-bottom: 40px;">
                <?php
                while ( have_posts() ) : the_post();
                    // Retrieve Custom Fields Meta
                    $version      = get_post_meta( get_the_ID(), '_nursoft_version', true );
                    $size         = get_post_meta( get_the_ID(), '_nursoft_size', true );
                    $downloads    = get_post_meta( get_the_ID(), '_nursoft_downloads', true );
                    $reputation   = get_post_meta( get_the_ID(), '_nursoft_reputation', true );
                    $badge        = get_post_meta( get_the_ID(), '_nursoft_badge', true );

                    // Setup formatted variables
                    $downloads_formatted = $downloads !== '' ? number_format( intval($downloads) ) : '0';
                    $reputation_value    = $reputation !== '' ? floatval($reputation) : 4.5;
                    $size_display        = $size !== '' ? esc_html($size) : 'N/A';
                    $version_display     = $version !== '' ? ' ' . esc_html($version) : '';

                    // Get Platform
                    $platforms = wp_get_post_terms( get_the_ID(), 'platform' );
                    $platform_slug = ! empty( $platforms ) && ! is_wp_error( $platforms ) ? $platforms[0]->slug : 'ms-windows';
                    $platform_name = ! empty( $platforms ) && ! is_wp_error( $platforms ) ? $platforms[0]->name : 'Windows';

                    // Define CPT Categories
                    $software_cats = wp_get_post_terms( get_the_ID(), 'software_cat' );
                    $cat_link_html = '';
                    if ( ! empty( $software_cats ) && ! is_wp_error( $software_cats ) ) {
                        $cat_link_html = '<a class="card_category" href="' . esc_url( get_term_link( $software_cats[0] ) ) . '">' . esc_html( $software_cats[0]->name ) . '</a>';
                    } else {
                        $cat_link_html = '<span class="card_category">' . __('General', 'nursoft') . '</span>';
                    }
                    ?>
                    
                    <!-- Product Card -->
                    <div class="card_wrap">
                        <!-- Dynamic glowing badge overlay -->
                        <?php echo nursoft_render_card_badge( $badge ); ?>

                        <div class="card_header">
                            <!-- Icon/Thumbnail -->
                            <a class="card_icon_link" href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'card_icon_img', 'alt' => get_the_title() ) ); ?>
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" class="card_icon_img" alt="Software logo" />
                                <?php endif; ?>
                            </a>

                            <div class="card_info">
                                <!-- Title -->
                                <a class="card_title_link" href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?><?php echo $version_display; ?>
                                </a>
                                <!-- Excerpt Description -->
                                <p class="card_desc"><?php echo get_the_excerpt(); ?></p>
                                <!-- Category -->
                                <?php echo $cat_link_html; ?>
                            </div>
                        </div>

                        <!-- Card Data Sub-Grid -->
                        <div class="card_data">
                            <!-- Platform indicator link -->
                            <a class="card_primary card_<?php echo esc_attr( $platform_slug ); ?>" href="<?php echo esc_url( get_term_link( $platforms[0] ) ); ?>">
                                <?php
                                // Custom SVGs for card platform
                                if ( $platform_slug === 'ms-windows' || $platform_slug === 'windows' ) {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="height:14px;"><path d="M0 80v160h224V52zm256-32v192h256V16zm0 224v192l256 32V272zM0 272v160l224 28V272z"></path></svg>';
                                } elseif ( $platform_slug === 'mac' || $platform_slug === 'macos' ) {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.003 512.003" style="height:14px;"><path d="M351.98 0c-27.296 1.888-59.2 19.36-77.792 42.112-16.96 20.64-30.912 51.296-25.472 81.088 29.824.928 60.64-16.96 78.496-40.096 16.704-21.536 29.344-52 24.768-83.104"></path><path d="M459.852 171.776c-26.208-32.864-63.04-51.936-97.824-51.936-45.92 0-65.344 21.984-97.248 21.984-32.896 0-57.888-21.92-97.6-21.92-39.008 0-80.544 23.84-106.88 64.608-37.024 57.408-30.688 165.344 29.312 257.28 21.472 32.896 50.144 69.888 87.648 70.208 33.376.32 42.784-21.408 88-21.632 45.216-.256 53.792 21.92 87.104 21.568 37.536-.288 67.776-41.28 89.248-74.176 15.392-23.584 21.12-35.456 33.056-62.08-86.816-33.056-100.736-156.512-14.816-203.904"></path></svg>';
                                } elseif ( strpos( $platform_slug, 'android' ) !== false ) {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.007 512.007" style="height:14px;"><path d="M64.004 192.007c-17.664 0-32 14.336-32 32v128c0 17.664 14.336 32 32 32s32-14.336 32-32v-128c0-17.664-14.336-32-32-32m384 0c-17.664 0-32 14.336-32 32v128c0 17.664 14.336 32 32 32s32-14.336 32-32v-128c0-17.664-14.336-32-32-32m-320 1.856v192c0 17.664 14.336 32 32 32v62.144c0 17.664 14.336 32 32 32s32-14.336 32-32v-62.144h64v62.144c0 17.664 14.336 32 32 32s32-14.336 32-32v-62.144c17.664 0 32-14.336 32-32v-192zm0-33.856h256c0-40.32-19.008-75.84-48.128-99.296l28.48-34.528c5.632-6.816 4.672-16.896-2.144-22.528-6.848-5.6-16.896-4.672-22.528 2.144l-31.136 37.728c-16.064-7.264-33.76-11.52-52.544-11.52-19.04 0-36.96 4.416-53.184 11.904L172.516 6.023c-5.536-6.88-15.584-8.032-22.496-2.496-6.88 5.536-8 15.584-2.496 22.496l28.096 35.136c-28.832 23.456-47.616 58.784-47.616 98.848m160-80c8.832 0 16 7.168 16 16s-7.168 16-16 16-16-7.168-16-16 7.168-16 16-16m-64 0c8.832 0 16 7.168 16 16s-7.168 16-16 16-16-7.168-16-16 7.168-16 16-16"></path></svg>';
                                } elseif ( $platform_slug === 'pc-games' ) {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="height:14px;"><path d="M512 363.78c-1.2-66.8-9.09-134.35-22.03-202.53-10.54-47.37-48.46-89.56-109.05-92.65-44.73-1.84-53.38 23.64-104.06 23.15-13.88-.09-27.75-.09-41.63 0-50.69.49-59.36-24.99-104.07-23.15-60.6 3.09-99.7 45.17-109.09 92.65C9.12 229.43 1.23 296.97.04 363.77c-.29 46.51 45.63 77.45 75.93 79.57 58.53 4.42 105.03-98.79 140.46-98.8 26.41.15 52.81.16 79.22 0 35.44 0 81.9 103.23 140.47 98.81 30.29-2.12 77.4-33.27 75.89-79.57zM190.94 233.44h-27.3v27.3c0 11.52-9.34 20.86-20.86 20.86s-20.86-9.34-20.86-20.86v-27.3h-27.3c-11.52 0-20.86-9.34-20.86-20.86s9.34-20.86 20.86-20.86h27.3v-27.3c0-11.52 9.34-20.86 20.86-20.86s20.86 9.34 20.86 20.86v27.3h27.3c11.52 0 20.86 9.34 20.86 20.86s-9.34 20.86-20.86 20.86m168.57 48.15c-16.33.44-29.91-12.46-30.35-28.78-.43-16.38 12.48-29.98 28.8-30.4 16.34-.41 29.94 12.48 30.36 28.82.41 16.34-12.48 29.94-28.81 30.36m49.25-78.85c-16.33.46-29.94-12.45-30.39-28.78-.41-16.36 12.48-29.94 28.82-30.39 16.36-.43 29.94 12.48 30.38 28.82.43 16.33-12.49 29.94-28.81 30.35"></path></svg>';
                                } else {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="height:14px;"><path d="M21 16.5c0 .38-.21.71-.53.88l-7.9 4.44a.99.99 0 0 1-.94 0l-7.9-4.44c-.32-.17-.53-.5-.53-.88V7.5c0-.38.21-.71.53-.88l7.9-4.44a.99.99 0 0 1 .94 0l7.9 4.44c.32.17.53.5.53.88v9z"/></svg>';
                                }
                                ?>
                                <span><?php echo esc_html( $platform_name ); ?></span>
                            </a>

                            <!-- Downloads metadata -->
                            <div class="card_meta-data">
                                <div class="card_meta-item" title="<?php echo $downloads_formatted; ?> downloads">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -16 512 512">
                                        <path d="M413.492 128.91C396.2 42.145 311.844-14.172 225.078 3.121 161.618 15.77 111.996 65.36 99.308 128.813 37.79 135.903-6.34 191.52.747 253.043c6.524 56.621 54.48 99.34 111.477 99.3h80.093c8.848 0 16.02-7.171 16.02-16.019s-7.172-16.02-16.02-16.02h-80.093c-44.239-.261-79.883-36.331-79.625-80.566.261-44.238 36.332-79.886 80.57-79.625 8.164 0 15.023-6.14 15.922-14.258 8.133-70.304 71.723-120.707 142.031-112.574 59.11 6.836 105.738 53.465 112.574 112.574 1.344 8.262 8.5 14.313 16.867 14.258 44.239 0 80.098 35.86 80.098 80.098 0 44.234-35.86 80.094-80.098 80.094H320.47c-8.848 0-16.02 7.172-16.02 16.02s7.172 16.019 16.02 16.019h80.097c61.926-.387 111.817-50.903 111.434-112.828-.352-56.395-42.531-103.754-98.508-110.606m0 0"></path>
                                        <path d="m313.02 385.184-40.61 40.62V224.192c0-8.847-7.172-16.02-16.015-16.02-8.848 0-16.02 7.173-16.02 16.02v201.614l-40.61-40.621c-6.144-6.368-16.288-6.543-22.652-.395-6.363 6.145-6.539 16.285-.394 22.649.133.136.261.265.394.394l67.938 67.953a16.1 16.1 0 0 0 5.176 3.461 15.83 15.83 0 0 0 12.335 0 16 16 0 0 0 5.172-3.46l67.938-67.954c6.363-6.144 6.539-16.285.394-22.648-6.148-6.364-16.289-6.54-22.652-.395-.133.129-.266.258-.394.395m0 0"></path>
                                    </svg>
                                    <span class="card_meta-text"><?php echo $downloads_formatted; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Rating & Size Subdata -->
                        <div class="card_subdata">
                            <?php echo nursoft_render_stars( $reputation_value ); ?>
                            <span class="card_size"><?php echo $size_display; ?></span>
                            <button class="quick-view-trigger-btn" data-post-id="<?php the_ID(); ?>" aria-label="<?php _e('Quick view', 'nursoft'); ?>">
                                <svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            </button>
                        </div>
                    </div>

                <?php endwhile; ?>
            </div>

            <!-- Sleek Dark Mode Pagination -->
            <div class="nursoft-pagination" style="display:flex; justify-content:center; gap:8px; margin-bottom: 40px;">
                <?php
                echo paginate_links( array(
                    'mid_size'  => 2,
                    'prev_text' => '<span style="padding:8px 16px; background:var(--bg-surface); border:1px solid var(--border-color); border-radius:6px; color:var(--text-secondary);">&laquo; Previous</span>',
                    'next_text' => '<span style="padding:8px 16px; background:var(--bg-surface); border:1px solid var(--border-color); border-radius:6px; color:var(--text-secondary);">Next &raquo;</span>',
                    'type'      => 'plain',
                ) );
                ?>
            </div>
            <style>
                .nursoft-pagination a, .nursoft-pagination span.current {
                    display: inline-block;
                    padding: 8px 16px;
                    background: var(--bg-surface);
                    border: 1px solid var(--border-color);
                    border-radius: 6px;
                    color: var(--text-secondary);
                    font-weight: 600;
                    transition: background var(--transition-fast), color var(--transition-fast);
                }
                .nursoft-pagination a:hover {
                    background: var(--bg-surface-hover);
                    color: var(--text-primary);
                }
                .nursoft-pagination span.current {
                    background: var(--accent-blue);
                    color: white;
                    border-color: var(--accent-blue);
                }
            </style>

        <?php else : ?>
            <p style="text-align: center; color: var(--text-secondary); margin: 60px 0;"><?php _e('No software matched your search query. Try another keyword!', 'nursoft'); ?></p>
        <?php endif; ?>
            </section>
        </div>
        <!-- Right / Sidebar Column -->
        <?php get_sidebar(); ?>
    </div>
</main>

<?php get_footer(); ?>
