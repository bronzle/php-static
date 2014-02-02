<?php
class MissingTemplate extends Exception {
  const CONTENT = -1;
  const LAYOUT = -2;
  const OTHER = -3;
  public $type;
  public $template;
  public $locations;
  public function __construct($type, $template, $locations) {
    $this->type = $type;
    $this->template = $template;
    $this->locations = $locations;
    switch ($type) {
      case self::CONTENT:
        $message = "Page missing: {$template}";
      break;
      case self::LAYOUT:
        $message = "Layout missing: {$template}";
      break;
      case self::OTHER:
        $message = "Template missing: {$template}";
      break;
    }
    parent::__construct($message);
  }
}
class InvalidConfiguration extends Exception {
  const READ_ERROR = -1;
  const MISSING_VALUE = -2;
  public function __construct($type, $item, $extra = null) {
    $this->item = $item;
    if ($type === self::READ_ERROR) {
      $message = "Error reading configuration file ({$item}) or configuration file is invalid; {$extra}";
    } elseif ($type === self::MISSING_VALUE) {
      $message = "A required value is missing from configuration, value: {$item}";
    } else {
      $message = 'Unknown error has occurred';
    }
    parent::__construct($message);
  }
}
