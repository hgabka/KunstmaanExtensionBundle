<?php

namespace Hgabka\KunstmaanExtensionBundle\Helper\Words;

use Hgabka\KunstmaanExtensionBundle\Helper\Number\Words;

/**
 * Class for translating numbers into Brazilian Portuguese. This class complies
 * to Brazilian Academy of Letters rules as of 2008-12-12.
 *
 * @category Numbers
 * @package  Numbers_Words
 * @author   Igor Feghali <ifeghali@php.net>
 * @license  PHP 3.01 http://www.php.net/license/3_01.txt
 * @link     http://pear.php.net/package/Numbers_Words
 */
class Words_pt_BR extends Words
{
    /**
     * Locale name
     * @var string
     * @access public
     */
    var $locale = 'pt_BR';

    /**
     * Language name in English
     * @var string
     * @access public
     */
    var $lang = 'Brazilian Portuguese';

    /**
     * Native language name
     * @var string
     * @access public
     */
    var $lang_native = 'Portuguęs Brasileiro';

    /**
     * The word for the minus sign
     * @var string
     * @access private
     */
    var $_minus = 'negativo';

    /**
     * The word separator for numerals
     * @var string
     * @access private
     */
    var $_sep = ' e ';

    /**
     * The special separator for numbers and currency names
     * @var string
     * @access private
     */
    var $_curr_sep = ' de ';

    /**
     * The array containing numbers 11-19.
     * In Brazilian Portuguese numbers in that range are contracted
     * in a single word.
     * @var array
     * @access private
     */
    var $_contractions = [
        '',
        'onze',
        'doze',
        'treze',
        'quatorze',
        'quinze',
        'dezesseis',
        'dezessete',
        'dezoito',
        'dezenove',
    ];

    var $_words = [
        /**
         * The array containing the digits (indexed by the digits themselves).
         * @var array
         * @access private
         */
        [
            '',         // 0: not displayed
            'um',
            'dois',
            'tręs',
            'quatro',
            'cinco',
            'seis',
            'sete',
            'oito',
            'nove',
        ],

        /**
         * The array containing numbers for 10,20,...,90.
         * @var array
         * @access private
         */
        [
            '',         // 0: not displayed
            'dez',
            'vinte',
            'trinta',
            'quarenta',
            'cinqüenta',
            'sessenta',
            'setenta',
            'oitenta',
            'noventa',
        ],

        /**
         * The array containing numbers for hundreds.
         * @var array
         * @access private
         */
        [
            '',         // 0: not displayed
            'cento',    // 'cem' is a special case handled in _toWords()
            'duzentos',
            'trezentos',
            'quatrocentos',
            'quinhentos',
            'seiscentos',
            'setecentos',
            'oitocentos',
            'novecentos',
        ],
    ];

    /**
     * The sufixes for exponents (singular)
     * @var array
     * @access private
     */
    var $_exponent = [
        '',         // 0: not displayed
        'mil',
        'milhăo',
        'bilhăo',
        'trilhăo',
        'quatrilhăo',
        'quintilhăo',
        'sextilhăo',
        'septilhăo',
        'octilhăo',
        'nonilhăo',
        'decilhăo',
        'undecilhăo',
        'dodecilhăo',
        'tredecilhăo',
        'quatuordecilhăo',
        'quindecilhăo',
        'sedecilhăo',
        'septendecilhăo',
    ];

    /**
     * The currency names (based on Wikipedia) and plurals
     *
     * @var array
     * @link http://pt.wikipedia.org/wiki/ISO_4217
     * @access private
     */
    var $_currency_names = [
        'BRL' => [['real', 'reais'], ['centavo', 'centavos']],
        'USD' => [['dólar', 'dólares'], ['centavo', 'centavos']],
        'EUR' => [['euro', 'euros'], ['centavo', 'centavos']],
        'GBP' => [['libra esterlina', 'libras esterlinas'], ['centavo', 'centavos']],
        'JPY' => [['iene', 'ienes'], ['centavo', 'centavos']],
        'ARS' => [['peso argentino', 'pesos argentinos'], ['centavo', 'centavos']],
        'MXN' => [['peso mexicano', 'pesos mexicanos'], ['centavo', 'centavos']],
        'UYU' => [['peso uruguaio', 'pesos uruguaios'], ['centavo', 'centavos']],
        'PYG' => [['guarani', 'guaranis'], ['centavo', 'centavos']],
        'BOB' => [['boliviano', 'bolivianos'], ['centavo', 'centavos']],
        'CLP' => [['peso chileno', 'pesos chilenos'], ['centavo', 'centavos']],
        'COP' => [['peso colombiano', 'pesos colombianos'], ['centavo', 'centavos']],
        'CUP' => [['peso cubano', 'pesos cubanos'], ['centavo', 'centavos']],
    ];

