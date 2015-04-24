soundasleep/phpdoc2
===================

`soundasleep/phpdoc2` is another PHP documentation generator, but tries to be cleaner
and smarter and more extensible than existing solutions.

## Using

For easy documentation generation, you can use Grunt with the [grunt-phpdoc2](https://github.com/soundasleep/grunt-phpdoc2) task.
For example, see the example Gruntfile provided in the [phpdoc2-openclerk](https://github.com/soundasleep/phpdoc2-openclerk/blob/gh-pages/Gruntfile.coffee).

## Demos

* [http://soundasleep.github.io/phpdoc2-openclerk/docs/index.html](Openclerk) - [source]((https://github.com/soundasleep/phpdoc2-openclerk))

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
* `@deprecated` tag
* Class variables
* `{@link foo actual text}` support
* `{@link plural}s` support
* Maybe try to provide tests for these types of tags, perhaps a sample project, or simply do HTML regexps on generated documentation
* Global functions support
* Option for `issue #123` to link to an external issue tracker
* Link through to open source projects for composer projects
* Link through to source code for GitHub projects

## See also

- [phpDox](http://phpdox.de/)
- [phpDocumentor](http://www.phpdoc.org/)
- [PHPDoc](http://www.phpdoc.de/)
