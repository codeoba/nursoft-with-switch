<?php
/**
 * The template for displaying the site header
 *
 * @author  Mohamed Nurdin Mgaza <codeoba@gmail.com>
 * @country Tanzania | +687001775
 * @version 1.6.0
 * @package Nursoft
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('nursoft-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header_wrap">
    <div class="container">
        <nav class="header_nav">
            <!-- Brand Logo -->
            <a aria-label="<?php bloginfo('name'); ?> - Software Store" class="header_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <?php 
                $custom_logo_url = get_theme_mod( 'nursoft_custom_logo', '' );
                if ( ! empty( $custom_logo_url ) ) : ?>
                    <img src="<?php echo esc_url( $custom_logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" style="max-height: 38px; width: auto; object-fit: contain;" />
                <?php else : ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1366 1204.41" class="icon" style="height: 28px; width: auto; fill: currentColor; margin-right: 10px;">
                        <g>
                            <path d="M386.89 47.18h939.13l-82.81 274.95H300.76zM256.5 467.89h686.45l-82.81 274.95H170.37zm-130.39 420.7h382.98l-82.82 274.96H39.98z"/>
                        </g>
                    </svg>
                    <span>NURSOFT</span>
                <?php endif; ?>
            </a>

            <!-- Search Form Container with Live Results -->
            <div class="search_wrap">
                <form role="search" method="get" class="header_search_form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="hidden" name="post_type" value="software" />
                    <input type="text" class="search_input" id="search-input-field" placeholder="<?php esc_attr_e( 'Search software here...', 'nursoft' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
                    <button class="search_submit" type="submit" aria-label="Search button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 461.516 461.516">
                            <path d="M185.746 371.332a185.3 185.3 0 0 0 113.866-39.11L422.39 455c9.172 8.858 23.787 8.604 32.645-.568 8.641-8.947 8.641-23.131 0-32.077L332.257 299.577c62.899-80.968 48.252-197.595-32.716-260.494S101.947-9.169 39.048 71.799-9.204 269.394 71.764 332.293a185.64 185.64 0 0 0 113.982 39.039M87.095 87.059c54.484-54.485 142.82-54.486 197.305-.002s54.486 142.82.002 197.305-142.82 54.486-197.305.002l-.002-.002c-54.484-54.087-54.805-142.101-.718-196.585z"></path>
                        </svg>
                    </button>
                </form>
                <!-- Live Search AJAX results dropdown -->
                <div class="live-search-results" id="live-search-results"></div>
            </div>
            
            <a class="menu_link" href="#" id="nursoft-categories-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path d="M29 11v14a4 4 0 0 1-4 4H11a4 4 0 0 1-4-4V11a4 4 0 0 1 4-4h14a4 4 0 0 1 4 4m24-4H39a4 4 0 0 0-4 4v14a4 4 0 0 0 4 4h14a4 4 0 0 0 4-4V11a4 4 0 0 0-4-4M25 35H11a4 4 0 0 0-4 4v14a4 4 0 0 0 4 4h14a4 4 0 0 0 4-4V39a4 4 0 0 0-4-4m21 0a11 11 0 1 0 11 11 11 11 0 0 0-11-11"></path></svg>
                <span>Categories</span>
            </a>

            <!-- Theme Toggle Switcher -->
            <button class="theme_toggle_btn" id="nursoft-theme-toggle-btn" aria-label="<?php esc_attr_e( 'Toggle Light/Dark Theme', 'nursoft' ); ?>">
                <!-- Dark Mode Sun Icon -->
                <svg class="sun_icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <!-- Light Mode Moon Icon -->
                <svg class="moon_icon" viewBox="0 0 24 24" style="display:none;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            </button>
        </nav>
    </div>
</header>

<div class="menu_wrap">
    <div class="container">
        <nav class="menu_nav">
            <?php
            // 1. Fetch platform terms dynamically
            $platforms = get_terms( array(
                'taxonomy'   => 'platform',
                'hide_empty' => false,
            ) );

            $menu_items = array();

            // Append platforms (Windows, Mac, Android, Games)
            if ( ! empty( $platforms ) && ! is_wp_error( $platforms ) ) {
                foreach ( $platforms as $platform ) {
                    // Exclude Books and Courses from the shared platform terms query
                    if ( $platform->slug === 'books' || $platform->slug === 'courses' ) {
                        continue;
                    }
                    
                    $menu_items[] = array(
                        'slug'   => $platform->slug,
                        'name'   => $platform->name,
                        'url'    => get_term_link( $platform ),
                        'active' => is_tax( 'platform', $platform->term_id )
                    );
                }
            }

            // Append Books CPT dynamically if enabled in customizer
            $enable_books = get_theme_mod( 'nursoft_enable_books', true );
            if ( $enable_books && $enable_books !== '0' && $enable_books !== 'off' ) {
                $menu_items[] = array(
                    'slug'   => 'books',
                    'name'   => __( 'Books', 'nursoft' ),
                    'url'    => get_post_type_archive_link( 'book' ),
                    'active' => is_post_type_archive( 'book' ) || is_singular( 'book' ) || is_tax( 'book_cat' )
                );
            }

            // Append Courses CPT dynamically if enabled in customizer
            $enable_courses = get_theme_mod( 'nursoft_enable_courses', true );
            if ( $enable_courses && $enable_courses !== '0' && $enable_courses !== 'off' ) {
                $menu_items[] = array(
                    'slug'   => 'courses',
                    'name'   => __( 'Courses', 'nursoft' ),
                    'url'    => get_post_type_archive_link( 'course' ),
                    'active' => is_post_type_archive( 'course' ) || is_singular( 'course' ) || is_tax( 'course_cat' )
                );
            }

            // Helper to match Term slug with custom beautiful SVG icon
            foreach ( $menu_items as $item ) :
                $slug = $item['slug'];
                $url = $item['url'];
                $icon_svg = '';

                // 1. Windows SVG
                if ( $slug === 'ms-windows' || $slug === 'windows' ) {
                    $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 80v160h224V52zm256-32v192h256V16zm0 224v192l256 32V272zM0 272v160l224 28V272z"></path></svg>';
                }
                // 2. Mac Apple SVG
                elseif ( $slug === 'mac' || $slug === 'macos' ) {
                    $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.003 512.003"><path d="M351.98 0c-27.296 1.888-59.2 19.36-77.792 42.112-16.96 20.64-30.912 51.296-25.472 81.088 29.824.928 60.64-16.96 78.496-40.096 16.704-21.536 29.344-52 24.768-83.104"></path><path d="M459.852 171.776c-26.208-32.864-63.04-51.936-97.824-51.936-45.92 0-65.344 21.984-97.248 21.984-32.896 0-57.888-21.92-97.6-21.92-39.008 0-80.544 23.84-106.88 64.608-37.024 57.408-30.688 165.344 29.312 257.28 21.472 32.896 50.144 69.888 87.648 70.208 33.376.32 42.784-21.408 88-21.632 45.216-.256 53.792 21.92 87.104 21.568 37.536-.288 67.776-41.28 89.248-74.176 15.392-23.584 21.12-35.456 33.056-62.08-86.816-33.056-100.736-156.512-14.816-203.904"></path></svg>';
                }
                // 3. Android Robot SVG
                elseif ( strpos( $slug, 'android' ) !== false ) {
                    $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.007 512.007"><path d="M64.004 192.007c-17.664 0-32 14.336-32 32v128c0 17.664 14.336 32 32 32s32-14.336 32-32v-128c0-17.664-14.336-32-32-32m384 0c-17.664 0-32 14.336-32 32v128c0 17.664 14.336 32 32 32s32-14.336 32-32v-128c0-17.664-14.336-32-32-32m-320 1.856v192c0 17.664 14.336 32 32 32v62.144c0 17.664 14.336 32 32 32s32-14.336 32-32v-62.144h64v62.144c0 17.664 14.336 32 32 32s32-14.336 32-32v-62.144c17.664 0 32-14.336 32-32v-192zm0-33.856h256c0-40.32-19.008-75.84-48.128-99.296l28.48-34.528c5.632-6.816 4.672-16.896-2.144-22.528-6.848-5.6-16.896-4.672-22.528 2.144l-31.136 37.728c-16.064-7.264-33.76-11.52-52.544-11.52-19.04 0-36.96 4.416-53.184 11.904L172.516 6.023c-5.536-6.88-15.584-8.032-22.496-2.496-6.88 5.536-8 15.584-2.496 22.496l28.096 35.136c-28.832 23.456-47.616 58.784-47.616 98.848m160-80c8.832 0 16 7.168 16 16s-7.168 16-16 16-16-7.168-16-16 7.168-16 16-16m-64 0c8.832 0 16 7.168 16 16s-7.168 16-16 16-16-7.168-16-16 7.168-16 16-16"></path></svg>';
                }
                // 4. PC Games Gamepad SVG
                elseif ( $slug === 'pc-games' ) {
                    $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M512 363.78c-1.2-66.8-9.09-134.35-22.03-202.53-10.54-47.37-48.46-89.56-109.05-92.65-44.73-1.84-53.38 23.64-104.06 23.15-13.88-.09-27.75-.09-41.63 0-50.69.49-59.36-24.99-104.07-23.15-60.6 3.09-99.7 45.17-109.09 92.65C9.12 229.43 1.23 296.97.04 363.77c-.29 46.51 45.63 77.45 75.93 79.57 58.53 4.42 105.03-98.79 140.46-98.8 26.41.15 52.81.16 79.22 0 35.44 0 81.9 103.23 140.47 98.81 30.29-2.12 77.4-33.27 75.89-79.57zM190.94 233.44h-27.3v27.3c0 11.52-9.34 20.86-20.86 20.86s-20.86-9.34-20.86-20.86v-27.3h-27.3c-11.52 0-20.86-9.34-20.86-20.86s9.34-20.86 20.86-20.86h27.3v-27.3c0-11.52 9.34-20.86 20.86-20.86s20.86 9.34 20.86 20.86v27.3h27.3c11.52 0 20.86 9.34 20.86 20.86s-9.34 20.86-20.86 20.86m168.57 48.15c-16.33.44-29.91-12.46-30.35-28.78-.43-16.38 12.48-29.98 28.8-30.4 16.34-.41 29.94 12.48 30.36 28.82.41 16.34-12.48 29.94-28.81 30.36m49.25-78.85c-16.33.46-29.94-12.45-30.39-28.78-.41-16.36 12.48-29.94 28.82-30.39 16.36-.43 29.94 12.48 30.38 28.82.43 16.33-12.49 29.94-28.81 30.35"></path></svg>';
                }
                // 5. Books CPT SVG
                elseif ( $slug === 'books' ) {
                    $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8M12 3h8c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2h-8M12 3v18"/></svg>';
                }
                // 6. Courses CPT SVG
                elseif ( $slug === 'courses' ) {
                    $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 9H3V5h9v7zm9 7H3v-5h18v5z"/></svg>';
                }
                // Default SVG
                else {
                    $icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16.5c0 .38-.21.71-.53.88l-7.9 4.44a.99.99 0 0 1-.94 0l-7.9-4.44c-.32-.17-.53-.5-.53-.88V7.5c0-.38.21-.71.53-.88l7.9-4.44a.99.99 0 0 1 .94 0l7.9 4.44c.32.17.53.5.53.88v9z"/></svg>';
                }

                $active_class = $item['active'] ? 'active' : '';
                ?>
                <a class="menu_link <?php echo esc_attr( $active_class ); ?>" href="<?php echo esc_url( $url ); ?>">
                    <?php echo $icon_svg; ?>
                    <span><?php echo esc_html( $item['name'] ); ?></span>
                    
                    <?php 
                    if ( $slug === 'pc-games' || $slug === 'android-games' ) : ?>
                        <span class="menu_private">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                <path d="M2.837 20.977 1.012 9.115c-.135-.876.863-1.474 1.572-.942l5.686 4.264a1.36 1.36 0 0 0 1.945-.333l4.734-7.1c.5-.75 1.602-.75 2.102 0l4.734 7.1a1.36 1.36 0 0 0 1.945-.333l5.686-4.264c.71-.532 1.707.066 1.572.942l-1.825 11.862zm24.953 6.582H4.21a1.373 1.373 0 0 1-1.373-1.373v-3.015h26.326v3.015c0 .758-.615 1.373-1.373 1.373"></path>
                            </svg>
                        </span>
                    <?php endif; ?>
                </a>
            <?php 
            endforeach;
            ?>
        </nav>
    </div>
</div>

<?php
// Fetch all software categories
$categories = get_terms( array(
    'taxonomy'   => 'software_cat',
    'hide_empty' => false,
) );
?>
<!-- Dynamic Categories Popup Modal -->
<div class="nursoft-categories-modal" id="nursoft-categories-modal">
    <div class="categories-modal-overlay" id="categories-modal-overlay"></div>
    <div class="categories-modal-content">
        <div class="categories-modal-header">
            <h4>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px; color: var(--accent-blue);"><path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/></svg>
                <span><?php _e('Software Categories', 'nursoft'); ?></span>
            </h4>
            <button class="categories-modal-close" id="categories-modal-close" aria-label="Close categories popup">&times;</button>
        </div>
        <div class="categories-grid">
            <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                <?php foreach ( $categories as $cat ) : 
                    $count = $cat->count;
                    ?>
                    <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="category-grid-card">
                        <div class="cat-card-info">
                            <span class="cat-card-name"><?php echo esc_html( $cat->name ); ?></span>
                            <span class="cat-card-count"><?php echo sprintf( _n( '%s App', '%s Apps', $count, 'nursoft' ), number_format( $count ) ); ?></span>
                        </div>
                        <div class="cat-card-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <p style="grid-column: 1 / -1; text-align: center; color: var(--text-secondary);"><?php _e('No categories found.', 'nursoft'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script to handle Categories popup trigger -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoriesBtn = document.getElementById('nursoft-categories-btn');
    const categoriesModal = document.getElementById('nursoft-categories-modal');
    const categoriesOverlay = document.getElementById('categories-modal-overlay');
    const categoriesClose = document.getElementById('categories-modal-close');

    if (categoriesBtn && categoriesModal) {
        categoriesBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Show modal with smooth fade-in
            categoriesModal.style.display = 'flex';
            setTimeout(() => {
                categoriesModal.classList.add('open');
            }, 10);
            document.body.style.overflow = 'hidden'; // Disable background scrolling
        });

        function closeModal() {
            categoriesModal.classList.remove('open');
            setTimeout(() => {
                categoriesModal.style.display = 'none';
            }, 300); // Wait for transition to complete
            document.body.style.overflow = ''; // Re-enable background scrolling
        }

        if (categoriesClose) {
            categoriesClose.addEventListener('click', closeModal);
        }

        if (categoriesOverlay) {
            categoriesOverlay.addEventListener('click', closeModal);
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && categoriesModal.classList.contains('open')) {
                closeModal();
            }
        });
    }

    // Premium Theme Toggle Button logic
    const themeBtn = document.getElementById('nursoft-theme-toggle-btn');
    if (themeBtn) {
        themeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            // Set attribute on html element
            document.documentElement.setAttribute('data-theme', newTheme);
            // Persist the choice
            localStorage.setItem('nursoft-theme', newTheme);
        });
    }
});
</script>

<?php 
// 1. Homepage Top Ad Slot
if ( is_front_page() || is_home() ) {
    nursoft_render_ad( 'nursoft_ad_home_top' );
}
?>

