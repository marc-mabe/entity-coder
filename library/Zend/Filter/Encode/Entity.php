<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: HtmlEntities.php 16217 2009-06-21 19:39:00Z thomas $
 */

/**
 * @see Zend_Filter_Encode_EncodeInterface
 */
require_once 'Zend/Filter/Encode/EncodeInterface.php';

/**
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Filter_Encode_Entity implements Zend_Filter_Encode_EncodeInterface
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
     * @var array
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
            'copy'   => "\xc2\xa9", 'reg'   => "\xc2\xae",
            'ordf'   => "\xc2\xaa", 'ordm'  => "\xc2\xba",
            'laquo'  => "\xc2\xab", 'raquo' => "\xc2\xbb",
            'not'    => "\xc2\xac",
            'shy'    => "\xc2\xad",
            'macr'   => "\xc2\xaf",
            'deg'    => "\xc2\xb0",
            'plusmn' => "\xc2\xb1",
            'sup1'   => "\xc2\xb9", 'sup2' => "\xc2\xb2", 'sup3' => "\xc2\xb3",
            'acute'  => "\xc2\xb4",
            'micro'  => "\xc2\xb5",
            'para'   => "\xc2\xb6",
            'middot' => "\xc2\xb7",
            'cedil'  => "\xc2\xb8",
            // @TODO: define multibyte UTF-8 chars using hexadecimal notation
            'frac14' => '¼', 'frac12' => '½', 'frac34' => '¾',
            'Agrave' => 'À', 'Aacute' => 'Á', 'Acirc'  => 'Â', 'Atilde' => 'Ã', 'Auml'   => 'Ä', 'Aring'  => 'Å', 'AElig'  => 'Æ',
            'agrave' => 'à', 'aacute' => 'á', 'acirc'  => 'â', 'atilde' => 'ã', 'auml'   => 'ä', 'aring'  => 'å', 'aelig'  => 'æ',
            'Ccedil' => 'Ç', 'ccedil' => 'ç',
            'Egrave' => 'È', 'Eacute' => 'É', 'Ecirc'  => 'Ê', 'Euml'   => 'Ë',
            'egrave' => 'è', 'eacute' => 'é', 'ecirc'  => 'ê', 'euml'   => 'ë',
            'Igrave' => 'Ì', 'Iacute' => 'Í', 'Icirc'  => 'Î', 'Iuml'   => 'Ï',
            'igrave' => 'ì', 'iacute' => 'í', 'icirc'  => 'î', 'iuml'   => 'ï',
            'ETH'    => 'Ð', 'eth'    => 'ð',
            'Ntilde' => 'Ñ',
            'Ograve' => 'Ò', 'Oacute' => 'Ó', 'Ocirc'  => 'Ô', 'Otilde' => 'Õ', 'Ouml'   => 'Ö',
            'ograve' => 'ò', 'oacute' => 'ó', 'ocirc'  => 'ô', 'otilde' => 'õ', 'ouml'   => 'ö',
            'times'  => '×',
            'Oslash' => 'Ø',
            'Ugrave' => 'Ù', 'Uacute' => 'Ú', 'Ucirc'  => 'Û', 'Uuml'   => 'Ü',
            'ugrave' => 'ù', 'uacute' => 'ú', 'ucirc'  => 'û', 'uuml'   => 'ü',
            'THORN'  => 'Þ', 'thorn'  => 'þ',
            'szlig'  => 'ß',
            'ntilde' => 'ñ',
            'divide' => '÷',
            'oslash' => 'ø',
            'Yacute' => 'Ý', 'yacute' => 'ý',
            'yuml'   => 'ÿ',

            /* greece (since HTML 4.0) */
            'Alpha'    => 'Α', 'alpha'   => 'α',
            'Beta'     => 'Β', 'beta'    => 'β',
            'Gamma'    => 'Γ', 'gamma'   => 'γ',
            'Delta'    => 'Δ', 'delta'   => 'δ',
            'Epsilon'  => 'Ε', 'epsilon' => 'ε',
            'Zeta'     => 'Ζ', 'zeta'    => 'ζ',
            'Eta'      => 'Η', 'eta'     => 'η',
            'Theta'    => 'Θ', 'theta'   => 'θ',
            'Iota'     => 'Ι', 'iota'    => 'ι',
            'Kappa'    => 'Κ', 'kappa'   => 'κ',
            'Lambda'   => 'Λ', 'lambda'  => 'λ',
            'Mu'       => 'Μ', 'mu'      => 'μ',
            'Nu'       => 'Ν', 'nu'      => 'ν',
            'Xi'       => 'Ξ', 'xi'      => 'ξ',
            'Omicron'  => 'Ο', 'omicron' => 'ο',
            'Pi'       => 'Π', 'pi'      => 'π',
            'Rho'      => 'Ρ', 'rho'     => 'ρ',
            'Sigma'    => 'Σ', 'sigma'   => 'σ', 'sigmaf'  => 'ς',
            'Tau'      => 'Τ', 'tau'     => 'τ',
            'Upsilon'  => 'Υ', 'upsilon' => 'υ',
            'Phi'      => 'Φ', 'phi'     => 'φ',
            'Chi'      => 'Χ', 'chi'     => 'χ',
            'Psi'      => 'Ψ', 'psi'     => 'ψ',
            'Omega'    => 'Ω', 'omega'   => 'ω',
            'thetasym' => 'ϑ',
            'upsih'    => 'ϒ',
            'piv'      => 'ϖ',

            /* math (since HTML 4.0) */
            'forall' => '∀', 'part'  => '∂', 'exist'  => '∃', 'empty' => '∅',
            'nabla'  => '∇', 'isin'  => '∈', 'notin' => '∉', 'ni'     => '∋',
            'prod'   => '∏', 'sum'    => '∑', 'minus'  => '−', 'lowast' => '∗',
            'radic'  => '√', 'prop'   => '∝', 'infin' => '∞', 'ang'    => '∠',
            'and'    => '∧', 'or'    => '∨',
            'cap'    => '∩', 'cup'    => '∪',
            'sub'    => '⊂', 'sup'   => '⊃',
            'nsub'   => '⊄',
            'sube'   => '⊆', 'supe'  => '⊇',
            'int'    => '∫',
            'there4' => '∴',
            'sim'    => '∼', 'cong'   => '≅', 'asymp' => '≈',
            'ne'     => '≠', 'equiv'  => '≡',
            'le'     => '≤', 'ge'     => '≥',
            'oplus'  => '⊕', 'otimes' => '⊗',
            'perp'   => '⊥',
            'sdot'   => '⋅',
            'loz'    => '◊',

            /* tech (since HTML 4.0) */
            'lceil' => '⌈', 'rceil' => '⌉', 'lfloor' => '⌊', 'rfloor' => '⌋',
            'lang'  => '〈', 'rang'  => '〉',

            /* arrow (since HTML 4.0) */
            'larr' => '←', 'uarr'  => '↑', 'rarr' => '→',  'darr' => '↓',
            'harr' => '↔', 'crarr' => '↵',
            'lArr' => '⇐', 'uArr'  => '⇑', 'rArr' => '⇒', 'dArr' => '⇓', 'hArr' => '⇔',

            /* div (since HTML 4.0) */
            'bull'    => '•', 'prime' => '′', 'Prime'  => '″',
            'oline'   => '‾', 'frasl' => '⁄',
            'weierp'  => '℘', 'image' => 'ℑ', 'real'   => 'ℜ',
            'trade'   => '™',
            'euro'    => "\xe2\x82\xac",
            'alefsym' => 'ℵ',
            'spades'  => '♠', 'clubs' => '♣', 'hearts' => '♥', 'diams' => '♦',

            /* latin (since HTML 4.0) */
            'OElig'   => 'Œ', 'oelig'  => 'œ',
            'Scaron'  => 'Š', 'scaron' => 'š',
            'Yuml'    => 'Ÿ',
            'fnof'    => 'ƒ',

            /* punctuation (since HTML 4.0) */
            'ensp'    => ' ', 'emsp'  => ' ', 'thinsp' => ' ',
            'zwnj'    => '‌',  'zwj'   => '‍',
            'lrm'     => '‎',  'rlm'   => '‏',
            'ndash'   => '–', 'mdash' => '—',
            'lsquo'   => '‘', 'rsquo' => '’',
            'sbquo'   => '‚', // 'bsquo' => '‚',
            'ldquo'   => '“', 'rdquo' => '”',
            'bdquo'   => '„',
            'dagger'  => '†', 'Dagger' => '‡',
            'hellip'  => '…',
            'permil'  => '‰',
            'lsaquo'  => '‹', 'rsaquo' => '›',

            /* diacritical (since HTML 4.0) */
            'circ'  => 'ˆ',
            'tilde' => '˜',
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
     * and not valit for output char set or special characters.
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
     * @var string Value of Zend_Filter_Encode_Entity::ACTION_*
     */
    protected $_invalidCharAction = self::ACTION_IGNORE;

    /**
     * The callback called on invalid characters
     * if on_invalid_char is set to callback.
     *
     * @var null|callback
     */
    protected $_invalidCharCallback = null;

    /**
     * The action if an invalid or unknown entity was detected on decode.
     *
     * @var string Value of Zend_Filter_Encode_Entity::ACTION_*
     */
    protected $_invalidEntityAction = self::ACTION_ENTITY;

    /**
     * The callback called on decode invalid entities
     * if on_invalid_entity is set to callback.
     *
     * @var null|callback
     */
    protected $_invalidEntityCallback = null;

    /**
     * The substituting character used with constant INVALID_CHAR_SUBSTITUTE
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
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Missing needed ext/iconv');
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
     * @return Zend_Filter_EntityEncode Provides a fluent interface
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
     * @return Zend_Filter_EntityEncode Provides a fluent interface
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
     * @return Zend_Filter_EntityEncode Provides a fluent interface
     */
    public function setEntityReference($entityReference) {
        if (is_string($entityReference)) {
            if (!isset(self::$_entityReferences[$entityReference])) {
                require_once 'Zend/Filter/Exception.php';
                throw new Zend_Filter_Exception("Unknown entity reference '{$entityReference}'");
            }
            $this->_entityReference = self::$_entityReferences[$entityReference];
        } elseif (is_array($entityReference)) {
            $this->_entityReference = $entityReference;
        } else {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception(
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
     * @return Zend_Filter_EntityEncode Provides a fluent interface
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
     * @return Zend_Filter_Encode_Entity Provides a fluent interface
     */
    public function setKeepSpecial($flag)
    {
        $this->_keepSpecial = (bool)$flag;
        return $this;
    }

    /**
     * Get the action which is done if an invalid character was detected.
     *
     * @return string Value of Zend_Filter_Encode_Entity::INVALID_CHAR_*
     */
    public function getInvalidCharAction()
    {
        return $this->_invalidCharAction;
    }

    /**
     * Set the action which is done if an invalid character was detected.
     *
     * @param string $action The action to set - value of Zend_Filter_Encode_Entity::INVALID_CHAR_*
     * @return Zend_Filter_Encode_Entity Provides a fluent interface
     * @throws Zend_Filter_Exception If an unknown $action was given.
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
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception("Unknown action '{$action}'");
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
     * @return Zend_Filter_Encode_Entity Provides a fluent interface
     * @throws Zend_Filter_Exception If an invalid callback was given.
     */
    public function setInvalidCharCallback($callback)
    {
        if ($callback !== null && !is_callable($callback)) {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Invalid calllback given');
        }

        $this->_invalidCharCallback = $callback;
    }

    /**
     * Get the action which is done if an invalid or unknown entity was detected.
     *
     * @return string Value of Zend_Filter_Encode_Entity::INVALID_ENTITY_*
     */
    public function getInvalidEntityAction()
    {
        return $this->_invalidEntityAction;
    }

    /**
     * Set the action which is done if an invalid or unknown entity was detected.
     *
     * @param string $action Value of Zend_Filter_Encode_Entity::INVALID_ENTITY_*
     * @return Zend_Filter_Encode_Entity Provides a fluent interface
     * @throws Zend_Filter_Exception If an unknown $action was given.
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
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception("Unknown action '{$action}'");
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
     * @return Zend_Filter_Encode_Entity Provides a fluent interface
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
                . '|[\xe0-\xef][\x80-\xbf]{2}' // or 3 bytes (1110xxxx [10xxxxxx, ...])
                . '|[\xf0-\xf7][\x80-\xbf]{3}' // or 4 bytes (11110xxx [10xxxxxx, ...])
                . '|[\xf8-\xfb][\x80-\xbf]{4}' // or 5 bytes (111110xx [10xxxxxx, ...])
                . '|[\xfd-\xfe][\x80-\xbf]{5}' // or 6 bytes (1111110x [10xxxxxx, ...])
                . '|\xfe[\x80-\xbf]{6}'        // or 7 bytes (11111110 [10xxxxxx, ...])
                . ')/s',
                array($this, '_encodeMultibyteMatches'),
                $text
            );

            // convert to output charset
            $text = $this->_utf8ToOutput($text);
        }

        return $text;
    }

    protected function _encodeMultibyteMatches(array $matches)
    {
        $char = (string)@iconv('UTF-8', $this->getOutputCharSet().'//IGNORE', $matches[1]);
        if ($char !== '') {
            return $matches[1];
        }

        return $this->_unicodeToEntity($this->_utf8ToUnicode($matches[1]));
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

    protected function _filterNumEntityMatches(array $matches) {
        $unicode = (int)$matches[1];

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
        if (!isset($char[0])) {
            $invalidEntityAction = $this->getInvalidEntityAction();
            switch ($invalidEntityAction) {
                case self::ACTION_CALLBACK:
                case self::ACTION_TRANSLIT_CALLBACK:
                    $callback = $this->getInvalidEntityCallback();
                    return call_user_func($callback, $matches[0]);

                case self::ACTION_ENTITY:
                case self::ACTION_TRANSLIT_ENTITY:
                    return $matches[0];

                case self::ACTION_EXCEPTION:
                case self::ACTION_TRANSLIT_EXCEPTION:
                    throw new Zend_Filter_Exception("Invalid entity {$matches[0]} found");

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

    protected function _filterHexEntityMatches(array $matches) {
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
        if (!isset($char[0])) {
            $invalidEntityAction = $this->getInvalidEntityAction();
            switch ($invalidEntityAction) {
                case self::ACTION_CALLBACK:
                case self::ACTION_TRANSLIT_CALLBACK:
                    $callback = $this->getInvalidEntityCallback();
                    return call_user_func($callback, $matches[0]);

                case self::ACTION_ENTITY:
                case self::ACTION_TRANSLIT_ENTITY:
                    return $matches[0];

                case self::ACTION_EXCEPTION:
                case self::ACTION_TRANSLIT_EXCEPTION:
                    throw new Zend_Filter_Exception("Invalid entity {$matches[0]} found");

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

        $invalidCharAction = $this->getInvalidCharAction();
        switch ($invalidCharAction) {
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
                throw new Zend_FilteR_Exception("Can't convert UTF-8 character '{$char}' (hex: {$hex}) to '{$this->getOutputCharSet()}'");

            case self::ACTION_IGNORE:
            case self::ACTION_TRANSLIT_IGNORE:
                return '';

            case self::ACTION_SUBSTITUTE:
            case self::ACTION_TRANSLIT_SUBSTITUTE:
                return $this->getSubstitute();
        }
    }

}
