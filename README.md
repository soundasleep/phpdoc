soundasleep/phpdoc2
===================

`soundasleep/phpdoc2` is another PHP documentation generator, but tries to be cleaner
and smarter and more extensible than existing solutions.

For example, mark up your classes and methods with PHPDoc comment blocks:

```php
namespace Openclerk\Currencies;

/**
 * A "currency" represents some unit of measurement that can
 * be converted into another "currency" unit, e.g. through an {@link Exchange}.
 * Can also cover commodities.
 *
 * This is the base interface; other interfaces will provide additional
 * functionality as necessary.
 */
interface Currency {

  /**
   * Get the unique three-letter currency code for this currency,
   * e.g. 'btc' or 'usd'. Must be lowercase. This is not visible to users.
   */
  public function getCode();

  /**
   * @return true if this can be considered a "cryptocurrency", e.g. "btc"
   */
  public function isCryptocurrency();

  // ...
}
```

## Using

```
php -f phpdoc2.php -- --directory "src/" --output "docs/"
```

For easy documentation generation, you can use Grunt with the [grunt-phpdoc2](https://github.com/soundasleep/grunt-phpdoc2) task.
For example, see the example Gruntfile provided in the [phpdoc2-openclerk](https://github.com/soundasleep/phpdoc2-openclerk/blob/gh-pages/Gruntfile.coffee).

## Templates

To override or extend these templates, add `--templates "dir"`. The generator will add this directory of templates to override the default ones. Uses [openclerk/pages](https://github.com/soundasleep/openclerk/pages), which means you can call subtemplates.

## Demos

* [Openclerk](http://soundasleep.github.io/phpdoc2-openclerk/docs/index.html) - [source](https://github.com/soundasleep/phpdoc2-openclerk)
* [phpunit](http://soundasleep.github.io/phpdoc2-phpunit/docs/index.html) - [source](https://github.com/soundasleep/phpdoc2-phpunit)

## Top-level tags supported

* `@throws` _(Class)_ _(description)_
* `@param` $name _(description)_
* `@return` description
* `@see` _(Class)_ _(description)_
* `@deprecated` _(description)_

## Inline tags supported

* `{@link http://foo.com}`
* `{@link Class}`, `{@link #method}, `{@link Class#method}`, `{@link Class description}`, `{@link Plural}s` etc
* `{@code ...}`

## TODO

* Look at compatibility with [phpdoc PSR standard](https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md) and [reference PSR examples](https://github.com/soundasleep/phpdoc-psr)
* Method and class summaries should only display the first sentance of the 'title' doc
* Highlight abstract classes
* Display inherited abstract methods on abstract classes
* `@author` tag
* `@since` tag
* `@var` tag
* `@inheritDoc` inline tag - might be tricky
* Class variables
* Class constants
* Global functions support
* Global constants
* Option for `issue #123` to link to an external issue tracker
* Link through to open source projects for composer projects
* Link through to source code for GitHub projects

## See also

- [phpDox](http://phpdox.de/)
- [phpDocumentor](http://www.phpdoc.org/)
- [PHPDoc](http://www.phpdoc.de/)
