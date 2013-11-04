<?php

	/*
	 * Translation class
	 * Copyrights: Jean-Christophe Cuvelier - Morris & Chapman Belgium
	 */
	
	class I18nTranslation
	{
		var $id;
		var $culture;
		var $source;
		var $target;
		
		const DB_NAME  = 'module_i18n_translations';
		
		public function __toString()
		{
			return $this->getSource();
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function setId($value)
		{
			$this->id = $value;
		}
	
		public function getCulture()
		{
			return $this->culture;
		}
		
		public function setCulture($value)
		{
			$this->culture = $value;
		}
		
		public function getSource()
		{
			return $this->source;
		}
		
		public function setSource($value)
		{
			$this->source = $value;
		}
		public function getTarget()
		{
			return $this->target;
		}
		
		public function setTarget($value)
		{
			$this->target = $value;
		}
	
		// SPECIFIC ACTIONS
		
		public static function getKnownCultures()
		{
	    $db = cms_utils::get_db();
	    $query = 'SELECT * FROM ' . cms_db_prefix() . self::DB_NAME . ' GROUP BY culture';
	    $dbresult = $db->Execute($query);
	    $cultures = array();
	
	    if ($dbresult && $dbresult->RecordCount() > 0)
	    {
	      while ($dbresult && $row = $dbresult->FetchRow())
	      {
					$cultures[] = $row['culture'];
	      }
	    }

	    return array_unique($cultures);
		}
	
	  // DB
	
	 	public function PopulateFromDb($row)
		{
		  $this->id = $row['id'];
		  $this->culture = $row['culture'];
		  $this->source = $row['source'];
		  $this->target = $row['target'];
		}
	
		public function save()
	  {
			//$this->checkForDouble();
	    // Upgrade or Insert ?
	    if ($this->checkForDouble() || $this->id != null)
	    {
	      $this->update();
	    }
	    else
	    {     
	      $this->insert();
	    }   

	  }
	
	  protected function checkForDouble()
	  {
		    $item = self::doSelectOne(array('where' => array('culture' => $this->getCulture(), 'source' => $this->getSource())));
				if (!empty($item)) // && $item->getId() != $this->id)
				{
					$this->id = $item->getId();
					return true;
				}
				else
				{
					return false;
				}
		}

	  protected function update()
	  {

	    $db = cms_utils::get_db();
	
	    $query = 'UPDATE  ' . cms_db_prefix() .  self::DB_NAME . ' 

	      SET ';

	      $query .= ' culture = ?, source = ?, target = ?';

	      $query .= '

	      WHERE

	      id = ?  ';


	      $result = $db->Execute($query,
	          array(
	             $this->getCulture(), 
							 $this->getSource(),
							 $this->getTarget(),
	             $this->getId()
	          )
	        );        
	
	        //return true;
	  }

	  protected function insert()
	  {
	   $db = cms_utils::get_db();

	    $this->setId($db->GenID(cms_db_prefix() .  self::DB_NAME .'_seq' ));

	    $query = 'INSERT INTO ' . cms_db_prefix() .  self::DB_NAME .  ' 

	      SET  id = ?,  ';

      $query .= ' culture = ?, source = ?, target = ?';


	       $db->Execute($query,
	          array(
							 $this->getId(),
	             $this->getCulture(), 
							 $this->getSource(),
							 $this->getTarget()
	          )
	        );

	        return true;    
	  }
	
		

	  public static function retrieveByPk($id)
	  {
	    return self::doSelectOne(array('where' => array('id' => $id)));    
	  }

	  public static function doSelectOne($params = array())
	  {
	    $items = self::doSelect($params);
	    if (!empty($items))
	    {
				//$items = array_values($items);
	      return $items[0];
	    }
	    else 
	    {
	      return null;
	    }   
	  }

	  public static function doSelect($params = array())
	  {
		$dbresult = self::executeQuery(self::DB_NAME, $params);
	    $items = array();
	    if ($dbresult && $dbresult->RecordCount() > 0)
	    {
	      while ($dbresult && $row = $dbresult->FetchRow())
	      {
	        $item = new self();
	        $item->PopulateFromDb($row);
	        $items[] = $item;
	      }
	    }

	    return  $items;   
	  }
		
		public static function executeQuery($db_name, $params = array())
		{
			$db = cms_utils::get_db();

		    $query = 'SELECT * FROM ' . cms_db_prefix() . $db_name;

		    $values = array();

		    if (isset($params['where']))
		    {

		      $fields = array();
		      foreach ($params['where'] as $field => $value) 
		      {
		        $fields[] = $field . ' =  ?';
		        $values[] = $value;
		      }

		      $query .= ' WHERE ' . implode(' AND ', $fields);
		    }

            if (isset($params['where_or']))
            {

                $fields = array();
                foreach ($params['where_or'] as $field => $value)
                {
                    $fields[] = $field . ' =  ?';
                    $values[] = $value;
                }

                $query .= ' WHERE ' . implode(' OR ', $fields);
            }

            if(isset($params['order_by']))
		    {
		     $query .= ' ORDER BY ' . implode(', ' , $params['order_by']);
		    }
		
				if(isset($params['group_by']))
				{
					 $query .= ' GROUP BY ' . $params['group_by'];
				}
		    /*
				else
		    {
		      $query .= ' ORDER BY position';
		    }
				*/

		    return $db->Execute($query, $values);
		
		}
	
	  public function delete()
	  {   
	    $db = cms_utils::get_db();
	    $query = 'DELETE FROM '. cms_db_prefix() . self::DB_NAME;
	    $query .= ' WHERE id = ?';
	    $db->Execute($query, array($this->id));   
	  }
	
	}