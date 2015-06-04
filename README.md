entity-coder
============

[![Build Status](https://secure.travis-ci.org/marc-mabe/entity-coder.png?branch=master)](http://travis-ci.org/marc-mabe/entity-coder)
[![Quality Score](https://scrutinizer-ci.com/g/marc-mabe/entity-coder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/marc-mabe/entity-coder/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/marc-mabe/entity-coder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/marc-mabe/entity-coder/?branch=master)
[![Dependency Status](https://www.versioneye.com/php/marc-mabe:entity-coder/dev-master/badge.png)](https://www.versioneye.com/php/marc-mabe:entity-coder/dev-master)

Encode & decode XML, HTML and own defined entities into different charsets.


FEATURES
--------

  * decode and encode hex entities
  * decode and encode decimal entities
  * convert from one charset to another
  * selectable if named entities will be used (on encode)
  * selectable if hex or decimal entities will be used (on encode)
  * selectable how to handle characters/entities which are not convertable to given output charset
  * selectable how to handle invalid characters
  * **ACTIONS:**
    * throw exception
    * call callback
    * ignore
    * substitute by a substitution character
    * use entity
    * translit by ASCII name 


Usage
-----

**Encode**
```php
$entityCoder = new EntityCoder(array(
    // set named entity reference
    // -> 'special', 'xml', 'html' or an array of your own entities
    'entityReference' => 'special',

    // set your input charset (default: ISO-8859-1)
    'inputCharSet' => 'UTF-8',

    // set your output charset (default: ISO-8859-1)
    'outputCharSet' => 'ISO-8859-15',

    // convert to hex entities if no named entity exists (default: false)
    'hex' => true,
));

$text = $entityCoder->encode($text);
```

**Decode**
```php
$entityCoder = new EntityCoder(array(
    // set named entity reference
    // -> 'special', 'xml', 'html' or an array of your own entities
    'entityReference' => 'html',

    // set your input charset (default: ISO-8859-1)
    'inputCharSet' => 'ISO-8859-15',

    // set your output charset (default: ISO-8859-1)
    'outputCharSet' => 'ASCII',

    // How to handle invalid characters
    // EXCEPTION  = throw an exception
    // CALLBACK   = call a callback
    // IGNORE     = replace by an empty character
    // SUBSTITUTE = substitute by an substitution character (default: "?")
    // TRANSLIT_* = convert to ASCII name with a fallback to one of the other actions
    'invalidCharAction' => EntityCoder::ACTION_SUBSTITUTE,

    // How to handle invalid entities
    // EXCEPTION  = throw an exception
    // CALLBACK   = call a callback
    // IGNORE     = replace by an empty character
    // SUBSTITUTE = substitute by an substitution character (default: "?")
    // ENTITY     = leave the entity as-is
    'invalidEntityAction' => EntityCoder::ACTION_ENTITY,
));

$text = $entityCoder->decode($text);
```


REQUIREMENTS
------------

This library requires PHP 5.3.0 or later.
Additionally the php extension iconv is needed. (enabled by default)


ISSUES AND FEEDBACK
-------------------

If you find code in this release behaving in an unexpected manner or
contrary to its documented behavior, please create an issue in the
GitHub issue tracker at:

https://github.com/marc-mabe/entity-coder/issues


LICENSE
-------

The files in this archive are released under the new BSD license.
You can find a copy of this license in LICENSE.txt.
