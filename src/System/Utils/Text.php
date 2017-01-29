<?php

/**
 * Text
 *
 * @author spool
 */

namespace System\Utils;

class Text {
  public static function isEmpty($string) {
    return strlen(trim($string)) == 0;
  }
}
