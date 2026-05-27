<?php
/**
 * The sidebar template containing the main widget area
 *
 * @author  Mohamed Nurdin Mgaza <codeoba@gmail.com>
 * @country Tanzania | +687001775
 * @version 1.6.0
 * @package Nursoft
 */
?>
<aside class="software_sidebar_col main_sidebar">
    
    <!-- 1. Default Fallback Widget: Dynamic Tabs (Latest, Popular, Comments) -->
    <div class="widget_box tabbed_sidebar_widget">
        <!-- Tab Headers -->
        <div class="sidebar_tab_headers">
            <button class="sidebar_tab_btn active" onclick="nursoftSwitchTab(event, 'tab-latest')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                <span><?php _e('Latest', 'nursoft'); ?></span>
            </button>
            <button class="sidebar_tab_btn" onclick="nursoftSwitchTab(event, 'tab-popular')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19.48 12.35c-1.57-4.08-7.16-6.04-7.16-6.04s.35 2.35-1.08 4.29c-1.39 1.9-3.4 3.25-3.4 5.83 0 3.7 3.1 6.7 6.8 6.7 3.7 0 6.8-3 6.8-6.7 0-1.5-.4-2.85-1.96-4.08zM14.6 20.4c-.9 1.1-2.4 1.4-3.5.7-1.1-.6-1.5-2-1-3.1.5-1 1.6-1.6 1.6-1.6s0 1.2.7 1.8c.7.6 1.7.3 2.1-.4.4-.7.1-1.7-.5-2.2-.6-.5-1.5-1.1-1.5-1.1s2.2.4 3.3 2.5c1.1 2 0 4.1-.7 4.9L14.6 20.4z"/></svg>
                <span><?php _e('Popular', 'nursoft'); ?></span>
            </button>
            <button class="sidebar_tab_btn" onclick="nursoftSwitchTab(event, 'tab-comments')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                <span><?php _e('Comments', 'nursoft'); ?></span>
            </button>
        </div>

        <!-- Tab Body Contents -->
        <div class="sidebar_tab_body">
            
            <!-- 1. Tab: LATEST UPDATED -->
            <div id="tab-latest" class="sidebar_tab_content" style="display: block;">
                <div class="widget_list">
                    <?php
                    $latest_args = array(
                        'post_type'      => 'software',
                        'posts_per_page' => 4,
                        'orderby'        => 'modified',
                        'order'          => 'DESC',
                    );
                    $latest_query = new WP_Query( $latest_args );
                    if ( $latest_query->have_posts() ) :
                        while ( $latest_query->have_posts() ) : $latest_query->the_post();
                            $rel_version   = get_post_meta( get_the_ID(), '_nursoft_version', true );
                            $rel_size      = get_post_meta( get_the_ID(), '_nursoft_size', true );
                            $rel_plats     = wp_get_post_terms( get_the_ID(), 'platform' );
                            $rel_plat_name = ! empty( $rel_plats ) && ! is_wp_error( $rel_plats ) ? $rel_plats[0]->name : 'Windows';
                            $mod_time      = human_time_diff( get_the_modified_time('U'), current_time('timestamp') ) . ' ' . __('ago', 'nursoft');
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
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                                        <span><?php echo esc_html($mod_time); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p class="no_data_text">' . __('No updates found.', 'nursoft') . '</p>';
                    endif;
                    ?>
                </div>
            </div>

            <!-- 2. Tab: POPULAR DOWNLOADS -->
            <div id="tab-popular" class="sidebar_tab_content" style="display: none;">
                <div class="widget_list">
                    <?php
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
                            $pop_version   = get_post_meta( get_the_ID(), '_nursoft_version', true );
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
                                    <div class="widget_item_meta" style="color: var(--accent-yellow);">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                        <span><?php echo $pop_downloads_formatted; ?> dls</span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p class="no_data_text">' . __('No downloads found.', 'nursoft') . '</p>';
                    endif;
                    ?>
                </div>
            </div>

            <!-- 3. Tab: RECENT COMMENTS -->
            <div id="tab-comments" class="sidebar_tab_content" style="display: none;">
                <div class="widget_list">
                    <?php
                    $comments_args = array(
                        'number'      => 4,
                        'status'      => 'approve',
                        'post_type'   => 'software',
                    );
                    $comments = get_comments( $comments_args );
                    if ( ! empty( $comments ) ) :
                        foreach ( $comments as $comment ) :
                            $post_id      = $comment->comment_post_ID;
                            $author       = esc_html($comment->comment_author);
                            $comment_text = wp_strip_all_tags( $comment->comment_content );
                            $comment_text = strlen($comment_text) > 45 ? substr($comment_text, 0, 42) . '...' : $comment_text;
                            ?>
                            <div class="widget_item">
                                <div class="widget_thumb" style="border-radius: 50%;">
                                    <?php echo get_avatar( $comment->comment_author_email, 48 ); ?>
                                </div>
                                <div class="widget_item_info">
                                    <span class="widget_comment_author"><?php echo $author; ?></span>
                                    <a class="widget_item_title" href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
                                        <?php echo esc_html($comment_text); ?>
                                    </a>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    else :
                        echo '<p class="no_data_text">' . __('No comments yet.', 'nursoft') . '</p>';
                    endif;
                    ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Script for Switching Dynamic Sidebar Tabs -->
    <script>
    function nursoftSwitchTab(evt, tabId) {
        // Get all tab content elements in the parent box
        var widget = evt.currentTarget.closest('.tabbed_sidebar_widget');
        var contents = widget.querySelectorAll('.sidebar_tab_content');
        var buttons = widget.querySelectorAll('.sidebar_tab_btn');
        
        // Hide all tab contents
        contents.forEach(function(content) {
            content.style.display = 'none';
        });
        
        // Remove active class from all buttons
        buttons.forEach(function(btn) {
            btn.classList.remove('active');
        });
        
        // Show current tab content and set active class
        widget.querySelector('#' + tabId).style.display = 'block';
        evt.currentTarget.classList.add('active');
    }
    </script>

    <!-- 2. Dynamic Sidebar Widgets -->
    <?php 
    // 5. Sidebar Square Banner Ad Slot
    nursoft_render_ad( 'nursoft_ad_sidebar', 'margin-bottom: 25px; text-align: center; max-width: 100%; display: flex; justify-content: center;' );
    ?>

    <?php if ( is_active_sidebar( 'main-sidebar' ) ) : ?>
        <?php dynamic_sidebar( 'main-sidebar' ); ?>
    <?php endif; ?>

</aside>
