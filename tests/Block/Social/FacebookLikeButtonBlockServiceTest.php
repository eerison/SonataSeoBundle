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

namespace Sonata\SeoBundle\Tests\Block\Social;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Test\BlockServiceTestCase;
use Sonata\BlockBundle\Util\OptionsResolver;
use Sonata\SeoBundle\Block\Social\FacebookLikeButtonBlockService;

final class FacebookLikeButtonBlockServiceTest extends BlockServiceTestCase
{
    public function testService()
    {
        $service = new FacebookLikeButtonBlockService(
            'sonata.block.service.facebook.like_button',
            $this->templating
        );

        $block = new Block();
        $block->setType('core.text');
        $block->setSettings([
            'url' => 'url_setting',
            'width' => 'width_setting',
            'show_faces' => 'show_faces_setting',
            'share' => 'share_setting',
            'layout' => 'layout_setting',
            'colorscheme' => 'colorscheme_setting',
            'action' => 'action_setting',
        ]);

        $optionResolver = new OptionsResolver();
        $service->setDefaultSettings($optionResolver);

        $blockContext = new BlockContext($block, $optionResolver->resolve($block->getSettings()));

        $formMapper = $this->createMock(FormMapper::class, [], [], '', false);
        $formMapper->expects(static::exactly(2))->method('add');

        $service->buildCreateForm($formMapper, $block);
        $service->buildEditForm($formMapper, $block);

        $service->execute($blockContext);

        static::assertSame('url_setting', $this->templating->parameters['settings']['url']);
        static::assertSame('width_setting', $this->templating->parameters['settings']['width']);
        static::assertSame('show_faces_setting', $this->templating->parameters['settings']['show_faces']);
        static::assertSame('share_setting', $this->templating->parameters['settings']['share']);
        static::assertSame('layout_setting', $this->templating->parameters['settings']['layout']);
        static::assertSame('colorscheme_setting', $this->templating->parameters['settings']['colorscheme']);
        static::assertSame('action_setting', $this->templating->parameters['settings']['action']);
    }
}
