<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
${"\x47\x4c\x4f\x42A\x4c\x53"}["n\x6clzy\x69"]="\x62l\x6f\x63\x6b\x49\x44";${"GLO\x42\x41\x4cS"}["\x70z\x6a\x67\x73\x61i"]="\x6c\x65\x66t";${"\x47L\x4f\x42\x41\x4c\x53"}["b\x68\x74\x6b\x70upur"]="\x6d\x6f\x64\x75\x6c\x65_\x6e\x61m\x65";${"\x47\x4c\x4fBAL\x53"}["\x63\x6bv\x63\x64\x72\x6e\x79pg\x71"]="\x77or\x6b\x66\x6c\x6fw\x49\x44";${"G\x4cOBA\x4cS"}["\x6acv\x65\x66\x66p\x79"]="\x73e\x74\x74i\x6e\x67\x73M\x6f\x64e\x6c";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x78\x76z\x67\x73\x73"]="\x72o\x6f\x74\x5fd\x69\x72\x65\x63t\x6f\x72\x79";global$root_directory;require_once(${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x78\x76z\x67\x73\x73"]}."/mo\x64ule\x73/W\x6fr\x6bflow\x32/\x61ut\x6f\x6co\x61d_w\x66.p\x68\x70");class Settings_Workflow2_PersonAdd_Action extends Settings_Vtiger_Basic_Action{public function process(Vtiger_Request$request){${"\x47\x4cO\x42A\x4cS"}["\x75\x6d\x62\x6eb\x6ay\x6c\x67\x75\x68\x65"]="a\x64\x62";$nceczpa="\x77or\x6b\x66\x6cow\x49D";$xdvdbfu="\x6c\x65\x66\x74";$wrdnbhm="\x73\x71\x6c";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x6c\x66\x63\x71\x73\x6e\x72\x69\x72\x75"]="\x73\x71\x6c";$kxgeopx="t\x6fp";${"\x47\x4c\x4f\x42AL\x53"}["y\x75s\x6ecc\x66b\x6c\x6d"]="le\x66\x74";${${"\x47LOB\x41\x4cS"}["u\x6d\x62\x6e\x62j\x79l\x67\x75\x68e"]}=PearDatabase::getInstance();${${"\x47L\x4f\x42\x41LS"}["\x6a\x63\x76e\x66\x66\x70y"]}=Settings_Vtiger_Module_Model::getInstance("Se\x74ting\x73:Wor\x6bflow\x32");${${"\x47\x4c\x4fB\x41\x4cS"}["\x63\x6bvcd\x72\x6e\x79\x70\x67\x71"]}=(int)$request->get("w\x6frk\x66l\x6fw");$bopymnci="\x74\x6f\x70";${${"\x47LO\x42AL\x53"}["\x62\x68\x74k\x70\x75\x70\x75r"]}=$_POST["\x6d\x6f\x64ule\x5f\x6eam\x65"];${"G\x4c\x4f\x42\x41\x4c\x53"}["\x64\x71f\x61\x6f\x62y\x65"]="\x77\x6f\x72k\x66\x6c\x6f\x77\x49\x44";list(${$kxgeopx},${$xdvdbfu})=$settingsModel->getFreeBlockPos(${$nceczpa});${"\x47\x4c\x4f\x42AL\x53"}["ev\x6a\x62\x68\x62u"]="t\x6f\x70";${${"\x47\x4c\x4f\x42A\x4cS"}["\x6c\x66\x63\x71sn\x72\x69\x72u"]}="\x49N\x53\x45\x52T\x20IN\x54O\x20\x76\x74i\x67er_\x77\x66\x70\x5f\x6f\x62\x6aec\x74s \x53\x45T\n \x20   \x20\x20  \x20  w\x6fr\x6b\x66\x6c\x6f\x77\x5f\x69\x64\x20\x3d\x20".${${"GL\x4f\x42AL\x53"}["\x64q\x66\x61\x6f\x62\x79\x65"]}.",\n\x20   \x20 \x20 \x20 \x20 x\x20\x3d\x20\x27".${${"G\x4c\x4f\x42\x41\x4c\x53"}["\x70z\x6a\x67\x73\x61\x69"]}."\x27,\n \x20    \x20\x20 \x20\x20 \x79\x20= '".${$bopymnci}."',\n    \x20\x20\x20\x20  \x20 mo\x64ule\x5f\x6eame\x20\x3d\x20\x27".${${"GL\x4fBAL\x53"}["b\x68\x74\x6bp\x75p\x75\x72"]}."\x27\n \x20  \x20   ";$adb->query(${$wrdnbhm});${${"GL\x4f\x42\x41\x4cS"}["\x6e\x6cl\x7ayi"]}=$adb->getLastInsertID();echo json_encode(array("\x65le\x6dent\x5f\x69\x64"=>"\x70\x65r\x73\x6f\x6e\x5f\x5f".${${"G\x4cOBAL\x53"}["nll\x7ay\x69"]},"t\x6f\x70\x50\x6fs"=>${${"\x47\x4cOBA\x4c\x53"}["\x65\x76\x6a\x62\x68\x62u"]},"lef\x74\x50os"=>${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x79\x75s\x6ec\x63\x66bl\x6d"]},));}}
?>