<?php  

class DarkSite_Release extends DataObject
{
    // pdfs for the dark site
    static $db = array(
        'Title' => 'Varchar(80)',
        'Excerpt' => 'Text',
        'Date' => 'Date',
        'HideInRSS' => 'Boolean',
    );

    static $has_one = array(
        'Parent' => 'DarkSite',
        'DarkRelease' => 'File',
    );

    static $summary_fields = array(
        'Title' => 'Title',
        'Date' => 'Date',
        'DarkRelease.Title' => 'Press Release PDF',
    );

    static $default_sort = 'Date ASC';

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
        $uploadify = new UploadField("DarkRelease", "Press Release PDF");
        $uploadify->setFolderName('Uploads/DarkSite/PressReleases');
        $uploadify->setAllowedExtensions($a);
        if (!Permission::check('ADMIN')) {
            //$uploadify->removeFolderSelection();
        }
        $datefield = new DateField('Date', 'Press Release Date');
        $datefield->setConfig('showcalendar', true);
        $datefield->setConfig('showdropdown', true);
        $datefield->setConfig('dateformat', 'MM/dd/YYYY');

        $f = new FieldList(
            $datefield,
            TextField::create('Title'),
            //new TextareaField('Excerpt', 'Excerpt'),
            $uploadify,
            CheckboxField::create('HideInRSS')->setTitle('Hide Press Release from RSS')
        );
        return $f;
    }
}
