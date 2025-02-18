<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\SeoBundle\Block\Breadcrumb;

use Sonata\BlockBundle\Block\BlockContextInterface;

/**
 * @final since sonata-project/seo-bundle 2.14
 *
 * BlockService for homepage breadcrumb.
 *
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 */
class HomepageBreadcrumbBlockService extends BaseBreadcrumbMenuBlockService
{
    public function getName()
    {
        return 'Breadcrumb (Homepage)';
    }

    protected function getMenu(BlockContextInterface $blockContext)
    {
        $menu = $this->getRootMenu($blockContext);

        return $menu;
    }
}
