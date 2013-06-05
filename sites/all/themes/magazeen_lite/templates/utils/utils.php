<?php
  function logged_in() {
    global $user;

    return ($user->uid != 0);
  }
?>
