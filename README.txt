EntityCoder

FEATURES
--------

  * decode and encode hex entities
  * decode and encode decimal entities
  * convert from one charset to another
  * selectable if named entities will be used (on encode)
  * selectable if hex or decimal entities will be used (on encode)
  * selectable how to handle characters/entities which are not convertable to given output charset
  * selectable how to handle invalid characters
  
  ACTIONS:
    * throw exception
    * call callback
    * ignore
    * substitute by a substitution character
    * use entity
    * translit by ASCII name 


REQUIREMENTS
------------

The EntityCoder requires PHP 5.3.0 or later.
Additionally the php extension iconv is needed. (enabled by default)


DOCUMENTATION
-------------

Online documentation can be found at:

https://github.com/marc-mabe/EntityCoder/wiki.


ISSUES AND FEEDBACK
-------------------

If you find code in this release behaving in an unexpected manner or
contrary to its documented behavior, please create an issue in the
GitHub issue tracker at:

https://github.com/marc-mabe/EntityCoder/issues


LICENSE
-------

The files in this archive are released under the new BSD license.
You can find a copy of this license in LICENSE.txt.
