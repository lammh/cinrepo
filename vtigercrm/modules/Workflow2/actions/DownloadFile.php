<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
${"\x47L\x4fBA\x4c\x53"}["\x72x\x73n\x68y\x6f\x70\x70h"]="\x70at\x68";${"G\x4c\x4f\x42\x41\x4cS"}["\x79\x65\x74\x69\x6eh\x6d\x78\x74"]="\x61\x64\x62";${"GLOBA\x4c\x53"}["ch\x6df\x6d\x79\x62w\x79\x63\x77\x62"]="\x72\x6f\x6f\x74_\x64\x69\x72\x65\x63tor\x79";global$root_directory;require_once(${${"GLO\x42A\x4cS"}["ch\x6d\x66\x6dybw\x79\x63\x77\x62"]}."/m\x6f\x64ul\x65s/W\x6fr\x6bf\x6c\x6f\x772/\x61u\x74\x6f\x6c\x6f\x61\x64\x5fwf.\x70hp");class Workflow2_DownloadFile_Action extends Vtiger_Action_Controller{function checkPermission(Vtiger_Request$request){return;}public function process(Vtiger_Request$request){${"\x47\x4c\x4f\x42\x41LS"}["\x6a\x68\x73lxixk\x73\x77"]="\x69d";$hbtbpmlh="p\x61\x74h";${"\x47\x4c\x4f\x42ALS"}["lx\x69nqw\x6e"]="\x70\x61\x72\x61\x6d\x73";${${"\x47\x4c\x4fBA\x4c\x53"}["\x79et\x69\x6e\x68\x6dx\x74"]}=PearDatabase::getInstance();$ougpgodj="f\x69\x6c\x65\x6e\x61\x6d\x65";${${"\x47\x4c\x4fB\x41\x4cS"}["l\x78\x69\x6e\x71\x77\x6e"]}=$request->getAll();${${"\x47\x4cO\x42\x41\x4c\x53"}["\x6a\x68\x73lx\x69\x78\x6b\x73\x77"]}=$request->get("i\x64");$wdittud="i\x64";$hsnxkpbpjgl="\x70a\x74\x68";${"G\x4c\x4fB\x41L\x53"}["ospxo\x74\x6ag"]="\x66i\x6c\x65n\x61\x6de";${$ougpgodj}=$request->get("\x66i\x6cenam\x65");${$hbtbpmlh}=vglobal("r\x6fot\x5fd\x69\x72\x65\x63\x74\x6f\x72y")."/\x6dodu\x6ce\x73/Wor\x6b\x66\x6c\x6fw\x32/tmp/\x64\x6fwnl\x6f\x61d/".${$wdittud};if(!file_exists(${$hsnxkpbpjgl})){\Workflow2::error_handler(E_ERROR,"F\x69\x6c\x65 t\x6f \x64\x6f\x77\x6elo\x61\x64 \x6e\x6f\x74 \x66\x6fu\x6ed!\x20\x59o\x75 \x63\x6ful\x64\x20\x64\x6fwn\x6c\x6f\x61\x64 a \x66ile\x20onl\x79\x20o\x6e\x65 t\x69\x6de\x21");}header("Pr\x61g\x6da: \x70\x75\x62\x6ci\x63");${"\x47L\x4f\x42\x41L\x53"}["b\x6a\x6eh\x73\x6a\x72\x6c\x73\x71\x6c"]="\x70\x61\x74h";header("Ex\x70\x69\x72\x65\x73:\x200");header("\x43ac\x68e-\x43\x6f\x6e\x74ro\x6c:\x20m\x75\x73t-\x72\x65\x76\x61l\x69d\x61t\x65, p\x6fs\x74-\x63h\x65ck=0, pr\x65-\x63heck\x3d0");header("C\x61\x63he-Con\x74r\x6f\x6c: \x70u\x62\x6c\x69c");header("C\x6f\x6e\x74e\x6et-\x44esc\x72\x69\x70\x74io\x6e:\x20\x46il\x65\x20T\x72\x61ns\x66\x65r");header("\x43\x6fnte\x6et-\x74\x79\x70\x65:\x20\x61p\x70l\x69\x63at\x69\x6f\x6e/\x6f\x63\x74\x65t-\x73tr\x65am");header("C\x6fntent-\x44is\x70os\x69\x74\x69\x6f\x6e:\x20\x61tt\x61chment\x3b f\x69\x6c\x65\x6e\x61m\x65\x3d\x22".${${"\x47\x4cO\x42\x41L\x53"}["\x6fsp\x78otjg"]}."\x22");header("\x43o\x6e\x74\x65n\x74-T\x72\x61\x6es\x66\x65r-E\x6e\x63od\x69ng: \x62ina\x72\x79");header("\x43o\x6e\x74\x65\x6et-L\x65n\x67\x74\x68:\x20".filesize(${${"G\x4c\x4fB\x41LS"}["\x62j\x6e\x68\x73\x6a\x72\x6c\x73\x71\x6c"]}));@readfile(${${"\x47\x4c\x4fB\x41\x4c\x53"}["rxs\x6eh\x79\x6f\x70p\x68"]});@unlink(${${"G\x4c\x4fB\x41\x4c\x53"}["\x72xsn\x68y\x6f\x70ph"]});exit();}public function validateRequest(Vtiger_Request$request){$request->validateReadAccess();}}
?>