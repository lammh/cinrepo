<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Workflow;${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x6a\x75\x63\x77\x77\x69\x6c"]="\x6be\x79";${"G\x4c\x4f\x42\x41LS"}["\x74m\x72\x63\x76\x72\x79\x63k"]="i\x74e\x6d";${"G\x4c\x4fB\x41\x4c\x53"}["\x73\x74\x79\x79c\x69"]="\x66i\x6c\x65";${"\x47L\x4f\x42\x41\x4cS"}["\x6e\x74\x66\x64w\x74"]="\x6d\x6f\x64\x75\x6c\x65\x4e\x61me";${"G\x4cOB\x41\x4c\x53"}["evux\x6dp\x6fj\x6c\x79v"]="\x63\x6fn\x66ig\x73";${"\x47\x4c\x4fB\x41\x4c\x53"}["\x71\x65m\x6a\x62ye\x6a\x74tv\x67"]="\x72\x65\x74u\x72\x6e";${"\x47\x4cO\x42AL\x53"}["\x71p\x69\x66q\x75"]="\x69t\x65\x6d\x73";abstract class RecordSource extends Extendable{public static function init(){self::_init(dirname(__FILE__)."/.\x2e/\x2e./\x65xt\x65\x6e\x64\x73/\x72e\x63\x6frd\x73\x6f\x75rce/");}public static function getAvailableSources($moduleName){${${"G\x4c\x4f\x42\x41\x4c\x53"}["\x71\x70\x69f\x71\x75"]}=self::getItems();$grqqkozjdrnt="\x69t\x65\x6d";$ehdlqjmc="\x69\x74e\x6d\x73";${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x71em\x6ab\x79ejt\x74\x76\x67"]}=array();foreach(${$ehdlqjmc} as${$grqqkozjdrnt}){$ludsgjor="\x63\x6f\x6e\x66\x69\x67\x73";$hkhttiwrlee="c\x6fn\x66\x69\x67s";${"\x47L\x4fB\x41\x4c\x53"}["\x71\x62uib\x6c\x70s\x64\x73"]="c\x6f\x6e\x66\x69\x67\x73";${${"G\x4c\x4f\x42AL\x53"}["\x65\x76ux\x6d\x70\x6f\x6a\x6cyv"]}=$item->getSources(${${"\x47\x4c\x4f\x42A\x4c\x53"}["\x6e\x74\x66dwt"]});${$ludsgjor}["\x69\x64"]=$item->getExtendableKey();${"\x47\x4c\x4f\x42\x41\x4c\x53"}["l\x74\x64\x74\x7a\x64z\x65w"]="fil\x65";${$hkhttiwrlee}=array(${${"\x47\x4cO\x42ALS"}["\x71bu\x69blp\x73d\x73"]});foreach(${${"\x47\x4cOB\x41\x4c\x53"}["e\x76\x75\x78\x6d\x70o\x6a\x6c\x79\x76"]} as${${"GLO\x42A\x4c\x53"}["\x6c\x74\x64\x74zdze\x77"]}){${${"\x47\x4cO\x42AL\x53"}["\x71\x65m\x6a\x62y\x65j\x74tvg"]}[]=${${"G\x4c\x4f\x42AL\x53"}["sty\x79\x63\x69"]};}}return${${"\x47\x4cO\x42\x41L\x53"}["\x71\x65mj\x62\x79ej\x74\x74\x76g"]};}public static function getRecords($key,$configuration,$moduleName,\Workflow\VTEntity$context){${"\x47L\x4fB\x41LS"}["t\x70f\x6f\x65\x68\x73\x6bm\x70\x6c"]="\x63on\x66i\x67u\x72a\x74\x69\x6fn";${"\x47L\x4f\x42\x41\x4c\x53"}["\x65\x6el\x64\x72\x6d\x6c"]="\x63\x6fn\x74\x65x\x74";${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x74m\x72\x63\x76r\x79\x63\x6b"]}=self::getItem(${${"\x47\x4c\x4fBAL\x53"}["\x6a\x75\x63\x77w\x69l"]});if(${${"\x47\x4cO\x42ALS"}["\x74\x6d\x72\x63\x76\x72\x79\x63\x6b"]}===false){return array();}return$item->doAction(${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x74\x70f\x6fe\x68s\x6bm\x70l"]},${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x6e\x74\x66dw\x74"]},${${"\x47\x4c\x4fBA\x4c\x53"}["e\x6e\x6c\x64r\x6dl"]});}abstract public function doAction($configuration,$moduleName,\Workflow\VTEntity$context);abstract public function getSources($moduleName);abstract public function beforeGetTaskform($viewer);}
?>