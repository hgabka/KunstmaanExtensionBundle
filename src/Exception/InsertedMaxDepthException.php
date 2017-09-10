<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Exception;

/**
 * Class InsertedMaxDepthException.
 *
 * There is a limit of page insertion to prepend the infinity insert cycle. You can change the limit with the
 * `webtown_kunstmaan_extension.max_page_insertion_depth` parameter.
 */
class InsertedMaxDepthException extends \Exception
{
}
