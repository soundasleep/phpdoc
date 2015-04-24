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

  function testThrow3($arg1, $arg2 = "String", $arg3 = array()) {

  }

  /**
   * @see #testThrow3($arg1, $arg2, $arg3)
   */
  function testSeeFullRef() {

  }

  /**
   * @see #testThrow3($arg1, $arg2)
   */
  function testSeePartialRef() {

  }

  /**
   * @see #testThrow3()
   */
  function testSeeLocalRef() {

  }

  /**
   * @see #testThrow3
   */
  function testSeeLocalRefEmpty() {

  }

  /**
   * @see MultipleParamsApi#testThrow3($arg1, $arg2, $arg3)
   */
  function testSeeFullQualifiedRef() {

  }

  /**
   * @see #testThrow3($arg1, $arg2, $arg3) this is a comment
   */
  function testSeeFullRefWithComment() {

  }

  /**
   * @see #testThrow3($arg1, $arg2) this is a comment
   */
  function testSeePartialRefWithComment() {

  }

  /**
   * @see #testThrow3() this is a comment
   */
  function testSeeLocalRefWithComment() {

  }

  /**
   * @see #testThrow3 this is a comment
   */
  function testSeeLocalRefEmptyWithComment() {

  }

  /**
   * @see MultipleParamsApi#testThrow3($arg1, $arg2, $arg3) this is a comment
   */
  function testSeeFullQualifiedRefWithComment($arg4 = 1, MultipleParamsApi $arg5 = null, EmptyNamespace $arg6 = null) {

  }

}
