<?php

namespace PHPDoc2\Test\Apis;

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
 * @param foo another parameter
 * @param bar another parameter
 *      across multiple lines
 * @see AdvancedTestApi
 */
class MultipleParamsApi extends Api {

  /**
   * Does something.
   *
   * This comment goes
   * over multiple lines.
   *
   * But it should be rendered nicely.
   * @param $arguments the arguments
   */
  function getJSON($arguments) {
    return array();
  }

  /**
   * @return a string
   */
  function getEndpoint() {
    return "/api/v2/:currency";
  }

  /**
   * @return one
   * @returns two
   */
  function test() {
    // empty
  }

  /**
   * @throws {@link BalanceException} if something
   */
  function testThrow1() {

  }

  /**
   * @throws BalanceException if something
   */
  function testThrow2() {

  }

}
