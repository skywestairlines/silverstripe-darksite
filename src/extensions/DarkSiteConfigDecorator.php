<?php
class DarkSiteConfigDecorator extends DataExtension {
	/**
	* Define extra database field for which holding page to show
	* 
	* @return Array
	*/
	/*function extraStatics() {
		return array(
			'has_one' => array(
				'ShowDarkPage' => 'SiteTree',
			)
		);
	}*/

	private static $db = array(
			//'ShowDarkPage' => 'SiteTree'
		);
	
	/**
	* Add a field to the config for choosing a holding page to show
	* 
	* @param FieldSet $fields
	* @return Void
	*/
	// public function updateCMSFields(FieldList &$fields) {
	// 	$DarkPages = DarkSiteHoldingPage::get()->filter('Status','Published');
	// 	// if there is a holding page in the siteTree then show the dropdown on the root page
	// 	if ($DarkPages) {
	// 		$treedropdownfield = new DropdownField(
	// 			"ShowDarkPageID", 
	// 			'Dark Site Page to display when Dark Site is Active',
	// 			$DarkPages->toDropDownMap()
	// 		);
	// 		$treedropdownfield->setHasEmptyDefault(true);
	// 		$fields->addFieldToTab("Root.Main", $treedropdownfield);
	// 	}
	// }
	
	// public function updateCMSActions() {
		
	// 	if($ds = DarkSite::get()->filter('Active', '1')->limit(1)) {
	// 		$message = '<span style="font-size:12pt;">The dark site is currently Active.</span>';
	// 		Session::set("FormInfo.Form_EditForm.formError.message", $message);
	// 		Session::set("FormInfo.Form_EditForm.formError.type", 'bad');
	// 	}
	// }
}