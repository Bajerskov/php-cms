<?php
class Validate {
  private $_passed = false,
          $_errors = array(),
          $_db = null;

  public function __construct(){
    $this->_db = DB::getInstance();
  }

  public function check($source, $items = array()) {
    foreach ($items as $item => $rules) {
      foreach ($rules as $rule => $rulevalue) {

        $value = trim($source[$item]);
        $item = escape($item);

        if($rule === 'required' && empty($value)){
          $this->addError("{$item} is required");
        } else if(!empty($value)){
          switch ($rule) {
            case 'min':
                if(strlen($value) < $rulevalue) {
                  $this->addError("{$item} must be a minium of {$rulevalue} characters.");
                }
              break;
              case 'max':
              if(strlen($value) > $rulevalue) {
                $this->addError("{$item} must be a maximum of {$rulevalue} characters.");
              }
              break;
              case 'matches':
                  if($value != $source[$rulevalue]) {
                    $this->addError("{$rulevalue} must match {$item}.");
                  }
              break;
              case 'unique':
                  $check = $this->_db->get($rulevalue, array($item, '=', $value));
                  if($check->count()) {
                    $this->addError("{$item} already exists.");
                  }
              break;
              case 'email':
                  if (!filter_var($item, FILTER_VALIDATE_EMAIL) === false) {
                      $this->addError("{$item} is already registered.");
                  }
              break;
              case 'date':
                  if (!DateTime::createFromFormat('Y-m-d', $value)) {
                      $this->addError("{$item} is not a valid date.");
                  }
              break;
              default:
              # code...
              break;
          }
        }

      }
    }

    if(empty($this->_errors)) {
      $this->_passed = true;
    }
    return $this;
  }

  private function addError($error) {
    $this->_errors[] = $error;
  }

  public function errors() {
    return $this->_errors;
  }

  public function passed() {
    return $this->_passed;
  }

}


 ?>
