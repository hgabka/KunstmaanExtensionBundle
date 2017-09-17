<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Helper\Number;

class NumberToText
{
    public function convert($num, $lang)
    {
        $minus = '-' === $num[0];

        if ($minus) {
            $num = substr($num, 1);
        }

        $nw = new Words();

        return $nw->toWords($num, $lang);
    }

    public function convertEuro($num, $lang)
    {
        $minus = '-' === $num[0];

        if ($minus) {
            $num = substr($num, 1);
        }

        $num = number_format($num, 2, '.', '');
        $nw = new Words();
        if (false === strpos($num, '.')) {
            return $nw->toWords($num, $lang).' Euros';
        }

        $parts = explode('.', $num);

        return $nw->toWords($parts[0], $lang).' euros '.(!empty($parts[1]) && '00' !== $parts[1] ? $nw->toWords($parts[1], $lang).' cents' : '');
    }
}
