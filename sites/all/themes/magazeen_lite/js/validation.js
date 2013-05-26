var errClass = 'field-error';

$(function() {

  $('.webform-client-form').submit(function() {
    var errors = false;

    $(this).find("input[type=text],input[type=email],textarea").each(function(i, field) {
      if ($(field).val() === "" || $(field).hasClass(errClass)) {
        errors = true;
        $(field).addClass(errClass);
        $(field).val("This is a required field.");
      }
    })

    return !errors;
  });

  $(document.body).delegate('.field-error', 'focus', function() {
    $(this).removeClass(errClass);
    $(this).val("");
  });
});
