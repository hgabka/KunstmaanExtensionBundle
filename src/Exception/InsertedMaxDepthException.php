<?php
/**
 * Created by IntelliJ IDEA.
 * User: gabe
 * Date: 2016.04.11.
 * Time: 10:38
 */

namespace Hgabka\KunstmaanExtensionBundle\Exception;

/**
 * Class InsertedMaxDepthException
 *
 * There is a limit of page insertion to prepend the infinity insert cycle. You can change the limit with the
 * `webtown_kunstmaan_extension.max_page_insertion_depth` parameter.
 *
 * @package Hgabka\KunstmaanExtensionBundle\Exception
 */
class InsertedMaxDepthException extends \Exception
{
}
