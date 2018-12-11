<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;

class DarkSite extends DataObject {
	static $db = array(
		'PRPlace'	 => 'Varchar(50)',
		//'iTimeStamp' => 'Varchar()',
		'Content' 	 => 'HTMLText',
		'MediaInq' 	 => 'Varchar(100)',
		'FamilyInq'  => 'HTMLText',
		'Familyfone' => 'Varchar(50)',
		'showFamilyFone' => 'Boolean',
		'MediaLoc' 	 => 'Varchar(100)',
		'MediaAddress' => 'Varchar(100)',
		'MediaDate'	 => 'Date',
		'MediaTime'  => 'Varchar(15)',
		'MediaSpeak' => 'Varchar(100)',
		'Active'	 => 'Boolean',
		//'DarkPasswd' => 'Varchar(75)',
		'showReleases' => 'Boolean',
		'showPartners' => 'Boolean',
		'showBriefing' => 'Boolean',
		'showContacts' => 'Boolean',
		'showResources' => 'Boolean',
		'FltNum' 	=> 'int',
		'Title' 	=> 'Varchar(40)',
		'showTab' 	=> 'Boolean'
	);


	
	static $has_one = array(
		//'DarkPage' => 'SiteTree'
	);
	
	static $has_many = array(
		'Releases' 	=> 'DarkSite_Release',
		'Resources' => 'DarkSite_Resources',
		//'Partners'  => 'Partner'
	);
	
	static $many_many = array(
		'Partners' => 'Partner'
	);
	
	static $defaults = array(
		'showReleases'  => 1,
		'showPartners'  => 1,
		'showBriefing'  => 1,					// don't really need this from the beginning??
		'showContacts'  => 1,
		'showResources' => 1,
		'PRPlace' 		=> 'St. George, UT',	// date(\"F j, Y g:i a T\")", <-- does not work, need to find out if I can get this to work somehow.
		//'MediaInq' 		=> '206.870.0406',
		'FamilyInq' 	=> 'SkyWest has set up a toll free number for those who believe that a family member or friend may have been on board Flight #XXXX.',
		//'Familyfone' 	=> '1.888.283.2153 or 801.679.4130',
		'Title'			=> 'Flight #XXXX'
	);
	
	static $summary_fields = array(
		'FltNum',
		'PRPlace' 	=> 'Title',
		//'Content',
		'Created',
		'Active',
		'LastEdited'
	);
	
	static $searchable_fields = array(
		'FltNum',
		'PRPlace',
		'Content'
	);
	
	static $default_sort = 'Active DESC';

	static $allowed_actions = array( 
         'edit', 
         'view', 
         'darkActivate',
         'darkDeactivate',
         'duplicate'
      ); 

	public function canView($member = null){
		
		return Member::currentUser()->inGroups(array('3','2'));
	}
	public function canCreate($member = null){
		
		return Member::currentUser()->inGroups(array('3','2'));
	}
	public function canEdit($member = null){
		
		return Member::currentUser()->inGroups(array('3','2'));
	}
	
	public function getDarkPage() {
		return $this->DarkPage;
	}
	
	// CHECK IF DARKSITE IS UPDATED 
	public function isUpdated(){
		
		if(strtotime($this->Created) + (60*5) < strtotime($this->LastEdited)){
			
			return true;
		}
	}
	public function Active() {
		return ($this->Active) ? 'Yes' : 'No';
	}
	
