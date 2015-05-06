<?php

namespace PHPDoc2\Test;

use PHPDoc2\MyLogger;
use PHPDoc2\Parser;

class MultipleParamsTest extends DocCommentTest {

  function getFile() {
    return __DIR__ . "/Apis/MultipleParamsApi.php";
  }

  function testNamespace() {
    $this->assertTrue(isset($this->result['namespaces']['PHPDoc2\Test\Apis']));
  }

  function testCommentTitle() {
    $this->assertEquals("An advanced test API.", $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['doc']['title']);
  }

  function testCommentDescription() {
    $this->assertEquals("This is an extended description.\nIt spans multiple lines.", $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['doc']['description']);
  }

  function testCommentParams() {
    $this->assertEquals(array(
      'currency' => 'the currency to use',
      'foo' => 'another parameter',
      'bar' => 'another parameter across multiple lines',
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['doc']['params']);
  }

  function testCommentSee() {
    $this->assertEquals(array(
      'AdvancedTestApi' => false,
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['doc']['see']);
  }

  function testCommentWithJustOneTag() {
    $this->assertFalse(!!$this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['getEndpoint']['doc']['title'], "The @return tag should not be interpreted as title");

    $this->assertEquals(array(
      'a string',
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['getEndpoint']['doc']['return']);
  }

  function testBothReturnTags() {
    $this->assertEquals(array(
      'one',
      'two'
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['test']['doc']['return']);
  }

  function testThrowsInlineLink() {
    $this->assertEquals(array(
      '{@link BalanceException} if something' => false,
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testThrow1']['doc']['throws']);
  }

  function testThrowsReferencesClass() {
    $this->assertEquals(array(
      'BalanceException' => 'if something',
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testThrow2']['doc']['throws']);
  }

  function testSeeFullRef() {
    $this->assertEquals(array(
      '#testThrow3($arg1, $arg2, $arg3)' => false,
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeeFullRef']['doc']['see']);
  }

  function testSeePartialRef() {
    $this->assertEquals(array(
      '#testThrow3($arg1, $arg2)' => false,
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeePartialRef']['doc']['see']);
  }

  function testSeeLocalRef() {
    $this->assertEquals(array(
      '#testThrow3()' => false,
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeeLocalRef']['doc']['see']);
  }

  function testSeeLocalRefEmpty() {
    $this->assertEquals(array(
      '#testThrow3' => false,
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeeLocalRefEmpty']['doc']['see']);
  }

  function testSeeFullQualifiedRef() {
    $this->assertEquals(array(
      'MultipleParamsApi#testThrow3($arg1, $arg2, $arg3)' => false,
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeeFullQualifiedRef']['doc']['see']);
  }

  // and the same with comments
  function testSeeFullRefWithComment() {
    $this->assertEquals(array(
      '#testThrow3($arg1, $arg2, $arg3)' => "this is a comment",
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeeFullRefWithComment']['doc']['see']);
  }

  function testSeePartialRefWithComment() {
    $this->assertEquals(array(
      '#testThrow3($arg1, $arg2)' => "this is a comment",
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeePartialRefWithComment']['doc']['see']);
  }

  function testSeeLocalRefWithComment() {
    $this->assertEquals(array(
      '#testThrow3()' => "this is a comment",
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeeLocalRefWithComment']['doc']['see']);
  }

  function testSeeLocalRefEmptyWithComment() {
    $this->assertEquals(array(
      '#testThrow3' => "this is a comment",
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeeLocalRefEmptyWithComment']['doc']['see']);
  }

  function testSeeFullQualifiedRefWithComment() {
    $this->assertEquals(array(
      'MultipleParamsApi#testThrow3($arg1, $arg2, $arg3)' => "this is a comment",
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testSeeFullQualifiedRefWithComment']['doc']['see']);
  }

  function testDeprecatedTag() {
    $this->assertEquals(array(
      "",
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['test']['doc']['deprecated']);
  }

  function testDeprecatedDescription() {
    $this->assertEquals(array(
      "use {@link #testThrow2()} instead",
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['MultipleParamsApi']['methods']['testThrow1']['doc']['deprecated']);
  }

}
