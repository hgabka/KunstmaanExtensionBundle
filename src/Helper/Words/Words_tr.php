<?php

namespace Hgabka\KunstmaanExtensionBundle\Helper\Words;

use Hgabka\KunstmaanExtensionBundle\Helper\Number\Words;

/**
 * Class for translating numbers into Italian.
 * It supports up to quadrilions
 *
 * @author Shahriyar Imanov <shehi@imanov.name>
 * @package Numbers_Words
 */
class Words_tr extends Words
{
    /**
     * Locale name
     * @var string
     * @access public
     */
    var $locale = 'tr';

    /**
     * Language name in English
     * @var string
     * @access public
     */
    var $lang = 'Turkish';

    /**
     * Native language name
     * @var string
     * @access public
     */
    var $lang_native = 'Türkçe';

    /**
     * The word for the minus sign
     * @var string
     * @access private
     */
    var $_minus = 'eksi';

    /**
     * The sufixes for exponents (singular and plural)
     * @var array
     * @access private
     */
    var $_exponent = [
        0  => [''],
        3  => ['bin'],
        6  => ['milyon'],
        12 => ['milyar'],
        18 => ['trilyon'],
        24 => ['katrilyon'],
    ];
    /**
     * The array containing the digits (indexed by the digits themselves).
     * @var array
     * @access private
     */
    var $_digits = [
        0 => 'sıfır',
        'bir',
        'iki',
        'üç',
        'dört',
        'beş',
        'altı',
        'yedi',
        'sekiz',
        'dokuz',
    ];

    /**
     * The word separator
     * @var string
     * @access private
     */
    var $_sep = ' ';

    /**
     * The currency names (based on the below links,
     * informations from central bank websites and on encyclopedias)
     *
     * @var array
     * @link http://30-03-67.dreamstation.com/currency_alfa.htm World Currency Information
     * @link http://www.jhall.demon.co.uk/currency/by_abbrev.html World currencies
     * @link http://www.shoestring.co.kr/world/p.visa/change.htm Currency names in English
     * @access private
     */
    var $_currency_names = [
        'ALL' => [['lek'], ['qindarka']],
        'AUD' => [['Avusturalya dolar?'], ['sent']],
        'BAM' => [['convertible marka'], ['fenig']],
        'BGN' => [['Bulgar levas?'], ['stotinka', 'stotinki']],
        'BRL' => [['real'], ['centavos']],
        'BWP' => [['Botswana pulas?'], ['thebe']],
        'BYR' => [['Belarus rublesi'], ['kopiejka']],
        'CAD' => [['Kanada dolar?'], ['sent']],
        'CHF' => [['?sveç frank?'], ['rapp']],
        'CHF' => [['Çin yuan?'], ['fen']],
        'CYP' => [['K?br?s pound\'u'], ['sent']],
        'CZK' => [['Çek kronu'], ['halerz']],
        'DKK' => [['Danmarka kronu'], ['ore']],
        'EEK' => [['kroon'], ['senti']],
        'EUR' => [['Avro'], ['Avro-sent']],
        'GBP' => [['pound', 'pound'], ['pence', 'pence']],
        'HKD' => [['Hong Kong dolar?'], ['sent']],
        'HRK' => [['Hırvatistan kunas?'], ['lipa']],
        'HUF' => [['Macar forinti'], ['filler']],
        'ILS' => [['yeni sheqel', 'yeni sheqels'], ['agora', 'agorot']],
        'ISK' => [['Izlanda kronu'], ['aurar']],
        'JPY' => [['Japon yeni'], ['sen']],
        'LTL' => [['Litvanya litas?'], ['sent']],
        'LVL' => [['Letonya lat?'], ['sentim']],
        'MKD' => [['Makedonya dinar?'], ['deni']],
        'MTL' => [['Malta liras?'], ['centym']],
        'NOK' => [['Norveç kronu'], ['oere']],
        'PLN' => [['zloty', 'zlotys'], ['grosz']],
        'ROL' => [['Romanya leu'], ['bani']],
        'RUB' => [['Ruble'], ['kopiejka']],
        'SEK' => [['?sveç kronu'], ['oere']],
        'SIT' => [['Tolar'], ['stotinia']],
        'SKK' => [['Slovakya kronu'], []],
        'TRY' => [['Türk Liras?'], ['kuru?']],
        'UAH' => [['Ukrayna hryvnyas?'], ['kopiyka']],
        'USD' => [['ABD Dolar?'], ['sent']],
        'YUM' => [['dinar'], ['para']],
        'ZAR' => [['Güney Afrika rand?'], ['sent']],
    ];

    /**
     * The default currency name
     * @var string
     * @access public
     */
    var $def_currency = 'TRY'; // Türk Liras?

