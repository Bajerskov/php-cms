<?php
class Input {
  public static function exists($type = 'post'){
    switch($type) {
      case 'post':
        return (!empty($_POST)) ? true : false;
      break;
      case 'get':
        return (!empty($_GET)) ? true : false;
      break;
      default:
        return false;
      break;
    }
  }


  public static function get($item) {
    if(isset($_POST[$item])){
      return $_POST[$item];
    } elseif(isset($_GET[$item])) {
      return $_GET[$item];
    }
    return '';
  }

  public static function dump($input) {
    $debug = var_export($input, true);
    return "<pre>".$debug."</pre>";
  }

  public static function dump_all($type = "post") {
    if($type === "post")
      $debug = var_export($_POST, true);
    else if($type === "get")
      $debug = var_export($_GET, true);

    return "<pre>".$debug."</pre>";
  }

}


 ?>
