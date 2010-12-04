<?php
/**
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/marc-mabe/EntityCoder/blob/master/LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@marc-bennewitz.de so I can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2010-2011 Marc Bennewitz
 * @license    New BSD License
 */

namespace EntityCoder;

/**
 * @copyright  Copyright (c) 2010-2011 Marc Bennewitz
 * @license    New BSD License
 */
class EntityCoder
{

    const ACTION_EXCEPTION  = 'exception';
    const ACTION_CALLBACK   = 'callback';
    const ACTION_IGNORE     = 'ignore';
    const ACTION_SUBSTITUTE = 'substitute';
    const ACTION_ENTITY     = 'entity';

    const ACTION_TRANSLIT_EXCEPTION  = 'translitException';
    const ACTION_TRANSLIT_CALLBACK   = 'translitCallback';
    const ACTION_TRANSLIT_IGNORE     = 'translitIgnore';
    const ACTION_TRANSLIT_SUBSTITUTE = 'translitSubstitute';
    const ACTION_TRANSLIT_ENTITY     = 'translitEntity';

    /**
     * Predefined entity references.
     *
     * @var array array(
     *                <string referenceName> => array(
     *                    <string entityName> => <utf-8 entityValue> [, ...]
     *                ) [, ...]
     *            )
     */
    public static $_entityReferences = array(
        /* special entities */
        'special' => array(
            'amp'  => '&',
            'lt'   => '<',
            'gt'   => '>',
            'quot' => '"',
        ),

        /* available on xml without any definition */
        'xml' => array(
            'amp'  => '&',
            'lt'   => '<',
            'gt'   => '>',
            'quot' => '"',
            'apos' => "'", // not available on html
        ),

        /* All HTML 4.0 entities */
        'html' => array(
            /* special entities */
            'amp'  => '&',
            'lt'   => '<',
            'gt'   => '>',
            'quot' => '"',

            /* latin-1 (since HTML 2.0/3.2) */
            'nbsp'   => "\xc2\xa0",
            'iexcl'  => "\xc2\xa1", 'iquest' => "\xc2\xbf",
            'curren' => "\xc2\xa4", 'cent'   => "\xc2\xa2", 'pound' => "\xc2\xa3", 'yen' => "\xc2\xa5",
            'brvbar' => "\xc2\xa6",
            'sect'   => "\xc2\xa7",
            'uml'    => "\xc2\xa8",
            'copy'   => "\xc2\xa9", 'reg'    => "\xc2\xae",
            'ordf'   => "\xc2\xaa", 'ordm'   => "\xc2\xba",
            'laquo'  => "\xc2\xab", 'raquo'  => "\xc2\xbb",
            'not'    => "\xc2\xac",
            'shy'    => "\xc2\xad",
            'macr'   => "\xc2\xaf",
            'deg'    => "\xc2\xb0",
            'plusmn' => "\xc2\xb1",
            'sup1'   => "\xc2\xb9", 'sup2'   => "\xc2\xb2", 'sup3' => "\xc2\xb3",
            'acute'  => "\xc2\xb4",
            'micro'  => "\xc2\xb5",
            'para'   => "\xc2\xb6",
            'middot' => "\xc2\xb7",
            'cedil'  => "\xc2\xb8",
            'frac14' => "\xc2\xbc", 'frac12' => "\xc2\xbd", 'frac34' => "\xc2\xbe",
            'Agrave' => "\xc3\x80", 'Aacute' => "\xc3\x81", 'Acirc'  => "\xc3\x82",
            'Atilde' => "\xc3\x83", 'Auml'   => "\xc3\x84", 'Aring'  => "\xc3\x85", 'AElig' => "\xc3\x86",
            'agrave' => "\xc3\xa0", 'aacute' => "\xc3\xa1", 'acirc'  => "\xc3\xa2",
            'atilde' => "\xc3\xa3", 'auml'   => "\xc3\xa4", 'aring'  => "\xc3\xa5", 'aelig' => "\xc3\xa6",
            'Ccedil' => "\xc3\x87", 'ccedil' => "\xc3\xa7",
            'Egrave' => "\xc3\x88", 'Eacute' => "\xc3\x89", 'Ecirc'  => "\xc3\x8a", 'Euml'  => "\xc3\x8b",
            'egrave' => "\xc3\xa8", 'eacute' => "\xc3\xa9", 'ecirc'  => "\xc3\xaa", 'euml'  => "\xc3\xab",
            'Igrave' => "\xc3\x8c", 'Iacute' => "\xc3\x8d", 'Icirc'  => "\xc3\x8e", 'Iuml'  => "\xc3\x8f",
            'igrave' => "\xc3\xac", 'iacute' => "\xc3\xad", 'icirc'  => "\xc3\xae", 'iuml'  => "\xc3\xaf",
            'ETH'    => "\xc3\x90", 'eth'    => "\xc3\xb0",
            'Ntilde' => "\xc3\x91", 'ntilde' => "\xc3\xb1",
            'Ograve' => "\xc3\x92", 'Oacute' => "\xc3\x93", 'Ocirc'  => "\xc3\x94", 'Otilde' => "\xc3\x95", 'Ouml' => "\xc3\x96",
            'ograve' => "\xc3\xb2", 'oacute' => "\xc3\xb3", 'ocirc'  => "\xc3\xb4", 'otilde' => "\xc3\xb5", 'ouml' => "\xc3\xb6",
            'times'  => "\xc3\x97",
            'Oslash' => "\xc3\x98", 'oslash' => "\xc3\xb8",
            'Ugrave' => "\xc3\x99", 'Uacute' => "\xc3\x9a", 'Ucirc'  => "\xc3\x9b", 'Uuml'   => "\xc3\x9c",
            'ugrave' => "\xc3\xb9", 'uacute' => "\xc3\xba", 'ucirc'  => "\xc3\xbb", 'uuml'   => "\xc3\xbc",
            'Yacute' => "\xc3\x9d", 'yacute' => "\xc3\xbd",
            'THORN'  => "\xc3\x9e", 'thorn'  => "\xc3\xbe",
            'szlig'  => "\xc3\x9f",
            'divide' => "\xc3\xb7",
            'yuml'   => "\xc3\xbf",

            /* latin (since HTML 4.0) */
            'OElig'   => "\xc5\x92", 'oelig'  => "\xc5\x93",
            'Scaron'  => "\xc5\xa0", 'scaron' => "\xc5\xa1",
            'Yuml'    => "\xc5\xb8",
            'fnof'    => "\xc6\x92",

            /* greece (since HTML 4.0) */
            'Alpha'    => "\xce\x91", 'alpha'   => "\xce\xb1",
            'Beta'     => "\xce\x92", 'beta'    => "\xce\xb2",
            'Gamma'    => "\xce\x93", 'gamma'   => "\xce\xb3",
            'Delta'    => "\xce\x94", 'delta'   => "\xce\xb4",
            'Epsilon'  => "\xce\x95", 'epsilon' => "\xce\xb5",
            'Zeta'     => "\xce\x96", 'zeta'    => "\xce\xb6",
            'Eta'      => "\xce\x97", 'eta'     => "\xce\xb7",
            'Theta'    => "\xce\x98", 'theta'   => "\xce\xb8",
            'Iota'     => "\xce\x99", 'iota'    => "\xce\xb9",
            'Kappa'    => "\xce\x9a", 'kappa'   => "\xce\xba",
            'Lambda'   => "\xce\x9b", 'lambda'  => "\xce\xbb",
            'Mu'       => "\xce\x9c", 'mu'      => "\xce\xbc",
            'Nu'       => "\xce\x9d", 'nu'      => "\xce\xbd",
            'Xi'       => "\xce\x9e", 'xi'      => "\xce\xbe",
            'Omicron'  => "\xce\x9f", 'omicron' => "\xce\xbf",
            'Pi'       => "\xce\xa0", 'pi'      => "\xcf\x80",
            'Rho'      => "\xce\xa1", 'rho'     => "\xcf\x81",
            'Sigma'    => "\xce\xa3", 'sigma'   => "\xcf\x83", 'sigmaf'  => "\xcf\x82",
            'Tau'      => "\xce\xa4", 'tau'     => "\xcf\x84",
            'Upsilon'  => "\xce\xa5", 'upsilon' => "\xcf\x85",
            'Phi'      => "\xce\xa6", 'phi'     => "\xcf\x86",
            'Chi'      => "\xce\xa7", 'chi'     => "\xcf\x87",
            'Psi'      => "\xce\xa8", 'psi'     => "\xcf\x88",
            'Omega'    => "\xce\xa9", 'omega'   => "\xcf\x89",
            'thetasym' => "\xcf\x91",
            'upsih'    => "\xcf\x92",
            'piv'      => "\xcf\x96",

            /* math (since HTML 4.0) */
            'forall' => "\xe2\x88\x80", 'part'   => "\xe2\x88\x82", 'exist' => "\xe2\x88\x83", 'empty'  => "\xe2\x88\x85",
            'nabla'  => "\xe2\x88\x87", 'isin'   => "\xe2\x88\x88", 'notin' => "\xe2\x88\x89", 'ni'     => "\xe2\x88\x8b",
            'prod'   => "\xe2\x88\x8f", 'sum'    => "\xe2\x88\x91", 'minus' => "\xe2\x88\x92", 'lowast' => "\xe2\x88\x97",
            'radic'  => "\xe2\x88\x9a", 'prop'   => "\xe2\x88\x9d", 'infin' => "\xe2\x88\x9e", 'ang'    => "\xe2\x88\xa0",
            'and'    => "\xe2\x88\xa7", 'or'     => "\xe2\x88\xa8",
            'cap'    => "\xe2\x88\xa9", 'cup'    => "\xe2\x88\xaa",
            'int'    => "\xe2\x88\xab",
            'there4' => "\xe2\x88\xb4",
            'sim'    => "\xe2\x88\xbc", 'cong'   => "\xe2\x89\x85", 'asymp' => "\xe2\x89\x88",
            'ne'     => "\xe2\x89\xa0", 'equiv'  => "\xe2\x89\xa1",
            'le'     => "\xe2\x89\xa4", 'ge'     => "\xe2\x89\xa5",
            'sub'    => "\xe2\x8a\x82", 'sup'    => "\xe2\x8a\x83",
            'nsub'   => "\xe2\x8a\x84",
            'sube'   => "\xe2\x8a\x86", 'supe'   => "\xe2\x8a\x87",
            'oplus'  => "\xe2\x8a\x95", 'otimes' => "\xe2\x8a\x97",
            'perp'   => "\xe2\x8a\xa5",
            'sdot'   => "\xe2\x8b\x85",
            'loz'    => "\xe2\x97\x8a",

            /* tech (since HTML 4.0) */
            'lceil' => "\xe2\x8c\x88", 'rceil' => "\xe2\x8c\89", 'lfloor' => "\xe2\8c\x8a", 'rfloor' => "\xe2\x8c\x8b",
            'lang'  => "\xe2\x8c\xa9", 'rang'  => "\xe2\x8c\xaa",

            /* arrow (since HTML 4.0) */
            'larr'  => "\xe2\x86\x90", 'uarr'  => "\xe2\x86\x91", 'rarr' => "\xe2\x86\x92", 'darr' => "\xe2\x86\x93", 'harr' => "\xe2\x86\x94",
            'lArr'  => "\xe2\x87\x90", 'uArr'  => "\xe2\x87\x91", 'rArr' => "\xe2\x87\x92", 'dArr' => "\xe2\x87\x93", 'hArr' => "\xe2\x87\x94",
            'crarr' => "\xe2\x86\xb5",

            /* div (since HTML 4.0) */
            'bull'    => "\xe2\x80\xa2", 'prime' => "\xe2\x80\xb2", 'Prime'  => "\xe2\x80\xb3",
            'oline'   => "\xe2\x80\xbe", 'frasl' => "\xe2\x81\x84",
            'euro'    => "\xe2\x82\xac",
            'image'   => "\xe2\x84\x91", 'weierp'  => "\xe2\x84\x98", 'real'   => "\xe2\x84\x9c",
            'trade'   => "\xe2\x84\xa2", 'alefsym' => "\xe2\x84\xb5",
            'spades'  => "\xe2\x99\xa0", 'clubs'   => "\xe2\x99\xa3", 'hearts' => "\xe2\x99\xa5", 'diams' => "\xe2\x99\xa6",

            /* punctuation (since HTML 4.0) */
            'ensp'    => "\xe2\x80\x82", 'emsp'  => "\xe2\x80\x83", 'thinsp' => "\xe2\x80\x89",
            'zwnj'    => "\xe2\x80\x8c", 'zwj'   => "\xe2\x80\x8d",
            'lrm'     => "\xe2\x80\x8e", 'rlm'   => "\xe2\x80\x8f",
            'ndash'   => "\xe2\x80\x93", 'mdash' => "\xe2\x80\x94",
            'lsquo'   => "\xe2\x80\x98", 'rsquo' => "\xe2\x80\x99",
            'sbquo'   => "\xe2\x80\x9a", // 'bsquo' => "\xe2\x80\x9a",
            'ldquo'   => "\xe2\x80\x9c", 'rdquo' => "\xe2\x80\x9d",
            'bdquo'   => "\xe2\x80\x9e",
            'dagger'  => "\xe2\x80\xa0", 'Dagger' => "\xe2\x80\xa1",
            'hellip'  => "\xe2\x80\xa6",
            'permil'  => "\xe2\x80\xb0",
            'lsaquo'  => "\xe2\x80\xb9", 'rsaquo' => "\xe2\x80\xba",

            /* diacritical (since HTML 4.0) */
            'circ'  => "\xcb\x86",
            'tilde' => "\xcb\x9c",
        ),
    );

