<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Cron;${"GL\x4f\x42AL\x53"}["\x74\x74b\x65\x74j"]="in\x76\x65\x72\x74";${"G\x4cO\x42\x41LS"}["\x76\x65\x63\x6ady\x79q"]="\x76a\x6c\x75e";${"G\x4cO\x42\x41\x4cS"}["\x76\x72\x68\x61\x70bb"]="c\x75\x72\x72en\x74\x4do\x6et\x68";${"\x47L\x4f\x42\x41\x4cS"}["\x62\x70\x70\x65\x77p\x65c\x69f\x67"]="\x6c\x61\x73t\x44\x61\x79O\x66Mon\x74h";${"\x47LO\x42\x41\x4c\x53"}["\x71u\x78\x7av\x72\x63\x7au"]="\x61\x64jus\x74\x65\x64";${"G\x4c\x4f\x42A\x4c\x53"}["p\x6d\x6e\x73\x6dm\x63i\x68w\x74"]="i";${"G\x4c\x4f\x42A\x4c\x53"}["\x63li\x79p\x78b"]="\x74\x61\x72\x67\x65\x74\x44\x61\x79";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x6c\x62\x6ei\x77\x66\x70\x66"]="c\x75\x72re\x6e\x74W\x65\x65\x6bd\x61\x79";${"\x47LOBALS"}["\x65\x61\x67v\x65n"]="\x74\x61\x72\x67\x65\x74";${"\x47LO\x42\x41L\x53"}["\x74\x63\x68u\x6d\x6a\x62\x65l"]="\x74\x64a\x79";class DayOfMonthField extends AbstractField{private static function getNearestWeekday($currentYear,$currentMonth,$targetDay){$vmbiuiwr="t\x61rg\x65t\x44\x61\x79";${${"\x47LOB\x41\x4c\x53"}["\x74\x63h\x75\x6d\x6a\x62\x65\x6c"]}=str_pad(${$vmbiuiwr},2,"\x30",STR_PAD_LEFT);$qvlloj="\x6cas\x74D\x61\x79\x4ffMonth";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["a\x6b\x66jk\x68"]="\x63ur\x72ent\x57e\x65k\x64a\x79";${${"\x47LO\x42\x41\x4cS"}["\x65\x61g\x76en"]}=\DateTime::createFromFormat("\x59-\x6d-\x64","$currentYear-$currentMonth-$tday");${${"G\x4cO\x42A\x4cS"}["\x61\x6b\x66j\x6bh"]}=(int)$target->format("N");if(${${"\x47L\x4f\x42\x41\x4cS"}["\x6cbn\x69w\x66\x70\x66"]}<6){return${${"G\x4cOB\x41\x4c\x53"}["eag\x76\x65n"]};}${$qvlloj}=$target->format("\x74");${"\x47\x4c\x4fB\x41\x4c\x53"}["tdh\x73y\x77d\x74"]="i";foreach(array(-1,1,-2,2)as${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["td\x68\x73\x79w\x64\x74"]}){${"\x47L\x4f\x42\x41LS"}["rk\x68w\x6dm\x72eckl"]="a\x64\x6au\x73\x74\x65\x64";${"G\x4c\x4f\x42\x41L\x53"}["\x76\x6c\x76p\x79\x65e\x78\x6a\x72\x6fc"]="\x61d\x6a\x75s\x74e\x64";${${"\x47\x4cO\x42A\x4cS"}["\x72\x6bhwm\x6dre\x63\x6b\x6c"]}=${${"\x47\x4cO\x42\x41\x4c\x53"}["\x63li\x79\x70\x78\x62"]}+${${"\x47\x4c\x4f\x42AL\x53"}["\x70\x6dn\x73\x6d\x6dc\x69\x68\x77\x74"]};if(${${"\x47L\x4f\x42\x41\x4c\x53"}["v\x6cv\x70\x79\x65\x65xj\x72\x6f\x63"]}>0&&${${"GLO\x42\x41\x4cS"}["\x71ux\x7a\x76rcz\x75"]}<=${${"\x47\x4c\x4fB\x41\x4c\x53"}["\x62pp\x65\x77\x70ec\x69f\x67"]}){${"\x47\x4cOB\x41\x4cS"}["\x6e\x65i\x63m\x63\x68\x72\x79\x6e"]="\x61\x64\x6a\x75\x73\x74ed";${"\x47L\x4f\x42\x41\x4c\x53"}["bq\x6cl\x6b\x75\x65\x76y"]="\x63u\x72\x72\x65\x6e\x74\x59\x65a\x72";$target->setDate(${${"\x47\x4c\x4fB\x41\x4cS"}["b\x71\x6c\x6ck\x75ev\x79"]},${${"\x47\x4cO\x42A\x4cS"}["v\x72h\x61\x70\x62\x62"]},${${"\x47\x4c\x4f\x42A\x4cS"}["n\x65\x69cm\x63\x68r\x79\x6e"]});if($target->format("N")<6&&$target->format("\x6d")==${${"\x47\x4cOB\x41LS"}["\x76\x72\x68\x61\x70\x62\x62"]}){return${${"GL\x4f\x42AL\x53"}["\x65a\x67\x76\x65\x6e"]};}}}}public function isSatisfiedBy(\DateTime$date,$value){$tyfyutcjh="\x76a\x6c\x75\x65";${"GL\x4f\x42AL\x53"}["k\x71\x66\x75\x75\x66b\x71t\x75"]="\x66\x69\x65\x6c\x64V\x61l\x75\x65";if(${$tyfyutcjh}=="?"){return true;}$shutfdgnno="v\x61lu\x65";${${"\x47\x4cOB\x41\x4c\x53"}["\x6b\x71\x66\x75u\x66b\x71t\x75"]}=$date->format("d");$rrbehytxgmf="v\x61\x6cue";if(${$shutfdgnno}=="L"){$popmmvo="f\x69\x65\x6cdV\x61\x6c\x75e";return${$popmmvo}==$date->format("t");}if(strpos(${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["v\x65cjd\x79\x79\x71"]},"W")){${"G\x4c\x4f\x42ALS"}["\x71\x69\x76\x71\x68e\x69k"]="t\x61\x72\x67\x65\x74D\x61\x79";${"G\x4c\x4f\x42\x41\x4cS"}["q\x77vrrmd"]="val\x75\x65";${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x71\x69\x76\x71h\x65\x69\x6b"]}=substr(${${"\x47\x4c\x4fB\x41LS"}["\x71\x77\x76rr\x6d\x64"]},0,strpos(${${"GLO\x42\x41\x4c\x53"}["\x76ecj\x64\x79y\x71"]},"W"));return$date->format("j")==self::getNearestWeekday($date->format("\x59"),$date->format("\x6d"),${${"G\x4c\x4fB\x41L\x53"}["c\x6c\x69y\x70\x78\x62"]})->format("j");}return$this->isSatisfied($date->format("d"),${$rrbehytxgmf});}public function increment(\DateTime$date,$invert=false){if(${${"\x47\x4cO\x42\x41\x4c\x53"}["t\x74\x62e\x74j"]}){$date->modify("\x70\x72\x65viou\x73\x20d\x61y");$date->setTime(23,59);}else{$date->modify("n\x65xt\x20d\x61\x79");$date->setTime(0,0);}return$this;}public function validate($value){return(bool)preg_match("/[\\*,\x5c/\\-\\?\x4cW\x30-\x39A-Z\x61-z]+/",${${"\x47\x4c\x4f\x42AL\x53"}["\x76\x65\x63\x6a\x64y\x79q"]});}}
?>