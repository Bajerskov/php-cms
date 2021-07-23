<?php
class Article {
  private $_count,
          $_data,
          $_db;


  public function __construct($id = null) {
    $this->_db = DB::getInstance();

    if (!$id) {
      $this->get();
    } else {
      $this->find($id);
    }
  }

  public function get($id = null) {

    if($id) {
      $data = $this->_db->get('articles', array('id', array('id', '=', $id)));
      if($data->count()) {
        $this->_data = $data->results();
        $this->_count = $data->count();
        return true;
      }
    } else {
      $data = $this->_db->query('SELECT * FROM articles ORDER BY `id` DESC',array());
      if($data->count()) {
        $this->_data = $data->results();
        $this->_count = $data->count();
        return true;
      }
    }
    return false;
  }





  public function find($id = null) {
    if($id) {
      $data = $this->_db->get('articles', array('id', '=' , $id));

      if($data->count()) {
        $this->_data = $data->first();
        return true;
      }
    }
    return false;
  }

  public function create($fields = array()) {
    $val = $this->_db->insert('articles', $fields);
    if($val) {
      return $val;
    } else {
      throw new Exception('There was a problem creating a new article');
    }
  }

  public function update($id, $fields = array()) {
    return $this->_db->update('articles',$id, $fields);
  }

  public function delete($id) {
    return $this->_db->delete('articles',array('id', '=', $id));
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