    /**
     * The default currency name
     * @var string
     * @access public
     */
    var $def_currency = 'BRL'; // Real

    // {{{ _toWords()

    /**
     * Converts a number to its word representation
     * in Brazilian Portuguese language
     *
     * @param integer $num An integer between -999E54 and 999E54
     *
     * @return string  The corresponding word representation
     *
     * @access protected
     * @author Igor Feghali <ifeghali@php.net>
     * @since  Numbers_Words 0.16.3
     */
    function _toWords($num)
    {
        $neg = 0;
        $ret = [];
        $words = [];

        /**
         * Negative ?
         */
        if ($num < 0) {
            $ret[] = $this->_minus;
            $num = -$num;
            $neg = 1;
        }

        /**
         * Removes leading zeros, spaces, decimals etc.
         * Adds thousands separator.
         */
        $num = number_format($num, 0, '.', '.');

        /**
         * Testing Zero
         */
        if ($num == 0) {
            return 'zero';
        }

        /**
         * Breaks into chunks of 3 digits.
         * Reversing array to process from right to left.
         */
        $chunks = array_reverse(explode(".", $num));

        /**
         * Looping through the chunks
         */
        foreach ($chunks as $index => $chunk) {
            /**
             * Testing Range
             */
            if (!array_key_exists($index, $this->_exponent)) {
                return Numbers_Words::raiseError('Number out of range.');
            }

            /**
             * Testing Zero
             */
            if ($chunk == 0) {
                continue;
            }

            /**
             * Testing plural of exponent
             */
            if ($chunk > 1) {
                $exponent = str_replace('ăo', 'őes', $this->_exponent[$index]);
            } else {
                $exponent = $this->_exponent[$index];
            }

            /**
             * Adding exponent
             */
            $ret[] = $exponent;

            /**
             * Actual Number
             */
            $word = array_filter($this->_parseChunk($chunk));
            $ret[] = implode($this->_sep, $word);
        }

        /**
         * In Brazilian Portuguese the last chunck must be separated under
         * special conditions.
         */
        if ((count($ret) > 2 + $neg)
            && $this->_mustSeparate($chunks)) {
            $ret[1 + $neg] = trim($this->_sep . $ret[1 + $neg]);
        }

        $ret = array_reverse(array_filter($ret));

        return implode(' ', $ret);
    }

    /**
     * Recursive function that parses an indivial chunk
     *
     * @param string $chunk String representation of a 3-digit-max number
     *
     * @return array Words of parsed number
     *
     * @access private
     * @author Igor Feghali <ifeghali@php.net>
     * @since  Numbers_Words 0.15.1
     */
    function _parseChunk($chunk)
    {
        /**
         * Base Case
         */
        if (!$chunk) {
            return [];
        }

        /**
         * 100 is a special case
         */
        if ($chunk == 100) {
            return ['cem'];
        }

        /**
         * Testing contractions (11~19)
         */
        if (($chunk < 20) && ($chunk > 10)) {
            return [$this->_contractions[$chunk % 10]];
        }

        $i = strlen($chunk) - 1;
        $n = (int)$chunk[0];
        $word = $this->_words[$i][$n];

        return array_merge([$word], $this->_parseChunk(substr($chunk, 1)));
    }

