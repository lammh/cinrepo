<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Workflow;${"\x47LOBA\x4c\x53"}["\x78rm\x75\x71\x63t\x66"]="v\x61\x6c\x75e";${"G\x4c\x4f\x42\x41\x4cS"}["o\x73\x69\x69\x6cyn\x62\x67"]="\x72\x65\x74\x75rn";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x6f\x67\x6cu\x79\x6a\x71"]="\x66\x69\x65ld";${"\x47\x4c\x4f\x42ALS"}["\x78\x72\x62\x70d\x6c\x6e"]="\x69\x74\x65\x6d\x73";${"GLO\x42A\x4cS"}["\x6fif\x75by\x74\x71sq"]="\x74\x79p\x65s";abstract class ConnectionProvider extends Extendable{protected static$ItemCache=array();public static function init(){self::_init(dirname(__FILE__)."/\x2e\x2e/\x2e./e\x78t\x65\x6ed\x73/p\x72\x6fvide\x72/");}public static function getProvider($id){${"G\x4c\x4fB\x41\x4c\x53"}["\x6f\x65\x79\x67\x6e\x6c\x73\x68\x6a"]="i\x64";${${"\x47\x4c\x4f\x42A\x4cS"}["o\x69f\x75\x62\x79\x74\x71s\x71"]}=self::getTypes();${"GLOBA\x4cS"}["\x73\x6d\x68qgf"]="\x49\x74e\x6d\x43\x61\x63\x68\x65";return self::getItem(self::${${"\x47L\x4fBA\x4cS"}["\x73\x6dh\x71gf"]}[${${"GL\x4f\x42A\x4c\x53"}["\x6fe\x79gn\x6cs\x68\x6a"]}]["\x66\x69\x6ce"]);}public static function getAvailableProviders($moduleName=''){${"\x47L\x4fBA\x4c\x53"}["fsz\x70\x6cg\x65\x72"]="\x72\x65t\x75\x72\x6e";$mtmlptd="\x69\x74e\x6d\x73";${${"\x47L\x4fB\x41\x4c\x53"}["\x78\x72\x62pd\x6c\x6e"]}=self::getItems();$vhjblzajvdb="\x69\x74\x65\x6d";${${"\x47L\x4fB\x41L\x53"}["\x66\x73z\x70\x6cger"]}=array();foreach(${$mtmlptd} as${$vhjblzajvdb}){${"G\x4cO\x42\x41\x4c\x53"}["\x71\x6a\x73\x67\x6f\x64\x73"]="m\x6f\x64ul\x65\x4eame";$ttcmqlpkvr="\x63\x6f\x6efi\x67\x73";${"G\x4c\x4f\x42\x41\x4c\x53"}["u\x71\x65\x78\x78\x73\x6f\x68\x75x"]="c\x6f\x6e\x66\x69\x67\x73";${$ttcmqlpkvr}=$item->getFieldTypes(${${"G\x4c\x4fBA\x4c\x53"}["\x71j\x73\x67ods"]});foreach(${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["u\x71\x65x\x78\x73\x6f\x68\x75\x78"]} as${${"\x47\x4c\x4f\x42A\x4c\x53"}["o\x67\x6c\x75\x79\x6a\x71"]}){${"\x47L\x4fB\x41\x4c\x53"}["\x6c\x78b\x6f\x71g\x75\x70nt"]="\x66\x69\x65\x6c\x64";${"\x47L\x4f\x42A\x4c\x53"}["r\x72\x76\x72\x66m\x68\x74x\x70r"]="\x49\x74e\x6dC\x61\x63\x68\x65";${${"\x47L\x4fB\x41LS"}["\x6c\x78\x62o\x71gu\x70nt"]}["file"]=$item->getExtendableKey();${"G\x4c\x4f\x42A\x4c\x53"}["\x67d\x6eo\x65\x68\x74tu"]="\x66\x69\x65l\x64";$ybobgedjafue="f\x69e\x6cd";${${"GL\x4f\x42ALS"}["\x6fg\x6c\x75\x79\x6aq"]}["\x74i\x74\x6ce"]=getTranslatedString(${${"\x47LO\x42ALS"}["\x6fg\x6c\x75\x79j\x71"]}["\x74itle"],"Se\x74ti\x6eg\x73:Work\x66\x6co\x772");self::${${"G\x4c\x4f\x42\x41L\x53"}["\x72\x72v\x72\x66m\x68\x74\x78\x70r"]}[${${"\x47\x4c\x4f\x42A\x4c\x53"}["\x6f\x67\x6c\x75\x79\x6aq"]}["id"]]=${$ybobgedjafue};${${"G\x4cOB\x41\x4cS"}["\x6f\x73\x69i\x6cy\x6e\x62\x67"]}[]=${${"G\x4c\x4f\x42\x41\x4c\x53"}["g\x64\x6e\x6f\x65\x68\x74\x74\x75"]};}}return${${"\x47LO\x42\x41L\x53"}["\x6f\x73i\x69\x6c\x79\x6ebg"]};}abstract public function renderFrontend($data,$context);abstract public function getFieldTypes($moduleName);public function getValue($value,$name,$type,$context){return${${"G\x4cO\x42\x41\x4c\x53"}["\x78\x72\x6du\x71\x63\x74\x66"]};}}
?>