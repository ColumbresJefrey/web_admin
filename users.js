document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchUser");
    const tableRows = document.querySelectorAll("tbody tr");

    // Search filter
    searchInput.addEventListener("keyup", function () {
        let filter = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            let name = row.cells[1].innerText.toLowerCase();
            let email = row.cells[2].innerText.toLowerCase();
            if (name.includes(filter) || email.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});

function editUser(userId) {
    window.location.href = `edit_user.php?id=${userId}`;
}

function deleteUser(userId) {
    if (confirm("Are you sure you want to delete this user?")) {
        window.location.href = `delete_user.php?id=${userId}`;
    }
}
