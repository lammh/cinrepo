<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
${"\x47\x4cO\x42\x41\x4cS"}["\x72\x79\x69\x6c\x6c\x72\x7a\x6c"]="r\x65s\x75\x6c\x74";${"GL\x4fB\x41L\x53"}["v\x68lh\x7ac\x74\x71"]="lo\x61\x64O\x6c\x64er\x53\x65\x74\x74\x69\x6eg\x55\x69";${"\x47\x4cOB\x41LS"}["\x72c\x78\x63\x79\x75\x73\x73\x61\x6e\x69"]="\x6d\x6f\x64ul\x65\x4d\x6f\x64\x65l";${"\x47\x4cO\x42\x41\x4c\x53"}["\x6c\x72\x6csc\x74y"]="\x72\x65q\x75e\x73\x74";${"G\x4cO\x42\x41LS"}["q\x6f\x6bf\x74\x70\x65"]="\x71u\x61\x6c\x69\x66i\x65d\x4do\x64\x75\x6ceN\x61\x6d\x65";${"GL\x4f\x42\x41LS"}["g\x73\x73p\x70\x63\x78"]="\x72\x6fo\x74_\x64i\x72\x65c\x74\x6fr\x79";global$root_directory;require_once(${${"GL\x4f\x42\x41LS"}["g\x73sp\x70cx"]}."/m\x6fdul\x65\x73/Workf\x6co\x77\x32/a\x75\x74\x6fload_wf\x2e\x70h\x70");class Settings_Workflow2_Default_View extends Settings_Vtiger_Index_View{public function preProcessSettings(Vtiger_Request$request){$yrykpljnc="\x73\x71\x6c";$oerxenbygyao="\x76iewe\x72";${${"\x47LOB\x41L\x53"}["\x71\x6f\x6b\x66\x74\x70e"]}=$request->getModule(false);${$oerxenbygyao}=$this->getViewer(${${"\x47\x4c\x4fB\x41\x4c\x53"}["lr\x6c\x73c\x74\x79"]});$this->moduleName=$request->getModule();$this->qualifiedModuleName=$request->getModule(false);$this->settingsModel=Settings_Vtiger_Module_Model::getInstance($this->qualifiedModuleName);${${"GL\x4f\x42\x41\x4c\x53"}["\x72\x63\x78c\x79\x75\x73s\x61n\x69"]}=Vtiger_Module_Model::getInstance("W\x6fr\x6b\x66\x6co\x77\x32");$viewer->assign("V\x45\x52\x53I\x4fN",$moduleModel->version);$viewer->assign("VI\x45W",$request->get("v\x69\x65w"));$viewer->assign("MO\x44ULE",$this->moduleName);$viewer->assign("QU\x41LI\x46\x49ED_\x4d\x4fDU\x4c\x45",$this->qualifiedModuleName);$viewer->assign("\x4cO\x41\x44_O\x4cD",Settings_Vtiger_Index_View::${${"G\x4c\x4f\x42\x41L\x53"}["\x76\x68\x6ch\x7act\x71"]});global$adb;${$yrykpljnc}="SE\x4cEC\x54 *\x20F\x52\x4f\x4d \x76t\x69\x67\x65r_wf_re\x70\x6f\x73\x69t\x6fr\x79_ty\x70\x65\x73\n\x20 \x20\x20 \x20 \x20\x20\x20   \x20\x20 \x49\x4e\x4eE\x52 J\x4fI\x4e vt\x69\x67\x65\x72\x5f\x77f_type\x73\x20\x4fN (vti\x67er\x5f\x77f_\x74\x79\x70\x65s\x2et\x79\x70\x65 = vt\x69\x67e\x72\x5fw\x66_\x72epo\x73\x69t\x6fry_t\x79\x70e\x73.\x6ea\x6d\x65\x20AN\x44 \x76\x74ig\x65\x72_w\x66\x5ft\x79p\x65\x73\x2erep\x6f\x5f\x69\x64\x20\x3d vti\x67\x65\x72_wf\x5f\x72\x65p\x6f\x73i\x74\x6f\x72\x79_ty\x70\x65\x73.r\x65po\x73_\x69d)\n \x20\x20   \x20\x20        WH\x45R\x45\x20\x76\x74\x69\x67e\x72_\x77\x66\x5f\x72\x65p\x6f\x73i\x74\x6f\x72y_\x74\x79p\x65s\x2e\x76\x65\x72\x73i\x6fn\x20\x3e \x76\x74\x69ge\x72_w\x66_t\x79\x70\x65\x73.\x76\x65rs\x69\x6fn L\x49\x4d\x49\x54\x20\x31\n \x20    \x20  \x20\x20\x20\x20\x20 \x20";$sbyhkryhvqd="\x73ql";${${"GL\x4fB\x41L\x53"}["\x72y\x69\x6c\x6c\x72\x7a\x6c"]}=$adb->query(${$sbyhkryhvqd});if($adb->num_rows(${${"G\x4c\x4f\x42\x41\x4cS"}["\x72\x79i\x6c\x6c\x72\x7al"]})>0){$viewer->assign("\x41V\x41IL\x41B\x4cE_\x54AS\x4b_U\x50\x44\x41TE",true);}else{$viewer->assign("AVA\x49\x4c\x41BLE\x5fT\x41S\x4b_U\x50D\x41\x54E",false);}$viewer->view("I\x6e\x64e\x78\x4denuStar\x74.t\x70l",${${"\x47\x4cO\x42\x41\x4cS"}["q\x6f\x6b\x66\x74pe"]});}}
?>