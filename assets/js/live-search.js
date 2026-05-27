/**
 * Nursoft Live AJAX Search Handler
 * Built for premium responsive desktop and mobile search bars.
 */
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input-field');
    const resultsContainer = document.getElementById('live-search-results');
    let debounceTimer;

    if (!searchInput || !resultsContainer) {
        return;
    }

    // Bind event listener to input
    searchInput.addEventListener('input', function() {
        const keyword = this.value.trim();

        // Clear existing timer to debounce calls
        clearTimeout(debounceTimer);

        if (keyword.length < 2) {
            resultsContainer.innerHTML = '';
            resultsContainer.style.display = 'none';
            return;
        }

        // Debounce search query by 250ms
        debounceTimer = setTimeout(function() {
            fetchLiveSearchResults(keyword);
        }, 250);
    });

    /**
     * Fetch search results from WordPress AJAX
     */
    function fetchLiveSearchResults(keyword) {
        const ajaxUrl = nursoftLiveSearch.ajaxurl;
        const nonce = nursoftLiveSearch.nonce;
        const requestUrl = `${ajaxUrl}?action=nursoft_live_search&security=${nonce}&keyword=${encodeURIComponent(keyword)}`;

        resultsContainer.innerHTML = '<div style="padding: 15px; text-align: center; color: var(--text-secondary); font-size: 13px;"><svg class="search-spinner" viewBox="0 0 50 50" style="width:20px;height:20px;animation:spin 1s linear infinite;margin-right:8px;display:inline-block;vertical-align:middle;"><circle cx="25" cy="25" r="20" fill="none" stroke="var(--accent-blue)" stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4"></circle></svg>Searching products...</div>';
        resultsContainer.style.display = 'block';

        // Add CSS rotation animation dynamically if not present
        if (!document.getElementById('nursoft-spinner-css')) {
            const style = document.createElement('style');
            style.id = 'nursoft-spinner-css';
            style.innerHTML = '@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
            document.head.appendChild(style);
        }

        fetch(requestUrl)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    renderResults(data.data);
                } else {
                    renderNoResults();
                }
            })
            .catch(error => {
                console.error('Error fetching live search results:', error);
                renderError();
            });
    }

    /**
     * Render matched software lists in HTML
     */
    function renderResults(items) {
        resultsContainer.innerHTML = '';
        
        items.forEach(item => {
            const itemElement = document.createElement('a');
            itemElement.href = item.url;
            itemElement.className = 'live-search-item';
            
            const thumbSrc = item.thumb ? item.thumb : '';
            const sizeBadge = item.size ? `<span class="card_size" style="font-size:10px;padding:1px 5px;margin-left:8px;">${item.size}</span>` : '';
            const platformText = item.platform ? `<span class="live-search-platform">${item.platform}</span>` : '';

            itemElement.innerHTML = `
                <img src="${thumbSrc}" class="live-search-thumb" alt="${item.title}" />
                <div class="live-search-details">
                    <div class="live-search-title">${item.title}</div>
                    <div class="live-search-meta">
                        ${platformText}
                        <span>verified safe</span>
                        ${sizeBadge}
                    </div>
                </div>
            `;
            resultsContainer.appendChild(itemElement);
        });

        resultsContainer.style.display = 'block';
    }

    /**
     * Render "No Results" state
     */
    function renderNoResults() {
        resultsContainer.innerHTML = '<div style="padding: 15px; text-align: center; color: var(--text-muted); font-size: 13px;">No software products found.</div>';
    }

    /**
     * Render Error state
     */
    function renderError() {
        resultsContainer.innerHTML = '<div style="padding: 15px; text-align: center; color: var(--accent-magenta); font-size: 13px;">Error loading results. Try again!</div>';
    }

    // Close results dropdown when user clicks away
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !resultsContainer.contains(event.target)) {
            resultsContainer.style.display = 'none';
        }
    });

    // Re-show dropdown if input has content and is focused
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2 && resultsContainer.children.length > 0) {
            resultsContainer.style.display = 'block';
        }
    });
});
