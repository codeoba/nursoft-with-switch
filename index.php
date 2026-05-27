<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @author  Mohamed Nurdin Mgaza <codeoba@gmail.com>
 * @country Tanzania | +687001775
 * @version 1.7.0
 * @package Nursoft
 */

get_header(); ?>

<main class="container main_content">
    <section class="products_section">
        <!-- Section Header -->
        <div class="section-header">
            <h4><?php _e('Latest Releases', 'nursoft'); ?></h4>
        </div>

        <?php if ( have_posts() ) : ?>
            <!-- Product/Post Grid -->
            <div class="product-grid" style="margin-bottom: 40px;">
                <?php
                while ( have_posts() ) : the_post();
                    // Fetch specifications if it happens to be software CPT
                    $version      = get_post_meta( get_the_ID(), '_nursoft_version', true );
                    $size         = get_post_meta( get_the_ID(), '_nursoft_size', true );
                    $downloads    = get_post_meta( get_the_ID(), '_nursoft_downloads', true );
                    $reputation   = get_post_meta( get_the_ID(), '_nursoft_reputation', true );
                    
                    $downloads_formatted = $downloads !== '' ? number_format( intval($downloads) ) : '0';
                    $size_display        = $size !== '' ? esc_html($size) : 'N/A';
                    $version_display     = $version !== '' ? ' ' . esc_html($version) : '';
                    ?>
                    
                    <!-- Post Card -->
                    <div class="card_wrap">
                        <div class="card_header">
                            <!-- Logo / Thumbnail -->
                            <a class="card_icon_link" href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'card_icon_img', 'alt' => get_the_title() ) ); ?>
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" class="card_icon_img" alt="Logo fallback" />
                                <?php endif; ?>
                            </a>

                            <div class="card_info">
                                <!-- Title -->
                                <a class="card_title_link" href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?><?php echo $version_display; ?>
                                </a>
                                <!-- Excerpt -->
                                <p class="card_desc"><?php echo get_the_excerpt(); ?></p>
                            </div>
                        </div>

                        <!-- Card Meta Footer -->
                        <div class="card_data">
                            <span class="card_primary" style="color:var(--accent-blue);">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="height:14px;fill:currentColor;vertical-align:middle;margin-right:4px;"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                                <span><?php echo esc_html( get_post_type() ); ?></span>
                            </span>
                            <?php if ( ! empty($size) ) : ?>
                                <span class="card_size"><?php echo $size_display; ?></span>
                            <?php endif; ?>
                            <?php if ( get_post_type() === 'software' ) : ?>
                                <button class="quick-view-trigger-btn" data-post-id="<?php the_ID(); ?>" aria-label="<?php _e('Quick view', 'nursoft'); ?>">
                                    <svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endwhile; ?>
            </div>

            <!-- Sleek Dark Pagination -->
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
            <p style="text-align: center; color: var(--text-secondary); margin: 60px 0;"><?php _e('No content available at the moment.', 'nursoft'); ?></p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>
