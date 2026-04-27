$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault(); // Stop the page from reloading

        var $form = $(this);
        var $submitButton = $form.find('input[type="submit"]');
        $submitButton.button('loading');

        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.response == 'success') {
                    $('.contact-form-success').removeClass('d-none');
                    $('.contact-form-error').addClass('d-none');
                    $form.find('.form-control').val(''); // Clear the form
                } else {
                    $('.contact-form-error').removeClass('d-none');
                    $('.contact-form-success').addClass('d-none');
                }
            },
            complete: function() {
                $submitButton.button('reset');
            }
        });
    });
});