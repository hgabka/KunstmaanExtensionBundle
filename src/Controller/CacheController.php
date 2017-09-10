<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CacheController extends Controller
{
    /**
     * @Route("/index", name="KunstmaanAdminBundle_cache")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/purge", name="KunstmaanAdminBundle_cache_purge")
     */
    public function purgeAction()
    {
        try {
            $this->get('hgabka_kunstmaan_extension.cache_manager')->forcePurgeAll();
            $this->addFlash('success', $this->get('translator')->trans('kuma_admin.cache.flash.cache_purged'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->get('translator')->trans(
                'kuma_admin.cache.flash.cache_not_purged.%exception%',
                ['%exception%' => $e->getMessage()]
            ));
        }

        return $this->redirectToRoute('KunstmaanAdminBundle_cache');
    }
}
