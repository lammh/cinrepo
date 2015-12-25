<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
${"\x47\x4c\x4fBAL\x53"}["\x63wd\x63\x66\x67\x6e\x6c"]="\x72\x65p\x6f_i\x64";${"G\x4cOBAL\x53"}["\x66\x64\x69b\x6e\x64\x75"]="roo\x74\x5fdi\x72ec\x74o\x72y";global$root_directory;require_once(${${"\x47\x4c\x4fB\x41LS"}["\x66\x64i\x62\x6e\x64\x75"]}."/mod\x75l\x65\x73/\x57o\x72k\x66\x6c\x6f\x772/\x61\x75tol\x6f\x61\x64_wf\x2ep\x68p");class Settings_Workflow2_RefreshTypes_Action extends Settings_Vtiger_Basic_Action{public function process(Vtiger_Request$request){${"GLO\x42\x41L\x53"}["n\x62\x6d\x77\x72\x65\x78"]="\x72e\x70o";$hfrnigx="\x72\x65\x70o_\x69\x64";${$hfrnigx}=(int)$request->get("r\x65\x70\x6f\x5f\x69d");${${"\x47L\x4f\x42\x41\x4c\x53"}["\x6e\x62\x6d\x77\x72e\x78"]}=new\Workflow\Repository(${${"\x47\x4cOB\x41\x4c\x53"}["\x63wdc\x66gn\x6c"]});if($request->get("mo\x64\x65")=="\x6e\x65\x77"){$repo->installAll(\Workflow\Repository::INSTALL_NEW);}else{$repo->installAll(\Workflow\Repository::INSTALL_ONLY_UPDATES);}}}
?>