    /**
     * In Brazilian Portuguese the last chunk must be separated from the others
     * when it is a hundred (100, 200, 300 etc.) or less than 100.
     *
     * @param array $chunks Array of integers that contains all the chunks of
     *                      the target number, in reverse order.
     *
     * @return boolean Returns true when last chunk must be separated
     *
     * @access private
     * @author Igor Feghali <ifeghali@php.net>
     * @since  Numbers_Words 0.15.1
     */
    function _mustSeparate($chunks)
    {
        $chunk = null;

        /**
         * Find first occurrence != 0.
         * (first chunk in array but last logical chunk)
         */
        reset($chunks);
        do {
            list(, $chunk) = each($chunks);
        } while ($chunk === '000');

        if (($chunk < 100) || !($chunk % 100)) {
            return true;
        }

        return false;
    }

    /**
     * Converts a currency value to its word representation
     * (with monetary units) in Portuguese Brazilian
     *
     * @param integer $int_curr An international currency symbol
     *                                  as defined by the ISO 4217 standard (three characters)
     * @param integer $decimal A money total amount without fraction part (e.g. amount of dollars)
     * @param integer $fraction Fractional part of the money amount (e.g. amount of cents)
     *                                  Optional. Defaults to false.
     * @param integer $convert_fraction Convert fraction to words (left as numeric if set to false).
     *                                  Optional. Defaults to true.
     *
     * @return string  The corresponding word representation for the currency
     *
     * @access public
     * @author Igor Feghali <ifeghali@php.net>
     * @since  Numbers_Words 0.11.0
     */
    function toCurrencyWords($int_curr, $decimal, $fraction = false, $convert_fraction = true)
    {
        $neg = 0;
        $ret = [];
        $nodec = false;

        /**
         * Negative ?
         * We can lose the '-' sign if we do the
         * check after number_format() call (i.e. -0.01)
         */
        if (substr($decimal, 0, 1) == '-') {
            $decimal = -$decimal;
            $neg = 1;
        }

        /**
         * Removes leading zeros, spaces, decimals etc.
         * Adds thousands separator.
         */
        $num = number_format($decimal, 0, '', '');

        /**
         * Checking if given currency exists in driver.
         * If not, use default currency
         */
        $int_curr = strtoupper($int_curr);
        if (!isset($this->_currency_name[$int_curr])) {
            $int_curr = $this->def_currency;
        }

        /**
         * Currency names and plural
         */
        $curr_names = $this->_currency_names[$int_curr];

        if ($num > 0) {
            /**
             * Word representation of decimal
             */
            $ret[] = $this->_toWords($num);

            /**
             * Special case.
             */
            if (substr($num, -6) == '000000') {
                $ret[] = trim($this->_curr_sep);
            }

            /**
             * Testing plural. Adding currency name
             */
            if ($num > 1) {
                $ret[] = $curr_names[0][1];
            } else {
                $ret[] = $curr_names[0][0];
            }
        }

        /**
         * Test if fraction was given and != 0
         */
        $fraction = (int)$fraction;
        if ($fraction) {

            /**
             * Removes leading zeros, spaces, decimals etc.
             * Adds thousands separator.
             */
            $num = number_format($fraction, 0, '.', '.');

            /**
             * Testing Range
             */
            if ($num < 0 || $num > 99) {
                return Numbers_Words::raiseError('Fraction out of range.');
            }

            /**
             * Have we got decimal?
             */
            if (count($ret)) {
                $ret[] = trim($this->_sep);
            } else {
                $nodec = true;
            }

            /**
             * Word representation of fraction
             */
            if ($convert_fraction) {
                $ret[] = $this->_toWords($num);
            } else {
                $ret[] = $num;
            }

            /**
             * Testing plural
             */
            if ($num > 1) {
                $ret[] = $curr_names[1][1];
            } else {
                $ret[] = $curr_names[1][0];
            }

            /**
             * Have we got decimal?
             * If not, include currency name after cents.
             */
            if ($nodec) {
                $ret[] = trim($this->_curr_sep);
                $ret[] = $curr_names[0][0];
            }
        }

        /**
         * Negative ?
         */
        if ($neg) {
            $ret[] = $this->_minus;
        }

        return implode(' ', $ret);
    }
}