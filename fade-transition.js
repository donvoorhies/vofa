document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in class to body when the page is loaded
    document.body.classList.add('fade-in');

    // Add click event listener to all links
    const links = document.querySelectorAll('a');
    links.forEach(function(link) {
        link.addEventListener('click', function(event) {
            const href = link.getAttribute('href');

            // Ignore clicks that should not be hijacked (new tab, downloads, hash-only links, modifier keys).
            if (!href || href.startsWith('#') || link.target === '_blank' || link.hasAttribute('download') || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0) {
                return;
            }

            // Resolve relative URLs and only animate same-origin page navigations.
            const url = new URL(href, window.location.href);
            if (url.origin === window.location.origin) {
                event.preventDefault(); // Prevent the default link behavior

                // Add fade-out class to body
                document.body.classList.add('fade-out');

                // Wait for the fade-out effect to finish, then navigate to the new page
                setTimeout(function() {
                    window.location.href = url.href;
                }, 500); // Duration should match the CSS transition duration
            }
        });
    });
});