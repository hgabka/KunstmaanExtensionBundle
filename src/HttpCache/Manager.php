<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\HttpCache;

use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\Filesystem\Filesystem;

class Manager
{
    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * For the future...
     *
     * @var CacheManager
     */
    protected $fosHttpCacheManager;

    /**
     * Manager constructor.
     *
     * @param string       $cacheDir
     * @param Filesystem   $filesystem
     * @param CacheManager $fosHttpCacheManager
     */
    public function __construct($cacheDir, Filesystem $filesystem, CacheManager $fosHttpCacheManager = null)
    {
        $this->cacheDir = $cacheDir;
        $this->filesystem = $filesystem;
        $this->fosHttpCacheManager = $fosHttpCacheManager;
    }

    public function forcePurgeAll()
    {
        $cacheDir = $this->cacheDir.DIRECTORY_SEPARATOR.'http_cache';
        if ($this->filesystem->exists($cacheDir) && is_dir($cacheDir)) {
            $this->filesystem->remove(new \FilesystemIterator($cacheDir));
        }
        if (defined('apc_clear_cache')) {
            apc_clear_cache();
            apc_clear_cache('user');
            apc_clear_cache('opcode');
        }
    }
}