    /**
     * Entity reference.
     *
     * @var array
     */
    protected $_entityReference = array();

    /**
     * Character set of input value.
     *
     * @var string
     */
    protected $_inputCharSet = 'ISO-8859-1';

    /**
     * Character set of output value.
     *
     * @var string
     */
    protected $_outputCharSet = 'ISO-8859-1';

    /**
     * Use hexadecimal or numeric entities for characters not in character reference
     * and not valid for output char set or special characters.
     *
     * @var boolean
     */
    protected $_hex = false;

    /**
     * Don't decode entities of special characters.
     * (", &, <, >)
     *
     * @var bool
     */
    protected $_keepSpecial = false;

    /**
     * The action if an entity can't convert to the given charset
     *
     * @var string Value of EntityCoder\EntityCoder::ACTION_*
     */
    protected $_invalidCharAction = self::ACTION_IGNORE;

    /**
     * The callback called on invalid characters
     * if invalid_char_action is set to callback.
     *
     * @var null|callback
     */
    protected $_invalidCharCallback = null;

    /**
     * The action if an invalid or unknown entity was detected on decode.
     *
     * @var string Value of EntityCoder\EntityCoder::ACTION_*
     */
    protected $_invalidEntityAction = self::ACTION_ENTITY;

    /**
     * The callback called on decode invalid entities
     * if invalid_entity_action is set to callback.
     *
     * @var null|callback
     */
    protected $_invalidEntityCallback = null;

