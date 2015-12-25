<?php
require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskSendPushover extends \Workflow\Task
{
    public function handleTask(&$context) {
		/* Insert here source code to execute the task */

        if (!extension_loaded('curl')) {
            return 'yes';
        }

        $userkey = $this->get('userkey', $context);
        $subject = $this->get('subject', $context);
        $content = $this->get('content', $context);
        $device = $this->get('device', $context);

        $token = $this->get('appkey', $context);
        if(empty($token)) {
            $token = base64_decode('YWVzbnRmem9TOWM1UWpaZkEyZTdWaFFpUkhldVlL');
        }
        if(empty($device)) {
            $device = 'all';
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.pushover.net/1/messages.xml');
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'token' => $token,
            'user' => $userkey,
            'title' => $subject,
            'message' => $content,
            'device' => $device,
            //'priority' => $this->getPriority(),
            //'timestamp' => $this->getTimestamp(),
            //'expire' => $this->getExpire(),
            //'retry' => $this->getRetry(),
            //'callback' => $this->getCallback(),
            //'url' => $this->getUrl(),
            //'sound' => $this->getSound(),
            //'url_title' => $this->getUrlTitle()
        ));
        $response = curl_exec($curl);

        $this->addStat($response);

		return "yes";
    }
	
    public function beforeGetTaskform($viewer) {
		/* Insert here source code to create custom configurations pages */
        if (!extension_loaded('curl')) {
            $this->addConfigHint('You cannot use this Task! You must install the cURL PHP Extension before usage.');
        }

    }	
    public function beforeSave(&$values) {
		/* Insert here source code to modify the values the user submit on configuration */
    }	
}
