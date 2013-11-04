<?php

/*
 * Translation link class
 * Copyrights: Jean-Christophe Cuvelier - Morris & Chapman Belgium
 */

class I18nLink extends I18nTranslation
{
    var $source_alias;
    var $target_alias;

    const DB_NAME = 'module_i18n_links';

    public function __toString()
    {
        return $this->getSource();
    }

    public function setSourceAlias($value)
    {
        $this->source_alias = $value;
    }

    public function getSourceAlias()
    {
        return $this->source_alias;
    }

    public function setTargetAlias($value)
    {
        $this->target_alias = $value;
    }

    public function getTargetAlias()
    {
        return $this->target_alias;
    }

    public static function getTranslatedLinks($page_id)
    {
        $items = self::doSelect(array('where' => array('target_alias' => $page_id)));

        $links = array();

        foreach($items as $item)
        {
            $links[$item->getCulture()] = $item->getTargetAlias();
        }

        return $links;
    }

    public static function getMainPageId($page_id, $language)
    {
        $item = self::doSelectOne(array('where' => array('target_alias' => $page_id, 'culture' => $language)));

        if($item)
        {
            return $item->getSourceAlias();
        }

        return null;

    }

    protected function checkForDouble()
    {
        $item = self::doSelectOne(array('where' => array('culture' => $this->getCulture(), 'source_alias' => $this->getSourceAlias())));
        if (!empty($item)) // && $item->getId() != $this->id)
        {
            $this->id = $item->getId();
            return true;
        } else {
            return false;
        }
    }

    public static function getKnownCultures()
    {
        $db = cms_utils::get_db();
        $query = 'SELECT * FROM ' . cms_db_prefix() . self::DB_NAME . ' GROUP BY culture';
        $dbresult = $db->Execute($query);
        $cultures = array();

        if ($dbresult && $dbresult->RecordCount() > 0) {
            while ($dbresult && $row = $dbresult->FetchRow()) {
                $cultures[] = $row['culture'];
            }
        }

        return array_unique($cultures);
    }

    public static function getAliasForSure($alias)
    {
        if (!is_numeric($alias)) {
            return $alias;
        }
        global $gCms;
        $manager =& $gCms->GetHierarchyManager();
        $node =& $manager->sureGetNodeById($alias);
        if (!isset($node)) return false;
        $content =& $node->GetContent();
        if ($content !== FALSE && is_object($content)) {
            return $content->Alias();
        }
    }

    protected function update()
    {
        $db = cms_utils::get_db();

        $query = 'UPDATE  ' . cms_db_prefix() . self::DB_NAME . '

	      SET ';

        $query .= ' culture = ?, source = ?, target = ?, source_alias = ?, target_alias = ?';

        $query .= '

	      WHERE

	      id = ?  ';


        $result = $db->Execute($query,
            array(
                $this->getCulture(),
                $this->getSource(),
                $this->getTarget(),
                $this->getSourceAlias(),
                $this->getTargetAlias(),

                $this->getId()
            )
        );

        //return true;
    }

    protected function insert()
    {
        $db = cms_utils::get_db();

        $query = 'INSERT INTO ' . cms_db_prefix() . self::DB_NAME . '

	      SET ';

        $query .= ' culture = ?, source = ?, target = ?, source_alias = ?, target_alias = ?';


        $db->Execute($query,
            array(
                $this->getCulture(),
                $this->getSource(),
                $this->getTarget(),
                $this->getSourceAlias(),
                $this->getTargetAlias()
            )
        );

        return true;
    }


    /**
     * @param array $params
     * @return I18nLink|array
     */
    public static function doSelect($params = array())
    {
        $dbresult = self::executeQuery(self::DB_NAME, $params);
        $items = array();
        if ($dbresult && $dbresult->RecordCount() > 0) {
            while ($dbresult && $row = $dbresult->FetchRow()) {
                $item = new self();
                $item->PopulateFromDb($row);
                $items[] = $item;
            }
        }

        return $items;
    }

    public static function retrieveByPk($id)
    {
        return self::doSelectOne(array('where' => array('id' => $id)));
    }

    public static function doSelectOne($params = array())
    {
        $items = self::doSelect($params);
        if (!empty($items)) {
            //$items = array_values($items);
            return $items[0];
        } else {
            return null;
        }
    }

    public function PopulateFromDb($row)
    {
        $this->id = $row['id'];
        $this->culture = $row['culture'];
        $this->source = $row['source'];
        $this->source_alias = $row['source_alias'];
        $this->target = $row['target'];
        $this->target_alias = $row['target_alias'];
    }
}