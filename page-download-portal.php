<?php
/**
 * Template Name: Nursoft Premium Download Portal Wait Screen
 * 
 * Handles countdown, security scan checklists, and dynamic file downloads.
 * Bypasses normal loops to display a dedicated premium download landing.
 */

// Safety check: ensure variables are defined from the interceptor
if ( ! isset( $post_id ) || ! isset( $download_url ) ) {
    wp_safe_redirect( home_url() );
    exit;
}

// 1. Immediately increment download count on backend!
$downloads_count = get_post_meta( $post_id, '_nursoft_downloads', true );
$downloads_count = ( $downloads_count !== '' ) ? intval( $downloads_count ) : 0;
update_post_meta( $post_id, '_nursoft_downloads', $downloads_count + 1 );

// Fetch software information
$title = get_the_title( $post_id );
$version = isset( $item['version'] ) ? $item['version'] : get_post_meta( $post_id, '_nursoft_version', true );
$size = get_post_meta( $post_id, '_nursoft_size', true );
$size_display = ! empty($size) ? esc_html($size) : 'N/A';

$platforms = wp_get_post_terms( $post_id, 'platform' );
$plat_name = ! empty($platforms) ? $platforms[0]->name : 'Windows';
$plat_slug = ! empty($platforms) ? $platforms[0]->slug : 'ms-windows';

get_header();
?>

