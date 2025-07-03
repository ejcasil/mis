function showToast(message, status) {
    var toastContainer = $('#toastContainer'); // Get the toast container
    var toastElement = $('<div>', {
        class: 'toast align-items-center text-bg-dark border-0',
        role: 'alert',
        'aria-live': 'assertive',
        'aria-atomic': 'true'
    });

    // Create toast content
    // var status_icon = status === "success" 
    //     ? "<i class='bx bxs-check-circle me-2'></i>" 
    //     : "<i class='bx bx-x-circle me-2'></i>";

    var status_icon = "<i class='bx bx-info-circle'></i>";

    // Set background color based on status
    // if (status !== "success") {
    //     toastElement.removeClass("text-bg-success").addClass("text-bg-danger");
    // } else {
    //     toastElement.addClass("text-bg-success");
    // }

    // Append content to the toast

    var toast_title = "<strong>Notification</strong><br>";

    var toastBody = $("<div>", { class: "d-flex" }).append(
        $("<div>", { class: "toast-body" }).html(`${status_icon} ${toast_title} ${message}`),
        $("<button>", {
            type: "button",
            class: "btn-close btn-close-white me-2 m-auto",
            "data-bs-dismiss": "toast",
            "aria-label": "Close"
        })
    );

    toastElement.append(toastBody);

    // Append toast to the container
    toastContainer.append(toastElement);

    // Initialize the toast (Bootstrap 5) and show it
    var bootstrapToast = new bootstrap.Toast(toastElement[0]);
    bootstrapToast.show();

    // Hide the toast after 9 seconds and remove from the DOM
    setTimeout(function() {
        bootstrapToast.hide();
        toastElement.remove(); // Remove from DOM after it's hidden
    }, 9000);
}