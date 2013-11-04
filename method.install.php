<?php
if (!isset($gCms)) exit;

	/*---------------------------------------------------------
	   Install()
	   When your module is installed, you may need to do some
	   setup. Typical things that happen here are the creation
	   and prepopulation of database tables, database sequences,
	   permissions, preferences, etc.
	   	   
	   For information on the creation of database tables,
	   check out the ADODB Data Dictionary page at
	   http://phplens.com/lens/adodb/docs-datadict.htm
	   
	   This function can return a string in case of any error,
	   and CMS will not consider the module installed.
	   Successful installs should return FALSE or nothing at all.
	  ---------------------------------------------------------*/
		
		// Typical Database Initialization
		$db =& $gCms->GetDb();
		
		// mysql-specific, but ignored by other database
		$taboptarray = array('mysql' => 'TYPE=MyISAM');
		$dict = NewDataDictionary($db);
		
        // table schema description
        $flds = "
			id I KEY,
			culture C(10),
			source XL,
			target XL
			";

		// create it. This should do error checking, but I'm a lazy sod.
		$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_i18n_translations",
				$flds, $taboptarray);
		$dict->ExecuteSQLArray($sqlarray);
		
        // table schema description
        $flds = "
			id I KEY AUTOINCREMENT,
			culture C(10),
			source XL,
			source_alias C(255),
			target XL,
			target_alias C(255)
			";

		// create it. This should do error checking, but I'm a lazy sod.
		$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_i18n_links",
				$flds, $taboptarray);
		$dict->ExecuteSQLArray($sqlarray);
		
		# Create the table indexes
		$idxflds = 'source, source_alias';
		$sqlarray = $dict->CreateIndexSQL('linkindex', cms_db_prefix()."module_i18n_links", $idxflds);
		$dict->ExecuteSQLArray($sqlarray);
		
		
		// permissions
		$this->CreatePermission('Manage I18n','Manage I18n');

		// preferences
		
		$this->SetPreference('harvest',false); // Should we collect all the translations strings ?
		$this->SetPreference('default_culture', 'en');

		// put mention into the admin log
		$this->Audit( 0, $this->Lang('friendlyname'), $this->Lang('installed',$this->GetVersion()));
		
?>