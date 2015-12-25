<?php
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));
ini_set('display_errors', 1);
error_reporting(E_ALL&~E_NOTICE);
class WfTaskAdd2GoogleCal extends \Workflow\Task
{
    private $additionallyDir = null;

    public function init() {
        $this->additionallyDir = $directory = vglobal('root_directory').'modules/Workflow2/extends/additionally/googlecal/';

        global $currentBlockID;
        $currentBlockID = $this->getBlockId();
        require_once($this->additionallyDir."/google-api-php-client/autoload.php");

		$callback = 'urn:ietf:wg:oauth:2.0:oob';
        $client = new \Google_Client();
        $client->setAccessType('offline');
        $client->setApplicationName("VtigerCRM_WorkflowDesigner");
        $client->setClientId('165426828888-7982si8gvbtvgid01nf7lkiolt0bs3vt.apps.googleusercontent.com');
        $client->setClientSecret('R1M9RkVUbxc7XGRLUWld376n');
        $client->setRedirectUri($callback);
        $client->setScopes(array('https://www.googleapis.com/auth/calendar'));

		$tokenFilename = $this->additionallyDir."token/tokenSession3_".$currentBlockID;

		if(!empty($_POST["code"])) {
            $client->authenticate($_POST['code']);
            $googleToken = $client->getAccessToken();

            file_put_contents($tokenFilename, serialize(array('accessToken' => $googleToken)));
            echo '<script type="text/javascript">window.location.href="index.php?module=Workflow2&parent=Settings&view=TaskConfig&taskid='.$this->getBlockId().'&done=1";</script>';
            exit();
		}

		if(file_exists($tokenFilename)) {
			$sessionToken = unserialize(file_get_contents($tokenFilename));
		} else {
			$sessionToken = "";
		}

       if(empty($sessionToken['accessToken'])) {
           $sessionToken = null;
       }

		if (empty($sessionToken)) {
            if(function_exists('csrf_get_tokens')) {
                $csrf = "<input type='hidden' name='".$GLOBALS['csrf']['input-name']."' value='".csrf_get_tokens()."' />";
            } else {
                $csrf = '';
            }

            $authUrl = $client->createAuthUrl();
            echo '<script type="text/javascript">window.open("'.$authUrl.'");</script>';
            echo '<div style="text-align:center;margin:40px 0;">',getTranslatedString('Because of Login Restrictions, you need to do the Login and Authorization within the PopUp and copy the Code you get in this Textfield.', 'Settings:Workflow2');
            echo '<form method="POST" action="#">'.$csrf.'<br/><input type="text" style="width:400px;" name="code"><br/><input type="submit" class="btn  btn-primary" name="submit" value="Submit the Code & Unlock Google Calendar Access" /> </form>';
            exit();
		}

		$client->setAccessToken($sessionToken['accessToken']);
		$this->service = new Google_Service_Calendar($client);
        $this->client = $client;
    }

    public function handleTask(&$context) {
        $entityDataKey = "block_".$this->getBlockId()."_eventid";

        // Setze das Datum und verwende das RFC 3339 Format.
        $startDate = $this->get("eventstartdate", $context);
        $this->addStat('Startdate'.$startDate);

        $startTime = $this->get("eventstarttime", $context);
        $this->addStat('Starttime'.$startTime);

        $parts = explode(':',$startTime);
        if(count($parts) == 3) {
            $startTime = $parts[0].':'.$parts[1];
        }

		if(strlen($startTime) < 5) {
			$startTime = "0".$startTime;
		}


        $duration = $this->get("eventduration", $context);

        $date = strtotime("+".$duration." minutes", strtotime($startDate." ".$startTime));

        $endDate = date("Y-m-d", $date);
        $endTime = date("H:i", $date);
        $tzOffset = "+01";

        $this->init();

        if(1==0 && $context->existEntityData($entityDataKey)) {
            $entityId = $context->getEntityData($entityDataKey);

            try {
                $event = $service->getCalendarEventEntry($entityId);
                $when = $service->newWhen();
                $when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
                $when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";

                $event->when = array($when);
                $event->save();

                return "yes";
            } catch (Zend_Gdata_App_Exception $e) {
                $this->addStat("existing Event not found. Create new!");
            }

        }

		$event = new Google_Service_Calendar_Event();
		$event->setSummary($this->get("eventtitle", $context));
		#$event->setLocation('Somewhere');
		$start = new Google_Service_Calendar_EventDateTime();
		$start->setDateTime("{$startDate}T{$startTime}:00.000{$tzOffset}:00");
		#$start->setTimeZone('America/Los_Angeles');
		$event->setStart($start);
        $event->setDescription($this->get("eventdescr", $context));

		$end = new Google_Service_Calendar_EventDateTime();
		$end->setDateTime("{$endDate}T{$endTime}:00.000{$tzOffset}:00");
		#$end->setTimeZone('America/Los_Angeles');
		$event->setEnd($end);
		$event->setVisibility($this->get("privacy"));

		$event = $this->service->events->insert(html_entity_decode($this->get("calendar")), $event);

        $context->addEntityData($entityDataKey, $event->getId());
		$this->storeAccessKey();

		return "yes";
    }

    public function storeAccessKey() {


        $tokenFilename = $this->additionallyDir."token/tokenSession3_".$this->getBlockId();
   		$googleToken = $this->client->getAccessToken();
   		file_put_contents($tokenFilename, serialize(array('accessToken' => $googleToken)));
   	}

    public function beforeGetTaskform($viewer) {
        $this->init();

        $calenderList = array();

        try {
            $listFeed = $this->service->calendarList->listCalendarList();
        } catch (\Exception $e) {
            echo "Fehler: " . $e->getMessage();
        }

        foreach ($listFeed as $calendar) {
            $calenderList[$calendar->getId()] = $calendar->getSummary();
        }

        $viewer->assign("calendar", $calenderList);
        $privacySettings = array(
            "default" => getTranslatedString("LBL_PRIV_DEFAULT", "Settings:Workflow2"),
            "public" => getTranslatedString("LBL_PRIV_PUBLIC", "Settings:Workflow2"),
            "private" => getTranslatedString("LBL_PRIV_PRIVATE", "Settings:Workflow2"),
        );
        $viewer->assign("privacySettings", $privacySettings);
    }

    public function beforeSave(&$values) {
		/* Insert here source code to modify the values the user submit on configuration */
    }	
}
