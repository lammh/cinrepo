<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Workflow;${"\x47\x4c\x4f\x42A\x4c\x53"}["i\x79\x68b\x65d\x6e\x6a\x70x"]="\x76\x61\x6c\x75\x65";${"\x47\x4c\x4f\x42A\x4cS"}["\x74\x6d\x63o\x72h"]="t\x6d\x70P\x61rts";${"GL\x4fB\x41L\x53"}["\x75\x70a\x6a\x63j\x6f\x65\x79"]="\x6d\x6f\x64\x65";${"GL\x4f\x42\x41LS"}["\x63\x69\x62\x75\x79\x65\x75\x77\x76\x69"]="i\x74\x65m";${"GL\x4f\x42\x41\x4c\x53"}["\x62o\x6cp\x6c\x6c\x77\x6f\x6d"]="\x66i\x6c\x65";${"\x47\x4cO\x42A\x4cS"}["\x76\x66\x68ws\x74\x62g"]="co\x6e\x66\x69\x67\x73";${"GL\x4fBA\x4c\x53"}["b\x68s\x65\x77\x6c\x6dq"]="i\x74e\x6ds";abstract class Attachment extends Extendable{const MODE_ADD_NEW_ATTACHMENTS="\x4d\x4fDE\x5f\x41\x44D\x5f\x4e\x45W\x5f\x41TT\x41C\x48M\x45NTS";const MODE_NOT_ADD_NEW_ATTACHMENTS="\x4dODE_\x4e\x4fT\x5fAD\x44\x5f\x4eE\x57\x5fAT\x54\x41C\x48\x4d\x45NT\x53";protected$_mode=Attachment::MODE_ADD_NEW_ATTACHMENTS;private$attachments=array();public static function init(){self::_init(dirname(__FILE__)."/../../e\x78tends/at\x74ac\x68m\x65n\x74s/");}public static function getAvailableOptions($moduleName){${${"\x47\x4c\x4fB\x41L\x53"}["\x62\x68\x73\x65\x77lm\x71"]}=self::getItems();$rpmnttlpwfu="\x69\x74e\x6d\x73";${"GLO\x42\x41\x4c\x53"}["\x74\x70y\x74\x74\x64\x73\x6fl\x66\x6f"]="\x72\x65\x74urn";${"\x47\x4cO\x42A\x4cS"}["\x66\x6b\x6a\x70\x73\x69\x6c\x6e\x61\x65\x74"]="\x72\x65t\x75\x72\x6e";${"\x47\x4c\x4f\x42\x41L\x53"}["\x77\x62p\x6a\x78\x66\x77\x62\x61\x77"]="\x69\x74\x65\x6d";${${"G\x4c\x4fBA\x4c\x53"}["t\x70y\x74\x74dsolf\x6f"]}=array();foreach(${$rpmnttlpwfu} as${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x77\x62\x70\x6a\x78\x66\x77\x62\x61w"]}){$wcbyppldvi="\x6d\x6f\x64\x75le\x4ea\x6d\x65";${${"GLOB\x41\x4c\x53"}["\x76\x66h\x77\x73\x74b\x67"]}=$item->getConfigurations(${$wcbyppldvi});foreach(${${"\x47L\x4f\x42\x41\x4cS"}["\x76f\x68w\x73\x74\x62\x67"]} as${${"\x47\x4cO\x42AL\x53"}["b\x6f\x6c\x70\x6c\x6cwo\x6d"]}){$djsrcmgcat="\x72\x65\x74\x75\x72\x6e";${$djsrcmgcat}[]=${${"\x47\x4c\x4f\x42\x41LS"}["\x62o\x6c\x70\x6c\x6c\x77\x6f\x6d"]};}}return${${"\x47\x4cOB\x41\x4c\x53"}["f\x6bj\x70si\x6c\x6e\x61\x65\x74"]};}public static function getAttachments($key,$value,$context,$mode=self::MODE_ADD_NEW_ATTACHMENTS){${"\x47\x4cO\x42A\x4c\x53"}["n\x78\x64\x73t\x78"]="tm\x70\x50\x61rt\x73";${"\x47\x4cOBA\x4c\x53"}["\x71\x66\x6dcpre\x68w\x65"]="\x61\x74\x74a\x63\x68me\x6ets";$zlcverkummj="t\x6d\x70P\x61r\x74\x73";$whrfthqbb="\x6be\x79";${"\x47\x4c\x4f\x42AL\x53"}["x\x6c\x6a\x63\x6cp\x6a\x6d\x63cj"]="a\x74t\x61\x63\x68\x6de\x6e\x74s";${"\x47\x4c\x4fB\x41\x4cS"}["\x7a\x72v\x62\x71\x76\x62"]="\x63\x6fn\x74\x65x\x74";${${"\x47\x4cOB\x41\x4c\x53"}["\x6e\x78\x64\x73t\x78"]}=explode("\x23",${$whrfthqbb});$gioqwyeymefl="val\x75\x65";${${"\x47\x4c\x4fB\x41\x4cS"}["\x63\x69\x62u\x79e\x75\x77v\x69"]}=self::getItem(${$zlcverkummj}[0]);$item->setMode(${${"\x47LO\x42AL\x53"}["up\x61jcjo\x65\x79"]});if(${${"\x47L\x4f\x42\x41L\x53"}["\x63i\x62\x75\x79\x65u\x77\x76i"]}===false){return array();}$item->clearAttachmentRecords();$item->generateAttachments(${${"\x47\x4c\x4f\x42AL\x53"}["\x74\x6dc\x6f\x72h"]}[1],${$gioqwyeymefl},${${"G\x4c\x4f\x42A\x4cS"}["\x7a\x72\x76b\x71\x76\x62"]},${${"\x47LO\x42AL\x53"}["\x75\x70\x61\x6ac\x6a\x6f\x65\x79"]});${${"\x47\x4cO\x42\x41\x4c\x53"}["qf\x6dcpr\x65\x68\x77\x65"]}=$item->getAttachmentRecords();return${${"\x47\x4c\x4f\x42\x41LS"}["x\x6c\x6ac\x6c\x70j\x6d\x63\x63\x6a"]};}abstract public function generateAttachments($context,$key,$value);abstract public function getConfigurations($moduleName);public function isAvailable($moduleName){return true;}public function setMode($mode){$this->_mode=${${"GL\x4f\x42ALS"}["\x75\x70\x61j\x63\x6aoey"]};}public function clearAttachmentRecords(){$this->attachments=array();}public function addAttachmentRecord($mode,$value,$filename=null){${"\x47\x4cOB\x41LS"}["bk\x76u\x73\x78\x72"]="\x66ile\x6ea\x6de";$iutbdzzyo="\x6d\x6fd\x65";$this->attachments[]=array(${$iutbdzzyo},${${"G\x4c\x4fB\x41\x4cS"}["iyh\x62ed\x6e\x6a\x70\x78"]},array("\x66\x69le\x6e\x61me"=>${${"GL\x4f\x42\x41LS"}["\x62\x6b\x76\x75\x73\x78r"]}));}public function getAttachmentRecords(){return$this->attachments;}}
?>