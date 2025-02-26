document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchAppointment");
    const tableRows = document.querySelectorAll("tbody tr");

    // Search filter
    searchInput.addEventListener("keyup", function () {
        let filter = searchInput.value.trim().toLowerCase();
        tableRows.forEach(row => {
            let matchFound = Array.from(row.cells).some(cell =>
                cell.innerText.toLowerCase().includes(filter)
            );
            row.style.display = matchFound ? "" : "none"; // Show or hide the row
        });
    });
});

// Edit Appointment
function editAppointment(appointmentId) {
    window.location.href = `edit_appointment.php?id=${appointmentId}`;
}

// Delete Appointment via API
function deleteAppointment(appointmentId) {
    if (confirm("Are you sure you want to delete this appointment?")) {
        fetch(`http://your-api-url/appointments/${appointmentId}`, {
            method: "DELETE",
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Appointment deleted successfully.");
                location.reload(); // Refresh the page to reflect changes
            } else {
                alert("Failed to delete appointment.");
            }
        })
        .catch(error => console.error("Error:", error));
    }
}
