<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Cron;${"\x47\x4c\x4fB\x41\x4cS"}["\x65m\x72r\x67k\x70\x69l\x63\x63"]="t\x69\x6d\x65\x7a\x6f\x6e\x65";class HoursField extends AbstractField{public function isSatisfiedBy(\DateTime$date,$value){${"\x47L\x4f\x42A\x4c\x53"}["\x76\x63\x69\x6d\x6f\x67\x62\x70\x70\x62"]="\x76\x61l\x75\x65";return$this->isSatisfied($date->format("\x48"),${${"\x47L\x4f\x42A\x4cS"}["\x76c\x69\x6d\x6f\x67\x62\x70pb"]});}public function increment(\DateTime$date,$invert=false){$fbgdwznuc="\x74\x69mezo\x6ee";${"G\x4cOB\x41\x4c\x53"}["\x73\x63\x71w\x71\x75\x79\x68\x6a"]="i\x6e\x76e\x72\x74";${$fbgdwznuc}=$date->getTimezone();$date->setTimezone(new\DateTimeZone("\x55\x54\x43"));if(${${"G\x4c\x4f\x42\x41L\x53"}["\x73cq\x77\x71\x75\x79\x68\x6a"]}){$date->modify("-1 h\x6fu\x72");$date->setTime($date->format("H"),59);}else{$date->modify("+\x31 \x68\x6f\x75\x72");$date->setTime($date->format("\x48"),0);}$date->setTimezone(${${"GLO\x42\x41\x4c\x53"}["e\x6d\x72rg\x6b\x70\x69l\x63c"]});return$this;}public function validate($value){${"\x47\x4c\x4fB\x41\x4c\x53"}["uk\x6f\x6ag\x76m\x71\x66\x77\x72"]="\x76al\x75\x65";return(bool)preg_match("/[\x5c*,\\/\x5c-0-\x39]+/",${${"G\x4c\x4f\x42\x41\x4c\x53"}["u\x6b\x6fjg\x76\x6d\x71\x66wr"]});}}
?>