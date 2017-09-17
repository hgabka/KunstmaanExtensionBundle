<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Imagine\Filter\Loader;

use Hgabka\KunstmaanExtensionBundle\Imagine\Filter\Fit;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Liip\ImagineBundle\Imagine\Filter\Loader\LoaderInterface;

class FitFilterLoader implements LoaderInterface
{
    /** @var ImagineInterface */
    protected $imagine;

    /**
     * FitFilterLoader constructor.
     *
     * @param ImagineInterface $imagine
     */
    public function __construct(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    /**
     * @param ImageInterface $image
     * @param array          $options
     *
     * @return mixed
     */
    public function load(ImageInterface $image, array $options = [])
    {
        list($width, $height) = $options['size'];
        if (!empty($options['inside_size'])) {
            list($insideWidth, $insideHeight) = $options['inside_size'];
        }

        $background = new Color(
            isset($options['background_color']) ? $options['background_color'] : '#fff',
            isset($options['transparency']) ? $options['transparency'] : null
        );

        $fitFilter = new Fit($insideWidth ?? $width, $insideHeight ?? $height);
        $image = $fitFilter->apply($image);

        $newWidth = $image->getSize()->getWidth();
        $newHeight = $image->getSize()->getHeight();

        if ($newWidth === $width && $newHeight === $height) {
            return $image;
        }
        $position = $options['position'] ?? 'center';

        if (false !== strstr($position, 'top')) {
            $top = 0;
        } elseif (false !== strstr($position, 'bottom')) {
            $top = $height - $newHeight;
        } else {
            $top = (int) ceil(($height - $newHeight) / 2);
        }

        if (false !== strstr($position, 'left')) {
            $left = 0;
        } elseif (false !== strstr($position, 'right')) {
            $left = $width - $newWidth;
        } else {
            $left = (int) ceil(($width - $newWidth) / 2);
        }

        $newSize = new Box($width, $height);

        $canvas = $this->imagine->create($newSize, $background);

        return $canvas->paste($image, new Point($left, $top));
    }
}
