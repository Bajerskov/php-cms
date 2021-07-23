<?php
class Settings {

  public $_data,
         $_pack,
         $_params;

  public function data(){

  }

  public function get(){

  }

  public function set() {

  }

  public function find($path = "default") {
    $files = array();
    foreach (glob("includes/themes/$path/package.json") as $file) {
      $files[] = $file;
    }
    return $files;
  }

  public function install($packet) {
      if($packet != null) {
        //unzip selected pack to temp folder.
        $zip = new ZipArchive;
        $res = $zip->open("includes/themes/$packet.zip");
        if ($res === TRUE) {
          $zip->extractTo("includes/themes/mez/");
          $zip->close();
          //try to find the install.pkt
          $json_string = file_get_contents("includes/themes/default/package.json");
          $json = json_decode($string, true);
          $jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($json, TRUE)),RecursiveIteratorIterator::SELF_FIRST);
          //return install params.
          return $jsonIterator;

          }

      } else {
        return false;
      }

  }

  public function packet() {

  }


}


 ?>
