<?php
class Page {
  private $_count,
          $_data,
          $_db;


  public function __construct($page = null) {
    $this->_db = DB::getInstance();

    if (!$page) {
      $this->get();
    } else {
      $this->find($page);
    }
  }

  public function get($page = null) {

    if($page) {
      $data = $this->_db->get('pages', array('id', array('id', '=', $page)));
      if($data->count()) {
        $this->_data = $data->results();
        $this->_count = $data->count();
        return true;
      }
    } else {
      $data = $this->_db->query('SELECT * FROM pages',array());
      if($data->count()) {
        $this->_data = $data->results();
        $this->_count = $data->count();
        return true;
      }
    }
    return false;
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

  public function create($fields = array()) {
    if(!$this->_db->insert('pages', $fields)) {
      throw new Exception('There was a problem creating a new account');
    }
  }

  public function update($id, $fields = array()) {
    return $this->_db->update('pages',$id,array(
      'name' => $fields['name'],
      'content' => $fields['content']
    ));
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
