<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Cron;${"G\x4c\x4f\x42\x41LS"}["\x70\x62w\x73\x6a\x6e\x6e\x76\x78\x6a"]="i\x6e\x76e\x72t";class MinutesField extends AbstractField{public function isSatisfiedBy(\DateTime$date,$value){$nndgcjqfdcsn="v\x61\x6cue";return$this->isSatisfied($date->format("i"),${$nndgcjqfdcsn});}public function increment(\DateTime$date,$invert=false){if(${${"GL\x4fBA\x4c\x53"}["p\x62\x77\x73\x6annvx\x6a"]}){$date->modify("-\x31 m\x69\x6eu\x74\x65");}else{$date->modify("+1\x20m\x69\x6e\x75\x74\x65");}return$this;}public function validate($value){${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x68\x6e\x7a\x62m\x71ff\x79\x6d\x77r"]="\x76\x61lue";return(bool)preg_match("/[\x5c*,\x5c/\\-0-9]+/",${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x68n\x7ab\x6d\x71\x66\x66y\x6dw\x72"]});}}
?>