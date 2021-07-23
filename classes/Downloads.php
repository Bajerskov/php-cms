<?php
class Downloads {
  private $_count,
          $_data,
          $_db;


  public function __construct($file = null) {
    $this->_db = DB::getInstance();

    if (!$file) {
      $this->get();
    } else {
      $this->find($file);
    }
  }

  public function get($file = null) {
    if($file) {
      $data = $this->_db->get('files', array('id', '=', $file));
      if($data->count()) {
        $this->_data = $data->results();
        return true;
      }
    } else {
      $data = $this->_db->query('SELECT * FROM files ORDER BY id DESC',array());
      if($data->count()) {
        $this->_data = $data->results();
        return true;
      }
    }
    return false;
  }

public function insertFile($fields) {
  if(!$this->_db->insert('files', $fields)) {
    throw new Exception('There was a problem uploading the file');
  }
}

public function deleteFile($fields) {
  if(!$this->_db->delete('files', $fields)) {
    throw new Exception('There was a problem deleting the file');
  }
}



  public function find($page = null) {
    if($page) {
      $data = $this->_db->get('pages', array('id', '=' , $page));

      if($data->count()) {
        $this->_data = $data->first();
        return true;
      }
    }
    return false;
  }

  public function delete($page) {
    return $this->_db->delete('pages',array('id', '=', $page));
  }

  public function exists() {
    return (!empty($this->_data)) ? true : false;
  }

  public function count() {
    return $this->_count;
  }

  public function data() {
    return $this->_data;
  }

}