    /**
     * The substituting character used with one of the substitute action
     *
     * @var string
     */
    protected $_substitute = '?';

    /**
     * Sets filter options
     *
     * @param  integer|array $quoteStyle
     * @param  string  $charSet
     * @return void
     */
    public function __construct($options = array())
    {
        if (!extension_loaded('iconv')) {
            throw new ExtensionNotLoadedException('Missing ext/iconv');
        }

	foreach ($options as $k => $v) {
            if (method_exists($this, 'set'.$k)) {
                $this->{'set'.$k}($v);
            }
        }
    }

    /**
     * Returns input character set.
     *
     * @return string
     */
    public function getInputCharSet()
    {
        return $this->_inputCharSet;
    }

    /**
     * Set input character set.
     *
     * @param  string $enc
     * @return EntityCoder\EntityCoder Provides a fluent interface
     */
    public function setInputCharSet($enc)
    {
        $this->_inputCharSet = strtoupper($enc);
        return $this;
    }

    /**
     * Returns output character set.
     *
     * @return string
     */
    public function getOutputCharSet()
    {
        return $this->_outputCharSet;
    }

    /**
     * Set output character set.
     *
     * @param  string $enc
     * @return EntityCoder\EntityCoder Provides a fluent interface
     */
    public function setOutputCharSet($enc)
    {
        $this->_outputCharSet = strtoupper($enc);
        return $this;
    }

