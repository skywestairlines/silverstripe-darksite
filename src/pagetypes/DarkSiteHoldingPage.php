<?php

use SilverStripe\Dev\Debug;
use SilverStripe\Security\Member;
use SilverStripe\Control\Director;
use SilverStripe\Control\Controller;
use SilverStripe\Security\Permission;


class DarkSiteHoldingPage extends Page {
	/*
		This is a dummy page that is populated by the DataObjects from DarkSite.php (Dark Site Admin in the CMS)
		We will NOT pull any info from this page, this is just a holder.
	*/
	private static $db = array(
		'myTitle' => 'Varchar(25)'
	);
	
	private static $defaults = array(
		'ShowInMenus' => 0,
		'ShowInSearch' => 0
	);
	
	private static $allowed_children = array();
	
	public function getCMSFields() {
		$f = parent::getCMSFields();
		// remove standard fields and show default text -- this is so no non admin or corpcomm person can mess with the data
		$f->removeByName('Content', true);
		$f->addFieldToTab('Root.Main', new LiteralField('', '<p>All data for this page is populated from the Dark Site Admin.</p>'));
		//$f->addFieldToTab('Root.Content.Main', new TextField('myTitle'));
		return $f;
	}
	
	function checkDarkActive() {
		$ds = DarkSite::get()->filter('Active','1')->limit(1)->count();	// check to see if darksite is active
			//Debug::show($ds);
			if($ds){
				return DarkSite::get()->filter('Active','1')->limit(1);
			}else
			return false;
	}
	
	/**
	* If a holding page is set then redirect to it, unless current user is an admin
	* 
	* @return Mixed Redirects browser or just returns
	*/
	public static function redirect_to_dark() {
		//Debug::show(Director::get_current_page()->ClassName);
		//$darkPage = SiteConfig::current_site_config()->ShowDarkPage();	// <-- returns Bool so we just need to just check for active or not
		//Debug::show($darkPage);
		if ($ds = DarkSite::get()->filter('Active','1')->limit(1)->count() ){
			//$ds = 0;
		} else {
			DarkSite::get()->filter('Active','1')->limit(1)->Active = 0;	// empty object error if I don't declare this
		}
		//Debug::show($ds->Active);
		$currentUser = Member::currentUser();
		if($ds) {	/*********** DARK SITE IS ACTIVE **************/
			//if ($darkPage->exists() && $darkPage instanceof DarkSiteHoldingPage && $darkPage->getExistsOnLive()) {
				/* IF ADMIN DONT SHOW HOME PAGE AS DARK SITE */
				//Do not redirect for admin users, allow them to browse the site
				//if($currentUser && ($currentUser->isAdmin() || Permission::checkMember($currentUser, 'CMS_ACCESS_DarkAdmin'))) {
					//return;
				//}
				// redirect
				//debug::show(Director::get_current_page()->ClassName);
				//if(Director::get_current_page() != $darkPage) {	// redirects EVERY page
				if(Director::get_current_page()->ClassName == 'HomePage') {	// only redirect the home page -- 
					return Controller::curr()->redirect('/skywest/');	//$darkPage->Link() . $ds->FltNum
					//DarkSiteHoldingPage_Controller::FltNum($ds->FltNum);
				}
			//}
		} else {	/*********** DARK SITE IS NOT ACTIVE **************/
			/*
				check for flt number in url
			*/
			if($currentUser && (Permission::checkMember($currentUser, 'CMS_ACCESS_DarkAdmin'))) {
				return;
			}
			// redirect
				//debug::show(Director::get_current_page()->ClassName);
			if(Director::get_current_page()->ClassName == 'DarkSiteHoldingPage') {
				// if they go to the root incident page 404'd! page!

				return Controller::curr()->redirect('/');
			}
			
		}
	}
	
	// redirect for fltnums in url
	public function FltNum($fltNum = '') {
		Debug::show('poop');
		if($fltNum) {
			$f = "`FltNum` = '$fltNum'";
			$c = new DarkSiteHoldingPage_Controller();
			$c->getURLParams();
			Debug::show($c->index());
			Debug::show('yes');
		} else {
			$f = '';
			Debug::show('no');
		}
	}
	
	/**
	* Remove Unpublish button if the dark site is currently activated
	* 
	* @return FieldSet The available actions for this page.
	*/
	function getCMSActions() {
		$member = Member::currentUser();
		$actions = parent::getCMSActions();
		if ($this->checkDarkActive()) {
			$actions->removeByName('action_unpublish');
			$actions->removeByName('action_delete');
			$url = Director::absoluteURL('admin/darkAdmin', true);
			$message = 'You cannot unpublish this page because the dark site is currently active.';
			Session::set("FormInfo.Form_EditForm.formError.message", $message);
			Session::set("FormInfo.Form_EditForm.formError.type", 'bad');
		}
		if(!Permission::checkMember($member, 'ADMIN')) {
			$actions->removeByName('action_unpublish');
		}
		return $actions;
	}
	
	/**
	* If the current page is set as the holding page then once it gets unpublished
	* it is removed as the holding page. Already removing unpublish button above so this 
	* is belt and braces.
	* 
	* @return Mixed False if cannot update site config
	*/
	function doUnpublish() {
		//If the dark site is NOT active then unplublish this page
		$siteConfig = SiteConfig::current_site_config();
		$holdingPage = $siteConfig->ShowDarkPage();
		if ($this == $holdingPage) {
			$siteConfig->setField('ShowHoldingPageID', 0);
			if (!$siteConfig->write()) {
				return false;
			}
		}
		parent::doUnpublish();
	}
	
	// limit who can play with this file :)
	public Function canCreate($member = NULL, $context = []) {
		// only allow Admins to create and/or delete this page
		if(!$member) $member = Member::currentUser();
		if(!$member) return false;
		return(
			Permission::checkMember($member, 'ADMIN')
		);
	}
	
	public function canEdit($member = NULL, $context = []) {
		//return $this->canCreate();				// only Permission::checkMember($member, 'CMS_ACCESS_DarkAdmin') can edit this page, I don't care who can view it, there is nothing there
		if(!$member) $member = Member::currentUser();
		if(!$member) return false;
		return(
			Permission::checkMember($member, 'ADMIN') || Permission::checkMember($member, 'CMS_ACCESS_DarkAdmin')
		);
	}
	
	public function canView($member = NULL, $context = []) {
		return true;
	}
	
	public function canDelete($member = NULL, $context = []) {
		return $this->canCreate();
	}
}

 