<?php
/**
 * Date: 24/03/14
 * Time: 17:38
 * Author: Jean-Christophe Cuvelier <jcc@morris-chapman.com>
 * @since 0.9.9
 */
class I18nPage {
    /**
     * @param $content_id
     * @return bool|ContentBase
     */
    private static function getContent($content_id)
    {
        /** @var cms_content_tree $manager */
        $manager = cmsms()->GetHierarchyManager();
        /** @var cms_content_tree $node */
        $node = $manager->find_by_tag('id', $content_id);
        if (!isset($node) || $node === false) {
            return false;
        }

        return $node->getContent();
    }

    /**
     * @param $content_id
     * @return bool|ContentBase
     */
    public static function getRootPageFromContentId($content_id)
    {
        $content = self::getContent($content_id);
        if (!$content) {
            return false;
        }
        return self::getRootPage($content);
    }

    /**
     * @param ContentBase $content
     * @return bool|ContentBase
     */
    public static function getRootPage(ContentBase $content)
    {
        if ($content->ParentId() != '-1') {
            $path = explode('.', $content->IdHierarchy());
            if (count($path) > 0) {
                reset($path);
                return self::getContent(current($path));
            } else {
                return false;
            }
        } else {
            return $content;
        }
    }

    public static function getCulturesFromRoots()
    {
        $cultures = array();
        $roots = self::getRootPages();

        foreach($roots as $node)
        {
            /** @var $node cms_content_tree */
            if($culture = I18nCulture::checkCulture($node->get_tag('alias')))
            {
                $cultures[$culture] = $culture;
            }
        }

        return $cultures;
    }

    public static function getLanguagesFromRoots()
    {
        $cultures = self::getCulturesFromRoots();
        $languages = array();
        foreach($cultures as $culture)
        {
            $languages[$culture] = I18nCulture::getLanguage($culture);
        }
        return $languages;
    }

    private static function getRootPages()
    {
        /** @var cms_content_tree $manager */
        $manager = CmsApp::get_instance()->GetHierarchyManager();
        $roots = $manager->getChildren();
        return $roots;
    }

    public static function getCulture($content_id)
    {
        $root = self::getRootPageFromContentId($content_id);

        if($root)
        {
            $culture = $root->Alias();
        }
        else
        {
            $culture = I18nCulture::getDefault('en');
        }

        return I18nCulture::checkCulture($culture);
    }

    public static function getLanguage($content_id)
    {
        return I18nCulture::getLanguage(self::getContent($content_id));
    }
}