<?php
if (!isset($gCms)) exit;

// Typical Database Initialization
$db =& $gCms->GetDb();

// mysql-specific, but ignored by other database
$taboptarray = array('mysql' => 'TYPE=MyISAM');
$dict = NewDataDictionary($db);

	/*---------------------------------------------------------
	   Upgrade()
	   If your module version number does not match the version
	   number of the installed module, the CMS Admin will give
	   you a chance to upgrade the module. This is the function
	   that actually handles the upgrade.
	   Ideally, this function should handle upgrades incrementally,
	   so you could upgrade from version 0.0.1 to 10.5.7 with
	   a single call. For a great example of this, see the News
	   module in the standard CMS install.
	  ---------------------------------------------------------*/
		$current_version = $oldversion;
		switch($current_version)
		{
			case "0.0.1":
				$current_version = '0.1.1';
			case "0.0.2":
				$current_version = '0.1.1';
			case "0.0.3":
				$current_version = '0.1.1';
			case "0.0.4":
				$current_version = '0.1.1';
			case "0.0.5":
				$current_version = '0.1.1';
			case "0.0.6":
				$current_version = '0.1.1';
			case "0.0.7":
				$current_version = '0.1.1';
			case "0.0.8":
				$current_version = '0.1.1';
			case "0.0.9":
				$current_version = '0.1.1';
			case "0.1.0":
				$current_version = '0.1.1';
			case "0.1.1":
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
		        $current_version = '0.1.2';
			

		}
		
		// put mention into the admin log
		$this->Audit( 0, $this->Lang('friendlyname'), $this->Lang('upgraded',$this->GetVersion()));

?>