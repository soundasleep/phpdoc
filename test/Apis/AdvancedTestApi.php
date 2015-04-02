<?php

namespace PHPDocParser\Test\Apis;

use \Apis\Api;

/**
 * An advanced
 * test API.
 *
 * This is an extended description.
 *
 * It spans multiple lines.
 *
 * @param currency   the currency to use
 */
class AdvancedTestApi extends Api {

  function getJSON($arguments) {
    return array();
  }

  function getEndpoint() {
    return "/api/v1/:currency";
  }

}
