<?php

namespace Hgabka\KunstmaanExtensionBundle\Imagine\Filter\Loader;

use Hgabka\KunstmaanExtensionBundle\Imagine\Filter\Fill;
use Imagine\Image\ImageInterface;
use Liip\ImagineBundle\Imagine\Filter\Loader\LoaderInterface;

class FillFilterLoader implements LoaderInterface
{
    /**
     * @param ImageInterface $image
     * @param array          $options
     *
     * @return ImageInterface
     */
    public function load(ImageInterface $image, array $options = [])
    {
        list($width, $height) = $options['size'];

        $filter = new Fill($width, $height, $options['position'] ?? 'center');

        return $filter->apply($image);
    }
}