    /**
     * Returns entity reference.
     * Format: array("<string name>" => <utf8 value>[, ...])
     *
     * @return array
     */
    public function getEntityReference() {
        return $this->_entityReference;
    }

    /**
     * Set entity reference.
     * Format: array("<string name>" => <utf8 value>[, ...])
     *    or:  name of a predefined entity reference
     *
     * @param array|string $entityReference Entity reference.
     * @return EntityCoder\EntityCoder Provides a fluent interface
     */
    public function setEntityReference($entityReference) {
        if (is_string($entityReference)) {
            if (!isset(self::$_entityReferences[$entityReference])) {
                throw new InvalidArgumentException("Unknown entity reference '{$entityReference}'");
            }
            $this->_entityReference = self::$_entityReferences[$entityReference];
        } elseif (is_array($entityReference)) {
            $this->_entityReference = $entityReference;
        } else {
            throw new InvalidArgumentException(
                'Invalid entity reference: must be an array '
              . 'or one of the predefined entity references: '
              . implode(', ', array_keys(self::$_entityReferences))
            );
        }

        return $this;
    }

    /**
     * Get the hex option
     *
     * @return boolean
     */
    public function getHex() {
        return $this->_hex;
    }

    /**
     * Sets the hex option.
     *
     * @param bool $flag
     * @return EntityCoder\EntityCoder Provides a fluent interface
     */
    public function setHex($flag) {
        $this->_hex = (bool)$flag;
        return $this;
    }

