
<!-- ----- debut Controller -->
<?php

class ControllerChatRoom {

  public static function chatRoom() {
    include 'config.php';


    $vue = $root . '/app/view/viewChatRoom.php';
    require ($vue);
  }



}

?>
<!-- ----- fin Controller -->


