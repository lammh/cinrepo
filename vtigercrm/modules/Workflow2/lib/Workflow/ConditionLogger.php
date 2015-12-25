<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Workflow;class ConditionLogger{private$_logs=array();private$_level=0;public function log($value){$ndyfyjfj="\x76\x61\x6cue";$this->_logs[]=str_repeat("\x20\x20",$this->_level).${$ndyfyjfj};}public function increaseLevel(){$this->_level++;}public function decreaseLevel(){$this->_level--;}public function getLogs(){return$this->_logs;}public function clearLogs(){$this->_logs=array();}}
?>