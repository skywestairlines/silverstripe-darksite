<?php

use SilverStripe\Dev\Debug;
use SilverStripe\View\Requirements;
use SilverStripe\Control\RSS\RSSFeed;

class DarkSiteHoldingPageController extends PageController
{
    public function init()
    {
        RSSFeed::linkToFeed($this->Link() . 'rss');
        parent::init();
        Requirements::css('skywest/ss-darksite: css/darkStyle.css');
    }

    public function index()
    {
        //$url = $_REQUEST['url'];
        $params = $this->getURLParams();
        //Debug::show($params['ID']);
        if (is_numeric($params['ID']) && $f = DarkSite::get()->filter('FltNum', $params['ID'])->limit(1)) {
            //Debug::show('found');
            return $this->customise($f)->renderWith('IncidentPage', 'DarkSiteHoldingPage');
            //return $this->latestIncidentID($params['ID']);
        } else {
            //Debug::show('not found!');
            //return self::httpError(404, 'Sorry that flight number could not be found.');
            if ($f = DarkSite::get()->filter('Active', '1')->limit(1)) {
                //debug::show($f);
                return $this->Customise($f)->renderWith('DarkSiteHoldingPage', 'Page');
            } else {
                return self::httpError(404, 'Sorry that flight number could not be found.');
            }
        }
    }

    public function FltNum($fltNum = '')
    {
        if ($fltNum) {
            if ($f = DarkSite::get()->filter('FltNum', $fltNum)->limit(1)) {
                // return flt incident stuff
                Debug::show('in FltNum');
                return $this->customise($f)->renderWith('DarkSiteHoldingPage', 'Page');
            } else {
                // return 404 page
                //return self::httpError(404, 'Sorry that flight number could not be found.');
            }
        } else {
            // return 404 page
            //return $this->customise($f)->httpError(404, 'No flight number was given.');
        }
    }
    public function rss()
    {
        $rss = new RSSFeed($this->getDarkReleases(), $this->Link(), 'Press Releases', 'SkyWest Airlines Press Releases', 'Title', 'Excerpt', 'Date', 'Date');

        //RSSFeed($entries, $link, $title, $Desc, $titleField, $DescriptionField, $authorField, $lastModified, $eTag);
        $rss->outputToBrowser();
        //$rss1->outputToBrowser();
    }

    public function getDarkReleases()
    {
        $this->currentDay = date('Y-m-d');
        $this->currentYear = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));
        $this->currentMonth = date('Y-m-d', mktime(0, 0, 0, 1, date('m'), date('Y')));
        $this->lastYear = date('Y-m-d', strtotime($this->currentYear . ' -1 year')); //date('Y-m-d', strtotime($this->firstDay . ' -1 year'));
        $this->twoYearsAgo = date('Y-m-d', strtotime($this->currentYear . ' -2 years')); //date('Y-m-d', strtotime($this->firstDay . ' -2 years'));
        return DarkSite_Release::get()->where("Date >= '" . $this->lastYear . "' AND HideInRSS = '0'")->sort('Date', 'DESC'); //  Hiding PRs from the RSS
    }

    /**
     * latestIncidentID function.    returns the ID of the most recent incident - need to overload this if user puts in flt number in url
     *
     * @access private
     * @return void
     */
    public function latestIncidentID($fltNum = '')
    {
        $params = $this->getURLParams();
        //Debug::show($params['ID']);
        if ($fltNum) {
            //Debug::show('flt num given');
            $f = "`FltNum` = '$fltNum'";
        } elseif (is_numeric($params['ID'])) {
            //Debug::show('flt num in params');
            $f = "`FltNum` = '" . $params['ID'] . "'";
        } else {
            //Debug::show('flt num NOT given');
            $f = '';
        }
        if ($d = DarkSite::get()->where($f)->limit(1)) {
            //Debug::show(DataObject::get_one('DarkSite', $f));
            return $d['ID']->ID;
        }
    }

    public function MainStatement()
    {
        if ($main = DarkSite::get()->byID($this->latestIncidentID())) {
            //Debug::show($this->latestIncidentID());
            return $main;
        }
        return false;
    }

    public function DarkReleases()
    {
        if ($pr = DarkSite_Release::get()->where("`ParentID` = '" . $this->latestIncidentID() . "'")->sort('SortOrder', 'ASC')->limit(3)) {
            return $pr;
        }
        return false;
    }

    public function DarkResources()
    {
        if ($resources = DarkSite_Resources::get()->where("`ParentID` = '" . $this->latestIncidentID() . "'")->sort('SortOrder', 'ASC')->limit(3)) {
            return $resources;
        }
        return false;
    }

    public function DarkPartner()
    {
        if ($partner = Partner::get()->where("`DarkSite_Partners`.`DarkSiteID` = '" . $this->latestIncidentID() . "'")->sort('SortOrder', 'ASC')->leftJoin('DarkSite_Partners', "DarkSite_Partners.PartnerID = Partner.ID")) {
            return $partner;
        }
        return false;
    }
}
