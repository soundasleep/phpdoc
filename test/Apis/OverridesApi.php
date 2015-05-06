<?php

namespace PHPDoc2\Test\Apis;

use \Apis\Api;

class OverridesApi extends MultipleParamsApi {

  /**
   * Does something different.
   */
  function getJSON($arguments) {
    return $arguments;
  }

  function getEndpoint() {
    return "/api/v2/:currency";
  }

  /**
   * Overrides the parent method.
   */
  function testThrow1() {

  }

}
