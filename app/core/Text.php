<?php

/**
 * Text
 *
 * @author spool
 */

namespace Core;

class Text {
  public static function isEmpty($string) {
    return strlen(trim($string)) == 0;
  }
}
