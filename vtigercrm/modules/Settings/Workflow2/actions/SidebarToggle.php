<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
${"\x47\x4c\x4f\x42\x41\x4cS"}["\x6f\x67\x6ex\x6a\x61\x76\x74\x61"]="\x72\x65\x71\x75\x65s\x74";${"\x47\x4c\x4f\x42A\x4c\x53"}["\x72\x7a\x67\x6d\x61c\x6fc"]="\x6ci\x6ek\x69\x64";${"G\x4c\x4f\x42\x41L\x53"}["\x78dyqgn\x78\x77n"]="m\x6fd\x65";${"\x47\x4c\x4f\x42\x41LS"}["d\x73r\x66\x73\x6dv\x79"]="\x72\x65sul\x74";${"\x47\x4cOB\x41\x4c\x53"}["\x62\x78w\x67a\x62\x79"]="\x74\x61\x62i\x64";${"\x47\x4cO\x42AL\x53"}["rnhwf\x71\x6a\x79x"]="sql";${"\x47\x4cO\x42\x41LS"}["\x68\x76\x61\x6d\x75\x68\x62n\x6ew\x75"]="\x61\x64\x62";${"\x47\x4c\x4f\x42\x41LS"}["h\x79\x6d\x79l\x63\x6fg\x6c\x62\x6c"]="\x72\x6f\x6ft\x5f\x64\x69r\x65\x63\x74o\x72y";global$root_directory;require_once(${${"\x47LO\x42\x41L\x53"}["\x68y\x6d\x79l\x63o\x67\x6c\x62\x6c"]}."/\x6do\x64\x75\x6c\x65\x73/\x57o\x72\x6bf\x6c\x6f\x77\x32/\x61u\x74olo\x61d_w\x66.\x70h\x70");class Settings_Workflow2_SidebarToggle_Action extends Settings_Vtiger_Basic_Action{public function process(Vtiger_Request$request){${"G\x4c\x4f\x42\x41\x4c\x53"}["ek\x6fscz\x73y"]="t\x61\x62\x69\x64";${${"\x47\x4c\x4f\x42A\x4c\x53"}["e\x6b\x6fs\x63\x7a\x73\x79"]}=getTabId($request->get("\x77o\x72\x6bf\x6c\x6f\x77\x4do\x64u\x6ce"));${"G\x4cO\x42AL\x53"}["\x6d\x68\x75ir\x79\x6d\x74\x6f\x79"]="\x74\x61\x62\x69d";${${"\x47\x4cO\x42\x41L\x53"}["\x68\x76\x61\x6d\x75\x68\x62\x6e\x6ewu"]}=\PearDatabase::getInstance();if($request->get("\x68id\x64\x65n")==true){ob_start();}if(!empty(${${"\x47\x4c\x4fBA\x4cS"}["mhui\x72\x79\x6d\x74\x6f\x79"]})){${"\x47\x4c\x4f\x42\x41\x4cS"}["\x6futaj\x62zy\x6f"]="\x72\x65\x73u\x6c\x74";${${"GLOBA\x4c\x53"}["\x72n\x68w\x66\x71\x6a\x79\x78"]}="\x53E\x4c\x45C\x54 \x6cink\x69\x64\x20FR\x4f\x4d v\x74\x69\x67er_\x6ci\x6ek\x73 \x57H\x45\x52E \x6c\x69nk\x74\x79\x70\x65 =\x20'D\x45\x54\x41\x49\x4c\x56I\x45WS\x49\x44E\x42A\x52W\x49D\x47ET' \x41N\x44 \x6c\x69\x6ekla\x62e\x6c\x20\x3d\x20\x27\x57\x6frk\x66\x6c\x6fw\x73\x27\x20A\x4eD\x20t\x61\x62id\x20=\x20".${${"\x47\x4c\x4f\x42ALS"}["\x62\x78\x77\x67\x61\x62\x79"]};${${"\x47\x4c\x4fB\x41\x4cS"}["\x64\x73\x72\x66\x73\x6d\x76\x79"]}=$adb->query(${${"G\x4c\x4f\x42\x41\x4cS"}["rn\x68wfq\x6a\x79\x78"]});${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x78\x64y\x71g\x6e\x78w\x6e"]}=$request->get("MO\x44\x45");$xyxnkgw="\x6do\x64e";if(${${"\x47L\x4fBAL\x53"}["x\x64y\x71g\x6e\x78w\x6e"]}=="\x41DD"&&$adb->num_rows(${${"\x47\x4c\x4fB\x41LS"}["o\x75t\x61\x6a\x62zyo"]})>1){${"GL\x4f\x42\x41\x4c\x53"}["\x63\x6e\x75bq\x70s\x66r\x74\x66"]="\x74a\x62id";$adb->query("DELET\x45\x20\x46ROM vti\x67er_\x6ci\x6e\x6bs WH\x45R\x45\x20(\x6c\x69\x6ekty\x70e \x3d\x20\x27DET\x41\x49\x4cV\x49\x45\x57SID\x45BARW\x49D\x47ET'\x20OR \x6c\x69nkt\x79\x70e\x20\x3d\x20\x27\x4cI\x53\x54V\x49EWS\x49D\x45B\x41\x52\x57\x49\x44\x47E\x54') A\x4e\x44\x20l\x69\x6ek\x6c\x61\x62\x65\x6c\x20=\x20'\x57\x6f\x72kfl\x6f\x77\x73\x27 AND\x20tabi\x64\x20= ".${${"\x47L\x4f\x42\x41\x4c\x53"}["\x62\x78\x77\x67\x61\x62\x79"]},true);${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x72\x6e\x68w\x66\x71jy\x78"]}="S\x45L\x45\x43T\x20\x6c\x69nk\x69\x64\x20F\x52OM \x76tiger_li\x6e\x6bs \x57\x48\x45R\x45 \x6cinkty\x70\x65\x20\x3d\x20\x27D\x45TAI\x4cVIE\x57SI\x44\x45\x42\x41\x52\x57IDGE\x54\x27\x20\x41N\x44 \x6c\x69\x6e\x6bl\x61\x62\x65l =\x20'Wo\x72\x6bflow\x73'\x20\x41\x4eD\x20ta\x62id\x20\x3d ".${${"\x47\x4c\x4fB\x41\x4c\x53"}["\x63\x6e\x75bqp\x73frtf"]};${${"GLO\x42\x41L\x53"}["\x64\x73\x72\x66\x73\x6dvy"]}=$adb->query(${${"G\x4c\x4fB\x41LS"}["\x72\x6ehwf\x71\x6a\x79x"]});}if(${${"G\x4c\x4fBA\x4c\x53"}["\x78\x64\x79\x71g\x6e\x78w\x6e"]}=="\x44\x45\x4c"||(empty(${$xyxnkgw})&&$adb->num_rows(${${"GL\x4fB\x41\x4cS"}["ds\x72\x66\x73m\x76y"]})>0)){${"\x47\x4c\x4f\x42\x41\x4c\x53"}["j\x79\x77gr\x69\x63c"]="\x74\x61\x62\x69d";$lxcddedp="\x6do\x64e";${$lxcddedp}="D\x45L";$adb->query("\x44E\x4cETE\x20F\x52O\x4d\x20\x76\x74ig\x65r\x5flin\x6b\x73\x20\x57H\x45RE (\x6ci\x6e\x6b\x74y\x70e = \x27\x44E\x54AILVI\x45\x57\x53I\x44\x45B\x41R\x57IDGET' O\x52\x20lin\x6bt\x79\x70e = '\x4cISTVIEWSI\x44EBA\x52WI\x44\x47E\x54\x27)\x20\x41\x4e\x44 \x6cin\x6b\x6ca\x62e\x6c =\x20'\x57\x6fr\x6b\x66\x6co\x77\x73' \x41\x4eD\x20t\x61b\x69\x64\x20= ".${${"\x47LO\x42A\x4c\x53"}["\x6a\x79\x77g\x72\x69c\x63"]},true);echo getTranslatedString("\x4c\x42L_A\x43T\x49VAT\x45\x5f\x53ID\x45B\x41R","\x53et\x74i\x6egs:\x57o\x72kflo\x77\x32");}elseif($adb->num_rows(${${"G\x4c\x4f\x42\x41\x4c\x53"}["\x64\x73r\x66\x73mvy"]})==0){${"GLOB\x41L\x53"}["\x6fc\x6f\x74\x62v"]="\x6d\x6f\x64\x65";${${"\x47\x4c\x4f\x42\x41L\x53"}["\x6f\x63ot\x62v"]}="ADD";${"GL\x4f\x42\x41\x4c\x53"}["\x68\x64l\x78\x68r\x64\x72ll\x75r"]="li\x6e\x6b\x69d";${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x72\x7a\x67\x6d\x61co\x63"]}=$adb->getUniqueID("\x76ti\x67\x65\x72_\x6ci\x6e\x6b\x73");$adb->query("INS\x45\x52T\x20INT\x4f\x20v\x74ig\x65r_\x6c\x69\x6ek\x73 S\x45\x54 \x6c\x69\x6e\x6b\x69d\x20\x3d \x27".${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x72z\x67\x6d\x61\x63\x6f\x63"]}."',li\x6e\x6b\x74\x79pe\x20= \x27D\x45\x54\x41\x49\x4c\x56\x49\x45W\x53\x49DEBARW\x49DGET', \x6c\x69n\x6bl\x61b\x65\x6c\x20= 'W\x6fr\x6b\x66\x6c\x6f\x77s\x27,\x20t\x61\x62\x69d =\x20".${${"\x47L\x4fB\x41LS"}["\x62x\x77\x67\x61\x62\x79"]}.",\x6c\x69\x6ek\x75\x72l\x3d'"."\x6do\x64ul\x65\x3d\x57\x6f\x72\x6bfl\x6f\x77\x32\x26\x76\x69\x65w=\x53i\x64e\x62arW\x69\x64ge\x74"."\x27",true);${${"\x47L\x4f\x42\x41\x4cS"}["rz\x67\x6d\x61c\x6f\x63"]}=$adb->getUniqueID("\x76tige\x72_link\x73");$qcahtlck="\x74\x61b\x69\x64";$adb->query("I\x4e\x53\x45\x52T\x20\x49N\x54O\x20\x76ti\x67er\x5fl\x69n\x6bs\x20\x53E\x54\x20\x6c\x69\x6eki\x64 =\x20\x27".${${"G\x4cO\x42\x41\x4c\x53"}["\x68\x64\x6cx\x68\x72dr\x6c\x6cu\x72"]}."\x27,l\x69nkty\x70e =\x20\x27L\x49S\x54V\x49EW\x53\x49\x44EBA\x52W\x49D\x47\x45\x54\x27, li\x6ek\x6cab\x65l \x3d '\x57orkf\x6c\x6f\x77s',\x20t\x61\x62id \x3d\x20".${$qcahtlck}.",\x6c\x69n\x6burl=\x27"."m\x6fd\x75le=\x57\x6f\x72k\x66\x6co\x77\x32&\x73\x72c_m\x6f\x64ule\x3d".$request->get("wor\x6bf\x6cowM\x6f\x64ule")."&v\x69\x65\x77\x3dS\x69\x64e\x62a\x72Li\x73tW\x69\x64\x67e\x74"."\x27",true);echo getTranslatedString("\x4c\x42L_\x44EA\x43TIV\x41\x54\x45_\x53ID\x45\x42\x41\x52","S\x65t\x74in\x67s:\x57\x6f\x72\x6b\x66\x6cow\x32");}}if($request->get("\x68i\x64\x64\x65n")==true){ob_end_clean();}if($request->get("\x77\x6frk\x66\x6cow\x4d\x6f\x64ule")=="\x43alenda\x72"){${"\x47\x4cO\x42\x41L\x53"}["\x6a\x72\x70y\x71\x6f\x79\x76doz"]="\x72\x65\x71\x75e\x73t2";${${"GL\x4fB\x41\x4c\x53"}["\x6a\x72\x70\x79\x71\x6f\x79\x76\x64\x6f\x7a"]}=${${"GL\x4fB\x41L\x53"}["\x6f\x67\x6e\x78\x6a\x61v\x74\x61"]};$request->set("\x4d\x4fD\x45",${${"\x47\x4c\x4f\x42AL\x53"}["x\x64\x79\x71\x67n\x78wn"]});$request->set("\x77\x6frkflow\x4dod\x75\x6c\x65","\x45ven\x74s");$request->set("\x68\x69\x64de\x6e",true);$this->process(${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x6f\x67\x6e\x78jav\x74a"]});}}}
?>