    /**
     * Get keep special option.
     *
     * @return bool
     */
    public function getKeepSpecial()
    {
        return $this->_keepSpecial;
    }

    /**
     * Sets keep special option
     *
     * @param bool $flag
     * @return EntityCoder\EntityCoder Provides a fluent interface
     */
    public function setKeepSpecial($flag)
    {
        $this->_keepSpecial = (bool)$flag;
        return $this;
    }

    /**
     * Get the action which is done if an invalid character was detected.
     *
     * @return string Value of EntityCoder\EntityCoder::INVALID_CHAR_*
     */
    public function getInvalidCharAction()
    {
        return $this->_invalidCharAction;
    }

    /**
     * Set the action which is done if an invalid character was detected.
     *
     * @param string $action The action to set - value of EntityCoder\EntityCoder::INVALID_CHAR_*
     * @return EntityCoder\EntityCoder Provides a fluent interface
     * @throws EntityCoder\InvalidArgumentException If an unknown $action was given.
     */
    public function setInvalidCharAction($action)
    {
        static $actions = array(
            self::ACTION_EXCEPTION, self::ACTION_CALLBACK,
            self::ACTION_IGNORE, self::ACTION_SUBSTITUTE,
            self::ACTION_ENTITY,
            self::ACTION_TRANSLIT_EXCEPTION, self::ACTION_TRANSLIT_CALLBACK,
            self::ACTION_TRANSLIT_IGNORE, self::ACTION_TRANSLIT_SUBSTITUTE,
            self::ACTION_TRANSLIT_ENTITY
        );

        if (!in_array($action, $actions)) {
            throw new InvalidArgumentException("Unknown action '{$action}'");
        }
        $this->_invalidCharAction = $action;

        return $this;
    }

    /**
     * Get the callback for invalid characters.
     *
     * @return null|callback
     */
    public function getInvalidCharCallback()
    {
        return $this->_invalidCharCallback;
    }

    /**
     * Set the callback for invalid characters.
     *
     * @param null|callback $callback
     * @return EntityCoder\EntityCoder Provides a fluent interface
     * @throws EntityCoder\InvalidArgumentException If an invalid callback was given.
     */
    public function setInvalidCharCallback($callback)
    {
        if ($callback !== null && !is_callable($callback)) {
            throw new InvalidArgumentException('Invalid calllback given');
        }

        $this->_invalidCharCallback = $callback;
    }

    /**
     * Get the action which is done if an invalid or unknown entity was detected.
     *
     * @return string Value of EntityCoder\EntityCoder::INVALID_ENTITY_*
     */
    public function getInvalidEntityAction()
    {
        return $this->_invalidEntityAction;
    }

    /**
     * Set the action which is done if an invalid or unknown entity was detected.
     *
     * @param string $action Value of EntityCoder\EntityCoder::INVALID_ENTITY_*
     * @return EntityCoder\EntityCoder Provides a fluent interface
     * @throws EntityCoder\InvalidArgumentException If an unknown $action was given.
     */
    public function setInvalidEntityAction($action)
    {
        static $actions = array(
            self::ACTION_EXCEPTION, self::ACTION_CALLBACK,
            self::ACTION_IGNORE, self::ACTION_SUBSTITUTE,
            self::ACTION_ENTITY,
            self::ACTION_TRANSLIT_EXCEPTION, self::ACTION_TRANSLIT_CALLBACK,
            self::ACTION_TRANSLIT_IGNORE, self::ACTION_TRANSLIT_SUBSTITUTE,
            self::ACTION_TRANSLIT_ENTITY
        );

        if (!in_array($action, $actions)) {
            throw new InvalidArgumentException("Unknown action '{$action}'");
        }
        $this->_invalidEntityAction = $action;

        return $this;
    }

    /**
     * Get the substituting string.
     *
     * This string will be used if an entity can't decoded to output charset
     * and on_invalid_char is set to substitute or translite and not translit was found.
     *
     * @return string
     */
    public function getSubstitute() {
        return $this->_substitute;
    }

