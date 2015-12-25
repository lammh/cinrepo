<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Cron;${"GLOB\x41\x4cS"}["\x69\x63\x64\x69\x61\x69\x70\x69\x73"]="\x69\x6e\x76\x65\x72\x74";class YearField extends AbstractField{public function isSatisfiedBy(\DateTime$date,$value){${"\x47\x4cO\x42AL\x53"}["e\x6ck\x6c\x73\x6e\x71b\x64"]="v\x61\x6c\x75e";return$this->isSatisfied($date->format("\x59"),${${"\x47LO\x42A\x4c\x53"}["e\x6ck\x6c\x73\x6e\x71bd"]});}public function increment(\DateTime$date,$invert=false){if(${${"\x47\x4c\x4f\x42A\x4c\x53"}["icd\x69\x61\x69\x70\x69\x73"]}){$date->modify("-\x31\x20\x79e\x61\x72");$date->setDate($date->format("\x59"),12,31);$date->setTime(23,59,0);}else{$date->modify("+\x31 \x79\x65\x61r");$date->setDate($date->format("Y"),1,1);$date->setTime(0,0,0);}return$this;}public function validate($value){${"\x47L\x4fB\x41\x4cS"}["\x78\x6a\x79\x6b\x69kv\x6b\x70\x74"]="\x76\x61\x6c\x75\x65";return(bool)preg_match("/[\x5c*,\\/\x5c-0-9]+/",${${"\x47\x4c\x4f\x42\x41LS"}["x\x6a\x79\x6b\x69\x6b\x76\x6b\x70\x74"]});}}
?>