<main class="container main_content" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="portal-container" style="position: relative; overflow: hidden; width: 100%;">
        <!-- Dynamic Glowing Border Glow Effect -->
        <div style="position: absolute; top: -100px; left: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(0,136,255,0.15) 0%, transparent 70%); z-index: 1; pointer-events: none;"></div>
        <div style="position: absolute; bottom: -100px; right: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(37,211,102,0.1) 0%, transparent 70%); z-index: 1; pointer-events: none;"></div>

        <div style="position: relative; z-index: 2;">
            <!-- Software Summary Badge -->
            <div class="portal-software-card">
                <div class="portal-software-icon">
                    <?php if ( has_post_thumbnail( $post_id ) ) : ?>
                        <?php echo get_the_post_thumbnail( $post_id, 'thumbnail', array( 'style' => 'width:100%; height:100%; object-fit:contain;' ) ); ?>
                    <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/default-logo.png" style="width:100%; height:100%; object-fit:contain;" alt="logo" />
                    <?php endif; ?>
                </div>
                <div class="portal-software-info">
                    <h1><?php echo esc_html( $title ); ?></h1>
                    <div class="portal-software-meta">
                        <span style="color: var(--accent-blue); font-weight: 600; text-transform: capitalize;"><?php echo esc_html( $plat_name ); ?></span>
                        <span>&bull;</span>
                        <span><?php _e('Version:', 'nursoft'); ?> <strong><?php echo esc_html( $version ); ?></strong></span>
                        <span>&bull;</span>
                        <span><?php _e('Size:', 'nursoft'); ?> <strong><?php echo $size_display; ?></strong></span>
                    </div>
                </div>
            </div>

            <!-- Glowing Pulsing Circular Countdown -->
            <div class="portal-timer-wrap">
                <svg class="portal-timer-circle" width="140" height="140">
                    <circle class="portal-timer-bg" cx="70" cy="70" r="60"></circle>
                    <!-- Dasharray matches exact circumference: 2 * PI * r = 2 * 3.14159 * 60 = 376.99 -->
                    <circle class="portal-timer-progress" id="portal-progress" cx="70" cy="70" r="60" stroke-dashoffset="0"></circle>
                </svg>
                <div class="portal-timer-text" id="portal-timer-countdown">5</div>
            </div>

            <!-- Header message -->
            <h3 style="font-family: var(--font-heading); font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 10px;" id="portal-header-msg">
                <?php _e('Verifying Package Security...', 'nursoft'); ?>
            </h3>
            <p style="font-size: 14px; color: var(--text-secondary); max-width: 420px; margin: 0 auto 35px;" id="portal-desc-msg">
                <?php _e('Please wait while our cloud servers establish a secure tunnel and run integrity checks on your package.', 'nursoft'); ?>
            </p>

            <!-- Stepping Security Scan Checklist -->
            <div class="portal-scan-list">
                <!-- Step 1 -->
                <div class="portal-scan-item" id="scan-step-1" style="display: flex; align-items: center; gap: 12px; font-size: 13px; color: var(--text-secondary); transition: all 0.3s; padding: 6px 0;">
                    <div class="scan-status-dot" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: transparent;">
                        <svg viewBox="0 0 24 24" style="width: 10px; height: 10px; fill: none; stroke: currentColor; stroke-width: 4; display: none;" class="scan-check-svg"><path d="M20 6L9 17l-5-5"/></svg>
                    </div>
                    <span><?php _e('Connecting to secure file mirror...', 'nursoft'); ?></span>
                </div>

                <!-- Step 2 -->
                <div class="portal-scan-item" id="scan-step-2" style="display: flex; align-items: center; gap: 12px; font-size: 13px; color: var(--text-secondary); transition: all 0.3s; padding: 6px 0; opacity: 0.6;">
                    <div class="scan-status-dot" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: transparent;">
                        <svg viewBox="0 0 24 24" style="width: 10px; height: 10px; fill: none; stroke: currentColor; stroke-width: 4; display: none;" class="scan-check-svg"><path d="M20 6L9 17l-5-5"/></svg>
                    </div>
                    <span><?php _e('Performing Cloud Malware scan (100% clean)...', 'nursoft'); ?></span>
                </div>

                <!-- Step 3 -->
                <div class="portal-scan-item" id="scan-step-3" style="display: flex; align-items: center; gap: 12px; font-size: 13px; color: var(--text-secondary); transition: all 0.3s; padding: 6px 0; opacity: 0.6;">
                    <div class="scan-status-dot" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: transparent;">
                        <svg viewBox="0 0 24 24" style="width: 10px; height: 10px; fill: none; stroke: currentColor; stroke-width: 4; display: none;" class="scan-check-svg"><path d="M20 6L9 17l-5-5"/></svg>
                    </div>
                    <span><?php _e('Verifying handshake key integrity...', 'nursoft'); ?></span>
                </div>

                <!-- Step 4 -->
                <div class="portal-scan-item" id="scan-step-4" style="display: flex; align-items: center; gap: 12px; font-size: 13px; color: var(--text-secondary); transition: all 0.3s; padding: 6px 0; opacity: 0.6;">
                    <div class="scan-status-dot" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: transparent;">
                        <svg viewBox="0 0 24 24" style="width: 10px; height: 10px; fill: none; stroke: currentColor; stroke-width: 4; display: none;" class="scan-check-svg"><path d="M20 6L9 17l-5-5"/></svg>
                    </div>
                    <span><?php _e('Generating high-speed download tunnel...', 'nursoft'); ?></span>
                </div>
            </div>

            <!-- Download Started Box (Hidden Initially) -->
            <div id="portal-started-box" style="display: none; background: rgba(37,211,102,0.04); border: 1px dashed rgba(37,211,102,0.2); border-radius: 12px; padding: 20px; max-width: 400px; margin: 0 auto; text-align: center; animation: fadeInUp 0.4s ease;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 32px; height: 32px; color: var(--accent-green); fill: currentColor; margin-bottom: 8px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                <h4 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;"><?php _e('Your download has started!', 'nursoft'); ?></h4>
                <p style="font-size: 12px; color: var(--text-secondary); margin-bottom: 12px;"><?php _e('The browser is now downloading the file. If it did not start automatically, please click below.', 'nursoft'); ?></p>
                <a href="<?php echo esc_url( $download_url ); ?>" class="download_btn" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 12px; border-radius: 6px; text-decoration: none; height: auto;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 14px; height: 14px; fill: currentColor;"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                    <span><?php _e('Download Manually', 'nursoft'); ?></span>
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Hidden iframe to trigger the download prompt without navigating away -->
<iframe id="portal-downloader-iframe" style="display:none;"></iframe>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const timerText = document.getElementById('portal-timer-countdown');
    const progressBar = document.getElementById('portal-progress');
    const downloaderIframe = document.getElementById('portal-downloader-iframe');
    const startedBox = document.getElementById('portal-started-box');
    const headerMsg = document.getElementById('portal-header-msg');
    const descMsg = document.getElementById('portal-desc-msg');
    
    // Circumference = 2 * PI * r = 2 * 3.14159 * 60 = 377
    const circumference = 377;
    progressBar.style.strokeDasharray = circumference;
    progressBar.style.strokeDashoffset = circumference; // Start fully hidden
    
    let timeRemaining = 5;
    const downloadUrl = '<?php echo esc_url_raw( $download_url ); ?>';

    // Steps timing checklist mapping (seconds)
    const scanSteps = [
        { id: 'scan-step-1', triggerTime: 4 }, // Triggers when 4s remaining
        { id: 'scan-step-2', triggerTime: 3 }, // Triggers when 3s remaining
        { id: 'scan-step-3', triggerTime: 2 }, // Triggers when 2s remaining
        { id: 'scan-step-4', triggerTime: 1 }  // Triggers when 1s remaining
    ];

    /**
     * Mark a checklist item as completed with animation
     */
    function completeStep(stepId) {
        const item = document.getElementById(stepId);
        if (!item) return;

        const dot = item.querySelector('.scan-status-dot');
        const svg = item.querySelector('.scan-check-svg');

        // Apply completed style changes
        item.style.opacity = '1';
        item.style.color = 'var(--text-primary)';
        
        dot.style.borderColor = 'var(--accent-green)';
        dot.style.backgroundColor = 'var(--accent-green)';
        dot.style.color = 'var(--theme-bg)';
        dot.style.boxShadow = '0 0 8px rgba(37,211,102,0.4)';
        
        svg.style.display = 'block';
    }

    /**
     * Update circular countdown progress ring
     */
    function setProgress(percent) {
        // Calculate offset: fully visible is 0, fully hidden is circumference
        const offset = circumference - (percent / 100) * circumference;
        progressBar.style.strokeDashoffset = offset;
    }

    // Set initial timer values
    setProgress(0);
    timerText.textContent = timeRemaining;

    // Start timer interval ticks
    const timerInterval = setInterval(function() {
        timeRemaining--;

        // 1. Update text
        if (timeRemaining >= 0) {
            timerText.textContent = timeRemaining;
        }

        // 2. Update circular path offset
        const percent = ((5 - timeRemaining) / 5) * 100;
        setProgress(percent);

        // 3. Trigger sequential checklists
        scanSteps.forEach(step => {
            if (timeRemaining <= step.triggerTime) {
                // Activate/highlight the next step
                const nextItem = document.getElementById(step.id);
                if (nextItem) nextItem.style.opacity = '1';
                
                // Complete previous step
                const currentStepIndex = scanSteps.findIndex(s => s.id === step.id);
                if (currentStepIndex > 0) {
                    completeStep(scanSteps[currentStepIndex - 1].id);
                }
            }
        });

        // 4. Timer finished
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            
            // Complete final step
            completeStep('scan-step-4');

            // Set final ready text
            timerText.textContent = '✓';
            timerText.style.color = 'var(--accent-green)';
            progressBar.style.stroke = 'var(--accent-green)';
            progressBar.style.filter = 'drop-shadow(0 0 6px var(--accent-green))';

            headerMsg.textContent = '<?php _e('Connection Verified!', 'nursoft'); ?>';
            headerMsg.style.color = 'var(--accent-green)';
            descMsg.textContent = '<?php _e('Security scan 100% clean. Bandwidth allocated.', 'nursoft'); ?>';

            // Show manual download box
            startedBox.style.display = 'block';

            // Trigger actual download via iframe redirect
            setTimeout(function() {
                downloaderIframe.src = downloadUrl;
            }, 300);
        }
    }, 1000);
});
</script>

<style>
    /* Spark/glow micro-animation definitions */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<?php
get_footer();
?>
