<?php

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\View\Requirements;

class DarkAdmin extends ModelAdmin {
	public function init() {
		parent::init();
		Requirements::javascript('skywest/ss-darksite: darksite/javascript/darkAdmin.js');
	}

	private static $managed_models = array(
		'DarkSite',// => array('record_controller' => 'DarkAdmin_RecordController'),
		'Partner'				// ! un-comment to edit partners list
		//'DarkSite_Password'		// ! this line should only be un-commented out when you need to set or change the password!!!!!!
	);

	private static $url_segment = 'darkAdmin';
	private static $menu_title = 'Dark Site';
	private static $set_page_length = 100;

	var $showImportForm = false;

	function getEditForm($id = null, $fields = null){
		 $form = parent::getEditForm($id , $fields);
		 $listfield = $form->Fields()->fieldByName($this->modelClass);
		 if($gridField = $listfield->getConfig()->getComponentByType('GridFieldDetailForm')) {
            $gridField->setItemRequestClass('DarkAdminPublishFieldDetailForm_ItemRequest');
        }
        return $form;


	}
}
