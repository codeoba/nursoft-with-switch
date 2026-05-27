/**
 * Nursoft Interactive Quick View Drawer Handler
 * Uses event delegation for high performance and compatibility with AJAX search/pagination.
 */
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('nursoft-drawer-overlay');
    const drawer = document.getElementById('nursoft-drawer');
    const closeBtn = document.getElementById('drawer-close');
    const body = document.getElementById('drawer-body');

    if (!overlay || !drawer || !closeBtn || !body) {
        return;
    }

    // Default loading state spinner HTML
    const loaderHTML = `
        <div style="display:flex;align-items:center;justify-content:center;height:100%;flex-direction:column;gap:12px;color:var(--text-muted);">
            <svg class="download-spinner" viewBox="0 0 50 50" style="width:36px;height:36px;animation:spin 1s linear infinite;fill:none;stroke:currentColor;stroke-width:4;stroke-linecap:round;stroke-dasharray:31.4 31.4;color:var(--accent-blue);">
                <circle cx="25" cy="25" r="20"></circle>
            </svg>
            <span>Loading spec data...</span>
        </div>
    `;

    /**
     * Open Quick View Drawer
     */
    function openDrawer(postId) {
        // Clear old content and show loading spinner
        body.innerHTML = loaderHTML;

        // Slide-in drawer and show blurred backdrop overlay
        overlay.classList.add('open');
        drawer.classList.add('open');
        document.body.style.overflow = 'hidden'; // Lock main page scrolling

        // Run AJAX query to fetch specs
        const url = `${nursoftQuickView.ajaxurl}?action=nursoft_quick_view&post_id=${postId}&nonce=${nursoftQuickView.nonce}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.html) {
                    body.innerHTML = data.data.html;
                } else {
                    const errMsg = data.data && data.data.message ? data.data.message : 'Error loading details.';
                    body.innerHTML = `<div style="padding:20px; text-align:center; color:var(--accent-red); font-weight:600;">${errMsg}</div>`;
                }
            })
            .catch(error => {
                console.error('AJAX Error in Quick View:', error);
                body.innerHTML = '<div style="padding:20px; text-align:center; color:var(--accent-red); font-weight:600;">Failed to fetch software specs. Please try again.</div>';
            });
    }

    /**
     * Close Quick View Drawer
     */
    function closeDrawer() {
        overlay.classList.remove('open');
        drawer.classList.remove('open');
        document.body.style.overflow = ''; // Restore main page scrolling
    }

    // Use event delegation on document body to capture clicks on .quick-view-trigger-btn
    document.body.addEventListener('click', function(event) {
        const btn = event.target.closest('.quick-view-trigger-btn');
        if (btn) {
            event.preventDefault();
            event.stopPropagation();
            const postId = btn.getAttribute('data-post-id');
            if (postId) {
                openDrawer(postId);
            }
        }
    });

    // Close on clicking 'X' button
    closeBtn.addEventListener('click', function(event) {
        event.preventDefault();
        closeDrawer();
    });

    // Close on clicking the backdrop overlay
    overlay.addEventListener('click', function(event) {
        closeDrawer();
    });

    // Close drawer when ESC key is pressed
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && drawer.classList.contains('open')) {
            closeDrawer();
        }
    });
});