    /**
     * Converts a number to its word representation
     * in italiano.
     *
     * @param  integer $num An integer between -infinity and infinity inclusive :)
     *                        that should be converted to a words representation
     * @param  integer $power The power of ten for the rest of the number to the right.
     *                        For example toWords(12,3) should give "doce mil".
     *                        Optional, defaults to 0.
     * @return string  The corresponding word representation
     *
     * @access protected
     * @author Filippo Beltramini
     * @since  Numbers_Words 0.16.3
     */
    function _toWords($num, $power = 0)
    {
        // The return string;
        $ret = '';

        // add a the word for the minus sign if necessary
        if (substr($num, 0, 1) == '-') {
            $ret = $this->_sep . $this->_minus;
            $num = substr($num, 1);
        }

        // strip excessive zero signs
        $num = preg_replace('/^0+/', '', $num);

        if (strlen($num) > 6) {
            $current_power = 6;
            // check for highest power
            if (isset($this->_exponent[$power])) {
                // convert the number above the first 6 digits
                // with it's corresponding $power.
                $snum = substr($num, 0, -6);
                $snum = preg_replace('/^0+/', '', $snum);
                if ($snum !== '') {
                    $ret .= $this->_toWords($snum, $power + 6);
                }
            }
            $num = substr($num, -6);
            if ($num == 0) {
                return $ret;
            }
        } elseif ($num == 0 || $num == '') {
            return (' ' . $this->_digits[0] . ' ');
            $current_power = strlen($num);
        } else {
            $current_power = strlen($num);
        }

        // See if we need "thousands"
        $thousands = floor($num / 1000);
        if ($thousands == 1) {
            $ret .= $this->_sep . 'bin' . $this->_sep;
        } elseif ($thousands > 1) {
            $ret .= $this->_toWords($thousands, 3) . $this->_sep;//. 'mil' . $this->_sep;
        }

        // values for digits, tens and hundreds
        $h = floor(($num / 100) % 10);
        $t = floor(($num / 10) % 10);
        $d = floor($num % 10);

        if ($h) {
            $ret .= $this->_sep . $this->_digits[$h] . $this->_sep . 'yüz';

            // in English only - add ' and' for [1-9]01..[1-9]99
            // (also for 1001..1099, 10001..10099 but it is harder)
            // for now it is switched off, maybe some language purists
            // can force me to enable it, or to remove it completely
            // if (($t + $d) > 0)
            //   $ret .= $this->_sep . 'and';
        }

        // decine: venti trenta, etc...
        switch ($t) {
            case 9:
                $ret .= $this->_sep . 'doksan';
                break;

            case 8:
                $ret .= $this->_sep . 'seksen';
                break;

            case 7:
                $ret .= $this->_sep . 'yetmiş';
                break;

            case 6:
                $ret .= $this->_sep . 'altmış';
                break;

            case 5:
                $ret .= $this->_sep . 'elli';
                break;

            case 4:
                $ret .= $this->_sep . 'kırk';
                break;

            case 3:
                $ret .= $this->_sep . 'otuz';
                break;

            case 2:
                $ret .= $this->_sep . 'yirmi';
                break;

            case 2:
                $ret .= $this->_sep . 'on';
                break;

                break;
        }

        if ($t > 1 && $d > 0) {
            $ret .= $this->_sep . $this->_digits[$d];
        }

        if ($power > 0) {
            if (isset($this->_exponent[$power])) {
                $lev = $this->_exponent[$power];
            }

            if (!isset($lev) || !is_array($lev)) {
                return null;
            }

            // if it's only one use the singular suffix
            if (($d == 1) and ($t == 0) and ($h == 0)) {
                $suffix = $lev[0];
            } else {
                $suffix = $lev[1];
            }
            if ($num != 0) {
                $ret .= $this->_sep . $suffix;
            }
        }

        return $ret;
    }
    // }}}

    // {{{ toCurrencyWords()

    /**
     * Converts a currency value to its word representation
     * (with monetary units) in English language
     *
     * @param  integer $int_curr An international currency symbol
     *                 as defined by the ISO 4217 standard (three characters)
     * @param  integer $decimal A money total amount without fraction part (e.g. amount of dollars)
     * @param  integer $fraction Fractional part of the money amount (e.g. amount of cents)
     *                 Optional. Defaults to false.
     * @param  integer $convert_fraction Convert fraction to words (left as numeric if set to false).
     *                 Optional. Defaults to true.
     *
     * @return string  The corresponding word representation for the currency
     *
     * @access public
     * @author Piotr Klaban <makler@man.torun.pl>
     * @since  Numbers_Words 0.4
     */
    function toCurrencyWords($int_curr, $decimal, $fraction = false, $convert_fraction = true)
    {
        $int_curr = strtoupper($int_curr);
        if (!isset($this->_currency_names[$int_curr])) {
            $int_curr = $this->def_currency;
        }
        $curr_names = $this->_currency_names[$int_curr];
        $ret = trim($this->_toWords($decimal));
        $lev = ($decimal == 1) ? 0 : 1;
        if ($lev > 0) {
            if (count($curr_names[0]) > 1) {
                $ret .= $this->_sep . $curr_names[0][$lev];
            } else {
                $ret .= $this->_sep . $curr_names[0][0];
            }
        } else {
            $ret .= $this->_sep . $curr_names[0][0];
        }

        if ($fraction !== false) {
            if ($convert_fraction) {
                $ret .= $this->_sep . trim($this->_toWords($fraction));
            } else {
                $ret .= $this->_sep . $fraction;
            }
            $lev = ($fraction == 1) ? 0 : 1;
            if ($lev > 0) {
                if (count($curr_names[1]) > 1) {
                    $ret .= $this->_sep . $curr_names[1][$lev];
                } else {
                    $ret .= $this->_sep . $curr_names[1][0] . 's';
                }
            } else {
                $ret .= $this->_sep . $curr_names[1][0];
            }
        }

        return $ret;
    }
}