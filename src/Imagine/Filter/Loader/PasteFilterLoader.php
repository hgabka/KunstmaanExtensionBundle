<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Imagine\Filter\Loader;

use Hgabka\KunstmaanExtensionBundle\Imagine\Filter\Paste;
use Imagine\Image\Color;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Liip\ImagineBundle\Imagine\Filter\Loader\LoaderInterface;

class PasteFilterLoader implements LoaderInterface
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

        $filter = new Paste(
            $this->imagine,
            $width,
            $height,
            $options['position'] ?? 'center',
            $options['color'] ?? '#fff',
            $options['transparency'] ?? 0
        );

        return $filter->apply($image);
    }
}
