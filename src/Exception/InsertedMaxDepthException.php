<?php

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
