<?php
/**
 * The template for displaying the footer
 *
 * @author  Mohamed Nurdin Mgaza <codeoba@gmail.com>
 * @country Tanzania | +687001775
 * @version 1.7.0
 * @package Nursoft
 */
?>

<?php 
// 2. Homepage Bottom Ad Slot
if ( is_front_page() || is_home() ) {
    nursoft_render_ad( 'nursoft_ad_home_bottom' );
}
?>

<footer class="footer_wrap">
    <div class="container">
        <div class="footer_grid">
            <!-- Column 1: About -->
            <div class="footer_about">
                <h5>NURSOFT</h5>
                <p><?php _e('Nursoft is the ultimate premium software repository, delivering safe, secure, and blazing fast downloads for Windows, macOS, Android Apps, Games, and much more. Experience high-speed server mirrors and verified programs curated for your desktop and mobile experience.', 'nursoft'); ?></p>
            </div>

            <!-- Column 2: Explore Platforms -->
            <div>
                <h6 class="footer_col_title"><?php _e('Explore', 'nursoft'); ?></h6>
                <ul class="footer_links">
                    <?php
                    $platforms = get_terms( array(
                        'taxonomy'   => 'platform',
                        'hide_empty' => false,
                        'number'     => 5
                    ) );
                    if ( ! empty( $platforms ) && ! is_wp_error( $platforms ) ) {
                        foreach ( $platforms as $platform ) {
                            echo '<li><a href="' . esc_url( get_term_link( $platform ) ) . '">' . esc_html( $platform->name ) . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>

            <!-- Column 3: Software Categories -->
            <div>
                <h6 class="footer_col_title"><?php _e('Categories', 'nursoft'); ?></h6>
                <ul class="footer_links">
                    <?php
                    $categories = get_terms( array(
                        'taxonomy'   => 'software_cat',
                        'hide_empty' => false,
                        'number'     => 5
                    ) );
                    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                        foreach ( $categories as $cat ) {
                            echo '<li><a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="footer_bottom">
            <div>
                &copy; <?php echo date('Y'); ?> <strong>Nursoft</strong>. <?php _e('All rights reserved. Coded with passion & precision.', 'nursoft'); ?>
            </div>
            
            <!-- Social Media Icons (Telegram, Twitter, Discord) -->
            <div class="footer_socials">
                <!-- Telegram -->
                <a class="footer_social_link" href="https://t.me/" target="_blank" rel="noopener noreferrer" aria-label="Telegram link">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.25-5.54 3.69-.52.36-1 .53-1.42.52-.47-.01-1.37-.26-2.03-.48-.82-.27-1.47-.42-1.42-.88.03-.24.35-.49.97-.74 3.79-1.65 6.32-2.73 7.57-3.25 3.61-1.48 4.36-1.74 4.85-1.75.11 0 .35.03.5.16.13.12.16.29.18.41-.02.09-.02.19-.03.29z"/>
                    </svg>
                </a>
                
                <!-- Twitter/X -->
                <a class="footer_social_link" href="https://twitter.com/" target="_blank" rel="noopener noreferrer" aria-label="Twitter X link">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                
                <!-- Discord -->
                <a class="footer_social_link" href="https://discord.com/" target="_blank" rel="noopener noreferrer" aria-label="Discord link">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M19.27 4.73a.12.12 0 0 0-.07-.05 15 15 0 0 0-3.44-1.06.13.13 0 0 0-.14.07c-.15.26-.33.62-.45.92a13.8 13.8 0 0 0-4.34 0c-.12-.3-.3-.66-.46-.92a.13.13 0 0 0-.14-.07 15 15 0 0 0-3.44 1.06.12.12 0 0 0-.07.05 16 16 0 0 0-2.66 7.42c0 .02 0 .05.02.07a15.4 15.4 0 0 0 4.6 2.3.13.13 0 0 0 .15-.05c.38-.51.72-1.07 1-1.66a.13.13 0 0 0-.07-.18 10 10 0 0 1-1.44-.69.13.13 0 0 1-.01-.22c.1-.07.2-.15.29-.23a.13.13 0 0 1 .14-.02c3.02 1.38 6.29 1.38 9.26 0a.13.13 0 0 1 .14.02c.1.08.19.16.3.23a.13.13 0 0 1-.01.22 9.6 9.6 0 0 1-1.44.69.13.13 0 0 0-.07.18c.27.6.62 1.15.99 1.66a.13.13 0 0 0 .15.05 15.37 15.37 0 0 0 4.6-2.3.12.12 0 0 0 .02-.07 15.94 15.94 0 0 0-2.67-7.42zM8.52 11.9c-.9 0-1.63-.82-1.63-1.84s.71-1.83 1.63-1.83c.91 0 1.64.82 1.64 1.83s-.72 1.84-1.64 1.84zm6.97 0c-.9 0-1.63-.82-1.63-1.84s.71-1.83 1.63-1.83c.92 0 1.64.82 1.64 1.83s-.71 1.84-1.64 1.84z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Footer Credit Bar -->
<div style="background: var(--bg-element); border-top: 1px solid var(--border-color); padding: 14px 0; text-align: center;">
    <div class="container">
        <p style="font-size: 12px; color: var(--text-muted); margin: 0;">
            &copy; <?php echo date('Y'); ?> <strong style="color: var(--text-secondary);">NURSOFT</strong> &mdash;
            Designed &amp; Developed by <a href="mailto:codeoba@gmail.com" style="color: var(--accent-blue); text-decoration: none; font-weight: 600;">Mohamed Nurdin Mgaza</a>
            &mdash; Tanzania &middot;
            <span style="color: var(--text-muted);">v1.8.0</span>
        </p>
    </div>
</div>

<!-- Interactive Quick View Drawer Overlay and Content -->
<div class="nursoft-drawer-overlay" id="nursoft-drawer-overlay"></div>
<div class="nursoft-drawer" id="nursoft-drawer">
    <div class="drawer-header">
        <h4><?php _e('Quick View Details', 'nursoft'); ?></h4>
        <button class="drawer-close" id="drawer-close" aria-label="Close details panel">&times;</button>
    </div>
    <div class="drawer-body" id="drawer-body">
        <!-- Dynamic content will load here via AJAX -->
        <div style="display:flex;align-items:center;justify-content:center;height:100%;flex-direction:column;gap:12px;color:var(--text-muted);">
            <svg class="download-spinner" viewBox="0 0 50 50" style="width:36px;height:36px;animation:spin 1s linear infinite;fill:none;stroke:currentColor;stroke-width:4;stroke-linecap:round;stroke-dasharray:31.4 31.4;color:var(--accent-blue);">
                <circle cx="25" cy="25" r="20"></circle>
            </svg>
            <span>Loading spec data...</span>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
