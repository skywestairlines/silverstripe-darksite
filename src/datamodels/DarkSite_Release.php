<?php

use SilverStripe\Assets\File;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Security\Member;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Security\Permission;
use SilverStripe\AssetAdmin\Forms\UploadField;

class DarkSite_Release extends DataObject
{
    // pdfs for the dark site
    private static $db = array(
        'Title' => 'Varchar(80)',
        'Excerpt' => 'Text',
        'Date' => 'Date',
        'HideInRSS' => 'Boolean',
        'Sort' => 'Int'
    );

    private static $has_one = array(
        'Parent' => DarkSite::class,
        'DarkRelease' => File::class,
    );

    private static $summary_fields = array(
        'Title' => 'Title',
        'Date' => 'Date',
        'DarkRelease.Title' => 'Press Release PDF',
    );

    private static $default_sort = 'Date ASC';

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
        $uploadify = new UploadField("DarkRelease", "Press Release PDF");
        $uploadify->setFolderName('Uploads/DarkSite/PressReleases');
        $uploadify->setAllowedExtensions($a);
        if (!Permission::check('ADMIN')) {
            //$uploadify->removeFolderSelection();
        }
        $datefield = new DateField('Date', 'Press Release Date');
        $datefield->config('showcalendar', true);
        $datefield->config('showdropdown', true);
        $datefield->config('dateformat', 'MM/dd/YYYY');

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
