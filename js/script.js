document.addEventListener('DOMContentLoaded', function() {
    // Show/Hide Password functionality
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });

    // Optional: Add active class to nav links based on current page
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    navLinks.forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });

    // Search functionality - NEW CODE
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const teachersList = document.getElementById('teachersList');
    const classesList = document.getElementById('classesList');

    function performSearch() {
        const query = searchInput.value.toLowerCase(); // Get search query and convert to lowercase

        // Filter Teachers
        if (teachersList) {
            const teacherItems = teachersList.querySelectorAll('.teacher-search-item');
            teacherItems.forEach(item => {
                const teacherName = item.querySelector('.teacher-name').textContent.toLowerCase();
                if (teacherName.includes(query)) {
                    item.style.display = 'block'; // Show if matches
                } else {
                    item.style.display = 'none'; // Hide if no match
                }
            });
        }

        // Filter Classes
        if (classesList) {
            const classItems = classesList.querySelectorAll('.class-search-item');
            classItems.forEach(item => {
                const className = item.querySelector('.class-name').textContent.toLowerCase();
                const classTeacher = item.querySelector('.class-teacher').textContent.toLowerCase(); // Check teacher too

                if (className.includes(query) || classTeacher.includes(query)) {
                    item.style.display = 'block'; // Show if matches
                } else {
                    item.style.display = 'none'; // Hide if no match
                }
            });
        }
    }

    // Attach event listeners
    if (searchButton) {
        searchButton.addEventListener('click', performSearch);
    }

    if (searchInput) {
        // Trigger search on 'Enter' key press
        searchInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        });
        // Optional: Live search as user types
        searchInput.addEventListener('keyup', performSearch);
    }
});