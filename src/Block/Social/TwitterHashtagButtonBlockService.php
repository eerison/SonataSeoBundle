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

namespace Sonata\SeoBundle\Block\Social;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Type\ImmutableArrayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Twitter hashtag button integration.
 *
 * @see https://about.twitter.com/resources/buttons#hashtag
 *
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 *
 * @deprecated since sonata-project/seo-bundle 2.14, to be removed in 3.0.
 */
class TwitterHashtagButtonBlockService extends BaseTwitterButtonBlockService
{
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => '@SonataSeo/Block/block_twitter_hashtag_button.html.twig',
            'url' => null,
            'hashtag' => null,
            'text' => null,
            'recommend' => null,
            'large_button' => false,
            'opt_out' => false,
            'language' => $this->languageList['en'],
        ]);
    }

    public function buildEditForm(FormMapper $form, BlockInterface $block)
    {
        $form->add('settings', ImmutableArrayType::class, [
            'keys' => [
                ['hashtag', TextType::class, [
                    'required' => true,
                    'label' => 'form.label_hashtag',
                ]],
                ['text', TextType::class, [
                    'required' => false,
                    'label' => 'form.label_text',
                ]],
                ['recommend', TextType::class, [
                    'required' => false,
                    'label' => 'form.label_recommend',
                ]],
                ['url', UrlType::class, [
                    'required' => false,
                    'label' => 'form.label_url',
                ]],
                ['large_button', CheckboxType::class, [
                    'required' => false,
                    'label' => 'form.label_large_button',
                ]],
                ['opt_out', CheckboxType::class, [
                    'required' => false,
                    'label' => 'form.label_opt_out',
                ]],
                ['language', ChoiceType::class, [
                    'required' => true,
                    'choices' => $this->languageList,
                    'label' => 'form.label_language',
                ]],
            ],
            'translation_domain' => 'SonataSeoBundle',
        ]);
    }

    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (null !== $code ? $code : $this->getName()), false, 'SonataSeoBundle', [
            'class' => 'fa fa-twitter',
        ]);
    }
}
