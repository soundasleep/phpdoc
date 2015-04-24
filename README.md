soundasleep/phpdoc2
===================

`soundasleep/phpdoc2` is another PHP documentation generator, but tries to be cleaner
and smarter and more extensible than existing solutions.

## Top-level tags supported

* `@throws` _(Class)_ _(description)_
* `@param` $name _(description)_
* `@return` description
* `@see` _(Class)_ _(description)_

## Inline tags supported

* `{@link http://foo.com}`
* `{@link Class}`, `{@link #method}, `{@link Class#method}`
* `{@code ...}`

## TODO

* Look at compatibility with [phpdoc PSR standard](https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md)
* Generate demo docs and publish
* Try generating docs for large projects e.g. Symfony
* Method and class summaries should only display the first sentance of the 'title' doc
* Highlight abstract classes
* Display inherited abstract methods on abstract classes
* `@author` tag
* `@var` tag
* `@inheritDoc` inline tag - might be tricky
* @deprecated tag
* Class variables
* `{@link foo actual text}` support
* `{@link plural}s` support
* Maybe try to provide tests for these types of tags, perhaps a sample project, or simply do HTML regexps on generated documentation
* Global functions support
* Option for `issue #123` to link to an external issue tracker
* Provide a `grunt` task/`bin` task
* Link through to open source projects for composer projects
* Link through to source code for GitHub projects

## See also

- [phpDox](http://phpdox.de/)
- [phpDocumentor](http://www.phpdoc.org/)
- [PHPDoc](http://www.phpdoc.de/)