	public function getCMSFields() {
		$f = parent::getCMSFields();
		$f->addFieldToTab('Root.Main', TextField::create('FltNum')->setTitle('Flight Number'));
		$f->addFieldToTab('Root.Main', TextField::create('PRPlace')->setTitle('Title'));
		$f->addFieldToTab('Root.Main', CheckboxField::create('showTab')->setTitle('Show Notice on Home Page'));
		$f->addFieldToTab('Root.Main', TextField::create('Title')->setTitle('Notice text'));
		$f->addFieldToTab('Root.Main', HTMLEditorField::create('Content')->setTitle('Content')->setRows(15));
		//$f->addFieldToTab('Root.Main', new LiteralField('', '<p style="color:red;">Date and Time will be set upon saving this form</p>'));
		
		$f->addFieldToTab('Root.Contacts', CheckboxField::create('showContacts')->setTitle('Show Contacts Info on Dark Site page'));
		$f->addFieldToTab('Root.Contacts', TextField::create('MediaInq')->setTitle('Media Inquiries Phone Number'));
		$f->addFieldToTab('Root.Contacts', CheckboxField::create('showFamilyFone')->setTitle('Show Family Phone on Page'));
		$f->addFieldToTab('Root.Contacts', HTMLEditorField::create('FamilyInq')->setTitle('Info for Family Members')->setRows(10));
		$f->addFieldToTab('Root.Contacts', TextField::create('Familyfone')->setTitle('Family Inquiries Phone Number'));
		
		$f->addFieldToTab('Root.Briefing', CheckboxField::create('showBriefing')->setTitle('Show Media Briefing Info on Dark Site page'));
		$f->addFieldToTab('Root.Briefing', TextField::create('MediaLoc')->setTitle('Location'));
		$f->addFieldToTab('Root.Briefing', TextField::create('MediaAddress')->setTitle('Location Address'));
		$f->addFieldToTab('Root.Briefing', $eventDate = new DateField('MediaDate', 'Date'));
		$f->addFieldToTab('Root.Briefing', TextField::create('MediaTime')->setTitle('Time'));
		$f->addFieldToTab('Root.Briefing', TextField::create('MediaSpeak')->setTitle('Speakers'));
		$eventDate->setConfig('showcalendar', true);
		$eventDate->setConfig('showdropdown', true);
		$eventDate->setConfig('dateformat', 'MM/dd/YYYY');
		//$eventTime->setConfig('showdropdown', true);
		
		$darkHoldPage = DarkSiteHoldingPage::get()->filter('Status' ,'Published');
		if($darkHoldPage) {
			if($this->FltNum != 0) {
				$f->addFieldToTab('Root.Activate', new LiteralField('', '<p><a href="/flight/'. $this->FltNum .'/" target="_blank">Preview Dark Site Page</a></p>'));
			}
			//$f->addFieldToTab('Root.Activate', $selectDarkPage = new TreeDropdownField('DarkPageID', 'Page site will redirect to', 'SiteTree'));
			$f->addFieldToTab('Root.Activate', CheckboxField::create('Active')->setTitle('Active'));
			$f->addFieldToTab('Root.Activate', $aPass = PasswordField::create('activatePassword')->setTitle('Password to activate/deactivate dark site'));
			$aPass->canBeEmpty = true;
			if($this->Active) {
				//$f->addFieldToTab('Root.Activate', new LiteralField('', '<p>Site is active, you MUST fill out the password to keep it active if you make changes.</p>'));
			}
		} else {
			//$f->removeByName('Active');
			$f->addFieldToTab('Root.Main', new LiteralField('', '<p style="color:red;font-size:12pt;">The dark site can NOT be activated until the Dark Holding page is published.</p>'));
		}
		
		//$f->removeByName('DarkPasswd');
		$f->removeByName('Title');
		
		$a = array('pdf');
		$rel = new GridField(
			'Releases',
			'DarkSite_Release',
			$this->Releases(),
			GridFieldConfig_RelationEditor::create()

		);
		//$rel->setPopupWidth('800');
		//$rel->setAddTitle('Press Release');
		//$rel->setAllowedFileTypes($a);
		$f->addFieldToTab('Root.Releases', CheckboxField::create('showReleases')->setTitle('Show Press Releases on Dark Site page'));
		$f->addFieldToTab('Root.Releases', $rel);
		
		$res = new GridField(
			
			'Resources',
			'DarkSite_Resources',
			$this->Resources(),
			GridFieldConfig_RelationEditor::create()

		);
		//$res->setPopupWidth('800');
		//$res->setAddTitle('Resource');
		$f->addFieldToTab('Root.Resources', CheckboxField::create('showResources')->setTitle('Show Resources on Dark Site page'));
		$f->addFieldToTab('Root.Resources', $res);
		
		/*$partner = new DataObjectManager(
			$this,
			'Partners',
			'Partner'
		);
		$partner->setPopupWidth('800');
		$partner->setAddTitle('Partner');
		$f->addFieldToTab('Root.Partners', CheckboxField::create('showPartners', 'Show Partner Info on Dark Site page'));
		$f->addFieldToTab('Root.Partners', $partner);*/
		$s = new GridField(
			'Partners',
			'Partners',
			$this->Partners(),
			GridFieldConfig_RelationEditor::create()
		);
		//$s->setPopupWidth('800');
		//if(!Permission::check('ADMIN')) {
		//	$s->removePermission('add');
		//}
		$f->addFieldToTab('Root.Partners', CheckboxField::create('showPartners')->setTitle('Show Selected Partner on Dark Site/Incident Page'));
		$f->addFieldToTab('Root.Partners', $s);
		
		if($this->Active) {
			$message = 'The dark site is currently Active.';
			Session::set("FormInfo.Form_EditForm.formError.message", $message);
			Session::set("FormInfo.Form_EditForm.formError.type", 'bad');
		}else{
			Session::clear('FormInfo.Form_EditForm.formError.message');
		}
		$f->removeByName('Active');
		return $f;
	}
	
	
	public function getCMSActions() {
		$actions = parent::getCMSActions();
		// new action

		$dupAction = FormAction::create('duplicate', 'Duplicate');
		$dupAction->setDescription('Duplicate this item');
		
		if($this->Active){
			$darkActivate = FormAction::create('darkDeactivate', 'Deactivate');
		}else{
			$darkActivate = FormAction::create('darkActivate', 'Activate');
		}
		$actions->push($dupAction);
		$actions->push($darkActivate);
		//add to existing actions
		
		
		return $actions;
	}
	
