<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Imagine\Filter;

use Imagine\Filter\FilterInterface;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class Fit implements FilterInterface
{
    const MODE_OUTBOUND = 'outbound';
    const MODE_INSET = 'inset';

    /** @var null|int */
    private $width;

    /** @var null|int */
    private $height;

    /** @var string */
    private $mode;

    /**
     * Fit constructor.
     *
     * @param null|int $width
     * @param null|int $height
     * @param string   $mode
     */
    public function __construct($width, $height, $mode = self::MODE_OUTBOUND)
    {
        $this->width = $width;
        $this->height = $height;
        $this->mode = $mode;
    }

    public function apply(ImageInterface $image)
    {
        $origWidth = $image->getSize()->getWidth();
        $origHeight = $image->getSize()->getHeight();

        $imgWidth = $this->width;
        $imgHeight = $this->height;
        $mode = $this->mode;

        if ($origWidth === $imgWidth && $imgHeight === $origHeight) {
            return $image;
        }

        if (null === $imgWidth || null === $imgHeight) {
            if (null === $imgHeight) {
                $imgHeight = (int) ceil(($imgWidth / $origWidth) * $origHeight);
            } elseif (null === $imgWidth) {
                $imgWidth = (int) ceil(($imgHeight / $origHeight) * $origWidth);
            }
        }

        if (self::MODE_INSET !== $mode) {
            if ($imgWidth / $origWidth < $imgHeight / $origHeight) {
                $newWidth = $imgWidth;
                $newHeight = ceil($origHeight * ($newWidth / $origWidth));
            } else {
                $newHeight = $imgHeight;
                $newWidth = ceil($origWidth * ($newHeight / $origHeight));
            }
        } else {
            if ($imgWidth / $origWidth > $imgHeight / $origHeight) {
                $newWidth = $imgWidth;
                $newHeight = ceil($origHeight * ($newWidth / $origWidth));
            } else {
                $newHeight = $imgHeight;
                $newWidth = ceil($origWidth * ($newHeight / $origHeight));
            }
        }

        return $image->resize(new Box($newWidth, $newHeight));
    }
}
