<?php
class Partner extends DataObject {
	static $db = array(
		'Title' 	  => 'Varchar(50)',
		'PartnerContent' 	  => 'HTMLText',
		'DarkPartnerLink' => 'Varchar(100)',
		'DarkShowMe'	  => 'Boolean'
	);
	
	static $has_one = array(
		//'Parent'   => 'DarkSite',
		'DarkLogo' => 'BetterImage'
	);
	
	static $belongs_many_many = array(
		'DarkSites' => 'DarkSite'
	);
	
	static $summary_fields = array(
		'Title'		  => 'Name',
		'DarkPartnerLink' => 'Link',
		'DarkThumb' 	  => 'Logo',
		//'Showing'		  => 'Displayed'
	);
	static $searchable_fields = array(
		'Title'
			);
	
	static $defaults = array(
		'PartnerContent' => 'SkyWest Airlines is working with the full support of [Airline Name] to provide ongoing information and support for those who had loved ones onboard FLight #XXXX.',
		'DarkShowMe'  => 1,
	);
	
	static $default_sort = 'Title ASC';
	public function canView($member = null){
		
		return Member::currentUser()->inGroups(array('3','2'));
	}
	public function canCreate($member = null){
		
		return Member::currentUser()->inGroups(array('3','2'));
	}
	public function canEdit($member = null){
		
		return Member::currentUser()->inGroups(array('3','2'));
	}
	
	protected function getShowing() {
		return ($this->DarkShowMe) ? 'Yes' : 'No';
	}
	
	public function getCMSFields() {
		$a = array('jpeg', 'jpg', 'gif', 'png');
		$uploadify = new UploadField("DarkLogo", "Partner Logo");
		$uploadify->setFolderName('Uploads/DarkSite/Logos');
		$uploadify->setAllowedExtensions($a);
		$uploadify->setCanAttachExisting(true);
		if(!Permission::check('ADMIN')) {
			//$uploadify->removeFolderSelection();
		}
		
		/*$f = new FieldSet(
			new CheckBoxField('DarkShowMe', 'Display this partner on the dark site'),
			new TextField('DarkTitle', 'Partner Name'),
			new SimpleTinyMCEField('DarkContent', 'Content'),
			new TextField('DarkPartnerLink', 'Partner Link'),
			$uploadify
		);*/
		$f = parent::getCMSFields();
		$f->removeByName('DarkSites');
		$f->removeByName('SortOrder');
		$f->removeByName('DarkShowMe');
		//$f->addFieldToTab('Root.Main', new CheckBoxField('DarkShowMe', 'Display this partner on the dark site'));
		$f->addFieldToTab('Root.Main', TextField::create('Title')->setTitle('Partner Name'));
		$f->addFieldToTab('Root.Main', HTMLEditorField::create('PartnerContent')->setTitle('Content')->setRows(20));
		$f->addFieldToTab('Root.Main', TextField::create('DarkPartnerLink')->setTitle('Partner Link'));
		$f->addFieldToTab('Root.Logo', $uploadify);
		
		return $f;
	}
	
	public function getDarkThumb() {
		if($this->DarkLogoID) {
			return $this->DarkLogo()->CMSThumbnail();
		} else {
			return 'No Logo';
		}
	}
	
	/**
	 * setHTTP function check the URL string for HTTP and if not present adds it.
	 * 
	 * @access protected
	 * @param mixed $url
	 * @return string
	 */
	protected function setHTTP($url) {
		$pattern	= '/^http/';
		if(preg_match($pattern, $url)) {
			return $url;
		} else {
			return $url = 'http://' . $url;
		}
	}
	
	function onBeforeWrite() {
		$this->DarkPartnerLink = $this->setHTTP($this->DarkPartnerLink);
		parent::onBeforeWrite();
	}
}