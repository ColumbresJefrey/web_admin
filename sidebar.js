document.addEventListener("DOMContentLoaded", function () {
    // Confirm logout function
    function confirmLogout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = 'logout.php'; // Redirect to logout.php
        }
    }

    // Attach the logout function to the button
    const logoutButton = document.getElementById('logout-btn'); // Use the ID to select the button
    if (logoutButton) {
        logoutButton.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default link action
            confirmLogout(); // Call the logout function
        });
    } else {
        console.error("Logout button not found. Please check the HTML.");
    }
});