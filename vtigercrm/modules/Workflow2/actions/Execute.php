<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
${"GLOBA\x4cS"}["\x6d\x6azlh\x6as\x67\x66w\x66"]="\x72ed\x69\x72\x65c\x74\x69o\x6e";${"\x47\x4cOBALS"}["owrei\x68b"]="\x72\x65s\x75l\x74";${"G\x4cO\x42A\x4c\x53"}["\x74x\x6ev\x64yi\x67\x75\x79"]="\x65\x6e\x61\x62l\x65Err\x6f\x72";${"\x47\x4c\x4f\x42\x41L\x53"}["xp\x62\x61\x6e\x6c\x6aq\x6d"]="\x76\x61lu\x65\x73";${"G\x4c\x4fB\x41\x4c\x53"}["n\x72j\x61\x6b\x71\x6b\x78\x66\x72\x6a"]="t\x6d\x70\x53tart\x66\x69el\x64\x73";${"\x47L\x4f\x42\x41\x4cS"}["\x79e\x6c\x6dd\x76\x74\x6eoz"]="c\x6f\x6etex\x74";${"\x47L\x4f\x42A\x4c\x53"}["\x78\x72\x66e\x6b\x6brn\x73\x64\x73"]="\x76\x61\x6c\x75\x65";${"G\x4c\x4f\x42\x41\x4c\x53"}["e\x62kw\x75u\x77\x62\x6a"]="\x6bey";${"\x47\x4c\x4f\x42\x41LS"}["\x78\x68\x64\x71\x70\x75u\x73\x68\x79xe"]="\x73t\x61\x72\x74\x66i\x65\x6cd\x73";${"\x47\x4cO\x42\x41\x4c\x53"}["\x77x\x69\x61\x62\x64\x64\x73\x71\x68"]="\x75\x73er";${"\x47\x4c\x4f\x42A\x4c\x53"}["\x69\x72\x7a\x76\x76\x76\x75\x6f\x75b"]="\x72\x6f\x77";${"G\x4cOB\x41\x4cS"}["\x77s\x65t\x79gx"]="wo\x72\x6b\x66\x6co\x77";${"G\x4c\x4fB\x41\x4c\x53"}["\x73w\x68\x79\x74dpb\x71a\x64"]="cu\x5f\x6d\x6fd\x65\x6c";${"\x47\x4c\x4f\x42AL\x53"}["\x68\x6a\x63\x6eq\x79d\x66k"]="ad\x62";${"GL\x4fBALS"}["w\x66\x72\x79o\x7a\x77\x74\x62"]="\x72\x6f\x6f\x74\x5fdi\x72\x65\x63t\x6f\x72\x79";use\Workflow\VTEntity;use\Workflow\VTTemplate;global$root_directory;require_once(${${"\x47\x4c\x4f\x42\x41LS"}["\x77fr\x79o\x7a\x77\x74b"]}."/\x6d\x6fdu\x6ces/\x57o\x72\x6b\x66lo\x772/autol\x6f\x61d_wf\x2ep\x68p");class Workflow2_Execute_Action extends Vtiger_Action_Controller{function checkPermission(Vtiger_Request$request){return true;}public function process(Vtiger_Request$request){${"G\x4c\x4f\x42\x41L\x53"}["\x63\x68\x63\x65\x76\x62jm"]="\x70\x61\x72\x61ms";${"G\x4c\x4fB\x41\x4c\x53"}["\x75\x64d\x6bk\x6dg\x6f\x6f"]="\x63u\x72\x72\x65n\x74\x5f\x75\x73\x65\x72";$hcnexc="\x73ql";$qdewlwhevj="\x61ll\x6fw_\x70a\x72a\x6c\x6c\x65\x6c";${${"GL\x4fB\x41LS"}["\x68\x6acn\x71\x79\x64\x66\x6b"]}=PearDatabase::getInstance();$ldobmlfv="\x72e\x73u\x6c\x74";${${"GLOB\x41\x4cS"}["c\x68\x63\x65\x76bj\x6d"]}=$request->getAll();$ythfmxwru="r\x65\x73\x75\x6c\x74";${"\x47\x4cO\x42\x41\x4cS"}["\x7a\x69\x79\x71\x68\x6e\x77\x62\x6b\x67h"]="\x73ta\x72\x74\x66\x69e\x6cd\x73";$etbpjhd="re\x64i\x72ec\x74i\x6f\x6e";${${"\x47L\x4f\x42\x41LS"}["\x75d\x64\x6b\x6b\x6d\x67\x6f\x6f"]}=${${"\x47\x4cOBALS"}["\x73\x77h\x79\x74\x64\x70\x62\x71a\x64"]}=Users_Record_Model::getCurrentUserModel();$poabnfyvn="ro\x77";$islvxox="\x77o\x72\x6b\x66\x6c\x6f\x77";${$qdewlwhevj}=$request->get("a\x6c\x6c\x6f\x77_p\x61\x72\x61llel","0");${$islvxox}=(int)$request->get("w\x6f\x72\x6b\x66\x6cow");$jjvyqosqc="\x73\x71l";${"\x47\x4c\x4f\x42\x41L\x53"}["\x6e\x6f\x6e\x6dm\x73\x70\x6e"]="\x72\x65\x73\x75\x6c\x74";${${"GLO\x42\x41\x4cS"}["z\x69y\x71\x68\x6e\x77\x62k\x67\x68"]}=$request->get("sta\x72\x74\x66\x69\x65lds");${$jjvyqosqc}="\x53E\x4c\x45\x43T\x20*\x20FR\x4fM \x76t\x69\x67e\x72\x5f\x77f_\x73e\x74\x74\x69ngs\x20W\x48ERE\x20i\x64\x20\x3d\x20? \x41N\x44 ac\x74\x69v\x65 =\x201";${$ythfmxwru}=$adb->pquery(${$hcnexc},array(${${"GLO\x42\x41\x4c\x53"}["\x77\x73e\x74y\x67\x78"]}));while(${$poabnfyvn}=$adb->fetch_array(${${"GLOBA\x4c\x53"}["\x6eo\x6e\x6d\x6ds\x70\x6e"]})){${"GL\x4f\x42\x41\x4cS"}["\x77\x72pb\x6b\x69\x72\x67"]="u\x73\x65\x72";$yropymdmfby="r\x6f\x77";if(${${"\x47\x4c\x4fBAL\x53"}["\x69\x72\x7av\x76vu\x6fu\x62"]}["e\x78\x65c\x75\x74io\x6e\x5fu\x73e\x72"]=="\x30"){$iivpozwkudif="\x72o\x77";${$iivpozwkudif}["e\x78e\x63u\x74\x69on_\x75se\x72"]=$current_user->id;}${"\x47L\x4f\x42A\x4cS"}["\x78\x79\x62\x71\x75gqfbv"]="u\x73\x65r";$xfxryhn="a\x6clo\x77_\x70a\x72\x61l\x6ce\x6c";${${"\x47\x4cO\x42\x41L\x53"}["\x78y\x62q\x75\x67\x71\x66\x62v"]}=new Users();$user->retrieveCurrentUserInfoFromFile(${${"G\x4c\x4f\x42A\x4c\x53"}["\x69\x72\x7a\x76\x76v\x75\x6f\x75\x62"]}["e\x78e\x63uti\x6f\x6e_u\x73er"]);$jvljtkcm="o\x62\x6a\x57o\x72\x6b\x66\x6co\x77";VTEntity::setUser(${${"G\x4cO\x42\x41L\x53"}["\x77x\x69ab\x64\x64s\x71\x68"]});${$jvljtkcm}=new\Workflow\Main(${${"GL\x4fB\x41\x4cS"}["i\x72\x7av\x76v\x75o\x75\x62"]}["i\x64"],false,${${"\x47\x4c\x4f\x42\x41LS"}["w\x72\x70bk\x69\x72\x67"]});$rlxpjpocoqi="c\x6f\x6ete\x78t";${"\x47\x4c\x4fB\x41\x4c\x53"}["\x6a\x6b\x62\x64\x71\x66\x71\x72\x63\x68\x6c"]="\x63o\x6e\x74e\x78\x74";$objWorkflow->setExecutionTrigger("W\x46\x32\x5fM\x41\x4e\x55\x45L\x4c");if(${$xfxryhn}==false&&$objWorkflow->isRunning($_POST["crmi\x64"])){continue;}${${"\x47\x4c\x4fB\x41\x4c\x53"}["\x6a\x6bb\x64\x71\x66\x71\x72c\x68\x6c"]}=VTEntity::getForId(intval($_POST["crmid"]));if(!empty(${$yropymdmfby}["\x73ta\x72\x74\x66\x69\x65\x6c\x64\x73"])&&empty(${${"\x47\x4c\x4fBA\x4c\x53"}["\x78h\x64q\x70\x75\x75\x73\x68\x79\x78\x65"]})){$jjwfklwbe="st\x61\x72\x74f\x69elds";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x6e\x63\x70s\x73f\x6a"]="v\x61\x6cu\x65";${"G\x4c\x4f\x42A\x4c\x53"}["\x62\x76\x7a\x6ekhwud\x6a\x76"]="\x72\x6fw";${"GLO\x42\x41\x4cS"}["\x62\x76o\x69\x73e"]="\x73t\x61r\x74\x66\x69\x65\x6c\x64\x73";${$jjwfklwbe}=unserialize(html_entity_decode(${${"\x47L\x4f\x42A\x4cS"}["\x62\x76\x7a\x6ek\x68\x77u\x64j\x76"]}["s\x74\x61\x72\x74f\x69e\x6cds"]));foreach(${${"G\x4c\x4f\x42\x41LS"}["\x62\x76\x6f\x69\x73\x65"]} as${${"\x47\x4cO\x42\x41\x4cS"}["\x65b\x6b\x77u\x75\x77\x62\x6a"]}=>${${"\x47L\x4f\x42\x41\x4cS"}["n\x63\x70\x73\x73f\x6a"]}){$vbsuvtx="\x76\x61\x6c\x75\x65";$umnyphuhq="\x6b\x65\x79";$wvynkcpns="\x76\x61\x6c\x75\x65";${$wvynkcpns}["def\x61ul\x74"]=trim(VTTemplate::parse(${${"\x47\x4cOB\x41L\x53"}["x\x72\x66e\x6b\x6b\x72\x6e\x73\x64\x73"]}["d\x65f\x61\x75l\x74"],${${"\x47\x4c\x4f\x42AL\x53"}["\x79\x65lm\x64\x76\x74n\x6f\x7a"]}));${${"\x47\x4c\x4f\x42AL\x53"}["x\x68d\x71\x70\x75\x75sh\x79x\x65"]}[${$umnyphuhq}]=${$vbsuvtx};}die(json_encode(array("r\x65\x73u\x6ct"=>"sta\x72\x74fi\x65\x6cds","\x77\x6f\x72kf\x6c\x6f\x77"=>intval($_POST["\x77\x6f\x72kf\x6c\x6fw"]),"\x66\x69elds"=>${${"\x47\x4c\x4fB\x41\x4cS"}["x\x68\x64q\x70\x75\x75\x73\x68y\x78\x65"]})));}if(isset($_POST["\x73t\x61rt\x66ie\x6cds"])&&count($_POST["\x73t\x61\x72\x74f\x69el\x64s"])>0){${${"G\x4cO\x42AL\x53"}["\x6e\x72j\x61\x6b\x71kx\x66r\x6a"]}=$_POST["st\x61\x72\x74fi\x65\x6cds"];${"GL\x4f\x42A\x4c\x53"}["\x73\x6b\x69\x67\x6c\x66\x69\x72"]="v\x61l\x75es";${${"\x47\x4c\x4f\x42\x41LS"}["x\x68d\x71\x70u\x75\x73hy\x78\x65"]}=array();foreach(${${"\x47\x4c\x4f\x42\x41L\x53"}["n\x72\x6a\x61\x6b\x71\x6b\x78fr\x6a"]} as${${"G\x4c\x4f\x42\x41L\x53"}["\x73\x6b\x69\x67lf\x69\x72"]}){$gmmmtwly="\x76\x61\x6c\x75e\x73";${${"G\x4c\x4f\x42\x41\x4cS"}["\x78hd\x71\x70\x75\x75shy\x78\x65"]}[${${"\x47\x4c\x4f\x42\x41L\x53"}["\x78\x70\x62\x61\x6e\x6c\x6a\x71\x6d"]}["\x6ea\x6de"]]=trim(${$gmmmtwly}["\x76al\x75\x65"]);}$context->setEnvironment("va\x6cue",${${"\x47\x4c\x4f\x42\x41L\x53"}["xh\x64\x71\x70uu\x73hy\x78e"]});}$objWorkflow->setContext(${$rlxpjpocoqi});$objWorkflow->start();$context->save();}Workflow2::${${"G\x4cOB\x41\x4cS"}["\x74\x78n\x76d\x79i\x67\x75\x79"]}=false;${${"\x47LO\x42\x41\x4cS"}["\x6fw\x72\x65\x69\x68\x62"]}=array("re\x73\x75lt"=>"\x6fk");${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x6dj\x7a\x6c\x68js\x67\x66\x77\x66"]}=$objWorkflow->getSuccessRedirection();if(${$etbpjhd}!==false){$sveupbcjtzb="\x72\x65\x64i\x72\x65\x63\x74\x69\x6f\x6e";${"\x47\x4cO\x42\x41\x4cS"}["uz\x6a\x61pa\x6fp\x69\x78"]="\x72\x65\x73u\x6ct";${${"\x47\x4cO\x42A\x4cS"}["\x75\x7a\x6a\x61\x70\x61\x6fp\x69x"]}["\x72ed\x69rect\x69\x6f\x6e"]=${$sveupbcjtzb};${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x6f\x77r\x65i\x68b"]}["r\x65dire\x63t\x69\x6fn\x5f\x74\x61r\x67\x65t"]=$objWorkflow->getSuccessRedirectionTarget();}die(json_encode(${$ldobmlfv}));}public function validateRequest(Vtiger_Request$request){$request->validateReadAccess();}}
?>