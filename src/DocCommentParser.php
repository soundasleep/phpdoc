<?php

namespace PHPDoc2;

class DocCommentParser {

  function parse($comment) {
    return array(
      'title' => $this->getTitle($comment),
      'description' => $this->getDescription($comment),
      'params' => $this->getParams($comment),
      'see' => $this->getSee($comment),
      'return' => $this->getReturn($comment),
      'throws' => $this->getThrows($comment),
    );
  }

  function getLines($comment) {
    $lines = explode("\n", $comment);
    $result = array();
    $previous = "";
    foreach ($lines as $i => $line) {
      $line = preg_replace("#^\\s*/\\*+\\s*#", "", $line);
      $line = preg_replace("#\\s*\\*/$#", "", $line);
      $line = preg_replace("#^\\s*\\*\\s*#", "", $line);
      $line = preg_replace("#\\s+#", " ", $line);

      if (trim($line)) {
        if (substr(trim($line), 0, 1) == "@") {
          if ($previous) {
            $result[] = $previous;
          }
          $previous = trim($line);
        } else {
          $previous = trim($previous . " " . $line);
        }
      } else {
        if ($previous) {
          $result[] = $previous;
          $previous = "";
        }
      }
    }
    if ($previous) {
      $result[] = $previous;
    }
    return $result;
  }

  function getTitle($comment) {
    $lines = $this->getLines($comment);
    if (isset($lines[0])) {
      if (substr($lines[0], 0, 1) !== "@") {
        // don't use @tags as title
        return $lines[0];
      }
    }
    return null;
  }

  function getDescription($comment) {
    $lines = $this->getLines($comment);
    $result = array();
    for ($i = 1; $i < count($lines); $i++) {
      if (substr($lines[$i], 0, 1) !== "@") {
        $result[] = $lines[$i];
      }
    }
    return implode("\n", $result);
  }

  /**
   * This gets both '@return' and '@returns' etc
   */
  function getHash($tag_name, $comment) {
    $lines = $this->getLines($comment);
    $result = array();
    for ($i = 0; $i < count($lines); $i++) {
      if (substr($lines[$i], 0, strlen("@" . $tag_name)) == "@" . $tag_name) {
        $bits = explode(" ", $lines[$i], 3);
        switch (count($bits)) {
          case 3:
            // don't hash out inline links
            if (substr($bits[1], 0, 1) === "{") {
              $new_key = implode(" ", array($bits[1], $bits[2]));
              $result[$new_key] = false;
            } else {
              $result[$bits[1]] = $bits[2];
            }
            break;
          case 2:
            $result[$bits[1]] = false;
            break;
        }
      }
    }
    return $result;
  }

  /**
   * This gets both '@return' and '@returns' etc
   */
  function getList($tag_name, $comment) {
    $lines = $this->getLines($comment);
    $result = array();
    for ($i = 0; $i < count($lines); $i++) {
      if (substr($lines[$i], 0, strlen("@" . $tag_name)) == "@" . $tag_name) {
        $bits = explode(" ", $lines[$i], 2);
        $result[] = $bits[1];
      }
    }
    return $result;
  }

  function getParams($comment) {
    return $this->getHash("param", $comment);
  }

  function getReturn($comment) {
    return $this->getList("return", $comment);
  }

  function getSee($comment) {
    return $this->getHash("see", $comment);
  }

  function getThrows($comment) {
    return $this->getHash("throw", $comment);
  }

}
