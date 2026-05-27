<?php
/**
 * Nursoft WordPress Theme — functions and definitions
 *
 * @author  Mohamed Nurdin Mgaza
 * @email   codeoba@gmail.com
 * @phone   +687001775
 * @country Tanzania
 * @version 1.7.0
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 * @package Nursoft
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/* ==========================================================================
   1. THEME SETUP & ENQUEUE ASSETS
   ========================================================================== */
function nursoft_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Switch default core markup for search form, comment form, etc., to output valid HTML5.
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );
}
add_action( 'after_setup_theme', 'nursoft_setup' );

/**
 * Register widget area.
 */
function nursoft_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'nursoft' ),
        'id'            => 'main-sidebar',
        'description'   => __( 'Widgets added here will appear in the main listing sidebar on PC/Desktop.', 'nursoft' ),
        'before_widget' => '<div id="%1$s" class="widget_box %2$s" style="margin-bottom: 25px;">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget_title">',
        'after_title'   => '</h5>',
    ) );
}
add_action( 'widgets_init', 'nursoft_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function nursoft_scripts() {
    // Load Fonts from Google (Outfit & Inter)
    wp_enqueue_style( 'nursoft-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap', array(), null );

    // Theme main stylesheet
    wp_enqueue_style( 'nursoft-style', get_stylesheet_uri(), array( 'nursoft-fonts' ), '1.9.8' );

    // Enqueue Live Search JS
    wp_enqueue_script( 'nursoft-live-search', get_template_directory_uri() . '/assets/js/live-search.js', array(), '1.9.8', true );
    wp_localize_script( 'nursoft-live-search', 'nursoftLiveSearch', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'nursoft-search-nonce' )
    ) );

    // Enqueue Download Handler JS
    wp_enqueue_script( 'nursoft-download-handler', get_template_directory_uri() . '/assets/js/download-handler.js', array(), '1.9.8', true );
    wp_localize_script( 'nursoft-download-handler', 'nursoftDownload', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ) );

    // Enqueue Quick View JS
    wp_enqueue_script( 'nursoft-quick-view', get_template_directory_uri() . '/assets/js/quick-view.js', array(), '1.9.8', true );
    wp_localize_script( 'nursoft-quick-view', 'nursoftQuickView', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'nursoft-quickview-nonce' )
    ) );
}
add_action( 'wp_enqueue_scripts', 'nursoft_scripts' );

/* ==========================================================================
   2. CUSTOM POST TYPE & TAXONOMIES
   ========================================================================== */
