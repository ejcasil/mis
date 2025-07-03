$(document).ready(function() {
    const $togglePassword = $('#togglePassword');
    const $password = $('#password');

    $("#username").focus();

    $togglePassword.on('click', function() {
        // Toggle the type attribute
        const type = $password.attr('type') === 'password' ? 'text' : 'password';
        $password.attr('type', type);

        // Toggle the icon class
        $togglePassword.toggleClass('fi-rs-crossed-eye fi-rs-eye');
    });

    $password.on('input', function() {
        if ($password.val().trim() !== '') {
            $togglePassword.removeClass('collapse');
        } else {
            $togglePassword.addClass('collapse');
        }
    });

    /**
     * Forgot password
     * Check if email exists
     */
    // $("#forgot-password-form").submit(function(e) {
    //     e.preventDefault(); 
    
    //     try {
    //         var formData = $(this).serialize();
    //         $.ajax({
    //             url: "<?= site_url('/recovery/forgot_password') ?>",
    //             type: "POST",
    //             data: formData,
    //             success: function(response) {
    //                 if (response.success) {
    //                     console.log(response);
    //                 } else {
    //                     var errors = response.errors;
    //                     var errorMessages = '';

    //                     if (Array.isArray(response.errors)) {
    //                         $.each(errors, function(field, message) {
    //                             errorMessages += '<p>' + message + '</p>';
    //                         });
    //                     } else if (response.errors && typeof response.errors === 'object') {
    //                         $.each(response.errors, function(field, message) {
    //                             errorMessages += '<p>' + message + '</p>';
    //                         });
    //                     } else {
    //                         errorMessages = response.errors;
    //                     }

    //                     $('.alert').html(errorMessages);
    //                     $('.alert').show();

    //                 }
    //             },
    //             error: function(xhr, error, thrown) {
    //                 console.error('Error:', error);
    //                 console.error('XHR:', xhr);
    //             }
    //         });
    //     } catch (error) {
    //         console.error(error);
    //     }
    // });

    

});
