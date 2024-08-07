document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in class to body when the page is loaded
    document.body.classList.add('fade-in');

    // Add click event listener to all links
    const links = document.querySelectorAll('a');
    links.forEach(function(link) {
        link.addEventListener('click', function(event) {
            const href = link.getAttribute('href');

            // Check if the link is an internal link
            if (href && href.startsWith(window.location.origin)) {
                event.preventDefault(); // Prevent the default link behavior

                // Add fade-out class to body
                document.body.classList.add('fade-out');

                // Wait for the fade-out effect to finish, then navigate to the new page
                setTimeout(function() {
                    window.location.href = href;
                }, 500); // Duration should match the CSS transition duration
            }
        });
    });
});