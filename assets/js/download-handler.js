/**
 * Nursoft Dynamic Download Handler
 * Built for smooth AJAX download incrementing and seamless redirection.
 */
document.addEventListener('DOMContentLoaded', function() {
    const downloadButtons = document.querySelectorAll('.nursoft-dl-btn');
    const downloadCountSpan = document.getElementById('download-count-value');

    if (downloadButtons.length === 0) {
        return;
    }

    downloadButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const postId = this.getAttribute('data-post-id');
            const downloadUrl = this.getAttribute('data-download-url');
            
            if (!postId || !downloadUrl) {
                return;
            }

            // Store original button content for reset
            const originalHTML = this.innerHTML;
            
            // Provide visual feedback
            this.innerHTML = `
                <svg class="download-spinner" viewBox="0 0 50 50" style="width:20px;height:20px;animation:spin 1s linear infinite;margin-right:8px;fill:none;stroke:currentColor;stroke-width:4;stroke-linecap:round;stroke-dasharray:31.4 31.4;">
                    <circle cx="25" cy="25" r="20"></circle>
                </svg>
                <span>Preparing...</span>
            `;
            this.style.pointerEvents = 'none'; // Disable double-clicking

            // Create form data payload for AJAX
            const formData = new FormData();
            formData.append('action', 'nursoft_increment_downloads');
            formData.append('post_id', postId);

            // Run AJAX increment request
            fetch(nursoftDownload.ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.new_count) {
                    // Update download count on screen in real-time!
                    if (downloadCountSpan) {
                        downloadCountSpan.textContent = data.data.new_count;
                    }
                }
                
                // Proceed to open download URL regardless of AJAX result to guarantee user gets their file!
                triggerDownload(downloadUrl);
            })
            .catch(error => {
                console.error('Error incrementing download count:', error);
                // Fallback: Proceed to trigger download even on error
                triggerDownload(downloadUrl);
            })
            .finally(() => {
                // Restore button visual state
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.style.pointerEvents = 'auto';
                }, 1000);
            });
        });
    });

    /**
     * Safely open download URL in a new browser tab
     */
    function triggerDownload(url) {
        window.open(url, '_blank');
    }
});
