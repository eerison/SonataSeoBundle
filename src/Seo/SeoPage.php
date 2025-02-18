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

namespace Sonata\SeoBundle\Seo;

/**
 * @final since sonata-project/seo-bundle 2.14
 *
 * http://en.wikipedia.org/wiki/Meta_element.
 */
class SeoPage implements SeoPageInterface
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $metas;

    /**
     * @var array<string, string>
     */
    protected $htmlAttributes;

    /**
     * @var string
     */
    protected $linkCanonical;

    /**
     * @var string
     */
    protected $separator;

    /**
     * @var array<string, string>
     */
    protected $headAttributes;

    /**
     * @var array<string, string>
     */
    protected $langAlternates;

    /**
     * @var array<string, string>
     */
    protected $oembedLinks;

    /**
     * @var string
     */
    private $originalTitle;

    /**
     * @var array<string, mixed>
     */
    private $breadcrumb = [];

    /**
     * @param string $title
     */
    public function __construct($title = '')
    {
        $this->title = $title;
        $this->metas = [
            'http-equiv' => [],
            'name' => [],
            'schema' => [],
            'charset' => [],
            'property' => [],
        ];

        // NEXT_MAJOR: Use property initialization
        $this->htmlAttributes = [];
        $this->headAttributes = [];
        $this->linkCanonical = '';
        $this->separator = ' ';
        $this->langAlternates = [];
        $this->oembedLinks = [];
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->originalTitle = $title;

        return $this;
    }

    public function addTitle($title)
    {
        @trigger_error(
            'The '.__METHOD__.' method is deprecated since 2.14, to be removed in 3.0. '.
            'Use '.__NAMESPACE__.'::addTitlePrefix() or '.__NAMESPACE__.'::addTitleSuffix .instead.',
            \E_USER_DEPRECATED
        );

        $this->title = $title.$this->separator.$this->title;

        return $this;
    }

    public function addTitlePrefix(string $prefix): self
    {
        $this->title = $prefix.$this->separator.$this->title;

        return $this;
    }

    public function addTitleSuffix(string $suffix): self
    {
        $this->title .= $this->separator.$suffix;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getOriginalTitle(): string
    {
        return $this->originalTitle;
    }

    public function getMetas()
    {
        return $this->metas;
    }

    public function addMeta($type, $name, /* string */ $value, array $extras = [])
    {
        if (!\is_string($value)) {
            @trigger_error(sprintf(
                'Passing meta content of type %s in %s is deprecated since version 2.8 and will be unsupported in version 3. Please cast the value to a string first.',
                \gettype($value),
                __METHOD__
            ), \E_USER_DEPRECATED);
        }

        if (!isset($this->metas[$type])) {
            $this->metas[$type] = [];
        }

        $this->metas[$type][$name] = [$value, $extras];

        return $this;
    }

    public function hasMeta($type, $name)
    {
        return isset($this->metas[$type][$name]);
    }

    public function removeMeta($type, $name)
    {
        unset($this->metas[$type][$name]);

        return $this;
    }

    public function setMetas(array $metas)
    {
        $this->metas = [];

        foreach ($metas as $type => $metaItem) {
            if (!\is_array($metaItem)) {
                throw new \RuntimeException('$metas must be an array');
            }

            foreach ($metaItem as $name => $meta) {
                [$content, $extras] = $this->normalize($meta);

                $this->addMeta($type, $name, $content, $extras);
            }
        }

        return $this;
    }

    public function setHtmlAttributes(array $attributes)
    {
        $this->htmlAttributes = $attributes;

        return $this;
    }

    public function addHtmlAttributes($name, $value)
    {
        $this->htmlAttributes[$name] = $value;

        return $this;
    }

    public function removeHtmlAttributes($name)
    {
        unset($this->htmlAttributes[$name]);

        return $this;
    }

    public function getHtmlAttributes()
    {
        return $this->htmlAttributes;
    }

    public function hasHtmlAttribute($name)
    {
        return isset($this->htmlAttributes[$name]);
    }

    public function setHeadAttributes(array $attributes)
    {
        $this->headAttributes = $attributes;

        return $this;
    }

    public function addHeadAttribute($name, $value)
    {
        $this->headAttributes[$name] = $value;

        return $this;
    }

    public function removeHeadAttribute($name)
    {
        unset($this->headAttributes[$name]);

        return $this;
    }

    public function getHeadAttributes()
    {
        return $this->headAttributes;
    }

    public function hasHeadAttribute($name)
    {
        return isset($this->headAttributes[$name]);
    }

    public function setLinkCanonical($link)
    {
        $this->linkCanonical = $link;

        return $this;
    }

    public function getLinkCanonical()
    {
        return $this->linkCanonical;
    }

    public function removeLinkCanonical()
    {
        $this->linkCanonical = '';
    }

    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    public function setLangAlternates(array $langAlternates)
    {
        $this->langAlternates = $langAlternates;

        return $this;
    }

    public function addLangAlternate($href, $hrefLang)
    {
        $this->langAlternates[$href] = $hrefLang;

        return $this;
    }

    public function removeLangAlternate($href)
    {
        unset($this->langAlternates[$href]);

        return $this;
    }

    public function hasLangAlternate($href)
    {
        return isset($this->langAlternates[$href]);
    }

    public function getLangAlternates()
    {
        return $this->langAlternates;
    }

    public function addOEmbedLink($title, $link)
    {
        $this->oembedLinks[$title] = $link;

        return $this;
    }

    public function getOEmbedLinks()
    {
        return $this->oembedLinks;
    }

    public function setBreadcrumb(string $context, array $options = []): self
    {
        $this->breadcrumb = array_merge_recursive([
            'menu_template' => '@SonataSeo/Block/breadcrumb.html.twig',
            'context' => $context,
        ], $options);

        return $this;
    }

    public function getBreadcrumbOptions(): array
    {
        return $this->breadcrumb;
    }

    /**
     * @param string|array $meta
     */
    private function normalize($meta): array
    {
        if (\is_string($meta)) {
            return [$meta, []];
        }

        return $meta;
    }
}
