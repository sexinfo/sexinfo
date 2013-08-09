var errClass = 'field-error';

$(function() {

  $('.webform-client-form').submit(function() {
    var errors = false;

    $(this).find("input[type=text],input[type=email],textarea").each(function(_, field) {
      if ($(field).val() === "" || $(field).hasClass(errClass)) {
        errors = true;
        $(field).addClass(errClass).val('This is a required field')
      }
    })

    return !errors;
  });

  $(document).on('focus', '.field-error', function() {
    $(this).removeClass(errClass).val('');
  });
});
