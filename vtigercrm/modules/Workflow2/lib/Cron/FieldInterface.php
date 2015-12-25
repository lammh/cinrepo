<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Cron;interface FieldInterface{public function isSatisfiedBy(\DateTime$date,$value);public function increment(\DateTime$date,$invert=false);public function validate($value);}
?>