	public function ActivateDark(){
			$this->Active = 1;
	}
		public function DeactivateDark(){
			$this->Active = 1;
	}
	
	/**********************************************************************************************************************************************************************************************/
	/*		There is an issue with the DarkPasswd stuff - it is KILLING the server (locally) so I am disabling it. poop.
	/**********************************************************************************************************************************************************************************************/
	/*public function checkDarkPass() {
		//Debug::show($this->DarkPasswd);
		return ($this->DarkPasswd) ? true : false;
		//return false;
	}*/
	/**********************************************************************************************************************************************************************************************/
	
	function onBeforeWrite() {
		/*
			check to see if active checkbox is ticked
				if not, ignore password field incase its filled out.
				if ticked, check to make sure password is correct via md5() - redirect back to root of darkAdmin
					untick any other active incidents
	
		if($this->Active) {
			$dPass = DataObject::get_by_id('DarkSite_Password', 1);
			//debug::show($dPass->finalPass);
			//debug::show(md5($this->activatePassword));
			if(md5($this->activatePassword) == $dPass->finalPass) {
				$message = '<span style="font-size:12pt;">The dark site has been activated.</span>';
				Session::set("FormInfo.Form_EditForm.formError.message", $message);
				Session::set("FormInfo.Form_EditForm.formError.type", 'bad');
				$this->Active = 1;
			} else {
				$message = '<span style="font-size:12pt;">The Dark Site was NOT activated because the password was wrong.</span>';
				Session::set("FormInfo.Form_EditForm.formError.message", $message);
				Session::set("FormInfo.Form_EditForm.formError.type", 'warning');
				$this->Active = 0;
			}
		}
			*/
		if(!$this->Title) {
			$this->Title = 'Flight ' . $this->FltNum;
		}
		parent::onBeforeWrite();
	}
	function onAfterWrite() {
		if($this->Active) {
				$message = 'The dark site has been activated.';
				Session::set("FormInfo.Form_EditForm.formError.message", $message);
				Session::set("FormInfo.Form_EditForm.formError.type", 'bad');
		}else{
				Session::clear('FormInfo.Form_EditForm.formError.message');
			}
	
	
	}
	/*
	   __________  __  ______ 
	  / ____/ __ \/ / / / __ \
	 / /   / /_/ / / / / / / /
	/ /___/ _, _/ /_/ / /_/ / 
	\____/_/ |_|\____/_____/                            
	
	*/
	
	// public function canCreate($member = null) {
	// 	if(!$member) $member = Member::currentUser();
	// 	if(!$member) return false;
	// 	return(
	// 		Permission::checkMember($member, 'CMS_ACCESS_DarkAdmin')
	// 	);
	// }
	
	// public function canView($member = null) {
	// 	$this->canEdit();
	// }
	
	// public function canEdit($member = null) {
	// 	if(!$member) $member = Member::currentUser();
	// 	if(!$member) return false;
	// 	return(
	// 		Permission::checkMember($member, 'CMS_ACCESS_DarkAdmin')
	// 	);
	// }
	
	// public function canDelete($member = null) {
	// 	if(!$member) $member = Member::currentUser();
	// 	if(!$member) return false;
	// 	return(
	// 		Permission::checkMember($member, 'ADMIN')
	// 	);
	// }
}

// class DarkSite_Password extends DataObject {
// 	static $db = array(
// 		'finalPass' => 'Varchar(75)'
// 	);
	
// 	static $summary_fields = array(
// 		'Title' => 'Title'
// 	);
	
// 	public function getTitle() {
// 		if($this->finalPass != '' || $this->finalPass != null) {
// 			return 'Edit Activation Password';
// 		} else {
// 			return 'Set Activation Password';
// 		}
// 	}
	
// 	public function getCMSFields() {
// 		$f = parent::getCMSFields();
// 		$f->addFieldToTab('Root.Main', $aPass = ConfirmedPasswordField::create('finalPass')->setTitle('Set Dark Site Activation Password'));
// 		$aPass->canBeEmpty = true;
// 		return $f;
// 	}
	
// 	function onBeforeWrite() {
// 		if($this->finalPass) {
// 			$this->finalPass = md5($this->finalPass);
// 		}
// 		parent::onBeforeWrite();
// 	}
// }



