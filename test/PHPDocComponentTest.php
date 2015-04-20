<?php

namespace PHPDoc2\Test;

class PHPDocComponentTest extends \ComponentTests\ComponentTest {

  function getRoots() {
    return array(__DIR__ . "/..");
  }

  /**
   * May be extended by child classes to define a list of path
   * names that will be excluded by {@link #iterateOver()}.
   */
  function getExcludes() {
    return array("/vendor/", "/docs/", "/.git/", "/.svn/", "/exchanges/", "/currencies/");
  }

}
