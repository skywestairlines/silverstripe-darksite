<?php
class DarkAdmin extends ModelAdmin {
	public function init() {
		parent::init();
		Requirements::javascript('darksite/javascript/darkAdmin.js');
	}
	
	public static $managed_models = array(
		'DarkSite',// => array('record_controller' => 'DarkAdmin_RecordController'),
		'Partner'				// ! un-comment to edit partners list
		//'DarkSite_Password'		// ! this line should only be un-commented out when you need to set or change the password!!!!!!
	);
	
	static $url_segment = 'darkAdmin';
	static $menu_title = 'Dark Site';
	static $set_page_length = 100;
	
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

class DarkAdminPublishFieldDetailForm extends GridFieldDetailForm {

}


class DarkAdminPublishFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest {

	private static $allowed_actions = array( 
         'edit', 
         'view', 
         'darkActivate',
         'darkDeactivate',
         'duplicate',
         'ItemEditForm'
      ); 
	public function ItemEditForm(){

		$form = parent::ItemEditForm();
		$formActions = $form->Actions();
		if ($actions = $this->record->getCMSActions()) 
            foreach ($actions as $action) 
               $formActions->push($action); 
          
         return $form; 
      } 
      public function onBeforeInit() {
		DarkSiteHoldingPage::redirect_to_dark();
		/*$pattern	= '/^\/\d{4}/';
		$url = $_REQUEST['url'];
		if(preg_match($pattern, $url)) {
			// redirect to incident page of given flt num
			//DarkSiteHoldingPage::FltNum($url);
			Debug::show('redirect!');
		}
		//Debug::show($_REQUEST['url']);*/
	}



	public function duplicate($data, $form, $request) {
		// dup the object
		$clone = $this->record->duplicate();
		
		// change the title so we know its a copy
		$clone->Title = 'Copy of '. $this->record->Title;
		
		$clone->write();
		
		// set the view to the new dup
		$this->record = $clone;
		
		if(Director::is_ajax()) {
			Controller::curr()->getResponse()->setStatusCode(
                200,
                'Duplicated!'
            );
		} else {
			$this->redirectBack();
		}
	}
	
	public function darkActivate($data, $form, $request){
		
		$darksite = $this->record;
		//Debug::show($darksite);
		$dPass = "l7u)q_.6VDpU";
		$password = $data['activatePassword'];
		//$password = $d[_Password];
			if($password == $dPass) {
				$darksite->Active = 1;
				$darksite->write();
				if(Director::is_ajax()){
					Controller::curr()->getResponse()->setStatusCode(
                200,
                'Dark site set to Active'
            );
				} else{
					Director::redirectBack();
				}
			}else{
				if(Director::is_ajax()){
					Controller::curr()->getResponse()->setStatusCode(
                200,
                'Check the Activate Password'
            );
				} else{
					Director::redirectBack();
				}
				
			}
		
		
	}
	
	public function darkDeactivate($data, $form, $request){
	
		$darksite = $this->record;
		$dPass = "y4w*a)5XQbZ";
		$password = $data['activatePassword'];
		//$password = $d[_Password];
		if($password == $dPass) {
			$darksite->Active = 0;
			$darksite->write();
		
			if(Director::is_ajax()){
				Controller::curr()->getResponse()->setStatusCode(
                200,
                'Dark site is Deactive'
            );
		} else{
			Director::redirectBack();
		}
		}else{
			if(Director::is_ajax()){
					Controller::curr()->getResponse()->setStatusCode(
                200,
                'Check the Deactivate Password'
            );
				} else{
					Director::redirectBack();
				}
		}
	}
}