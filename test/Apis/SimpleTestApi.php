<?php

namespace PHPDocParser\Test\Apis;

use \Apis\Api;

/**
 * A simple test API.
 *
 * This is an extended description.
 */
class SimpleTestApi extends Api {

  function getJSON($arguments) {
    return array();
  }

  function getEndpoint() {
    return "/api/v1/simple";
  }

}