function nursoft_register_software_cpt() {
    // 1. Software CPT
    $software_labels = array(
        'name'                  => _x( 'Software', 'Post Type General Name', 'nursoft' ),
        'singular_name'         => _x( 'Software Item', 'Post Type Singular Name', 'nursoft' ),
        'menu_name'             => __( 'Software', 'nursoft' ),
        'name_admin_bar'        => __( 'Software', 'nursoft' ),
        'all_items'             => __( 'All Software', 'nursoft' ),
        'add_new_item'          => __( 'Add New Software', 'nursoft' ),
        'add_new'               => __( 'Add New', 'nursoft' ),
        'new_item'              => __( 'New Software', 'nursoft' ),
        'edit_item'             => __( 'Edit Software', 'nursoft' ),
        'update_item'           => __( 'Update Software', 'nursoft' ),
        'view_item'             => __( 'View Software', 'nursoft' ),
        'search_items'          => __( 'Search Software', 'nursoft' ),
        'not_found'             => __( 'Not found', 'nursoft' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'nursoft' ),
    );

    $software_args = array(
        'label'                 => __( 'Software Item', 'nursoft' ),
        'description'           => __( 'Custom CPT for listing downloadable software items.', 'nursoft' ),
        'labels'                => $software_labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'taxonomies'            => array( 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-download',
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type( 'software', $software_args );

    // 2. Book CPT (New)
    $enable_books = get_theme_mod( 'nursoft_enable_books', true );
    if ( $enable_books && $enable_books !== '0' && $enable_books !== 'off' ) {
        $book_labels = array(
            'name'                  => _x( 'Books', 'Post Type General Name', 'nursoft' ),
            'singular_name'         => _x( 'Book Item', 'Post Type Singular Name', 'nursoft' ),
            'menu_name'             => __( 'Books', 'nursoft' ),
            'name_admin_bar'        => __( 'Book', 'nursoft' ),
            'all_items'             => __( 'All Books', 'nursoft' ),
            'add_new_item'          => __( 'Add New Book', 'nursoft' ),
            'add_new'               => __( 'Add New', 'nursoft' ),
            'new_item'              => __( 'New Book', 'nursoft' ),
            'edit_item'             => __( 'Edit Book', 'nursoft' ),
            'update_item'           => __( 'Update Book', 'nursoft' ),
            'view_item'             => __( 'View Book', 'nursoft' ),
            'search_items'          => __( 'Search Books', 'nursoft' ),
            'not_found'             => __( 'No books found', 'nursoft' ),
            'not_found_in_trash'    => __( 'No books found in Trash', 'nursoft' ),
        );

        $book_args = array(
            'label'                 => __( 'Book Item', 'nursoft' ),
            'description'           => __( 'Custom CPT for listing books and PDFs.', 'nursoft' ),
            'labels'                => $book_labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
            'taxonomies'            => array( 'post_tag' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 6,
            'menu_icon'             => 'dashicons-book-alt',
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type( 'book', $book_args );
    }

    // 3. Course CPT (New)
    $enable_courses = get_theme_mod( 'nursoft_enable_courses', true );
    if ( $enable_courses && $enable_courses !== '0' && $enable_courses !== 'off' ) {
        $course_labels = array(
            'name'                  => _x( 'Courses', 'Post Type General Name', 'nursoft' ),
            'singular_name'         => _x( 'Course Item', 'Post Type Singular Name', 'nursoft' ),
            'menu_name'             => __( 'Courses', 'nursoft' ),
            'name_admin_bar'        => __( 'Course', 'nursoft' ),
            'all_items'             => __( 'All Courses', 'nursoft' ),
            'add_new_item'          => __( 'Add New Course', 'nursoft' ),
            'add_new'               => __( 'Add New', 'nursoft' ),
            'new_item'              => __( 'New Course', 'nursoft' ),
            'edit_item'             => __( 'Edit Course', 'nursoft' ),
            'update_item'           => __( 'Update Course', 'nursoft' ),
            'view_item'             => __( 'View Course', 'nursoft' ),
            'search_items'          => __( 'Search Courses', 'nursoft' ),
            'not_found'             => __( 'No courses found', 'nursoft' ),
            'not_found_in_trash'    => __( 'No courses found in Trash', 'nursoft' ),
        );

        $course_args = array(
            'label'                 => __( 'Course Item', 'nursoft' ),
            'description'           => __( 'Custom CPT for listing video courses and bootcamps.', 'nursoft' ),
            'labels'                => $course_labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
            'taxonomies'            => array( 'post_tag' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 7,
            'menu_icon'             => 'dashicons-welcome-learn-more',
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type( 'course', $course_args );
    }
}
add_action( 'init', 'nursoft_register_software_cpt', 0 );

/**
 * Register Taxonomies: Platform & Specific Category Taxonomies
 */
function nursoft_register_taxonomies() {
    // 1. Shared Platform Taxonomy (Windows, Mac, Android, Books, Courses)
    $platform_labels = array(
        'name'              => _x( 'Platforms', 'taxonomy general name', 'nursoft' ),
        'singular_name'     => _x( 'Platform', 'taxonomy singular name', 'nursoft' ),
        'search_items'      => __( 'Search Platforms', 'nursoft' ),
        'all_items'         => __( 'All Platforms', 'nursoft' ),
        'parent_item'       => __( 'Parent Platform', 'nursoft' ),
        'parent_item_colon' => __( 'Parent Platform:', 'nursoft' ),
        'edit_item'         => __( 'Edit Platform', 'nursoft' ),
        'update_item'       => __( 'Update Platform', 'nursoft' ),
        'add_new_item'      => __( 'Add New Platform', 'nursoft' ),
        'new_item_name'     => __( 'New Platform Name', 'nursoft' ),
        'menu_name'         => __( 'Platforms', 'nursoft' ),
    );

    $platform_args = array(
        'hierarchical'      => true,
        'labels'            => $platform_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'platform' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'platform', array( 'software' ), $platform_args );

    // 2. Software Category Taxonomy (Graphics, Utilities, PDF, etc. - ONLY for software CPT)
    $cat_labels = array(
        'name'              => _x( 'Software Categories', 'taxonomy general name', 'nursoft' ),
        'singular_name'     => _x( 'Software Category', 'taxonomy singular name', 'nursoft' ),
        'search_items'      => __( 'Search Categories', 'nursoft' ),
        'all_items'         => __( 'All Categories', 'nursoft' ),
        'parent_item'       => __( 'Parent Category', 'nursoft' ),
        'parent_item_colon' => __( 'Parent Category:', 'nursoft' ),
        'edit_item'         => __( 'Edit Category', 'nursoft' ),
        'update_item'       => __( 'Update Category', 'nursoft' ),
        'add_new_item'      => __( 'Add New Category', 'nursoft' ),
        'new_item_name'     => __( 'New Category Name', 'nursoft' ),
        'menu_name'         => __( 'Software Categories', 'nursoft' ),
    );

    $cat_args = array(
        'hierarchical'      => true,
        'labels'            => $cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'software-cat' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'software_cat', array( 'software' ), $cat_args );

    // 3. Book Category Taxonomy (Fiction, Non-Fiction, etc. - ONLY for book CPT)
    $enable_books = get_theme_mod( 'nursoft_enable_books', true );
    if ( $enable_books && $enable_books !== '0' && $enable_books !== 'off' ) {
        $book_cat_labels = array(
            'name'              => _x( 'Book Categories', 'taxonomy general name', 'nursoft' ),
            'singular_name'     => _x( 'Book Category', 'taxonomy singular name', 'nursoft' ),
            'search_items'      => __( 'Search Book Categories', 'nursoft' ),
            'all_items'         => __( 'All Book Categories', 'nursoft' ),
            'parent_item'       => __( 'Parent Book Category', 'nursoft' ),
            'parent_item_colon' => __( 'Parent Book Category:', 'nursoft' ),
            'edit_item'         => __( 'Edit Book Category', 'nursoft' ),
            'update_item'       => __( 'Update Book Category', 'nursoft' ),
            'add_new_item'      => __( 'Add New Book Category', 'nursoft' ),
            'new_item_name'     => __( 'New Book Category Name', 'nursoft' ),
            'menu_name'         => __( 'Book Categories', 'nursoft' ),
        );

        $book_cat_args = array(
            'hierarchical'      => true,
            'labels'            => $book_cat_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'book-cat' ),
            'show_in_rest'      => true,
        );
        register_taxonomy( 'book_cat', array( 'book' ), $book_cat_args );
    }

    // 4. Course Category Taxonomy (Web Dev, Design, etc. - ONLY for course CPT)
    $enable_courses = get_theme_mod( 'nursoft_enable_courses', true );
    if ( $enable_courses && $enable_courses !== '0' && $enable_courses !== 'off' ) {
        $course_cat_labels = array(
            'name'              => _x( 'Course Categories', 'taxonomy general name', 'nursoft' ),
            'singular_name'     => _x( 'Course Category', 'taxonomy singular name', 'nursoft' ),
            'search_items'      => __( 'Search Course Categories', 'nursoft' ),
            'all_items'         => __( 'All Course Categories', 'nursoft' ),
            'parent_item'       => __( 'Parent Course Category', 'nursoft' ),
            'parent_item_colon' => __( 'Parent Course Category:', 'nursoft' ),
            'edit_item'         => __( 'Edit Course Category', 'nursoft' ),
            'update_item'       => __( 'Update Course Category', 'nursoft' ),
            'add_new_item'      => __( 'Add New Course Category', 'nursoft' ),
            'new_item_name'     => __( 'New Course Category Name', 'nursoft' ),
            'menu_name'         => __( 'Course Categories', 'nursoft' ),
        );

        $course_cat_args = array(
            'hierarchical'      => true,
            'labels'            => $course_cat_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'course-cat' ),
            'show_in_rest'      => true,
        );
        register_taxonomy( 'course_cat', array( 'course' ), $course_cat_args );
    }
}
add_action( 'init', 'nursoft_register_taxonomies', 0 );

/**
 * Unified sorting interceptor for archive queries (Platforms, Categories, and Search)
 */
function nursoft_archive_sorting_pre_get_posts( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        // 1. Ensure all CPTs display in platform taxonomies
        if ( is_tax( 'platform' ) ) {
            $query->set( 'post_type', array( 'software', 'book', 'course' ) );
        }

        // 2. Intercept sorting parameters
        if ( is_tax( 'platform' ) || is_tax( 'software_cat' ) || is_tax( 'book_cat' ) || is_tax( 'course_cat' ) || is_post_type_archive( array( 'software', 'book', 'course' ) ) || is_archive() ) {
            $sort = isset( $_GET['sort'] ) ? sanitize_text_field( $_GET['sort'] ) : 'updated';
            
            if ( $sort === 'new' ) {
                $query->set( 'orderby', 'date' );
                $query->set( 'order', 'DESC' );
            } elseif ( $sort === 'updated' ) {
                $query->set( 'orderby', 'modified' );
                $query->set( 'order', 'DESC' );
            } elseif ( $sort === 'downloads' ) {
                $query->set( 'meta_key', '_nursoft_downloads' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
            } elseif ( $sort === 'rating' ) {
                $query->set( 'meta_key', '_nursoft_reputation' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
            }
        }
    }
}
add_action( 'pre_get_posts', 'nursoft_archive_sorting_pre_get_posts', 20 );

function nursoft_auto_init_platforms_and_categories() {
    // 0. Clean up old legacy Book/Course terms if they exist in the 'software_cat' taxonomy
    $legacy_terms_to_delete = array(
        'books', 'courses', 'technology-programming', 'business-finance', 'science-mathematics', 
        'self-help-development', 'novels-literature', 'biography-history', 'health-wellness', 
        'art-graphic-design', 'kids-education', 'languages-reference',
        'web-development', 'mobile-app-development', 'data-science-ai', 'graphic-design-ui-ux',
        'digital-marketing', 'python-backend', 'photography-video', 'business-entrepreneur',
        'language-learning', 'personal-finance'
    );
    foreach ( $legacy_terms_to_delete as $term_slug ) {
        $term = get_term_by( 'slug', $term_slug, 'software_cat' );
        if ( $term ) {
            wp_delete_term( $term->term_id, 'software_cat' );
        }
    }

    // 2. Pre-populate Book Categories in 'book_cat'
    $book_subs = array(
        'technology-programming'  => 'Technology & Programming',
        'business-finance'        => 'Business & Finance',
        'science-mathematics'     => 'Science & Mathematics',
        'self-help-development'   => 'Self-Help & Personal Development',
        'novels-literature'       => 'Novels & Literature',
        'biography-history'       => 'Biography & History',
        'health-wellness'         => 'Health & Wellness',
        'art-graphic-design'      => 'Art & Graphic Design',
        'kids-education'          => 'Kids & Education',
        'languages-reference'     => 'Languages & Reference',
    );
    foreach ( $book_subs as $slug => $name ) {
        $term = get_term_by( 'slug', $slug, 'book_cat' );
        if ( ! $term ) {
            wp_insert_term( $name, 'book_cat', array( 'slug' => $slug ) );
        }
    }

    // 3. Pre-populate Course Categories in 'course_cat'
    $course_subs = array(
        'web-development'         => 'Web Development',
        'mobile-app-development'  => 'Mobile App Development',
        'data-science-ai'         => 'Data Science & AI',
        'graphic-design-ui-ux'    => 'Graphic Design & UI/UX',
        'digital-marketing'       => 'Digital Marketing',
        'python-backend'          => 'Python & Backend',
        'photography-video'       => 'Photography & Video Editing',
        'business-entrepreneur'   => 'Business & Entrepreneurship',
        'language-learning'       => 'Language Learning',
        'personal-finance'        => 'Personal Finance',
    );
    foreach ( $course_subs as $slug => $name ) {
        $term = get_term_by( 'slug', $slug, 'course_cat' );
        if ( ! $term ) {
            wp_insert_term( $name, 'course_cat', array( 'slug' => $slug ) );
        }
    }

    // 4. Pre-populate Software Categories in 'software_cat'
    $software_subs = array(
        'graphics-design'       => 'Graphics & Design',
        'office-pdf'            => 'Office & PDF Tools',
        'multimedia-audio'      => 'Multimedia & Audio',
        'video-editors'         => 'Video Editors',
        'operating-systems'     => 'Operating Systems',
        'development-ides'      => 'Development & IDEs',
        'internet-downloaders'  => 'Internet & Downloaders',
        'antivirus-security'    => 'Antivirus & Security',
        'system-utilities'      => 'System Utilities',
        '3d-modeling-cad'       => '3D Modeling & CAD',
    );
    foreach ( $software_subs as $slug => $name ) {
        $term = get_term_by( 'slug', $slug, 'software_cat' );
        if ( ! $term ) {
            wp_insert_term( $name, 'software_cat', array( 'slug' => $slug ) );
        }
    }
}
add_action( 'admin_init', 'nursoft_auto_init_platforms_and_categories' );

/**
 * Add Platform Selection field to the ADD Software Category screen
 */
function nursoft_software_cat_add_form_fields( $taxonomy ) {
    $platforms = get_terms( array(
        'taxonomy'   => 'platform',
        'hide_empty' => false,
    ) );
    ?>
    <div class="form-field term-platforms-wrap">
        <label><?php _e( 'Associated Platforms', 'nursoft' ); ?></label>
        <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fff; border-radius: 4px;">
            <?php if ( ! empty( $platforms ) && ! is_wp_error( $platforms ) ) : ?>
                <?php foreach ( $platforms as $platform ) : ?>
                    <label style="display: block; margin-bottom: 6px; font-weight: normal;">
                        <input type="checkbox" name="associated_platforms[]" value="<?php echo esc_attr( $platform->term_id ); ?>" />
                        <?php echo esc_html( $platform->name ); ?>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p style="color: #999; margin: 0;"><?php _e( 'No platforms found. Please add platforms first.', 'nursoft' ); ?></p>
            <?php endif; ?>
        </div>
        <p class="description"><?php _e( 'Select which platforms this category or sub-category belongs to. Used to display correct sub-categories on platform archive sidebars.', 'nursoft' ); ?></p>
    </div>
    <?php
}
add_action( 'software_cat_add_form_fields', 'nursoft_software_cat_add_form_fields', 10, 1 );

/**
 * Add Platform Selection field to the EDIT Software Category screen
 */
function nursoft_software_cat_edit_form_fields( $term, $taxonomy ) {
    $platforms = get_terms( array(
        'taxonomy'   => 'platform',
        'hide_empty' => false,
    ) );
    $associated = get_term_meta( $term->term_id, '_nursoft_associated_platforms', false );
    if ( ! is_array( $associated ) ) {
        $associated = array();
    }
    ?>
    <tr class="form-field term-platforms-wrap">
        <th scope="row"><label><?php _e( 'Associated Platforms', 'nursoft' ); ?></label></th>
        <td>
            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fff; border-radius: 4px; max-width: 400px;">
                <?php if ( ! empty( $platforms ) && ! is_wp_error( $platforms ) ) : ?>
                    <?php foreach ( $platforms as $platform ) : ?>
                        <label style="display: block; margin-bottom: 6px; font-weight: normal;">
                            <input type="checkbox" name="associated_platforms[]" value="<?php echo esc_attr( $platform->term_id ); ?>" <?php checked( in_array( $platform->term_id, $associated ) ); ?> />
                            <?php echo esc_html( $platform->name ); ?>
                        </label>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p style="color: #999; margin: 0;"><?php _e( 'No platforms found. Please add platforms first.', 'nursoft' ); ?></p>
                <?php endif; ?>
            </div>
            <p class="description"><?php _e( 'Select which platforms this category or sub-category belongs to. Used to display correct sub-categories on platform archive sidebars.', 'nursoft' ); ?></p>
        </td>
    </tr>
    <?php
}
add_action( 'software_cat_edit_form_fields', 'nursoft_software_cat_edit_form_fields', 10, 2 );

/**
 * Save Associated Platforms when Category is created/edited
 */
function nursoft_save_software_cat_platforms( $term_id ) {
    delete_term_meta( $term_id, '_nursoft_associated_platforms' );
    if ( isset( $_POST['associated_platforms'] ) && is_array( $_POST['associated_platforms'] ) ) {
        $platforms = array_map( 'intval', $_POST['associated_platforms'] );
        foreach ( $platforms as $p_id ) {
            add_term_meta( $term_id, '_nursoft_associated_platforms', $p_id );
        }
    }
}
add_action( 'created_software_cat', 'nursoft_save_software_cat_platforms', 10, 1 );
add_action( 'edited_software_cat', 'nursoft_save_software_cat_platforms', 10, 1 );

/* ==========================================================================
   3. CUSTOM FIELDS (METABOX) FOR SOFTWARE DATA
   ========================================================================== */
/**
 * Enqueue media library scripts on Software admin screens
 */
function nursoft_admin_enqueue_media_scripts() {
    global $pagenow, $post_type;
    if ( ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) && $post_type == 'software' ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'nursoft_admin_enqueue_media_scripts' );

function nursoft_add_software_metaboxes() {
    add_meta_box(
        'nursoft_software_details',
        __( 'Software Specifications (Nursoft/FileCR)', 'nursoft' ),
        'nursoft_software_meta_box_html',
        'software',
        'normal',
        'high'
    );
    add_meta_box(
        'nursoft_book_details',
        __( 'Book Specifications (Nursoft/FileCR)', 'nursoft' ),
        'nursoft_book_meta_box_html',
        'book',
        'normal',
        'high'
    );
    add_meta_box(
        'nursoft_course_details',
        __( 'Course Specifications (Nursoft/FileCR)', 'nursoft' ),
        'nursoft_course_meta_box_html',
        'course',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'nursoft_add_software_metaboxes' );

function nursoft_software_meta_box_html( $post ) {
    // Get existing values
    $size         = get_post_meta( $post->ID, '_nursoft_size', true );
    $downloads    = get_post_meta( $post->ID, '_nursoft_downloads', true );
    $reputation   = get_post_meta( $post->ID, '_nursoft_reputation', true );
    $badge        = get_post_meta( $post->ID, '_nursoft_badge', true );
    $requirements = get_post_meta( $post->ID, '_nursoft_requirements', true );
    $instructions = get_post_meta( $post->ID, '_nursoft_instructions', true );
    
    // Load dynamic versions list
    $versions = get_post_meta( $post->ID, '_nursoft_versions_list', true );
    if ( ! is_array( $versions ) ) {
        $versions = array();
    }
    
    // Automatic import of legacy single fields if list is empty
    if ( empty( $versions ) ) {
        $old_version = get_post_meta( $post->ID, '_nursoft_version', true );
        $old_direct  = get_post_meta( $post->ID, '_nursoft_direct_download_url', true );
        if ( empty($old_direct) ) {
            $old_direct = get_post_meta( $post->ID, '_nursoft_download_url', true );
        }
        $old_torrent = get_post_meta( $post->ID, '_nursoft_torrent_download_url', true );
        $old_history = get_post_meta( $post->ID, '_nursoft_version_history', true );
        
        if ( ! empty($old_version) || ! empty($old_direct) || ! empty($old_torrent) ) {
            $versions[] = array(
                'version'     => $old_version !== '' ? $old_version : 'v1.0',
                'date'        => '',
                'direct_url'  => $old_direct,
                'torrent_url' => $old_torrent,
                'changelog'   => $old_history
            );
        }
    }
    
    // Screenshots Meta (Media Library IDs and External URLs)
    $screenshots_ids = get_post_meta( $post->ID, '_nursoft_screenshots_ids', true );
    $screenshots_ext = get_post_meta( $post->ID, '_nursoft_screenshots_ext', true );
    
    // Backwards compatibility fallback to old field
    $old_screenshots = get_post_meta( $post->ID, '_nursoft_screenshots', true );
    if ( empty($screenshots_ext) && ! empty($old_screenshots) && ! is_numeric($old_screenshots) ) {
        $screenshots_ext = $old_screenshots;
    }

    // Default values if empty
    if ( $downloads === '' ) $downloads = 0;
    if ( $reputation === '' ) $reputation = '4.5';
    if ( $badge === '' ) $badge = 'none';

    // CSRF Nonce
    wp_nonce_field( 'nursoft_save_software_meta', 'nursoft_software_meta_nonce' );
    ?>
    <style>
        .nursoft-meta-row { display: flex; margin-bottom: 15px; align-items: flex-start; }
        .nursoft-meta-label { width: 180px; font-weight: bold; padding-top: 6px; }
        .nursoft-meta-field { flex-grow: 1; }
        .nursoft-meta-field input[type="text"], .nursoft-meta-field input[type="url"], .nursoft-meta-field select, .nursoft-meta-field textarea { width: 100%; max-width: 600px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .nursoft-meta-field textarea { height: 100px; font-family: monospace; }
        .nursoft-meta-desc { font-style: italic; font-size: 12px; color: #666; margin-top: 4px; }
        
        /* Screenshots Uploader Style */
        .nursoft-uploader-box { background: #f9f9f9; border: 1px dashed #ccc; border-radius: 6px; padding: 20px; max-width: 600px; text-align: center; }
        .nursoft-preview-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
        .nursoft-preview-item { position: relative; width: 90px; height: 90px; border: 1px solid #ddd; border-radius: 6px; overflow: hidden; background: #fff; }
        .nursoft-preview-item img { width: 100%; height: 100%; object-fit: cover; }
        .nursoft-preview-item .remove-img { position: absolute; top: 4px; right: 4px; background: rgba(220, 53, 69, 0.9); color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; font-weight: bold; border: none; }
        .nursoft-preview-item .remove-img:hover { background: #dc3545; }
    </style>
    <div style="padding: 10px;">
        <!-- Size -->
        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_size"><?php _e('File Size', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_size" name="nursoft_size" value="<?php echo esc_attr( $size ); ?>" placeholder="e.g. 1.2 GB or 45.2 MB" />
            </div>
        </div>

        <!-- Downloads -->
        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_downloads"><?php _e('Download Count', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="number" id="nursoft_downloads" name="nursoft_downloads" value="<?php echo esc_attr( $downloads ); ?>" min="0" style="width: 150px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" />
            </div>
        </div>

        <!-- Reputation -->
        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_reputation"><?php _e('Reputation Rating (1.0 - 5.0)', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_reputation" name="nursoft_reputation" value="<?php echo esc_attr( $reputation ); ?>" placeholder="e.g. 4.6" style="width: 100px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" />
            </div>
        </div>

        <!-- Badge -->
        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_badge"><?php _e('Software Badge Tag', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <select id="nursoft_badge" name="nursoft_badge">
                    <option value="none" <?php selected( $badge, 'none' ); ?>><?php _e('None', 'nursoft'); ?></option>
                    <option value="featured" <?php selected( $badge, 'featured' ); ?>><?php _e('Featured (Crown / Orange Star)', 'nursoft'); ?></option>
                    <option value="recommended" <?php selected( $badge, 'recommended' ); ?>><?php _e('Recommended (Heart)', 'nursoft'); ?></option>
                    <option value="preactivated" <?php selected( $badge, 'preactivated' ); ?>><?php _e('Preactivated (Pre-cracked)', 'nursoft'); ?></option>
                    <option value="full_version" <?php selected( $badge, 'full_version' ); ?>><?php _e('Full Version (Premium Pro)', 'nursoft'); ?></option>
                    <option value="free_download" <?php selected( $badge, 'free_download' ); ?>><?php _e('Free Download', 'nursoft'); ?></option>
                    <option value="multilingual" <?php selected( $badge, 'multilingual' ); ?>><?php _e('Multilingual (Multi-Language)', 'nursoft'); ?></option>
                    <option value="portable" <?php selected( $badge, 'portable' ); ?>><?php _e('Portable (Runs without Install)', 'nursoft'); ?></option>
                    <option value="cracked" <?php selected( $badge, 'cracked' ); ?>><?php _e('Cracked (Patch Included)', 'nursoft'); ?></option>
                    <option value="unlocked" <?php selected( $badge, 'unlocked' ); ?>><?php _e('Unlocked (Pro / Premium)', 'nursoft'); ?></option>
                    <option value="retail" <?php selected( $badge, 'retail' ); ?>><?php _e('Retail (Official Build)', 'nursoft'); ?></option>
                    <option value="patched" <?php selected( $badge, 'patched' ); ?>><?php _e('Patched Version', 'nursoft'); ?></option>
                    <option value="beta_dev" <?php selected( $badge, 'beta_dev' ); ?>><?php _e('Beta / Dev Version', 'nursoft'); ?></option>
                    <option value="ad_free" <?php selected( $badge, 'ad_free' ); ?>><?php _e('Ad-Free Version', 'nursoft'); ?></option>
                </select>
            </div>
        </div>

        <!-- Software Versions & Download Links Manager Section -->
        <div class="nursoft-meta-row" style="flex-direction: column; align-items: stretch; margin-bottom: 25px; border-top: 1px solid #ddd; padding-top: 20px;">
            <div class="nursoft-meta-label" style="width: 100%; margin-bottom: 12px;">
                <label style="font-size: 14px; color: #23282d; font-weight: bold;"><?php _e('Software Versions & Download Links Manager', 'nursoft'); ?></label>
                <div class="nursoft-meta-desc" style="font-weight: normal; margin-top: 4px; font-style: normal; color: #50575e;">
                    <?php _e('Manage all releases of this software. The first item in the list will be treated as the Latest Version and displayed as the primary download link on the site.', 'nursoft'); ?>
                </div>
            </div>
            
            <div id="nursoft-versions-list" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 15px;">
                <?php foreach ( $versions as $index => $item ) : ?>
                    <div class="nursoft-version-card" style="background: #fdfdfd; border: 1px solid #ccd0d4; border-radius: 6px; padding: 15px; position: relative; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                        <button type="button" class="nursoft-delete-version-btn button-link-delete" style="position: absolute; top: 12px; right: 15px; color: #dc3232; font-weight: 500; cursor: pointer; border: none; background: none;"><?php _e('Delete Version', 'nursoft'); ?></button>
                        <h4 style="margin: 0 0 12px 0; font-size: 13px; color: #1d2327; display: flex; align-items: center; gap: 6px;">
                            <span class="version-index-badge" style="background: #2271b1; color: white; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;"><?php echo $index + 1; ?></span>
                            <?php echo $index === 0 ? '<strong>' . __('Latest Release', 'nursoft') . '</strong>' : __('Prior Release', 'nursoft'); ?>
                        </h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php _e('Version Name / Tag', 'nursoft'); ?></label>
                                <input type="text" name="nursoft_versions[<?php echo $index; ?>][version]" value="<?php echo esc_attr( $item['version'] ); ?>" placeholder="e.g. v2026 (v27.7.0)" style="width: 100%; padding: 6px;" required />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php _e('Release Date / Month', 'nursoft'); ?></label>
                                <input type="text" name="nursoft_versions[<?php echo $index; ?>][date]" value="<?php echo esc_attr( $item['date'] ); ?>" placeholder="e.g. 2026-05 or May 2026" style="width: 100%; padding: 6px;" />
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php _e('Direct Download URL', 'nursoft'); ?></label>
                                <input type="url" name="nursoft_versions[<?php echo $index; ?>][direct_url]" value="<?php echo esc_url( $item['direct_url'] ); ?>" placeholder="https://files.nursoft.com/download.zip" style="width: 100%; padding: 6px;" />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php _e('Torrent / Magnet Link', 'nursoft'); ?></label>
                                <input type="text" name="nursoft_versions[<?php echo $index; ?>][torrent_url]" value="<?php echo esc_attr( $item['torrent_url'] ); ?>" placeholder="magnet:?xt=urn:btih:..." style="width: 100%; padding: 6px;" />
                            </div>
                        </div>
                        
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php _e('Version Highlights / Changelog', 'nursoft'); ?></label>
                            <textarea name="nursoft_versions[<?php echo $index; ?>][changelog]" placeholder="e.g. New UI design, fixed crashes, added pre-activated features." style="width: 100%; height: 50px; padding: 6px; font-family: sans-serif;"><?php echo esc_textarea( $item['changelog'] ); ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div>
                <button type="button" class="button button-primary" id="nursoft-add-version-btn" style="display: inline-flex; align-items: center; gap: 6px;">
                    <span class="dashicons dashicons-plus-alt" style="font-size: 16px; width: 16px; height: 16px; margin-top: 3px;"></span>
                    <?php _e('Add New Version & Links', 'nursoft'); ?>
                </button>
            </div>
        </div>

        <!-- Script for Dynamic Versions Manager -->
        <script>
            jQuery(document).ready(function($) {
                var $list = $('#nursoft-versions-list');
                var $addBtn = $('#nursoft-add-version-btn');
                
                // Counter to generate unique input indices
                var count = $list.children('.nursoft-version-card').length;
                
                function updateIndices() {
                    $list.children('.nursoft-version-card').each(function(index) {
                        var $card = $(this);
                        // Update the index badge
                        $card.find('.version-index-badge').text(index + 1);
                        // Update the title
                        var titleText = index === 0 ? '<strong><?php echo esc_js(__("Latest Release", "nursoft")); ?></strong>' : '<?php echo esc_js(__("Prior Release", "nursoft")); ?>';
                        $card.find('h4').html('<span class="version-index-badge" style="background: ' + (index === 0 ? '#2271b1' : '#646970') + '; color: white; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">' + (index + 1) + '</span> ' + titleText);
                        
                        // Rename input fields
                        $card.find('input, textarea').each(function() {
                            var name = $(this).attr('name');
                            if (name) {
                                var newName = name.replace(/nursoft_versions\[\d+\]/, 'nursoft_versions[' + index + ']');
                                $(this).attr('name', newName);
                            }
                        });
                    });
                }
                
                $addBtn.on('click', function(e) {
                    e.preventDefault();
                    
                    var cardHtml = `
                        <div class="nursoft-version-card" style="background: #fdfdfd; border: 1px solid #ccd0d4; border-radius: 6px; padding: 15px; position: relative; box-shadow: 0 1px 3px rgba(0,0,0,0.04); display: none;">
                            <button type="button" class="nursoft-delete-version-btn button-link-delete" style="position: absolute; top: 12px; right: 15px; color: #dc3232; font-weight: 500; cursor: pointer; border: none; background: none;"><?php echo esc_js(__('Delete Version', 'nursoft')); ?></button>
                            <h4 style="margin: 0 0 12px 0; font-size: 13px; color: #1d2327; display: flex; align-items: center; gap: 6px;">
                                <span class="version-index-badge" style="background: #2271b1; color: white; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;"></span>
                                <strong><?php echo esc_js(__('New Version', 'nursoft')); ?></strong>
                            </h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php echo esc_js(__('Version Name / Tag', 'nursoft')); ?></label>
                                    <input type="text" name="nursoft_versions[__INDEX__][version]" placeholder="e.g. v2026 (v27.7.0)" style="width: 100%; padding: 6px;" required />
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php echo esc_js(__('Release Date / Month', 'nursoft')); ?></label>
                                    <input type="text" name="nursoft_versions[__INDEX__][date]" placeholder="e.g. 2026-05 or May 2026" style="width: 100%; padding: 6px;" />
                                </div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php echo esc_js(__('Direct Download URL', 'nursoft')); ?></label>
                                    <input type="url" name="nursoft_versions[__INDEX__][direct_url]" placeholder="https://files.nursoft.com/download.zip" style="width: 100%; padding: 6px;" />
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php echo esc_js(__('Torrent / Magnet Link', 'nursoft')); ?></label>
                                    <input type="text" name="nursoft_versions[__INDEX__][torrent_url]" placeholder="magnet:?xt=urn:btih:..." style="width: 100%; padding: 6px;" />
                                </div>
                            </div>
                            
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 4px;"><?php echo esc_js(__('Version Highlights / Changelog', 'nursoft')); ?></label>
                                <textarea name="nursoft_versions[__INDEX__][changelog]" placeholder="e.g. New UI design, fixed crashes, added pre-activated features." style="width: 100%; height: 50px; padding: 6px; font-family: sans-serif;"></textarea>
                            </div>
                        </div>
                    `;
                    
                    var newCardHtml = cardHtml.replace(/__INDEX__/g, count);
                    var $newCard = $(newCardHtml);
                    
                    $list.prepend($newCard);
                    $newCard.slideDown(250);
                    
                    count++;
                    updateIndices();
                });
                
                $list.on('click', '.nursoft-delete-version-btn', function(e) {
                    e.preventDefault();
                    var $card = $(this).closest('.nursoft-version-card');
                    
                    if (confirm('<?php echo esc_js(__("Are you sure you want to delete this version and its associated download links?", "nursoft")); ?>')) {
                        $card.slideUp(250, function() {
                            $(this).remove();
                            updateIndices();
                        });
                    }
                });
            });
        </script>

        <!-- Requirements -->
        <div class="nursoft-meta-row" style="border-top: 1px solid #ddd; padding-top: 20px; margin-top: 20px;">
            <div class="nursoft-meta-label"><label for="nursoft_requirements"><?php _e('System Requirements', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <textarea id="nursoft_requirements" name="nursoft_requirements" placeholder="Operating System: Windows 10/11&#10;Processor: Intel Core i5&#10;Memory: 8 GB RAM&#10;Storage: 4 GB available space"><?php echo esc_textarea( $requirements ); ?></textarea>
            </div>
        </div>

        <!-- Installation Instructions -->
        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_instructions"><?php _e('Installation Instructions', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <textarea id="nursoft_instructions" name="nursoft_instructions" placeholder="0. Disable antivirus if needed.&#10;1. Install Program.&#10;2. Apply patch/crack.&#10;3. Done, enjoy!"><?php echo esc_textarea( $instructions ); ?></textarea>
                <div class="nursoft-meta-desc"><?php _e('Instructions will be shown inside a clean toggle block on the software single page.', 'nursoft'); ?></div>
            </div>
        </div>

        <!-- Drag & Drop Media Screenshots -->
        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label">
                <label><?php _e('Screenshots', 'nursoft'); ?></label>
            </div>
            <div class="nursoft-meta-field">
                <div class="nursoft-uploader-box">
                    <p style="margin-bottom: 10px; font-weight: 500;"><?php _e('Upload or Select Screenshots from Media Library', 'nursoft'); ?></p>
                    <button type="button" class="button button-secondary" id="nursoft_select_screenshots_btn">
                        <span class="wp-media-buttons-icon"></span> <?php _e('Select / Upload Images', 'nursoft'); ?>
                    </button>
                    <input type="hidden" id="nursoft_screenshots_ids" name="nursoft_screenshots_ids" value="<?php echo esc_attr( $screenshots_ids ); ?>" />
                    
                    <!-- Preview Container -->
                    <div class="nursoft-preview-grid" id="nursoft_screenshots_preview">
                        <?php
                        if ( ! empty($screenshots_ids) ) {
                            $ids_array = explode(',', $screenshots_ids);
                            foreach ( $ids_array as $img_id ) {
                                $img_url = wp_get_attachment_image_url( $img_id, 'thumbnail' );
                                if ( $img_url ) {
                                    echo '<div class="nursoft-preview-item" data-id="' . esc_attr($img_id) . '">';
                                    echo '<img src="' . esc_url($img_url) . '" />';
                                    echo '<button type="button" class="remove-img">&times;</button>';
                                    echo '</div>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- External Screenshot URLs -->
        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_screenshots_ext"><?php _e('External Screenshot URLs', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <textarea id="nursoft_screenshots_ext" name="nursoft_screenshots_ext" placeholder="https://example.com/shot1.jpg, https://example.com/shot2.jpg" style="height: 60px;"><?php echo esc_textarea( $screenshots_ext ); ?></textarea>
                <div class="nursoft-meta-desc"><?php _e('If you prefer, you can add external image links separated by commas.', 'nursoft'); ?></div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle Drag and Drop WordPress Media Uploader -->
    <script>
        jQuery(document).ready(function($) {
            var file_frame;
            var $button = $('#nursoft_select_screenshots_btn');
            var $ids_input = $('#nursoft_screenshots_ids');
            var $preview_grid = $('#nursoft_screenshots_preview');

            $button.on('click', function(e) {
                e.preventDefault();

                // If the media frame already exists, reopen it.
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media({
                    title: '<?php echo esc_js(__('Select or Drag & Drop Screenshots', 'nursoft')); ?>',
                    button: {
                        text: '<?php echo esc_js(__('Insert Screenshots', 'nursoft')); ?>'
                    },
                    multiple: true  // Allow multiple files selection
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function() {
                    var selection = file_frame.state().get('selection');
                    var ids = $ids_input.val() ? $ids_input.val().split(',') : [];

                    selection.map(function(attachment) {
                        attachment = attachment.toJSON();
                        
                        // Prevent duplicates
                        if (ids.indexOf(attachment.id.toString()) === -1) {
                            ids.push(attachment.id);

                            var thumb_url = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                            
                            // Append preview item
                            $preview_grid.append(
                                '<div class="nursoft-preview-item" data-id="' + attachment.id + '">' +
                                    '<img src="' + thumb_url + '" />' +
                                    '<button type="button" class="remove-img">&times;</button>' +
                                '</div>'
                            );
                        }
                    });

                    // Save IDs comma-separated
                    $ids_input.val(ids.join(','));
                });

                // Finally, open the modal
                file_frame.open();
            });

            // Handle image removal
            $preview_grid.on('click', '.remove-img', function(e) {
                e.preventDefault();
                var $item = $(this).parent();
                var id_to_remove = $item.data('id').toString();
                var ids = $ids_input.val().split(',');

                ids = ids.filter(function(id) {
                    return id !== id_to_remove;
                });

                $ids_input.val(ids.join(','));
                $item.fadeOut(200, function() {
                    $(this).remove();
                });
            });
        });
    </script>
    <?php
}

function nursoft_save_software_meta( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['nursoft_software_meta_nonce'] ) ) {
        return;
    }

    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nursoft_software_meta_nonce'], 'nursoft_save_software_meta' ) ) {
        return;
    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Auto save check
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Save fields
    if ( isset( $_POST['nursoft_size'] ) ) {
        update_post_meta( $post_id, '_nursoft_size', sanitize_text_field( $_POST['nursoft_size'] ) );
    }

    if ( isset( $_POST['nursoft_downloads'] ) ) {
        update_post_meta( $post_id, '_nursoft_downloads', intval( $_POST['nursoft_downloads'] ) );
    }

    if ( isset( $_POST['nursoft_reputation'] ) ) {
        update_post_meta( $post_id, '_nursoft_reputation', sanitize_text_field( $_POST['nursoft_reputation'] ) );
    }

    if ( isset( $_POST['nursoft_badge'] ) ) {
        update_post_meta( $post_id, '_nursoft_badge', sanitize_text_field( $_POST['nursoft_badge'] ) );
    }

    // Save dynamic versions list
    if ( isset( $_POST['nursoft_versions'] ) && is_array( $_POST['nursoft_versions'] ) ) {
        $versions = array();
        foreach ( $_POST['nursoft_versions'] as $v ) {
            $version_name = sanitize_text_field( $v['version'] );
            if ( empty( $version_name ) ) {
                continue; // Skip empty versions
            }
            $versions[] = array(
                'version'     => $version_name,
                'date'        => sanitize_text_field( $v['date'] ),
                'direct_url'  => esc_url_raw( $v['direct_url'] ),
                'torrent_url' => sanitize_text_field( $v['torrent_url'] ),
                'changelog'   => sanitize_textarea_field( $v['changelog'] )
            );
        }
        
        update_post_meta( $post_id, '_nursoft_versions_list', $versions );
        
        // Synchronize first/latest version to single fields for compatibility with search, slider, and archive lists!
        if ( ! empty( $versions ) ) {
            $latest = $versions[0];
            update_post_meta( $post_id, '_nursoft_version', $latest['version'] );
            update_post_meta( $post_id, '_nursoft_direct_download_url', $latest['direct_url'] );
            update_post_meta( $post_id, '_nursoft_download_url', $latest['direct_url'] );
            update_post_meta( $post_id, '_nursoft_torrent_download_url', $latest['torrent_url'] );
            
            // Build the version history text block to keep single-page version history compatible or we can just render the list on single page
            $history_text = '';
            foreach ( $versions as $item ) {
                $history_text .= $item['version'];
                if ( ! empty( $item['date'] ) ) {
                    $history_text .= ' (' . $item['date'] . ')';
                }
                if ( ! empty( $item['changelog'] ) ) {
                    $history_text .= ' - ' . str_replace( array( "\r\n", "\r", "\n" ), " ", $item['changelog'] );
                }
                $history_text .= "\n";
            }
            update_post_meta( $post_id, '_nursoft_version_history', trim($history_text) );
        } else {
            // Delete legacy fields if list is empty
            update_post_meta( $post_id, '_nursoft_version', '' );
            update_post_meta( $post_id, '_nursoft_direct_download_url', '' );
            update_post_meta( $post_id, '_nursoft_download_url', '' );
            update_post_meta( $post_id, '_nursoft_torrent_download_url', '' );
            update_post_meta( $post_id, '_nursoft_version_history', '' );
        }
    } else {
        // If the array is missing (e.g. all deleted), clear metadata
        update_post_meta( $post_id, '_nursoft_versions_list', array() );
        update_post_meta( $post_id, '_nursoft_version', '' );
        update_post_meta( $post_id, '_nursoft_direct_download_url', '' );
        update_post_meta( $post_id, '_nursoft_download_url', '' );
        update_post_meta( $post_id, '_nursoft_torrent_download_url', '' );
        update_post_meta( $post_id, '_nursoft_version_history', '' );
    }

    if ( isset( $_POST['nursoft_requirements'] ) ) {
        update_post_meta( $post_id, '_nursoft_requirements', sanitize_textarea_field( $_POST['nursoft_requirements'] ) );
    }

    if ( isset( $_POST['nursoft_instructions'] ) ) {
        update_post_meta( $post_id, '_nursoft_instructions', sanitize_textarea_field( $_POST['nursoft_instructions'] ) );
    }

    // Save Screenshot Media IDs & External URLs
    if ( isset( $_POST['nursoft_screenshots_ids'] ) ) {
        update_post_meta( $post_id, '_nursoft_screenshots_ids', sanitize_text_field( $_POST['nursoft_screenshots_ids'] ) );
    }

    if ( isset( $_POST['nursoft_screenshots_ext'] ) ) {
        update_post_meta( $post_id, '_nursoft_screenshots_ext', sanitize_textarea_field( $_POST['nursoft_screenshots_ext'] ) );
        // Sync to old field
        update_post_meta( $post_id, '_nursoft_screenshots', sanitize_textarea_field( $_POST['nursoft_screenshots_ext'] ) );
    }
}
add_action( 'save_post_software', 'nursoft_save_software_meta' );

/**
 * 3.1 Book CPT Metabox HTML & Save
 */
function nursoft_book_meta_box_html( $post ) {
    // Get existing values
    $author       = get_post_meta( $post->ID, '_nursoft_book_author', true );
    $pages        = get_post_meta( $post->ID, '_nursoft_book_pages', true );
    $format       = get_post_meta( $post->ID, '_nursoft_book_format', true );
    $language     = get_post_meta( $post->ID, '_nursoft_book_language', true );
    
    $size         = get_post_meta( $post->ID, '_nursoft_size', true );
    $downloads    = get_post_meta( $post->ID, '_nursoft_downloads', true );
    $reputation   = get_post_meta( $post->ID, '_nursoft_reputation', true );
    $badge        = get_post_meta( $post->ID, '_nursoft_badge', true );
    
    $direct_url   = get_post_meta( $post->ID, '_nursoft_direct_download_url', true );
    if ( empty( $direct_url ) ) {
        $direct_url = get_post_meta( $post->ID, '_nursoft_download_url', true );
    }
    $torrent_url  = get_post_meta( $post->ID, '_nursoft_torrent_download_url', true );

    wp_nonce_field( 'nursoft_save_book_meta', 'nursoft_book_meta_nonce' );
    ?>
    <div class="nursoft-meta-wrapper" style="padding: 10px 0;">
        <style>
            .nursoft-meta-row { display: flex; align-items: center; margin-bottom: 15px; }
            .nursoft-meta-label { width: 200px; flex-shrink: 0; font-weight: 600; }
            .nursoft-meta-field { flex-grow: 1; }
            .nursoft-meta-field input[type="text"], .nursoft-meta-field input[type="url"], .nursoft-meta-field input[type="number"], .nursoft-meta-field select, .nursoft-meta-field textarea { width: 100%; max-width: 500px; padding: 6px; }
            .nursoft-meta-desc { font-size: 12px; color: #666; margin-top: 4px; }
        </style>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_book_author"><?php _e('Book Author', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_book_author" name="nursoft_book_author" value="<?php echo esc_attr( $author ); ?>" placeholder="e.g. Robert Kiyosaki" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_book_pages"><?php _e('Number of Pages', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="number" id="nursoft_book_pages" name="nursoft_book_pages" value="<?php echo esc_attr( $pages ); ?>" placeholder="e.g. 340" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_book_format"><?php _e('Book Format', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <select id="nursoft_book_format" name="nursoft_book_format">
                    <option value="PDF" <?php selected( $format, 'PDF' ); ?>>PDF</option>
                    <option value="EPUB" <?php selected( $format, 'EPUB' ); ?>>EPUB</option>
                    <option value="MOBI" <?php selected( $format, 'MOBI' ); ?>>MOBI</option>
                    <option value="TXT" <?php selected( $format, 'TXT' ); ?>>TXT</option>
                </select>
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_book_language"><?php _e('Language', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_book_language" name="nursoft_book_language" value="<?php echo esc_attr( $language ); ?>" placeholder="e.g. English, Swahili" />
            </div>
        </div>

        <div class="nursoft-meta-row" style="border-top: 1px solid #ddd; padding-top: 15px;">
            <div class="nursoft-meta-label"><label for="nursoft_size"><?php _e('File Size', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_size" name="nursoft_size" value="<?php echo esc_attr( $size ); ?>" placeholder="e.g. 12.4 MB" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_downloads"><?php _e('Downloads Count', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="number" id="nursoft_downloads" name="nursoft_downloads" value="<?php echo esc_attr( $downloads ); ?>" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_reputation"><?php _e('Reputation / Rating Value (1-5)', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_reputation" name="nursoft_reputation" value="<?php echo esc_attr( $reputation ); ?>" placeholder="e.g. 4.8" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_badge"><?php _e('Reputation Badge', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <select id="nursoft_badge" name="nursoft_badge">
                    <option value="" <?php selected( $badge, '' ); ?>><?php _e('None (Normal)', 'nursoft'); ?></option>
                    <option value="featured" <?php selected( $badge, 'featured' ); ?>><?php _e('Featured (Star)', 'nursoft'); ?></option>
                    <option value="recommended" <?php selected( $badge, 'recommended' ); ?>><?php _e('Recommended (Heart)', 'nursoft'); ?></option>
                    <option value="full_version" <?php selected( $badge, 'full_version' ); ?>><?php _e('Premium Pro', 'nursoft'); ?></option>
                    <option value="free_download" <?php selected( $badge, 'free_download' ); ?>><?php _e('Free Download', 'nursoft'); ?></option>
                </select>
            </div>
        </div>

        <div class="nursoft-meta-row" style="border-top: 1px solid #ddd; padding-top: 15px;">
            <div class="nursoft-meta-label"><label for="nursoft_direct_download_url"><?php _e('Direct Download URL', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="url" id="nursoft_direct_download_url" name="nursoft_direct_download_url" value="<?php echo esc_url( $direct_url ); ?>" placeholder="https://..." />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_torrent_download_url"><?php _e('Torrent / Magnet Link', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_torrent_download_url" name="nursoft_torrent_download_url" value="<?php echo esc_attr( $torrent_url ); ?>" placeholder="magnet:?xt=..." />
            </div>
        </div>
    </div>
    <?php
}

function nursoft_save_book_meta( $post_id ) {
    if ( ! isset( $_POST['nursoft_book_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['nursoft_book_meta_nonce'], 'nursoft_save_book_meta' ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if ( isset( $_POST['nursoft_book_author'] ) ) {
        update_post_meta( $post_id, '_nursoft_book_author', sanitize_text_field( $_POST['nursoft_book_author'] ) );
    }
    if ( isset( $_POST['nursoft_book_pages'] ) ) {
        update_post_meta( $post_id, '_nursoft_book_pages', intval( $_POST['nursoft_book_pages'] ) );
    }
    if ( isset( $_POST['nursoft_book_format'] ) ) {
        update_post_meta( $post_id, '_nursoft_book_format', sanitize_text_field( $_POST['nursoft_book_format'] ) );
    }
    if ( isset( $_POST['nursoft_book_language'] ) ) {
        update_post_meta( $post_id, '_nursoft_book_language', sanitize_text_field( $_POST['nursoft_book_language'] ) );
    }
    if ( isset( $_POST['nursoft_size'] ) ) {
        update_post_meta( $post_id, '_nursoft_size', sanitize_text_field( $_POST['nursoft_size'] ) );
    }
    if ( isset( $_POST['nursoft_downloads'] ) ) {
        update_post_meta( $post_id, '_nursoft_downloads', intval( $_POST['nursoft_downloads'] ) );
    }
    if ( isset( $_POST['nursoft_reputation'] ) ) {
        update_post_meta( $post_id, '_nursoft_reputation', sanitize_text_field( $_POST['nursoft_reputation'] ) );
    }
    if ( isset( $_POST['nursoft_badge'] ) ) {
        update_post_meta( $post_id, '_nursoft_badge', sanitize_text_field( $_POST['nursoft_badge'] ) );
    }
    if ( isset( $_POST['nursoft_direct_download_url'] ) ) {
        update_post_meta( $post_id, '_nursoft_direct_download_url', esc_url_raw( $_POST['nursoft_direct_download_url'] ) );
        update_post_meta( $post_id, '_nursoft_download_url', esc_url_raw( $_POST['nursoft_direct_download_url'] ) );
    }
    if ( isset( $_POST['nursoft_torrent_download_url'] ) ) {
        update_post_meta( $post_id, '_nursoft_torrent_download_url', sanitize_text_field( $_POST['nursoft_torrent_download_url'] ) );
    }
}
add_action( 'save_post_book', 'nursoft_save_book_meta' );

/**
 * 3.2 Course CPT Metabox HTML & Save
 */
function nursoft_course_meta_box_html( $post ) {
    // Get existing values
    $instructor   = get_post_meta( $post->ID, '_nursoft_course_instructor', true );
    $duration     = get_post_meta( $post->ID, '_nursoft_course_duration', true );
    $lessons      = get_post_meta( $post->ID, '_nursoft_course_lessons', true );
    $level        = get_post_meta( $post->ID, '_nursoft_course_level', true );
    $language     = get_post_meta( $post->ID, '_nursoft_course_language', true );
    
    $size         = get_post_meta( $post->ID, '_nursoft_size', true );
    $downloads    = get_post_meta( $post->ID, '_nursoft_downloads', true );
    $reputation   = get_post_meta( $post->ID, '_nursoft_reputation', true );
    $badge        = get_post_meta( $post->ID, '_nursoft_badge', true );
    
    $direct_url   = get_post_meta( $post->ID, '_nursoft_direct_download_url', true );
    if ( empty( $direct_url ) ) {
        $direct_url = get_post_meta( $post->ID, '_nursoft_download_url', true );
    }
    $torrent_url  = get_post_meta( $post->ID, '_nursoft_torrent_download_url', true );

    wp_nonce_field( 'nursoft_save_course_meta', 'nursoft_course_meta_nonce' );
    ?>
    <div class="nursoft-meta-wrapper" style="padding: 10px 0;">
        <style>
            .nursoft-meta-row { display: flex; align-items: center; margin-bottom: 15px; }
            .nursoft-meta-label { width: 200px; flex-shrink: 0; font-weight: 600; }
            .nursoft-meta-field { flex-grow: 1; }
            .nursoft-meta-field input[type="text"], .nursoft-meta-field input[type="url"], .nursoft-meta-field input[type="number"], .nursoft-meta-field select, .nursoft-meta-field textarea { width: 100%; max-width: 500px; padding: 6px; }
            .nursoft-meta-desc { font-size: 12px; color: #666; margin-top: 4px; }
        </style>
        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_course_instructor"><?php _e('Course Instructor', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_course_instructor" name="nursoft_course_instructor" value="<?php echo esc_attr( $instructor ); ?>" placeholder="e.g. Dr. Angela Yu" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_course_duration"><?php _e('Course Duration', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_course_duration" name="nursoft_course_duration" value="<?php echo esc_attr( $duration ); ?>" placeholder="e.g. 45 Hours" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_course_lessons"><?php _e('Number of Lessons', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="number" id="nursoft_course_lessons" name="nursoft_course_lessons" value="<?php echo esc_attr( $lessons ); ?>" placeholder="e.g. 120" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_course_level"><?php _e('Skill Level', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <select id="nursoft_course_level" name="nursoft_course_level">
                    <option value="All Levels" <?php selected( $level, 'All Levels' ); ?>>All Levels</option>
                    <option value="Beginner" <?php selected( $level, 'Beginner' ); ?>>Beginner</option>
                    <option value="Intermediate" <?php selected( $level, 'Intermediate' ); ?>>Intermediate</option>
                    <option value="Advanced" <?php selected( $level, 'Advanced' ); ?>>Advanced</option>
                </select>
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_course_language"><?php _e('Course Language', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_course_language" name="nursoft_course_language" value="<?php echo esc_attr( $language ); ?>" placeholder="e.g. English" />
            </div>
        </div>

        <div class="nursoft-meta-row" style="border-top: 1px solid #ddd; padding-top: 15px;">
            <div class="nursoft-meta-label"><label for="nursoft_size"><?php _e('Course File Size', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_size" name="nursoft_size" value="<?php echo esc_attr( $size ); ?>" placeholder="e.g. 3.2 GB" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_downloads"><?php _e('Downloads Count', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="number" id="nursoft_downloads" name="nursoft_downloads" value="<?php echo esc_attr( $downloads ); ?>" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_reputation"><?php _e('Reputation / Rating Value (1-5)', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_reputation" name="nursoft_reputation" value="<?php echo esc_attr( $reputation ); ?>" placeholder="e.g. 4.9" />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_badge"><?php _e('Reputation Badge', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <select id="nursoft_badge" name="nursoft_badge">
                    <option value="" <?php selected( $badge, '' ); ?>><?php _e('None (Normal)', 'nursoft'); ?></option>
                    <option value="featured" <?php selected( $badge, 'featured' ); ?>><?php _e('Featured (Star)', 'nursoft'); ?></option>
                    <option value="recommended" <?php selected( $badge, 'recommended' ); ?>><?php _e('Recommended (Heart)', 'nursoft'); ?></option>
                    <option value="full_version" <?php selected( $badge, 'full_version' ); ?>><?php _e('Premium Pro', 'nursoft'); ?></option>
                    <option value="free_download" <?php selected( $badge, 'free_download' ); ?>><?php _e('Free Download', 'nursoft'); ?></option>
                </select>
            </div>
        </div>

        <div class="nursoft-meta-row" style="border-top: 1px solid #ddd; padding-top: 15px;">
            <div class="nursoft-meta-label"><label for="nursoft_direct_download_url"><?php _e('Direct Download URL', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="url" id="nursoft_direct_download_url" name="nursoft_direct_download_url" value="<?php echo esc_url( $direct_url ); ?>" placeholder="https://..." />
            </div>
        </div>

        <div class="nursoft-meta-row">
            <div class="nursoft-meta-label"><label for="nursoft_torrent_download_url"><?php _e('Torrent / Magnet Link', 'nursoft'); ?></label></div>
            <div class="nursoft-meta-field">
                <input type="text" id="nursoft_torrent_download_url" name="nursoft_torrent_download_url" value="<?php echo esc_attr( $torrent_url ); ?>" placeholder="magnet:?xt=..." />
            </div>
        </div>
    </div>
    <?php
}

function nursoft_save_course_meta( $post_id ) {
    if ( ! isset( $_POST['nursoft_course_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['nursoft_course_meta_nonce'], 'nursoft_save_course_meta' ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if ( isset( $_POST['nursoft_course_instructor'] ) ) {
        update_post_meta( $post_id, '_nursoft_course_instructor', sanitize_text_field( $_POST['nursoft_course_instructor'] ) );
    }
    if ( isset( $_POST['nursoft_course_duration'] ) ) {
        update_post_meta( $post_id, '_nursoft_course_duration', sanitize_text_field( $_POST['nursoft_course_duration'] ) );
    }
    if ( isset( $_POST['nursoft_course_lessons'] ) ) {
        update_post_meta( $post_id, '_nursoft_course_lessons', intval( $_POST['nursoft_course_lessons'] ) );
    }
    if ( isset( $_POST['nursoft_course_level'] ) ) {
        update_post_meta( $post_id, '_nursoft_course_level', sanitize_text_field( $_POST['nursoft_course_level'] ) );
    }
    if ( isset( $_POST['nursoft_course_language'] ) ) {
        update_post_meta( $post_id, '_nursoft_course_language', sanitize_text_field( $_POST['nursoft_course_language'] ) );
    }
    if ( isset( $_POST['nursoft_size'] ) ) {
        update_post_meta( $post_id, '_nursoft_size', sanitize_text_field( $_POST['nursoft_size'] ) );
    }
    if ( isset( $_POST['nursoft_downloads'] ) ) {
        update_post_meta( $post_id, '_nursoft_downloads', intval( $_POST['nursoft_downloads'] ) );
    }
    if ( isset( $_POST['nursoft_reputation'] ) ) {
        update_post_meta( $post_id, '_nursoft_reputation', sanitize_text_field( $_POST['nursoft_reputation'] ) );
    }
    if ( isset( $_POST['nursoft_badge'] ) ) {
        update_post_meta( $post_id, '_nursoft_badge', sanitize_text_field( $_POST['nursoft_badge'] ) );
    }
    if ( isset( $_POST['nursoft_direct_download_url'] ) ) {
        update_post_meta( $post_id, '_nursoft_direct_download_url', esc_url_raw( $_POST['nursoft_direct_download_url'] ) );
        update_post_meta( $post_id, '_nursoft_download_url', esc_url_raw( $_POST['nursoft_direct_download_url'] ) );
    }
    if ( isset( $_POST['nursoft_torrent_download_url'] ) ) {
        update_post_meta( $post_id, '_nursoft_torrent_download_url', sanitize_text_field( $_POST['nursoft_torrent_download_url'] ) );
    }
}
add_action( 'save_post_course', 'nursoft_save_course_meta' );

/* ==========================================================================
   4. AJAX ACTIONS (LIVE SEARCH & DOWNLOAD INCREMENTOR)
   ========================================================================== */

/**
 * Handle AJAX Live Search
 */
function nursoft_ajax_live_search() {
    check_ajax_referer( 'nursoft-search-nonce', 'security' );

    $keyword = isset( $_GET['keyword'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';

    if ( empty( $keyword ) ) {
        wp_send_json_success( array() );
    }

    $args = array(
        'post_type'      => array( 'software', 'book', 'course' ),
        'posts_per_page' => 6,
        's'              => $keyword,
    );

    $query = new WP_Query( $args );
    $results = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            
            $post_type = get_post_type();

            // Get post platform
            $platforms = wp_get_post_terms( get_the_ID(), 'platform' );
            $platform_name = '';
            if ( ! empty( $platforms ) && ! is_wp_error( $platforms ) ) {
                $platform_name = $platforms[0]->name;
            }

            // Get meta fields based on CPT
            $size = get_post_meta( get_the_ID(), '_nursoft_size', true );
            $title_suffix = '';

            if ( $post_type === 'book' ) {
                $author = get_post_meta( get_the_ID(), '_nursoft_book_author', true );
                if ( $author ) {
                    $title_suffix = ' (By ' . $author . ')';
                }
            } elseif ( $post_type === 'course' ) {
                $instructor = get_post_meta( get_the_ID(), '_nursoft_course_instructor', true );
                if ( $instructor ) {
                    $title_suffix = ' (Course by ' . $instructor . ')';
                }
            } else {
                $version = get_post_meta( get_the_ID(), '_nursoft_version', true );
                if ( $version ) {
                    $title_suffix = ' ' . $version;
                }
            }

            // Thumbnail
            $thumb_url = '';
            if ( has_post_thumbnail() ) {
                $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
            } else {
                $thumb_url = get_template_directory_uri() . '/assets/img/default-logo.png';
            }

            $results[] = array(
                'id'       => get_the_ID(),
                'title'    => get_the_title() . $title_suffix,
                'url'      => get_permalink(),
                'thumb'    => $thumb_url,
                'platform' => $platform_name,
                'size'     => $size,
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success( $results );
}
add_action( 'wp_ajax_nursoft_live_search', 'nursoft_ajax_live_search' );
add_action( 'wp_ajax_nopriv_nursoft_live_search', 'nursoft_ajax_live_search' );

/**
 * Handle AJAX Download Count Increment
 */
function nursoft_ajax_increment_downloads() {
    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

    if ( ! $post_id ) {
        wp_send_json_error( 'Invalid post ID' );
    }

    $current_downloads = get_post_meta( $post_id, '_nursoft_downloads', true );
    $current_downloads = $current_downloads !== '' ? intval( $current_downloads ) : 0;
    
    $new_downloads = $current_downloads + 1;
    update_post_meta( $post_id, '_nursoft_downloads', $new_downloads );

    wp_send_json_success( array( 'new_count' => number_format($new_downloads) ) );
}
add_action( 'wp_ajax_nursoft_increment_downloads', 'nursoft_ajax_increment_downloads' );
add_action( 'wp_ajax_nopriv_nursoft_increment_downloads', 'nursoft_ajax_increment_downloads' );

/* ==========================================================================
   5. AUTO SEED BEAUTIFUL DEMO SOFTWARE DATA ON ACTIVATION
   ========================================================================== */
function nursoft_seed_demo_data() {
    // Only run this in admin to prevent overhead
    if ( ! is_admin() ) {
        return;
    }

    // Check if we've already seeded demo data to prevent double execution
    if ( get_option( 'nursoft_demo_seeded' ) ) {
        return;
    }

    // Check if CPT exists
    if ( ! post_type_exists( 'software' ) ) {
        return;
    }

    // 1. First, create platforms
    $platforms = array(
        'Windows'       => 'ms-windows',
        'Mac'           => 'mac',
        'Android Apps'  => 'android',
        'Android Games' => 'android-games',
        'PC Games'      => 'pc-games',
    );

    $platform_term_ids = array();
    foreach ( $platforms as $name => $slug ) {
        $term = get_term_by( 'slug', $slug, 'platform' );
        if ( ! $term ) {
            $inserted = wp_insert_term( $name, 'platform', array( 'slug' => $slug ) );
            if ( ! is_wp_error( $inserted ) ) {
                $platform_term_ids[$slug] = $inserted['term_id'];
            }
        } else {
            $platform_term_ids[$slug] = $term->term_id;
        }
    }

    // 2. Create categories
    $categories = array(
        'Graphics & Design',
        'Download Managers',
        'Multimedia',
        'Operating System',
        'Tools & Utilities',
        'Photography & Design',
        'Video Editors',
    );

    $category_term_ids = array();
    foreach ( $categories as $cat_name ) {
        $term = get_term_by( 'name', $cat_name, 'software_cat' );
        if ( ! $term ) {
            $inserted = wp_insert_term( $cat_name, 'software_cat' );
            if ( ! is_wp_error( $inserted ) ) {
                $category_term_ids[$cat_name] = $inserted['term_id'];
            }
        } else {
            $category_term_ids[$cat_name] = $term->term_id;
        }
    }

    // 3. Create Software Products
    $demo_software = array(
        array(
            'title'        => 'uTorrent Pro',
            'desc'         => '#1 BitTorrent download client on desktops worldwide. Very lightweight, extremely fast, and supports bulk queueing and scheduling.',
            'version'      => '3.6.0',
            'size'         => '36.7 MB',
            'downloads'    => 920653,
            'reputation'   => '4.2',
            'badge'        => 'featured',
            'platform'     => 'ms-windows',
            'category'     => 'Download Managers',
            'requirements' => "OS: Windows 7/8/10/11\nProcessor: Intel Pentium 4 or higher\nMemory: 512 MB RAM\nStorage: 100 MB available space",
            'download_url' => 'https://files.nursoft.com/utorrent-pro.zip',
            'screenshots'  => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=600,https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=600',
        ),
        array(
            'title'        => 'Adobe Photoshop 2026',
            'desc'         => 'The world’s best imaging and graphic design software. Create and enhance photographs, illustrations, and 3D artwork. Design websites and mobile apps.',
            'version'      => 'v27.7.0.11',
            'size'         => '4.2 GB',
            'downloads'    => 20783,
            'reputation'   => '4.7',
            'badge'        => 'recommended',
            'platform'     => 'ms-windows',
            'category'     => 'Graphics & Design',
            'requirements' => "OS: Windows 10 (64-bit) version 22H2 or later\nProcessor: Multicore Intel or AMD processor with 64-bit support\nMemory: 8 GB RAM (16 GB Recommended)\nGraphics: DirectX 12 support GPU with 2 GB RAM\nStorage: 20 GB available space",
            'download_url' => 'https://files.nursoft.com/photoshop-2026.zip',
            'screenshots'  => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=600,https://images.unsplash.com/photo-1563089145-599997674d42?q=80&w=600',
        ),
        array(
            'title'        => 'Autodesk Maya 2027.1',
            'desc'         => 'Autodesk Maya 3D animation, modeling, simulation, and rendering software provides an integrated, powerful toolset.',
            'version'      => '2027.1',
            'size'         => '2.8 GB',
            'downloads'    => 487,
            'reputation'   => '4.3',
            'badge'        => 'featured',
            'platform'     => 'mac',
            'category'     => 'Graphics & Design',
            'requirements' => "OS: macOS 12.x, 13.x, 14.x or later\nProcessor: Apple Silicon M1/M2/M3 or Intel Multicore\nMemory: 8 GB RAM (16 GB Recommended)\nStorage: 10 GB available space",
            'download_url' => 'https://files.nursoft.com/maya-mac.zip',
            'screenshots'  => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=600',
        ),
        array(
            'title'        => 'SnapTube - YouTube Downloader',
            'desc'         => 'Download videos and music from YouTube, Facebook, Instagram, TikTok, and other platforms directly to your Android device in high definition or MP3 formats.',
            'version'      => '7.60.1',
            'size'         => '28.4 MB',
            'downloads'    => 1149,
            'reputation'   => '4.5',
            'badge'        => 'recommended',
            'platform'     => 'android',
            'category'     => 'Multimedia',
            'requirements' => "OS: Android 5.0 or higher\nPermissions: Internet connection, Storage Write/Read permission",
            'download_url' => 'https://files.nursoft.com/snaptube.apk',
            'screenshots'  => 'https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?q=80&w=600',
        ),
        array(
            'title'        => 'Picsart: AI Photo Video Editor',
            'desc'         => 'Join the Picsart community of over 150 million creators worldwide. With the Picsart AI photo editor and video editor, you can bring your creativity to life.',
            'version'      => '29.9.9',
            'size'         => '78.2 MB',
            'downloads'    => 284,
            'reputation'   => '3.9',
            'badge'        => 'none',
            'platform'     => 'android',
            'category'     => 'Photography & Design',
            'requirements' => "OS: Android 6.0 or higher\nMemory: 2 GB RAM minimum",
            'download_url' => 'https://files.nursoft.com/picsart-premium.apk',
            'screenshots'  => 'https://images.unsplash.com/photo-1563089145-599997674d42?q=80&w=600',
        )
    );

    foreach ( $demo_software as $soft ) {
        // Check if software already exists
        $existing = get_page_by_title( $soft['title'], OBJECT, 'software' );
        if ( ! $existing ) {
            $post_id = wp_insert_post( array(
                'post_title'   => $soft['title'],
                'post_content' => $soft['desc'],
                'post_excerpt' => substr($soft['desc'], 0, 100) . '...',
                'post_status'  => 'publish',
                'post_type'    => 'software',
            ) );

            if ( $post_id && ! is_wp_error( $post_id ) ) {
                // Set metadata
                update_post_meta( $post_id, '_nursoft_version', $soft['version'] );
                update_post_meta( $post_id, '_nursoft_size', $soft['size'] );
                update_post_meta( $post_id, '_nursoft_downloads', $soft['downloads'] );
                update_post_meta( $post_id, '_nursoft_reputation', $soft['reputation'] );
                update_post_meta( $post_id, '_nursoft_badge', $soft['badge'] );
                update_post_meta( $post_id, '_nursoft_download_url', $soft['download_url'] );
                update_post_meta( $post_id, '_nursoft_requirements', $soft['requirements'] );
                update_post_meta( $post_id, '_nursoft_screenshots', $soft['screenshots'] );

                // Dynamic realistic version history for demo seeded items
                $version_val = floatval(preg_replace('/[^0-9.]/', '', $soft['version']));
                if ( !$version_val ) { $version_val = 1.0; }
                $v1 = $soft['version'];
                $v2 = round($version_val * 0.9, 1);
                $v3 = round($version_val * 0.8, 1);
                $demo_history = "v{$v1} (2026-05) - " . __('New feature updates, performance improvements, and critical stability fixes.', 'nursoft') . "\n" .
                                "v{$v2} (2026-01) - " . __('Major core platform overhaul, user interface redesigned.', 'nursoft') . "\n" .
                                "v{$v3} (2025-09) - " . __('Initial major stable release with dual download links.', 'nursoft');
                update_post_meta( $post_id, '_nursoft_version_history', $demo_history );

                // Seed dynamic repeatable versions list with download links!
                $seeded_versions = array(
                    array(
                        'version'     => $v1,
                        'date'        => '2026-05',
                        'direct_url'  => $soft['download_url'],
                        'torrent_url' => 'magnet:?xt=urn:btih:d7971ee6a85859e9&dn=' . urlencode($soft['title']),
                        'changelog'   => __('New feature updates, performance improvements, and critical stability fixes.', 'nursoft')
                    ),
                    array(
                        'version'     => 'v' . $v2,
                        'date'        => '2026-01',
                        'direct_url'  => $soft['download_url'] . '?v=' . $v2,
                        'torrent_url' => '',
                        'changelog'   => __('Major core platform overhaul, user interface redesigned.', 'nursoft')
                    ),
                    array(
                        'version'     => 'v' . $v3,
                        'date'        => '2025-09',
                        'direct_url'  => $soft['download_url'] . '?v=' . $v3,
                        'torrent_url' => '',
                        'changelog'   => __('Initial major stable release with dual download links.', 'nursoft')
                    )
                );
                update_post_meta( $post_id, '_nursoft_versions_list', $seeded_versions );

                // Set terms
                if ( isset( $platform_term_ids[$soft['platform']] ) ) {
                    wp_set_post_terms( $post_id, array( $platform_term_ids[$soft['platform']] ), 'platform' );
                }

                if ( isset( $category_term_ids[$soft['category']] ) ) {
                    wp_set_post_terms( $post_id, array( $category_term_ids[$soft['category']] ), 'software_cat' );
                }
            }
        }
    }

    // Set seeded flag
    update_option( 'nursoft_demo_seeded', true );
}
add_action( 'admin_init', 'nursoft_seed_demo_data' );

/* ==========================================================================
   6. RATING STARS RENDER HELPER
   ========================================================================== */
function nursoft_render_stars( $rating ) {
    $rating = floatval( $rating );
    $output = '<div class="ratings rated" title="' . esc_attr($rating) . ' stars">';
    
    for ( $i = 1; $i <= 5; $i++ ) {
        if ( $rating >= $i ) {
            // Full Star SVG
            $output .= '<svg viewBox="0 0 320 320" class="rating-star"><path d="M160 0l49.4 100.1L320 116.4l-80 78 18.9 110.1L160 252.7l-98.9 51.8L80 194.4l-80-78 110.6-16.3L160 0z"/></svg>';
        } elseif ( $rating > ($i - 1) ) {
            // Half Star SVG
            $output .= '<svg viewBox="0 0 320 320" class="rating-star"><defs><linearGradient id="halfGrad"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#5d6275" stop-opacity="1"/></linearGradient></defs><path fill="url(#halfGrad)" d="M160 0l49.4 100.1L320 116.4l-80 78 18.9 110.1L160 252.7l-98.9 51.8L80 194.4l-80-78 110.6-16.3L160 0z"/></svg>';
        } else {
            // Empty Star SVG
            $output .= '<svg viewBox="0 0 320 320" class="rating-star empty" style="color:#5d6275;"><path d="M160 0l49.4 100.1L320 116.4l-80 78 18.9 110.1L160 252.7l-98.9 51.8L80 194.4l-80-78 110.6-16.3L160 0z"/></svg>';
        }
    }
    
    $output .= '</div>';
    return $output;
}

/* ==========================================================================
   7. DYNAMIC GLOWING BADGE RENDER HELPER
   ========================================================================== */
function nursoft_render_card_badge( $badge ) {
    if ( empty($badge) || $badge === 'none' ) {
        return '';
    }
    
    $badge_class = 'badge-' . esc_attr($badge);
    $badge_label = '';
    switch ($badge) {
        case 'featured': $badge_label = __('Featured', 'nursoft'); break;
        case 'recommended': $badge_label = __('Recommended', 'nursoft'); break;
        case 'preactivated': $badge_label = __('Preactivated', 'nursoft'); break;
        case 'full_version': $badge_label = __('Full Version', 'nursoft'); break;
        case 'free_download': $badge_label = __('Free Download', 'nursoft'); break;
        case 'multilingual': $badge_label = __('Multilingual', 'nursoft'); break;
        case 'portable': $badge_label = __('Portable', 'nursoft'); break;
        case 'cracked': $badge_label = __('Cracked', 'nursoft'); break;
        case 'unlocked': $badge_label = __('Unlocked', 'nursoft'); break;
        case 'retail': $badge_label = __('Retail Build', 'nursoft'); break;
        case 'patched': $badge_label = __('Patched', 'nursoft'); break;
        case 'beta_dev': $badge_label = __('Beta / Dev', 'nursoft'); break;
        case 'ad_free': $badge_label = __('Ad-Free', 'nursoft'); break;
        default: $badge_label = ucfirst(str_replace('_', ' ', $badge)); break;
    }
    
    return '<span class="card_badge ' . esc_attr($badge_class) . '" title="' . esc_attr($badge_label) . '">' . esc_html($badge_label) . '</span>';
}

/* ==========================================================================
   8. ADAPTIVE OS & DEVICE PLATFORM DETECTOR
   ========================================================================== */
function nursoft_get_prioritized_platform() {
    $user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
    
    // 1. Mobile Check (Ensure it is a phone/tablet, but NOT a desktop OS)
    $is_mobile = wp_is_mobile() || preg_match( '/Android|iPhone|iPad|iPod|Mobile/i', $user_agent );
    
    // If it is mobile, prioritize Android!
    if ( $is_mobile && ! preg_match( '/Windows NT/i', $user_agent ) && ! preg_match( '/Macintosh/i', $user_agent ) ) {
        return 'android';
    }
    
    // 2. Apple Macintosh (macOS desktop)
    if ( preg_match( '/Macintosh|Mac OS X/i', $user_agent ) && ! preg_match( '/iPhone|iPad|iPod/i', $user_agent ) ) {
        return 'mac';
    }
    
    // 3. Desktop Windows PC (Windows desktop)
    if ( preg_match( '/Windows|Win64|Win32/i', $user_agent ) ) {
        return 'ms-windows';
    }
    
    // Default fallback
    return 'ms-windows';
}

/* ==========================================================================
   9. UNIFIED CATALOG ARCHIVE SORTING BY MODIFIED DATE
   ========================================================================== */
function nursoft_order_archives_by_modified( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_tax( 'platform' ) || $query->is_tax( 'software_cat' ) || $query->is_search() || $query->is_post_type_archive( 'software' ) ) {
            $query->set( 'orderby', 'modified' );
            $query->set( 'order', 'DESC' );
        }
    }
}
add_action( 'pre_get_posts', 'nursoft_order_archives_by_modified' );

/* ==========================================================================
   10. INTERACTIVE QUICK VIEW DRAWER AJAX HANDLER
   ========================================================================== */
function nursoft_ajax_quick_view() {
    // Nonce verification for security
    check_ajax_referer( 'nursoft-quickview-nonce', 'nonce' );
    
    $post_id = isset( $_GET['post_id'] ) ? intval( $_GET['post_id'] ) : 0;
    if ( ! $post_id || get_post_type( $post_id ) !== 'software' ) {
        wp_send_json_error( array( 'message' => __( 'Invalid software item ID.', 'nursoft' ) ) );
    }
    
    $post = get_post( $post_id );
    setup_postdata( $post );
    
    // Fetch specifications
    $version      = get_post_meta( $post_id, '_nursoft_version', true );
    $size         = get_post_meta( $post_id, '_nursoft_size', true );
    $downloads    = get_post_meta( $post_id, '_nursoft_downloads', true );
    $reputation   = get_post_meta( $post_id, '_nursoft_reputation', true );
    $badge        = get_post_meta( $post_id, '_nursoft_badge', true );
    $requirements = get_post_meta( $post_id, '_nursoft_requirements', true );
    $versions_list= get_post_meta( $post_id, '_nursoft_versions_list', true );
    
    // Formatting
    $downloads_formatted = $downloads !== '' ? number_format( intval($downloads) ) : '0';
    $reputation_value    = $reputation !== '' ? floatval($reputation) : 4.5;
    $size_display        = $size !== '' ? esc_html($size) : 'N/A';
    $version_display     = $version !== '' ? esc_html($version) : '1.0.0';
    
    // Platform & Category Terms
    $platforms = wp_get_post_terms( $post_id, 'platform' );
    $platform_name = ! empty( $platforms ) && ! is_wp_error( $platforms ) ? $platforms[0]->name : 'Windows';
    $platform_slug = ! empty( $platforms ) && ! is_wp_error( $platforms ) ? $platforms[0]->slug : 'ms-windows';
    
    $categories = wp_get_post_terms( $post_id, 'software_cat' );
    $cat_name = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[0]->name : __('General', 'nursoft');
    
    // Screenshots Resolving
    $screenshots_ids = get_post_meta( $post_id, '_nursoft_screenshots_ids', true );
    $screenshots_ext = get_post_meta( $post_id, '_nursoft_screenshots_ext', true );
    $old_screenshots = get_post_meta( $post_id, '_nursoft_screenshots', true );
    if ( empty($screenshots_ext) && ! empty($old_screenshots) && ! is_numeric($old_screenshots) ) {
        $screenshots_ext = $old_screenshots;
    }
    
    $screenshot_urls = array();
    if ( ! empty($screenshots_ids) ) {
        $ids_array = explode(',', $screenshots_ids);
        foreach ( $ids_array as $img_id ) {
            $full_url = wp_get_attachment_image_url( $img_id, 'large' );
            if ( $full_url ) {
                $screenshot_urls[] = $full_url;
            }
        }
    }
    if ( ! empty($screenshots_ext) ) {
        $ext_array = array_map('trim', explode(',', $screenshots_ext));
        foreach ( $ext_array as $url ) {
            if ( ! empty($url) ) {
                $screenshot_urls[] = $url;
            }
        }
    }
    
    ob_start();
    ?>
    <!-- Software Header Info -->
    <div class="drawer-icon-header">
        <div class="drawer-icon">
            <?php if ( has_post_thumbnail( $post_id ) ) : ?>
                <?php echo get_the_post_thumbnail( $post_id, 'thumbnail' ); ?>
            <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" alt="logo" />
            <?php endif; ?>
        </div>
        <div class="drawer-title-wrap">
            <h5 class="drawer-title"><?php echo esc_html( get_the_title( $post_id ) ); ?><?php if($version) echo ' ' . esc_html($version); ?></h5>
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-top: 4px;">
                <span class="drawer-cat"><?php echo esc_html( $cat_name ); ?></span>
                <?php echo nursoft_render_card_badge( $badge ); ?>
            </div>
        </div>
    </div>
    
    <!-- Specs Grid -->
    <div class="soft_tech_grid" style="display:grid; grid-template-columns: repeat(2, 1fr); gap:12px; margin-bottom:24px;">
        <div class="soft_tech_card" style="padding:12px; background:var(--bg-surface-hover); border:1px solid var(--border-color); border-radius:10px;">
            <div class="soft_tech_label" style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;"><?php _e('Version', 'nursoft'); ?></div>
            <div class="soft_tech_value" style="font-size:14px; font-weight:700; color:var(--text-primary);"><?php echo $version_display; ?></div>
        </div>
        <div class="soft_tech_card" style="padding:12px; background:var(--bg-surface-hover); border:1px solid var(--border-color); border-radius:10px;">
            <div class="soft_tech_label" style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;"><?php _e('File Size', 'nursoft'); ?></div>
            <div class="soft_tech_value" style="font-size:14px; font-weight:700; color:var(--text-primary);"><?php echo $size_display; ?></div>
        </div>
        <div class="soft_tech_card" style="padding:12px; background:var(--bg-surface-hover); border:1px solid var(--border-color); border-radius:10px;">
            <div class="soft_tech_label" style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;"><?php _e('Platform', 'nursoft'); ?></div>
            <div class="soft_tech_value" style="font-size:14px; font-weight:700; color:var(--accent-blue); text-transform:capitalize;"><?php echo esc_html( $platform_name ); ?></div>
        </div>
        <div class="soft_tech_card" style="padding:12px; background:var(--bg-surface-hover); border:1px solid var(--border-color); border-radius:10px;">
            <div class="soft_tech_label" style="font-size:11px; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;"><?php _e('Downloads', 'nursoft'); ?></div>
            <div class="soft_tech_value" style="font-size:14px; font-weight:700; color:var(--accent-green);"><?php echo $downloads_formatted; ?></div>
        </div>
    </div>
    
    <!-- Short Description / Excerpt -->
    <div style="margin-bottom:24px;">
        <h6 style="font-size:13px; font-weight:700; color:var(--text-primary); margin-bottom:8px; display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;color:var(--accent-blue);"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            <span><?php _e('Overview', 'nursoft'); ?></span>
        </h6>
        <p style="font-size:13px; color:var(--text-secondary); line-height:1.6; margin:0;"><?php echo get_the_excerpt( $post_id ); ?></p>
    </div>
    
    <!-- Requirements if any -->
    <?php if ( ! empty($requirements) ) : ?>
        <div style="margin-bottom:24px; padding:12px; background:rgba(255, 235, 59, 0.03); border:1px solid rgba(255, 235, 59, 0.1); border-radius:10px;">
            <h6 style="font-size:12px; font-weight:700; color:var(--accent-yellow); margin-bottom:6px; text-transform:uppercase;"><?php _e('System Requirements', 'nursoft'); ?></h6>
            <div style="font-size:12px; color:var(--text-secondary); line-height:1.5;"><?php echo esc_html( $requirements ); ?></div>
        </div>
    <?php endif; ?>
    
    <!-- Screenshot gallery list -->
    <?php if ( ! empty( $screenshot_urls ) ) : ?>
        <div style="margin-bottom:24px;">
            <h6 style="font-size:13px; font-weight:700; color:var(--text-primary); margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;color:var(--accent-blue);"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/></svg>
                <span><?php _e('Screenshots', 'nursoft'); ?></span>
            </h6>
            <div style="display:flex; gap:10px; overflow-x:auto; padding-bottom:8px; scrollbar-width:thin; scrollbar-color:var(--border-color) transparent;">
                <?php foreach ( $screenshot_urls as $img_url ) : ?>
                    <a href="<?php echo esc_url($img_url); ?>" target="_blank" style="flex-shrink:0; width:150px; height:90px; border:1px solid var(--border-color); border-radius:8px; overflow:hidden; background:#050608; display:block;">
                        <img src="<?php echo esc_url($img_url); ?>" style="width:100%; height:100%; object-fit:cover; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1.0)'" alt="Screenshot" />
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Version Release Downloads -->
    <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--border-color);">
        <h6 style="font-size:13px; font-weight:700; color:var(--text-primary); margin-bottom:12px; display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;color:var(--accent-blue);"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
            <span><?php _e('Download Release', 'nursoft'); ?></span>
        </h6>
        
        <?php
        // Resolve target items
        $has_downloads = false;
        if ( ! empty($versions_list) && is_array($versions_list) ) {
            $has_downloads = true;
            $latest_item = $versions_list[0];
            $latest_ver = $latest_item['version'];
            $latest_direct = $latest_item['direct_url'];
            $latest_torrent = $latest_item['torrent_url'];
            $version_idx = 0;
        } else {
            // Legacy fallbacks
            $latest_ver = $version_display;
            $latest_direct = get_post_meta( $post_id, '_nursoft_direct_download_url', true );
            if ( empty($latest_direct) ) {
                $latest_direct = get_post_meta( $post_id, '_nursoft_download_url', true );
            }
            $latest_torrent = get_post_meta( $post_id, '_nursoft_torrent_download_url', true );
            $version_idx = 0;
            if ( ! empty($latest_direct) || ! empty($latest_torrent) ) {
                $has_downloads = true;
            }
        }
        ?>
        
        <?php if ( $has_downloads ) : ?>
            <div style="display:flex; flex-direction:column; gap:10px; width:100%;">
                <?php if ( ! empty($latest_direct) ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( array( 'nursoft_action' => 'download_portal', 'post_id' => $post_id, 'version_idx' => $version_idx, 'type' => 'direct' ), home_url('/') ) ); ?>" target="_blank" class="download_btn" style="width:100%; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; height:auto; text-decoration:none; font-weight:600; font-size:13px; border-radius:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width:16px;height:16px;fill:currentColor;"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                        <span>Direct Download (<?php echo esc_html($latest_ver); ?>)</span>
                    </a>
                <?php endif; ?>
                <?php if ( ! empty($latest_torrent) ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( array( 'nursoft_action' => 'download_portal', 'post_id' => $post_id, 'version_idx' => $version_idx, 'type' => 'torrent' ), home_url('/') ) ); ?>" target="_blank" class="download_btn" style="width:100%; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; height:auto; background:linear-gradient(135deg, #00b4db, #0083b0); box-shadow:none; text-decoration:none; font-weight:600; font-size:13px; border-radius:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width:16px;height:16px;fill:currentColor;"><path d="M12 2C7.58 2 4 5.58 4 10v4c0 3.31 2.69 6 6 6h4c3.31 0 6-2.69 6-6v-4c0-4.42-3.58-8-8-8zm6 12c0 2.21-1.79 4-4 4h-4c-2.21 0-4-1.79-4-4v-4c0-3.31 2.69-6 6-6s6 2.69 6 6v4zM8 10h2v2H8zm6 0h2v2h-2z"/></svg>
                        <span>Torrent Download (<?php echo esc_html($latest_ver); ?>)</span>
                    </a>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <p style="font-size:12px; color:var(--text-muted); text-align:center;"><?php _e('No download links available for this product yet.', 'nursoft'); ?></p>
        <?php endif; ?>
        
        <!-- View Full Page details -->
        <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" style="display:block; text-align:center; margin-top:15px; font-size:12px; color:var(--accent-blue); font-weight:600; text-decoration:none; transition:opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
            <?php _e('View Full Details & Changelog &rarr;', 'nursoft'); ?>
        </a>
    </div>
    <?php
    wp_reset_postdata();
    $html = ob_get_clean();
    wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_nursoft_quick_view', 'nursoft_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_nursoft_quick_view', 'nursoft_ajax_quick_view' );


/* ==========================================================================
   11. IMMERSIVE DOWNLOAD PORTAL REDIRECT INTERCEPTOR
   ========================================================================== */
function nursoft_download_portal_redirect() {
    if ( isset( $_GET['nursoft_action'] ) && $_GET['nursoft_action'] === 'download_portal' ) {
        $post_id     = isset( $_GET['post_id'] ) ? intval( $_GET['post_id'] ) : 0;
        $version_idx = isset( $_GET['version_idx'] ) ? intval( $_GET['version_idx'] ) : 0;
        $type        = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : 'direct';
        
        if ( ! $post_id || get_post_type( $post_id ) !== 'software' ) {
            wp_safe_redirect( home_url() );
            exit;
        }
        
        $versions_list = get_post_meta( $post_id, '_nursoft_versions_list', true );
        if ( ! is_array( $versions_list ) || ! isset( $versions_list[$version_idx] ) ) {
            // Fallback to legacy single fields
            $version_num = get_post_meta( $post_id, '_nursoft_version', true );
            $direct_url  = get_post_meta( $post_id, '_nursoft_direct_download_url', true );
            if ( empty($direct_url) ) {
                $direct_url = get_post_meta( $post_id, '_nursoft_download_url', true );
            }
            $torrent_url = get_post_meta( $post_id, '_nursoft_torrent_download_url', true );
            
            $item = array(
                'version'     => $version_num ? $version_num : '1.0.0',
                'direct_url'  => $direct_url,
                'torrent_url' => $torrent_url,
            );
        } else {
            $item = $versions_list[$version_idx];
        }
        
        $download_url = ( $type === 'torrent' ) ? $item['torrent_url'] : $item['direct_url'];
        if ( empty( $download_url ) ) {
            wp_safe_redirect( get_permalink( $post_id ) );
            exit;
        }
        
        // Render the beautiful immersive download portal page!
        include get_template_directory() . '/page-download-portal.php';
        exit;
    }
}
add_action( 'template_redirect', 'nursoft_download_portal_redirect' );


/* ==========================================================================
   12. CUSTOM LOGO & ADVERTISING SYSTEM (Customizer Options)
   ========================================================================== */

/**
 * Register Customizer options for custom logo upload and ad placement codes.
 */
function nursoft_customize_register( $wp_customize ) {
    // 1. Logo Customization Section
    $wp_customize->add_section( 'nursoft_logo_section', array(
        'title'       => __( 'Site Logo & Identity', 'nursoft' ),
        'priority'    => 30,
        'description' => __( 'Upload your custom site logo (replaces the default NURSOFT text and icon).', 'nursoft' ),
    ) );

    $wp_customize->add_setting( 'nursoft_custom_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'nursoft_custom_logo', array(
        'label'    => __( 'Upload Custom Logo', 'nursoft' ),
        'section'  => 'nursoft_logo_section',
        'settings' => 'nursoft_custom_logo',
    ) ) );

    // 2. Advertisement Codes Section
    $wp_customize->add_section( 'nursoft_ads_section', array(
        'title'       => __( 'Advertising System (Ads)', 'nursoft' ),
        'priority'    => 40,
        'description' => __( 'Paste your AdSense or HTML banner advertising codes here. Leave fields empty to hide ads.', 'nursoft' ),
    ) );

    // Ad Slot 1: Homepage Top banner (Below Header)
    $wp_customize->add_setting( 'nursoft_ad_home_top', array(
        'default'           => '',
        'sanitize_callback' => 'nursoft_sanitize_ad_code',
    ) );
    $wp_customize->add_control( 'nursoft_ad_home_top', array(
        'label'    => __( 'Homepage Top Banner (728x90)', 'nursoft' ),
        'section'  => 'nursoft_ads_section',
        'type'     => 'textarea',
    ) );

    // Ad Slot 2: Homepage Bottom banner (Above Footer)
    $wp_customize->add_setting( 'nursoft_ad_home_bottom', array(
        'default'           => '',
        'sanitize_callback' => 'nursoft_sanitize_ad_code',
    ) );
    $wp_customize->add_control( 'nursoft_ad_home_bottom', array(
        'label'    => __( 'Homepage Bottom Banner (728x90)', 'nursoft' ),
        'section'  => 'nursoft_ads_section',
        'type'     => 'textarea',
    ) );

    // Ad Slot 3: Single Software Page Top banner
    $wp_customize->add_setting( 'nursoft_ad_single_top', array(
        'default'           => '',
        'sanitize_callback' => 'nursoft_sanitize_ad_code',
    ) );
    $wp_customize->add_control( 'nursoft_ad_single_top', array(
        'label'    => __( 'Single Page Top Banner (Near Title)', 'nursoft' ),
        'section'  => 'nursoft_ads_section',
        'type'     => 'textarea',
    ) );

    // Ad Slot 4: Inside Article / Content Middle
    $wp_customize->add_setting( 'nursoft_ad_single_content', array(
        'default'           => '',
        'sanitize_callback' => 'nursoft_sanitize_ad_code',
    ) );
    $wp_customize->add_control( 'nursoft_ad_single_content', array(
        'label'    => __( 'Inside Article Content (Middle of Page)', 'nursoft' ),
        'section'  => 'nursoft_ads_section',
        'type'     => 'textarea',
    ) );

    // Ad Slot 5: Single Software Page Bottom banner (Above Downloads)
    $wp_customize->add_setting( 'nursoft_ad_single_bottom', array(
        'default'           => '',
        'sanitize_callback' => 'nursoft_sanitize_ad_code',
    ) );
    $wp_customize->add_control( 'nursoft_ad_single_bottom', array(
        'label'    => __( 'Single Page Bottom Banner (Above Downloads)', 'nursoft' ),
        'section'  => 'nursoft_ads_section',
        'type'     => 'textarea',
    ) );

    // Ad Slot 6: Sidebar Banner Ad
    $wp_customize->add_setting( 'nursoft_ad_sidebar', array(
        'default'           => '',
        'sanitize_callback' => 'nursoft_sanitize_ad_code',
    ) );
    $wp_customize->add_control( 'nursoft_ad_sidebar', array(
        'label'    => __( 'Sidebar Square/Vertical Banner (300x250 or 300x600)', 'nursoft' ),
        'section'  => 'nursoft_ads_section',
        'type'     => 'textarea',
    ) );

    // 3. Module Control Section
    $wp_customize->add_section( 'nursoft_modules_section', array(
        'title'       => __( 'Theme Modules (ON/OFF)', 'nursoft' ),
        'priority'    => 25,
        'description' => __( 'Enable or disable major modules of the theme. Disabling a module automatically unregisters its post type, taxonomy, metaboxes, and removes its menu buttons.', 'nursoft' ),
    ) );

    // Enable/Disable E-Books Module
    $wp_customize->add_setting( 'nursoft_enable_books', array(
        'default'           => true,
        'sanitize_callback' => 'nursoft_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'nursoft_enable_books', array(
        'label'    => __( 'Enable E-Books Module', 'nursoft' ),
        'section'  => 'nursoft_modules_section',
        'type'     => 'checkbox',
    ) );

    // Enable/Disable Video Courses Module
    $wp_customize->add_setting( 'nursoft_enable_courses', array(
        'default'           => true,
        'sanitize_callback' => 'nursoft_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'nursoft_enable_courses', array(
        'label'    => __( 'Enable Video Courses Module', 'nursoft' ),
        'section'  => 'nursoft_modules_section',
        'type'     => 'checkbox',
    ) );
}
add_action( 'customize_register', 'nursoft_customize_register' );

/**
 * Sanitization helper for custom checkboxes
 */
function nursoft_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && ( true === $checked || '1' === $checked || 1 === $checked || 'on' === $checked ) ) ? true : false );
}


/**
 * Sanitization helper for custom script/ad codes
 */
function nursoft_sanitize_ad_code( $input ) {
    return $input; // Allow raw script tag inputs for Adsense/Javascript codes
}

/**
 * Helper function to render advertisement wrappers nicely in templates
 */
function nursoft_render_ad( $ad_option_key, $css_styles = 'margin: 20px auto; text-align: center; max-width: 100%; display: flex; justify-content: center;' ) {
    $ad_code = get_theme_mod( $ad_option_key, '' );
    if ( ! empty( $ad_code ) ) {
        echo '<div class="nursoft-ad-container" style="' . esc_attr( $css_styles ) . '">';
        echo $ad_code;
        echo '</div>';
    }
}

/**
 * Inject ad directly into the middle of the article content (Single Software page)
 */
function nursoft_inject_content_ads( $content ) {
    if ( is_singular( 'software' ) && in_the_loop() && is_main_query() ) {
        $ad_code = get_theme_mod( 'nursoft_ad_single_content', '' );
        if ( ! empty( $ad_code ) ) {
            $ad_html = '<div class="nursoft-ad-container" style="margin: 25px auto; text-align: center; max-width: 100%; display: flex; justify-content: center;">' . $ad_code . '</div>';
            
            // Insert ad after the 2nd paragraph if possible, otherwise append it
            $paragraphs = explode( '</p>', $content );
            if ( count( $paragraphs ) > 2 ) {
                foreach ( $paragraphs as $index => $paragraph ) {
                    if ( trim( $paragraph ) ) {
                        $paragraphs[$index] .= '</p>';
                    }
                    if ( $index === 1 ) { // After 2nd paragraph
                        $paragraphs[$index] .= $ad_html;
                    }
                }
                $content = implode( '', $paragraphs );
            } else {
                $content .= $ad_html;
            }
        }
    }
    return $content;
}
add_filter( 'the_content', 'nursoft_inject_content_ads' );


/* ==========================================================================
   13. AUTOMATIC ADVANCED SEO ENGINE (Meta Tags & JSON-LD Schema)
   ========================================================================== */

/**
 * Dynamically injects highly optimized SEO meta tags, OpenGraph tags,
 * and structured JSON-LD schemas inside the <head> section of all pages.
 */
function nursoft_dynamic_seo_engine() {
    // 1. Robots, Canonical & Core Meta tags
    echo "\n<!-- Nursoft Premium SEO Engine (v1.9.0) -->\n";
    
    $canonical_url = esc_url( home_url( $_SERVER['REQUEST_URI'] ) );
    echo '<link rel="canonical" href="' . $canonical_url . '" />' . "\n";
    
    // Default Fallback values
    $site_name   = get_bloginfo( 'name' );
    $description = get_bloginfo( 'description' );
    if ( empty( $description ) ) {
        $description = __( 'Download premium verified software, apps, games and utilities for Windows, macOS, Android. Blazing fast direct and torrent download mirrors.', 'nursoft' );
    }
    
    $meta_title = $site_name;
    $og_type    = 'website';
    $og_image   = get_theme_mod( 'nursoft_custom_logo', get_template_directory_uri() . '/screenshot.png' );
    
    // 2. Specific Optimizations for Single Software Listing Pages
    if ( is_singular( 'software' ) ) {
        global $post;
        $post_id = $post->ID;
        
        $version      = get_post_meta( $post_id, '_nursoft_version', true );
        $size         = get_post_meta( $post_id, '_nursoft_size', true );
        $requirements = get_post_meta( $post_id, '_nursoft_requirements', true );
        
        $platforms     = wp_get_post_terms( $post_id, 'platform' );
        $platform_name = ! empty( $platforms ) && ! is_wp_error( $platforms ) ? $platforms[0]->name : 'Windows';
        
        $categories = wp_get_post_terms( $post_id, 'software_cat' );
        $category_name = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[0]->name : 'Utilities';

        // Title: [AppName] [Version] Free Download ([Platform])
        $meta_title = sprintf( __( '%1$s %2$s Free Download (%3$s) &mdash; %4$s', 'nursoft' ), get_the_title( $post_id ), $version, $platform_name, $site_name );
        
        // Excerpt or dynamic description
        if ( has_excerpt( $post_id ) ) {
            $description = wp_strip_all_tags( get_the_excerpt( $post_id ) );
        } else {
            $description = sprintf( __( 'Download %1$s %2$s for %3$s (%4$s). Verified safe and secure high-speed download link setup. Get it now on %5$s!', 'nursoft' ), get_the_title( $post_id ), $version, $platform_name, $size, $site_name );
        }
        
        $og_type = 'article';
        if ( has_post_thumbnail( $post_id ) ) {
            $og_image = get_the_post_thumbnail_url( $post_id, 'large' );
        }
        
        // Dynamic Keywords Generation
        $keywords = array(
            get_the_title($post_id),
            get_the_title($post_id) . ' ' . $version,
            'download ' . get_the_title($post_id),
            'free ' . get_the_title($post_id),
            $platform_name . ' software',
            $category_name,
            'direct download',
            'torrent link'
        );
        echo '<meta name="keywords" content="' . esc_attr( implode( ', ', $keywords ) ) . '" />' . "\n";
        
        // === Injection of structured JSON-LD Schema (SoftwareApplication & Breadcrumbs) ===
        ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "SoftwareApplication",
      "@id": "<?php echo esc_url( get_permalink( $post_id ) ); ?>#software",
      "name": "<?php echo esc_attr( get_the_title( $post_id ) ); ?>",
      "softwareVersion": "<?php echo esc_attr( $version ); ?>",
      "fileSize": "<?php echo esc_attr( $size ); ?>",
      "applicationCategory": "<?php echo esc_attr( $category_name ); ?>",
      "operatingSystem": "<?php echo esc_attr( $platform_name ); ?>",
      "downloadUrl": "<?php echo esc_url( get_permalink( $post_id ) ); ?>",
      "image": "<?php echo esc_url( $og_image ); ?>",
      "description": "<?php echo esc_attr( $description ); ?>",
      "offers": {
        "@type": "Offer",
        "price": "0.00",
        "priceCurrency": "USD"
      }
      <?php if ( ! empty( $requirements ) ) : ?>,
      "requirements": "<?php echo esc_attr( $requirements ); ?>"
      <?php endif; ?>
    },
    {
      "@type": "BreadcrumbList",
      "@id": "<?php echo esc_url( get_permalink( $post_id ) ); ?>#breadcrumb",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "<?php _e('Home', 'nursoft'); ?>",
          "item": "<?php echo esc_url( home_url('/') ); ?>"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "<?php echo esc_attr( $platform_name ); ?>",
          "item": "<?php echo esc_url( get_term_link( $platforms[0]->term_id ) ); ?>"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "<?php echo esc_attr( get_the_title( $post_id ) ); ?>"
        }
      ]
    }
  ]
}
</script>
        <?php
    }
    
    // 3. Render SEO tags in head
    echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />' . "\n";
    
    // OpenGraph (Facebook, Discord, Telegram preview)
    echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $meta_title ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . $canonical_url . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '" />' . "\n";
    echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";
    echo '<meta property="og:image:width" content="1200" />' . "\n";
    echo '<meta property="og:image:height" content="630" />' . "\n";
    
    // Twitter Cards
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $meta_title ) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url( $og_image ) . '" />' . "\n";
    echo "<!-- End Nursoft SEO Engine -->\n\n";
}
add_action( 'wp_head', 'nursoft_dynamic_seo_engine', 1 );


/* ==========================================================================
   14. INTELLIGENT AUTO-TAGGING ENGINE (On Post Save)
   ========================================================================== */

/**
 * Automatically generates and assigns highly optimized tags to a Software CPT post 
 * based on its title, content, platform, and standard high-traffic software keywords.
 */
function nursoft_auto_tagging_system( $post_id, $post, $update ) {
    // 1. Safety checks: verify CPT, prevent autosaves, revisions or quick edits from stripping tags
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( $post->post_type !== 'software' ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Prevent recursive loop when updating tags
    remove_action( 'save_post', 'nursoft_auto_tagging_system', 10, 3 );

    $title   = strtolower( $post->post_title );
    $content = strtolower( $post->post_content );
    
    $generated_tags = array();

    // 2. Extract software brand name from title (e.g. "Adobe Photoshop 2026 v27" -> "Adobe Photoshop")
    // Strip versions, builds, and common taglines
    $clean_title = preg_replace( '/\b(v?\d+(\.\d+)*|202\d|build|crack|free|download|full|activated|latest|version)\b/i', '', $post->post_title );
    $clean_title = trim( preg_replace( '/\s+/', ' ', $clean_title ) );
    
    if ( ! empty( $clean_title ) && strlen( $clean_title ) > 2 ) {
        $generated_tags[] = $clean_title;
        // Also add first two words if title is long (e.g. "Adobe Photoshop Elements" -> "Adobe Photoshop")
        $title_words = explode( ' ', $clean_title );
        if ( count( $title_words ) > 1 ) {
            $generated_tags[] = $title_words[0] . ' ' . $title_words[1];
        }
    }

    // 3. High-Traffic Core Software Tag Dictionary & Rules
    $keyword_dictionary = array(
        'crack'       => array( 'Cracked', 'Pre-activated', 'Full Version' ),
        'patch'       => array( 'Patched', 'Pre-activated' ),
        'keygen'      => array( 'Keygen included', 'Serial Key' ),
        'serial'      => array( 'Serial Key', 'License Code' ),
        'activated'   => array( 'Activated', 'Pre-activated' ),
        'portable'    => array( 'Portable Version', 'No Install' ),
        'repack'      => array( 'Repack', 'Pre-activated' ),
        'multilingual'=> array( 'Multilingual', 'Multi-language' ),
        'offline'     => array( 'Offline Installer', 'Standalone Setup' ),
        'android'     => array( 'Android App', 'APK Mod', 'Mobile App' ),
        'apk'         => array( 'APK Mod', 'Mobile App', 'Android Game' ),
        'windows'     => array( 'Windows Software', 'PC App' ),
        'mac'         => array( 'macOS Software', 'Mac App' ),
        'game'        => array( 'PC Games', 'Cracked Games' ),
        'utility'     => array( 'PC Utilities', 'System Tools' ),
        'antivirus'   => array( 'Security Tools', 'Antivirus' ),
        'graphic'     => array( 'Graphics Design', 'Creative Tools' ),
        'editor'      => array( 'Media Editors', 'Creative Tools' ),
        'video'       => array( 'Video Editing', 'Media Tools' ),
        'audio'       => array( 'Audio Editing', 'Music Production' ),
        'driver'      => array( 'System Drivers', 'System Tools' ),
        'pdf'         => array( 'PDF Tools', 'Office Suite' ),
        'office'      => array( 'Office Suite', 'Productivity Tools' ),
        'free'        => array( 'Free Download', 'Free Premium' ),
    );

    // Scan Title and Content against the SEO Tag Dictionary
    foreach ( $keyword_dictionary as $trigger => $tags_to_apply ) {
        if ( strpos( $title, $trigger ) !== false || strpos( $content, $trigger ) !== false ) {
            foreach ( $tags_to_apply as $tag ) {
                $generated_tags[] = $tag;
            }
        }
    }

    // 4. Default Core Platform Tags (Highly Recommended for this Software Theme)
    $platforms = wp_get_post_terms( $post_id, 'platform' );
    if ( ! empty( $platforms ) && ! is_wp_error( $platforms ) ) {
        foreach ( $platforms as $platform ) {
            $generated_tags[] = $platform->name;
            $generated_tags[] = $platform->name . ' Download';
        }
    }

    // 5. Default General Niches Tags (Universal software download tags)
    $generated_tags[] = 'Free Download';
    $generated_tags[] = 'Verified Safe';
    $generated_tags[] = 'Latest Version';
    $generated_tags[] = 'Direct Link';

    // 6. Clean, sanitize, and unique filter the tags
    $generated_tags = array_map( 'sanitize_text_field', $generated_tags );
    $generated_tags = array_unique( array_filter( array_map( 'trim', $generated_tags ) ) );

    // Limit to 10 highly relevant tags to prevent tag spamming and keep clean index
    $final_tags = array_slice( $generated_tags, 0, 10 );

    // 7. Assign the tags back to the Software post (Appends tags without deleting existing custom user tags)
    wp_set_post_terms( $post_id, $final_tags, 'post_tag', true );

    // Re-register hook
    add_action( 'save_post', 'nursoft_auto_tagging_system', 10, 3 );
}
add_action( 'save_post', 'nursoft_auto_tagging_system', 10, 3 );


/**
 * AJAX endpoints for premium Bookmarks/Favorites sync
 */
add_action( 'wp_ajax_nursoft_sync_favorites', 'nursoft_sync_favorites_ajax' );
add_action( 'wp_ajax_nopriv_nursoft_sync_favorites', 'nursoft_sync_favorites_ajax' );

function nursoft_sync_favorites_ajax() {
    // Return early if nonce is invalid
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'nursoft-fav-nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Invalid security token' ) );
    }
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => 'User not logged in' ) );
    }
    
    $user_id   = get_current_user_id();
    $favorites = isset( $_POST['favorites'] ) ? array_map( 'intval', $_POST['favorites'] ) : array();
    
    update_user_meta( $user_id, '_nursoft_favorites', $favorites );
    wp_send_json_success( array( 'message' => 'Favorites synced successfully' ) );
}

add_action( 'wp_ajax_nursoft_get_favorites_details', 'nursoft_get_favorites_details_ajax' );
add_action( 'wp_ajax_nopriv_nursoft_get_favorites_details', 'nursoft_get_favorites_details_ajax' );

function nursoft_get_favorites_details_ajax() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'nursoft-fav-nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Invalid security token' ) );
    }
    
    $post_ids = isset( $_POST['post_ids'] ) ? array_map( 'intval', $_POST['post_ids'] ) : array();
    
    if ( is_user_logged_in() ) {
        $db_favs = get_user_meta( get_current_user_id(), '_nursoft_favorites', true );
        if ( is_array( $db_favs ) && ! empty( $db_favs ) ) {
            $post_ids = array_unique( array_merge( $post_ids, $db_favs ) );
        }
    }
    
    if ( empty( $post_ids ) ) {
        wp_send_json_success( array( 'html' => '<p class="no-bookmarks" style="text-align:center;color:var(--text-muted);padding:20px;font-weight:600;">' . esc_html__('No bookmarks saved yet.', 'nursoft') . '</p>' ) );
    }
    
    $args = array(
        'post_type'      => array( 'software', 'book', 'course' ),
        'post__in'       => $post_ids,
        'posts_per_page' => -1,
    );
    
    $query = new WP_Query( $args );
    $html = '';
    
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id    = get_the_ID();
            $post_type  = get_post_type();
            
            // Determine version tag
            $latest_version = '';
            if ( $post_type === 'software' ) {
                $versions = get_post_meta( $post_id, '_nursoft_versions_list', true );
                if ( is_array( $versions ) && ! empty( $versions ) ) {
                    $latest_version = $versions[0]['version'];
                } else {
                    $latest_version = get_post_meta( $post_id, '_nursoft_version', true );
                }
            } elseif ( $post_type === 'book' ) {
                $latest_version = get_post_meta( $post_id, '_nursoft_book_format', true );
            } elseif ( $post_type === 'course' ) {
                $latest_version = get_post_meta( $post_id, '_nursoft_course_duration', true );
            }
            
            $thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'thumbnail' ) : get_template_directory_uri() . '/assets/img/default-logo.png';
            
            $html .= '<div class="fav-item-card" data-post-id="' . esc_attr( $post_id ) . '" data-latest-version="' . esc_attr( $latest_version ) . '" style="display:flex;align-items:center;gap:15px;padding:12px;background:var(--bg-element);border:1px solid var(--border-color);border-radius:10px;margin-bottom:10px;position:relative; transition: transform var(--transition-fast);">';
            $html .= '  <img src="' . esc_url( $thumb ) . '" style="width:40px;height:40px;object-fit:cover;border-radius:8px;background:var(--bg-surface);border:1px solid var(--border-color);" />';
            $html .= '  <div style="flex-grow:1;min-width:0;">';
            $html .= '    <a href="' . esc_url( get_permalink() ) . '" style="font-weight:700;font-size:13.5px;color:var(--text-primary);display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . esc_html( get_the_title() ) . '</a>';
            $html .= '    <span style="font-size:11px;color:var(--accent-blue);font-weight:700;text-transform:uppercase;">' . esc_html( $post_type ) . '</span>';
            $html .= '  </div>';
            $html .= '  <div class="fav-action-wrap" style="display:flex;align-items:center;gap:10px;">';
            $html .= '    <span class="update-badge-container" style="display:none;"></span>';
            $html .= '    <button class="remove-fav-btn" data-post-id="' . esc_attr( $post_id ) . '" style="background:none;border:none;color:var(--accent-magenta);cursor:pointer;padding:5px;display:flex;align-items:center;justify-content:center;" title="' . esc_attr__('Remove Bookmark', 'nursoft') . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>';
            $html .= '  </div>';
            $html .= '</div>';
        }
        wp_reset_postdata();
    } else {
        $html = '<p class="no-bookmarks" style="text-align:center;color:var(--text-muted);padding:20px;font-weight:600;">' . esc_html__('No bookmarks saved yet.', 'nursoft') . '</p>';
    }
    
    wp_send_json_success( array( 'html' => $html ) );
}


/**
 * AJAX Login Handler
 */
add_action( 'wp_ajax_nursoft_ajax_login', 'nursoft_ajax_login_handler' );
add_action( 'wp_ajax_nopriv_nursoft_ajax_login', 'nursoft_ajax_login_handler' );

function nursoft_ajax_login_handler() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'nursoft-fav-nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed.' ) );
    }

    $info = array();
    $info['user_login']    = sanitize_user( $_POST['username'] );
    $info['user_password'] = $_POST['password'];
    $info['remember']      = true;

    $user_signon = wp_signon( $info, false );

    if ( is_wp_error( $user_signon ) ) {
        wp_send_json_error( array( 'message' => $user_signon->get_error_message() ) );
    } else {
        wp_send_json_success( array( 'message' => 'Logged in successfully! Reloading...' ) );
    }
}

/**
 * AJAX Registration Handler
 */
add_action( 'wp_ajax_nursoft_ajax_register', 'nursoft_ajax_register_handler' );
add_action( 'wp_ajax_nopriv_nursoft_ajax_register', 'nursoft_ajax_register_handler' );

function nursoft_ajax_register_handler() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'nursoft-fav-nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed.' ) );
    }

    $username = sanitize_user( $_POST['username'] );
    $email    = sanitize_email( $_POST['email'] );
    $password = $_POST['password'];

    if ( username_exists( $username ) ) {
        wp_send_json_error( array( 'message' => 'Username already exists.' ) );
    }
    if ( email_exists( $email ) ) {
        wp_send_json_error( array( 'message' => 'Email already registered.' ) );
    }

    $user_id = wp_create_user( $username, $password, $email );

    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
    } else {
        // Automatically sign the user in
        $info = array();
        $info['user_login']    = $username;
        $info['user_password'] = $password;
        $info['remember']      = true;
        wp_signon( $info, false );

        wp_send_json_success( array( 'message' => 'Registered successfully! Reloading...' ) );
    }
}

