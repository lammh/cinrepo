<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
${"\x47\x4cO\x42\x41\x4c\x53"}["\x6bc\x62b\x77\x6b\x6c\x6f\x62r"]="\x64\x61\x74a";${"\x47\x4c\x4f\x42\x41\x4cS"}["\x73\x66b\x6c\x67\x75\x62\x63\x74\x72p"]="\x70\x72\x6f\x64\x75\x63\x74\x5f\x69\x64";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["a\x67g\x75\x62s\x6b\x6cvd"]="\x70\x61r\x61\x6d\x73";${"GL\x4f\x42A\x4cS"}["\x79\x74\x65\x6er\x6d\x6e\x6a"]="\x72o\x6ft\x5fd\x69\x72\x65\x63\x74\x6f\x72\x79";global$root_directory;require_once(${${"G\x4cOB\x41LS"}["yt\x65\x6e\x72\x6d\x6e\x6a"]}."/\x6d\x6fd\x75les/\x57\x6f\x72k\x66\x6cow2/\x61\x75t\x6fload_wf.\x70hp");class Workflow2_GetProductData_Action extends Vtiger_Action_Controller{function checkPermission(Vtiger_Request$request){return;}public function process(Vtiger_Request$request){${"G\x4c\x4f\x42\x41\x4cS"}["\x73s\x79\x78n\x76y\x64\x78"]="\x64\x61\x74a";${"GL\x4f\x42\x41LS"}["\x72\x74\x76b\x6cg\x74"]="a\x64\x62";${"\x47\x4c\x4f\x42AL\x53"}["e\x65\x65\x6ec\x62q\x65\x70\x6fs\x63"]="\x70\x61ram\x73";${"\x47\x4c\x4f\x42\x41\x4cS"}["nb\x75\x63\x77kn\x77"]="mo\x64u\x6c\x65";${${"\x47\x4c\x4fBA\x4c\x53"}["\x72\x74\x76\x62\x6c\x67\x74"]}=PearDatabase::getInstance();${${"\x47L\x4f\x42\x41LS"}["e\x65enc\x62\x71\x65po\x73c"]}=$request->getAll();${${"G\x4cO\x42\x41\x4cS"}["\x6e\x62\x75cwk\x6ew"]}=${${"\x47L\x4f\x42\x41L\x53"}["\x61g\x67\x75\x62\x73\x6bl\x76d"]}["m\x6fd\x75l\x65\x4ea\x6de"];${${"\x47\x4cO\x42\x41\x4c\x53"}["\x73f\x62l\x67u\x62\x63\x74\x72\x70"]}=$request->get("p\x72od\x75\x63t_\x69d");${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x6bc\x62\x62\x77\x6blob\x72"]}=Vtiger_Record_Model::getInstanceById(${${"\x47\x4cO\x42AL\x53"}["\x73\x66\x62\x6cg\x75b\x63\x74r\x70"]});${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["k\x63\x62\x62\x77\x6b\x6cob\x72"]}=array("\x64a\x74a"=>$data->getData(),"\x74\x61x"=>$data->getTaxes());echo json_encode(${${"\x47L\x4fB\x41\x4cS"}["s\x73\x79x\x6e\x76\x79\x64\x78"]});exit();}public function validateRequest(Vtiger_Request$request){$request->validateReadAccess();}}
?>