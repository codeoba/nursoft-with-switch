<?php
/**
 * The template for displaying Book post type archives
 *
 * @author  Mohamed Nurdin Mgaza <codeoba@gmail.com>
 * @country Tanzania | +687001775
 * @version 1.6.0
 * @package Nursoft
 */

get_header();
?>

<main class="container main_content">
    <div class="software_layout">
        <div class="software_main_col">
            <section class="products_section">
        <!-- Archive Header Title -->
        <div class="section-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
            <div>
                <h4 style="font-family: var(--font-heading); font-size: 24px; font-weight: 700; margin: 0; color: var(--text-primary);">
                    <?php post_type_archive_title(); ?>
                </h4>
                <span style="font-size: 13px; color: var(--text-secondary);"><?php _e('Explore E-Books & Guides', 'nursoft'); ?></span>
            </div>
            
            <!-- Sorting Dropdown -->
            <div class="archive_sort_wrap" style="position: relative; display: inline-block;">
                <select onchange="location = this.value;" style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 16px; font-size: 13.5px; font-weight: 600; color: var(--text-secondary); cursor: pointer; transition: all var(--transition-fast); outline: none; border-color: rgba(255, 255, 255, 0.1);">
                    <?php
                    $sort = isset( $_GET['sort'] ) ? sanitize_text_field( $_GET['sort'] ) : 'updated';
                    ?>
                    <option value="<?php echo esc_url( add_query_arg( 'sort', 'updated' ) ); ?>" <?php selected( $sort, 'updated' ); ?>><?php _e('Recently Updated', 'nursoft'); ?></option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort', 'new' ) ); ?>" <?php selected( $sort, 'new' ); ?>><?php _e('Newest Releases', 'nursoft'); ?></option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort', 'downloads' ) ); ?>" <?php selected( $sort, 'downloads' ); ?>><?php _e('Most Downloaded', 'nursoft'); ?></option>
                    <option value="<?php echo esc_url( add_query_arg( 'sort', 'rating' ) ); ?>" <?php selected( $sort, 'rating' ); ?>><?php _e('Highest Rated', 'nursoft'); ?></option>
                </select>
            </div>
        </div>

        <?php if ( have_posts() ) : ?>
            <!-- Product Grid -->
            <div class="product-grid" style="margin-bottom: 40px;">
                <?php
                while ( have_posts() ) : the_post();
                    $badge     = get_post_meta( get_the_ID(), '_nursoft_badge', true );
                    $downloads = get_post_meta( get_the_ID(), '_nursoft_downloads', true );
                    $reputation= get_post_meta( get_the_ID(), '_nursoft_reputation', true );
                    $size      = get_post_meta( get_the_ID(), '_nursoft_size', true );
                    
                    $downloads_formatted = $downloads !== '' ? number_format( intval($downloads) ) : '0';
                    $reputation_value    = $reputation !== '' ? floatval($reputation) : 4.5;
                    $size_display        = $size !== '' ? esc_html($size) : 'N/A';
                    
                    $author   = get_post_meta( get_the_ID(), '_nursoft_book_author', true );
                    $pages    = get_post_meta( get_the_ID(), '_nursoft_book_pages', true );
                    $format   = get_post_meta( get_the_ID(), '_nursoft_book_format', true );
                    $language = get_post_meta( get_the_ID(), '_nursoft_book_language', true );
                    
                    $book_cats = wp_get_post_terms( get_the_ID(), 'book_cat' );
                    $cat_link_html = '';
                    if ( ! empty( $book_cats ) && ! is_wp_error( $book_cats ) ) {
                        $cat_link_html = '<a class="card_category" href="' . esc_url( get_term_link( $book_cats[0] ) ) . '">' . esc_html( $book_cats[0]->name ) . '</a>';
                    } else {
                        $cat_link_html = '<span class="card_category">' . __('General Ebooks', 'nursoft') . '</span>';
                    }
                    ?>
                    <!-- Ebook Custom Card -->
                    <div class="card_wrap">
                        <?php echo nursoft_render_card_badge( $badge ); ?>
                        <div class="card_header">
                            <a class="card_icon_link" href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'card_icon_img', 'alt' => get_the_title() ) ); ?>
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" class="card_icon_img" alt="Book cover" />
                                <?php endif; ?>
                            </a>
                            <div class="card_info">
                                <a class="card_title_link" href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                                <p class="card_desc" style="margin-bottom: 4px;"><?php echo get_the_excerpt(); ?></p>
                                <div style="font-size: 12.5px; color: var(--accent-blue); font-weight: 600; margin-bottom: 4px;">
                                    <?php if($author) printf( __('By %s', 'nursoft'), esc_html($author) ); ?>
                                    <?php if($language) echo ' • ' . esc_html($language); ?>
                                </div>
                                <?php echo $cat_link_html; ?>
                            </div>
                        </div>
                        <div class="card_data">
                            <span class="card_primary card_books" style="background-color: rgba(94, 114, 228, 0.1); color: #5e72e4; border: 1px solid rgba(94, 114, 228, 0.25); padding: 4px 10px; border-radius: 20px;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="12" height="12" fill="currentColor" style="vertical-align: middle; margin-right: 3px;"><path d="M12 3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8M12 3h8c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2h-8M12 3v18"/></svg>
                                <span><?php _e('Ebook', 'nursoft'); ?></span>
                            </span>
                            <div class="card_meta-data">
                                <div class="card_meta-item" title="<?php echo $downloads_formatted; ?> downloads">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -16 512 512"><path d="M413.492 128.91C396.2 42.145 311.844-14.172 225.078 3.121 161.618 15.77 111.996 65.36 99.308 128.813 37.79 135.903-6.34 191.52.747 253.043c6.524 56.621 54.48 99.34 111.477 99.3h80.093c8.848 0 16.02-7.171 16.02-16.019s-7.172-16.02-16.02-16.02h-80.093c-44.239-.261-79.883-36.331-79.625-80.566.261-44.238 36.332-79.886 80.57-79.625 8.164 0 15.023-6.14 15.922-14.258 8.133-70.304 71.723-120.707 142.031-112.574 59.11 6.836 105.738 53.465 112.574 112.574 1.344 8.262 8.5 14.313 16.867 14.258 44.239 0 80.098 35.86 80.098 80.098 0 44.234-35.86 80.094-80.098 80.094H320.47c-8.848 0-16.02 7.172-16.02 16.02s7.172 16.019 16.02 16.019h80.097c61.926-.387 111.817-50.903 111.434-112.828-.352-56.395-42.531-103.754-98.508-110.606m0 0"></path><path d="m313.02 385.184-40.61 40.62V224.192c0-8.847-7.172-16.02-16.015-16.02-8.848 0-16.02 7.173-16.02 16.02v201.614l-40.61-40.621c-6.144-6.368-16.288-6.543-22.652-.395-6.363 6.145-6.539 16.285-.394 22.649.133.136.261.265.394.394l67.938 67.953a16.1 16.1 0 0 0 5.176 3.461 15.83 15.83 0 0 0 12.335 0 16 16 0 0 0 5.172-3.46l67.938-67.954c6.363-6.144 6.539-16.285.394-22.648-6.148-6.364-16.289-6.54-22.652-.395-.133.129-.266.258-.394.395m0 0"></path></svg>
                                    <span class="card_meta-text"><?php echo $downloads_formatted; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="card_subdata">
                            <?php echo nursoft_render_stars( $reputation_value ); ?>
                            <span class="card_size"><?php echo $size_display; ?><?php if($format) echo ' ' . esc_html($format); ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Sleek Dark Mode Pagination -->
            <div class="nursoft-pagination">
                <?php
                echo paginate_links( array(
                    'mid_size'  => 2,
                    'prev_text' => '&laquo; Previous',
                    'next_text' => 'Next &raquo;',
                    'type'      => 'plain',
                ) );
                ?>
            </div>

        <?php else : ?>
            <p style="text-align: center; color: var(--text-secondary); margin: 60px 0;"><?php _e('No books found in this archive.', 'nursoft'); ?></p>
        <?php endif; ?>
            </section>
        </div>
        <!-- Right / Sidebar Column -->
        <?php get_sidebar(); ?>
    </div>
</main>

<?php get_footer(); ?>