    /**
     * Set the substituting string.
     *
     * @param string $substitute
     * @return EntityCoder\EntityCoder Provides a fluent interface
     */
    public function setSubstitute($substitute) {
        $this->_substitute = (string)$substitute;
        return $this;
    }

    /**
     * Returns the string $value, converting characters to their corresponding entity
     * equivalents where they exist
     *
     * @param  string $text
     * @return string
     */
    public function encode($text)
    {
        $text = (string)$text;
        if ($text === '') {
            return '';
        }

        $text = $this->_inputToUtf8($text);

        $entRef = array_flip($this->getEntityReference());
        foreach ($entRef as &$ent) {
            $ent = '&' . $ent . ';';
        }

        // convert special chars to there numeric entities
        if ( !isset($entRef['"']) ) {
            $entRef['"'] = $this->_unicodeToEntity(34);
        }
        if ( !isset($entRef['&']) ) {
            $entRef['&'] = $this->_unicodeToEntity(38);
        }
        if ( !isset($entRef["'"]) ) {
            $entRef["'"] = $this->_unicodeToEntity(39);
        }
        if ( !isset($entRef['<']) ) {
            $entRef['<'] = $this->_unicodeToEntity(60);
        }
        if ( !isset($entRef['>']) ) {
            $entRef['>'] = $this->_unicodeToEntity(62);
        }

        $text = strtr($text, $entRef);

        // on converting to output charset we create entities only if character can't be converted.
        if ( $this->getOutputCharSet() != 'UTF-8') {
            // convert multibyte characters if they are not available on output encoding
            $text = preg_replace_callback(
                '/('
                . '[\xc0-\xdf][\x80-\xbf]'     // 2 bytes (110xxxxx 10xxxxxx)
                . '|[\xe0-\xef][\x80-\xbf]{2}' // 3 bytes (1110xxxx [10xxxxxx, ...])
                . '|[\xf0-\xf7][\x80-\xbf]{3}' // 4 bytes (11110xxx [10xxxxxx, ...])
                . '|[\xf8-\xfb][\x80-\xbf]{4}' // 5 bytes (111110xx [10xxxxxx, ...])
                . '|[\xfd-\xfe][\x80-\xbf]{5}' // 6 bytes (1111110x [10xxxxxx, ...])
                . '|\xfe[\x80-\xbf]{6}'        // 7 bytes (11111110 [10xxxxxx, ...])
                . ')/s',
                array($this, '_encodeMultibyteMatches'),
                $text
            );

            // convert to output charset
            $text = $this->_utf8ToOutput($text);
        }

        return $text;
    }

    protected function _encodeMultibyteMatches(array &$matches)
    {
        $char = &$matches[1];

        if ( ($outputEncoding = $this->getOutputCharSet()) != 'UTF-8') {
            $conv = (string)@iconv('UTF-8', $this->getOutputCharSet() . '//IGNORE', $char);
            if ($conv !== '') {
                return $char;
            }
        }

        return $this->_unicodeToEntity($this->_utf8ToUnicode($char));
    }

    /**
     * Converting entities to their corresponding characters.
     *
     * @param  string $text
     * @return string
     */
    public function decode($text)
    {
        $text = $this->_inputToUtf8($text);

        // decode hex entities
        $pattern = '/&#x([a-f0-9]+);/i';
        $text    = preg_replace_callback($pattern, array($this, '_filterHexEntityMatches'), $text);

        // decode numeric entities
        $pattern = '/&#([0-9]+);/';
        $text    = preg_replace_callback($pattern, array($this, '_filterNumEntityMatches'), $text);

        // prepare entity reference
        $entRef = $this->getEntityReference();

        if ($this->getKeepSpecial()) {
            // do not decode special entities
            // TODO: remove by value not by key
            unset(
                $entRef['amp'],
                $entRef['lt'],
                $entRef['gt'],
                $entRef['quot'],
                $entRef['apos']
            );
        }

        // decode entity values
        $entFilter = clone $this;
        foreach ($entRef as $entName => &$entValue) {
            $entRefTmp = $entRef;
            unset($entRefTmp[$entName]);
            $entFilter->setEntityReference($entRefTmp);
            $entValue = $entFilter->decode($entValue);
        }

        $text = strtr($text, $entRef);

        $text = $this->_utf8ToOutput($text);

        return $text;
    }

