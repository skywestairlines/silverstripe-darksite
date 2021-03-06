<?php

use SilverStripe\Assets\File;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;
use SilverStripe\AssetAdmin\Forms\UploadField;

class DarkSite_Resource extends DataObject
{
    // pages that can be accessible during the dark site - must be refered from dark site otherwise will be redirected back to dark site
    private static $db = array(
        'Title' => 'Varchar(80)',
        'SortOrder' => 'Int',
        "Sort" => 'Int'
    );

    private static $has_one = array(
        'DarkResource' => File::class,
        'Parent' => DarkSite::class,
        /*    not linking to pages anymore
    'PageLink' => 'SiteTree'*/
    );

    private static $summary_fields = array(
        'Title' => 'Title',
        'DarkResource.Name' => 'FileName',
        /*'PageLink.Title' => 'Title',
    'PageLink.URLSegment' => 'Link'*/
    );
    public function canView($member = NULL, $context = [])
    {

        return Member::currentUser()->inGroups(array('3', '2'));
    }
    public function canCreate($member = NULL, $context = [])
    {

        return Member::currentUser()->inGroups(array('3', '2'));
    }
    public function canEdit($member = NULL, $context = [])
    {

        return Member::currentUser()->inGroups(array('3', '2'));
    }

    public function getCMSFields()
    {
        $a = array('pdf');
        $upload = new UploadField('DarkResource', 'Resource PDF File');
        $upload->setFolderName('Uploads/DarkSite/Resources');
        $upload->setAllowedExtensions($a);
        $f = new FieldList(
            $title = TextField::create('Title'),
            $upload
            //$dropdown = new SimpleTreeDropdownField('PageLinkID', 'Page Link', 'SiteTree')
        );
        //$dropdown->setEmptyString('Select One...');
        return $f;
    }
}
