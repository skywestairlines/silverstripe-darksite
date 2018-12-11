<?php
class DarkSiteControllerExtension extends DataExtension {
   
	/**
	* Redirect to the Dark Site Page if necessary
	* 
	* @return Void
	*/
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
  
	/**
	* Indicate if we are currently on the Dark site page.
	* Useful for using in templates and view files.
	* 
	* @return Boolean whether current page is the set holding page
	*/
	public function OnDarkPage() {
		$DarkPage = SiteConfig::current_site_config()->ShowDarkPage();	// <-- fix this line
		if (Director::get_current_page() == $DarkPage) {
			return true;
		}
		return false;
	}
}