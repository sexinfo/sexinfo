$(function() {
  var $menu = $('.menu-537 div');

  $('.menu-537').hover(
    function () {
      //show its submenu
      $menu.show();

    },
    function () {
      //hide its submenu
      $menu.hide();
    }
  );
});