/**
 * AJAX Notification Feed Handler
 */
add_action( 'wp_ajax_nursoft_get_recent_notifications', 'nursoft_get_recent_notifications_handler' );
add_action( 'wp_ajax_nopriv_nursoft_get_recent_notifications', 'nursoft_get_recent_notifications_handler' );

function nursoft_get_recent_notifications_handler() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'nursoft-fav-nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed.' ) );
    }

    $args = array(
        'post_type'      => array( 'software', 'book', 'course' ),
        'posts_per_page' => 5,
        'orderby'        => 'modified',
        'order'          => 'DESC'
    );

    $query = new WP_Query( $args );
    $html = '';

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id    = get_the_ID();
            $post_type  = get_post_type();
            $modified   = get_the_modified_date('U');
            $created    = get_the_date('U');

            // Determine if new or updated
            $is_update = ($modified - $created > 86400); // Modified more than 24 hours after creation
            $badge = $is_update ? '<span style="background:rgba(255, 0, 128, 0.1);color:var(--accent-magenta);font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;border:1px solid rgba(255, 0, 128, 0.2);">Updated</span>' : '<span style="background:rgba(43, 203, 186, 0.1);color:#2bcbba;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;border:1px solid rgba(43, 203, 186, 0.2);">New</span>';

            $thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'thumbnail' ) : get_template_directory_uri() . '/assets/img/default-logo.png';
            $time_diff = human_time_diff( get_the_modified_date('U'), current_time('timestamp') ) . ' ago';

            $html .= '<div class="notification-item" style="display:flex;align-items:center;gap:12px;padding:10px;border-bottom:1px solid var(--border-color);transition:background var(--transition-fast);" onmouseover="this.style.background=\'var(--bg-surface-hover)\'" onmouseout="this.style.background=\'none\'">';
            $html .= '  <img src="' . esc_url( $thumb ) . '" style="width:36px;height:36px;object-fit:cover;border-radius:6px;border:1px solid var(--border-color);" />';
            $html .= '  <div style="flex-grow:1;min-width:0;">';
            $html .= '    <a href="' . esc_url( get_permalink() ) . '" style="font-weight:600;font-size:12.5px;color:var(--text-primary);display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-decoration:none;">' . esc_html( get_the_title() ) . '</a>';
            $html .= '    <div style="display:flex;align-items:center;gap:8px;margin-top:2px;">';
            $html .= '      ' . $badge;
            $html .= '      <span style="font-size:10px;color:var(--text-muted);">' . esc_html( $time_diff ) . '</span>';
            $html .= '    </div>';
            $html .= '  </div>';
            $html .= '</div>';
        }
        wp_reset_postdata();
    } else {
        $html = '<p style="text-align:center;color:var(--text-muted);padding:15px;font-size:12px;">No new notifications.</p>';
    }

    wp_send_json_success( array( 'html' => $html ) );
}






