document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchStylist");
    const tableRows = document.querySelectorAll("tbody tr");

    // Search filter
    searchInput.addEventListener("keyup", function () {
        let filter = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            let name = row.cells[1].innerText.toLowerCase();
            if (name.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});

// Edit Stylist
function editStylist(stylistId) {
    window.location.href = `edit_stylist.php?id=${stylistId}`;
}

// Delete Stylist
function deleteStylist(stylistId) {
    if (confirm("Are you sure you want to delete this stylist?")) {
        window.location.href = `delete_stylist.php?id=${stylistId}`;
    }
}