    protected function _filterNumEntityMatches(array &$matches) {
        $unicode = (int)$matches[1];

        if ($this->getKeepSpecial()
          && ( $unicode == 34 // "
            || $unicode == 38 // &
            || $unicode == 39 // '
            || $unicode == 60 // <
            || $unicode == 62 // >
          )
        ) {
            return $matches[0]; // return entity
        }

        $char = $this->_unicodeToUtf8($unicode);
        if ($char === '') {
            switch ($this->getInvalidEntityAction()) {
                case self::ACTION_CALLBACK:
                case self::ACTION_TRANSLIT_CALLBACK:
                    $callback = $this->getInvalidEntityCallback();
                    return call_user_func($callback, $matches[0]);

                case self::ACTION_ENTITY:
                case self::ACTION_TRANSLIT_ENTITY:
                    return $matches[0];

                case self::ACTION_EXCEPTION:
                case self::ACTION_TRANSLIT_EXCEPTION:
                    throw new InvalidEntityException("Invalid entity {$matches[0]} found");

                case self::ACTION_IGNORE:
                case self::ACTION_TRANSLIT_IGNORE:
                    return '';

                case self::ACTION_SUBSTITUTE:
                case self::ACTION_TRANSLIT_SUBSTITUTE:
                    return $this->getSubstitute();
            }
        }

        return $char;
    }

    protected function _filterHexEntityMatches(array &$matches) {
        $unicode = hexdec($matches[1]);

        if ($this->getKeepSpecial()
          && ( $unicode == 34 // "
            || $unicode == 38 // &
            || $unicode == 39 // '
            || $unicode == 60 // <
            || $unicode == 62 // >
          )
        ) {
            return $matches[0];
        }

        $char = $this->_unicodeToUtf8($unicode);
        if ($char === '') {
            switch ($this->getInvalidEntityAction()) {
                case self::ACTION_CALLBACK:
                case self::ACTION_TRANSLIT_CALLBACK:
                    $callback = $this->getInvalidEntityCallback();
                    return call_user_func($callback, $matches[0]);

                case self::ACTION_ENTITY:
                case self::ACTION_TRANSLIT_ENTITY:
                    return $matches[0];

                case self::ACTION_EXCEPTION:
                case self::ACTION_TRANSLIT_EXCEPTION:
                    throw new InvalidEntityException("Invalid entity {$matches[0]} found");

                case self::ACTION_IGNORE:
                case self::ACTION_TRANSLIT_IGNORE:
                    return '';

                case self::ACTION_SUBSTITUTE:
                case self::ACTION_TRANSLIT_SUBSTITUTE:
                    return $this->getSubstitute();
            }
        }

        return $char;
    }

    protected function _unicodeToEntity($code)
    {
        return ($this->_hex === false) ? '&#' . $code . ';' : '&#x' . dechex($code) . ';';
    }

    protected function _utf8ToUnicode($char)
    {
        $ord = ord($char[0]); // first byte
        if (($ord & 192) != 192) {
            return $ord; // not a multibyte character
        }

        $binBuf = '';
        for ($i = 0; $i < 8; $i++) {
            $ord = $ord << 1;  // shift it left
            if ($ord & 128) {  // if 8th bit is set, there are still bytes in sequence.
                $binBuf.= substr('00000000' . decbin(ord($char[$i+1])), -6);
            } else {
                break;
            }
        }
        $binBuf = substr('00000000' . decbin(ord($char[0])), -(6-$i)) . $binBuf;
        return bindec($binBuf);
    }

    protected function _unicodeToUtf8($unicode) {
        if ($unicode < 0x80) {
            return chr($unicode);

        } elseif ($unicode < 0x800) {
            return chr(0xC0 | $unicode >> 6)
                   . chr(0x80 | $unicode & 0x3F);

        } elseif ($unicode < 0x10000) {
            return chr(0xE0 | $unicode >> 12)
                   . chr(0x80 | ($unicode >> 6) & 0x3F)
                   . chr(0x80 | $unicode & 0x3F);

        } elseif ($unicode < 0x200000) {
            return chr(0xF0 | $unicode >> 18)
                   . chr(0x80 | ($unicode >> 12) & 0x3F)
                   . chr(0x80 | ($unicode >> 6) & 0x3F)
                   . chr(0x80 | $unicode & 0x3F);
        } elseif ($unicode < 0x4000000) {
            return chr(0xF8 | ($unicode >> 24))
                   . chr(0x80 | (($unicode >> 18) & 0x3F))
                   . chr(0x80 | (($unicode >> 12) & 0x3F))
                   . chr(0x80 | (($unicode >> 6) & 0x3F))
                   . chr(0x80 | ($unicode & 0x3F));
        } elseif ($unicode < 0x80000000) {
            return chr(0xFC | ($unicode >> 30))
                   . chr(0x80 | (($unicode >> 24) & 0x3F))
                   . chr(0x80 | (($unicode >> 18) & 0x3F))
                   . chr(0x80 | (($unicode >> 12) & 0x3F))
                   . chr(0x80 | (($unicode >> 6) & 0x3F))
                   . chr(0x80 | ($unicode & 0x3F));
        }

        return false;
    }

