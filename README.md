soundasleep/phpdoc
==================

`soundasleep/phpdoc` is another PHP documentation generator, but tries to be cleaner
and smarter and more extensible than existing solutions.

## Top-level tags supported

* `@throws` _(Class)_ _(description)_
* `@param` $name _(description)_
* `@return` _description_
* `@see` _(Class)_ _(description)_

## Inline tags supported

* `{@link http://foo.com}`
* `{@link Class}`, `{@link #method}, `{@link Class#method}`
* `{@code ...}`

## TODO

* Provide project options e.g. project name
* Generate demo docs and publish
* Try generating docs for large projects e.g. Symfony
* Method and class summaries should only display the first sentance of the 'title' doc
* Display types of paramemters e.g. `Logger $logger`
* Methods displaying default values e.g. ` = array()`
* Display inherited abstract methods on abstract classes
* @author tag
* @link tag
* @var tag
* @inheritDoc inline tag - might be tricky
* @override tag
* _Overrides_ for methods
* @deprecated tag
* Class variables
* {@link foo actual text} support
* {@link plural}s support
* Maybe try to provide tests for these types of tags, perhaps a sample project, or simply do HTML regexps on generated documentation
* Test on non-namespaced classes and interfaces
* Global functions support
* Option for `issue #123` to link to an external issue tracker
* Provide a `grunt` task/`bin` task

## See also

- [phpDox](http://phpdox.de/)
- [phpDocumentor](http://www.phpdoc.org/)
- [PHPDoc](http://www.phpdoc.de/)
