<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Workflow\Preset;${"G\x4cO\x42\x41\x4c\x53"}["\x6e\x62\x6c\x66\x6e\x64kf\x75"]="\x73\x63\x72\x69\x70\x74";${"G\x4c\x4f\x42\x41\x4c\x53"}["be\x6e\x61i\x66\x74\x6b\x76"]="a\x76\x61\x69\x6c\x61\x62\x6ce\x46\x69\x6c\x65\x41\x63\x74i\x6fns";${"\x47\x4cOBA\x4c\x53"}["\x72u\x75j\x6f\x77\x73"]="\x77\x69\x64\x74\x68";${"\x47\x4cO\x42ALS"}["\x6b\x6f\x73j\x6e\x76\x6e\x71\x74"]="\x76i\x65w\x65r";${"\x47L\x4fBAL\x53"}["i\x6b\x69m\x67h\x67m\x6d\x66"]="\x64\x61\x74\x61";use\Workflow\VtUtils;use\Workflow\VTEntity;class FileActions extends\Workflow\Preset{protected$_JSFiles=array('FileActions.js');protected$_fromFields=null;public function beforeSave($data){return${${"\x47LO\x42A\x4cS"}["\x69k\x69\x6dg\x68\x67\x6d\x6d\x66"]};}public function beforeGetTaskform($transferData){${"G\x4c\x4f\x42\x41\x4c\x53"}["\x6ew\x6bl\x79\x76"]="\x74r\x61\x6e\x73fe\x72\x44\x61\x74a";$emzpvn="\x61v\x61il\x61\x62\x6ceFi\x6c\x65\x41c\x74i\x6fns";$dboktonzzwl="a\x64b";global$current_user;${$dboktonzzwl}=\PearDatabase::getInstance();$washibvkmmrl="data";list(${$washibvkmmrl},${${"G\x4c\x4f\x42A\x4c\x53"}["\x6bo\x73\x6a\x6e\x76nqt"]})=${${"G\x4cO\x42\x41\x4c\x53"}["\x6e\x77\x6bl\x79v"]};${$emzpvn}=\Workflow\FileAction::getAvailableActions($this->parameter["mo\x64u\x6c\x65"]);if(empty($this->parameter["\x77i\x64th"])){${"GL\x4fB\x41\x4c\x53"}["p\x6e\x6co\x71n\x6fxg\x68\x6d\x63"]="\x77\x69\x64\x74\x68";${${"GLO\x42\x41\x4c\x53"}["\x70\x6el\x6f\x71\x6e\x6f\x78\x67\x68m\x63"]}=600;}else{$qdistlen="\x77\x69\x64\x74h";${$qdistlen}=intval($this->parameter["\x77i\x64t\x68"]);}$gsuayydy="\x74\x72\x61\x6e\x73\x66\x65\x72\x44\x61t\x61";$viewer->assign("\x66\x69\x65l\x64",$this->field);$viewer->assign("\x77i\x64th",${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x72\x75\x75\x6a\x6f\x77s"]});$viewer->assign("\x61\x76\x61il\x61\x62l\x65\x46\x69le\x41c\x74io\x6e\x73",${${"\x47LO\x42\x41\x4c\x53"}["\x62\x65\x6ea\x69\x66\x74\x6b\x76"]});$viewer->assign("fi\x6ceac\x74\x69\x6fn\x73_".$this->field,$viewer->fetch("m\x6f\x64ul\x65s/S\x65\x74\x74\x69n\x67\x73/\x57\x6f\x72kf\x6c\x6f\x772/\x68\x65\x6cpers/Fi\x6c\x65\x41\x63\x74i\x6f\x6es\x2et\x70l"));$this->addInlineJS(${${"\x47\x4cO\x42A\x4c\x53"}["n\x62\x6c\x66nd\x6b\x66\x75"]});return${$gsuayydy};}}
?>