    protected function _inputToUtf8($input)
    {
        $from = $this->getInputCharSet();
        if ($input === '' || $from == 'UTF-8') {
            return $input;
        }

        switch ($this->getInvalidCharAction()) {
            case self::ACTION_TRANSLIT_CALLBACK:
            case self::ACTION_TRANSLIT_ENTITY:
            case self::ACTION_TRANSLIT_EXCEPTION:
            case self::ACTION_TRANSLIT_IGNORE:
            case self::ACTION_TRANSLIT_SUBSTITUTE:
                $iconvTo = 'UTF-8//TRANSLIT//IGNORE';
            default:
                $iconvTo = 'UTF-8//IGNORE';
        }

        // TODO: handle invalid characters
        return (string)@iconv($from, $iconvTo, $input);
    }

    protected function _utf8ToOutput($utf8)
    {
        $to = $this->getOutputCharSet();
        if ($utf8 === '' || $to == 'UTF-8') {
            return $utf8;
        }

        switch ($this->getInvalidCharAction()) {
            case self::ACTION_TRANSLIT_CALLBACK:
            case self::ACTION_TRANSLIT_ENTITY:
            case self::ACTION_TRANSLIT_EXCEPTION:
            case self::ACTION_TRANSLIT_IGNORE:
            case self::ACTION_TRANSLIT_SUBSTITUTE:
                $iconvTo = $to . '//TRANSLIT//IGNORE';
                break;
            default:
                $iconvTo = $to . '//IGNORE';
        }

        $strLen = strlen($utf8);
        $outStr = '';
        for ($ptr = 0; $ptr < $strLen; $ptr++) {
            $char = $utf8[$ptr];
            $ord  = ord($char);
            if ($ord > 127) {
                // Multibyte found (first byte!)
                if ($ord & 64) {
                    // The first byte must have the 7th bit set!
                    for ($i = 0; $i < 8; $i++) { // For each byte in MB string
                        $ord = $ord << 1; // Shift char left
                        if ($ord & 128) { // 8th bit
                            // There are still bytes in sequence
                            $char.= $utf8[ ++$ptr ]; // Add the next byte
                        } else {
                            break;
                        }
                    }

                    $tmp = (string)@iconv('UTF-8', $iconvTo, $char);
                    // iconv feature //TRANSLIT//IGNORE convert not tranlitable characters to "?"
                    if ($tmp === '' || $tmp === '?') {
                        $outStr.= $this->_handleInvalidChar($char);
                    } else {
                        $outStr.= $tmp;
                    }
                } else {
                    // Invalid UTF-8
                    $outStr.= $this->_handleInvalidChar($char);
                }
            } else {
                // ASCII (0 - 127)
                $outStr.= $char;
            }
        }

        return $outStr;
    }

    protected function _handleInvalidChar($char)
    {
        $invalidCharAction = $this->getInvalidCharAction();
        switch ($invalidCharAction) {
            case self::ACTION_CALLBACK:
            case self::ACTION_TRANSLIT_CALLBACK:
                $callback = $this->getInvalidCharCallback();
                return (string)call_user_func($callback, $char);

            case self::ACTION_ENTITY:
            case self::ACTION_TRANSLIT_ENTITY:
                $unicode = $this->_utf8ToUnicode($char);
                return $this->getHex() ? '&#x' . dechex($unicode) . ';' : '&#' . $unicode . ';';

            case self::ACTION_EXCEPTION:
            case self::ACTION_TRANSLIT_EXCEPTION:
                $hex = dechex(bindec($char));
                throw new InvalidCharacterException(
                    "Can't convert UTF-8 character '{$char}' ({$hex}) to '{$this->getOutputCharSet()}'"
                );

            case self::ACTION_IGNORE:
            case self::ACTION_TRANSLIT_IGNORE:
                return '';

            case self::ACTION_SUBSTITUTE:
            case self::ACTION_TRANSLIT_SUBSTITUTE:
                return $this->getSubstitute();
        }
    }

}
