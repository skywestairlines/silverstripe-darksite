git statu<?php  

class DarkSite_Resources extends DataObject
{
    // pages that can be accessible during the dark site - must be refered from dark site otherwise will be redirected back to dark site
    static $db = array(
        'Title' => 'Varchar(80)',
    );

    static $has_one = array(
        'DarkResource' => 'File',
        'Parent' => 'DarkSite',
        /*    not linking to pages anymore
    'PageLink' => 'SiteTree'*/
    );

    static $summary_fields = array(
        'Title' => 'Title',
        'DarkResource.Name' => 'FileName',
        /*'PageLink.Title' => 'Title',
    'PageLink.URLSegment' => 'Link'*/
    );
    public function canView($member = null)
    {

        return Member::currentUser()->inGroups(array('3', '2'));
    }
    public function canCreate($member = null)
    {

        return Member::currentUser()->inGroups(array('3', '2'));
    }
    public function canEdit($member = null)
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
