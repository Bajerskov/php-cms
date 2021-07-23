<?php
class Calendar {
  private $_count,
          $_data,
          $_db;


  public function __construct() {
    $this->_db = DB::getInstance();
      $this->get();
  }

  public function get() {

      $data = $this->_db->query('SELECT * FROM calendar ORDER BY id ASC',array());
      if($data->count()) {
        $this->_data = $data->results();
        return true;
      }

    return false;
  }

  public function find($cal = null) {
    if($cal) {
      $data = $this->_db->get('calendar', array('id', '=' , $cal));

      if($data->count()) {
        $this->_data = $data->first();
        return true;
      }
    }
    return false;
  }

  public function create($fields = array()) {
    if(!$this->_db->insert('calendar', $fields)) {
      throw new Exception('There was a problem creating a new calendar entry');
    }
  }

  public function update($date, $fields = array()) {
    return $this->_db->update('calendar',$date,array(
      'dato' => $fields['date'],
      'description' => $fields['description'],
      'page_id' => $fields['page_id']
    ));
  }

  public function delete($date) {
    return $this->_db->delete('calendar',array('id', '=', $date));
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
