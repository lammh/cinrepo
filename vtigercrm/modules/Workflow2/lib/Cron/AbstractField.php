<?php /*
This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

It belongs to the Workflow Designer and must not be distrubuted without the complete extension
*/
namespace Cron;${"\x47\x4c\x4f\x42A\x4cS"}["\x66\x70h\x68\x6dt\x6e\x72\x62\x67"]="s\x74\x65p\x53i\x7a\x65";${"GL\x4fBA\x4c\x53"}["eb\x76e\x72\x70nh\x7a"]="t\x6f";${"\x47\x4c\x4fB\x41L\x53"}["v\x6b\x6f\x6d\x73\x69\x77qs"]="\x69";${"\x47\x4cO\x42\x41\x4c\x53"}["\x64\x79\x6f\x79\x75i\x73"]="\x6ff\x66\x73\x65\x74";${"G\x4c\x4f\x42\x41\x4c\x53"}["\x77s\x67h\x6c\x64\x70k\x6c\x6d\x65"]="r\x61\x6e\x67\x65";${"\x47\x4cO\x42\x41L\x53"}["\x66\x76fwlj\x76uu"]="\x70\x61\x72ts";${"\x47\x4cO\x42\x41LS"}["\x73o\x64m\x6f\x66"]="da\x74\x65\x56al\x75e";${"\x47LO\x42\x41L\x53"}["\x62\x76\x78\x75\x76d\x71m\x66\x75"]="\x76\x61l\x75\x65";abstract class AbstractField implements FieldInterface{public function isSatisfied($dateValue,$value){${"G\x4c\x4f\x42\x41L\x53"}["\x79\x70z\x62\x6d\x6f\x77"]="\x64\x61\x74\x65\x56\x61l\x75\x65";${"\x47\x4cO\x42ALS"}["\x76\x6e\x75qd\x74\x76\x77\x73\x64"]="\x76\x61\x6cu\x65";$vczzgdgflp="\x76a\x6c\x75e";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x66\x6f\x6bd\x6al\x65"]="va\x6cu\x65";if($this->isIncrementsOfRanges(${${"\x47\x4c\x4f\x42A\x4c\x53"}["\x62\x76x\x75\x76\x64\x71\x6d\x66\x75"]})){${"\x47\x4c\x4fB\x41\x4c\x53"}["\x65uq\x76byx"]="\x64at\x65\x56alu\x65";${"\x47L\x4f\x42\x41\x4c\x53"}["w\x77oy\x66\x75"]="\x76\x61\x6c\x75\x65";return$this->isInIncrementsOfRanges(${${"G\x4cO\x42\x41\x4c\x53"}["\x65u\x71\x76\x62\x79x"]},${${"\x47L\x4f\x42\x41L\x53"}["\x77\x77\x6f\x79\x66u"]});}elseif($this->isRange(${${"GL\x4fB\x41L\x53"}["fo\x6b\x64\x6al\x65"]})){$vvwfgvdm="\x76\x61lu\x65";return$this->isInRange(${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x73o\x64\x6d\x6ff"]},${$vvwfgvdm});}return${$vczzgdgflp}=="*"||${${"\x47\x4cOB\x41L\x53"}["\x79p\x7a\x62m\x6f\x77"]}==${${"G\x4c\x4fBAL\x53"}["\x76\x6eu\x71d\x74\x76wsd"]};}public function isRange($value){return strpos(${${"G\x4c\x4f\x42AL\x53"}["\x62\x76\x78uv\x64\x71\x6d\x66u"]},"-")!==false;}public function isIncrementsOfRanges($value){return strpos(${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x62\x76xuvd\x71\x6d\x66\x75"]},"/")!==false;}public function isInRange($dateValue,$value){${"\x47\x4c\x4fBA\x4cS"}["\x6ejh\x6bm\x72\x71n\x78\x75\x6b"]="d\x61te\x56\x61\x6cue";$cssnbmfj="p\x61r\x74\x73";$vjceplcr="\x70a\x72ts";${"\x47\x4c\x4f\x42A\x4c\x53"}["\x73\x6c\x69\x69\x67\x78"]="\x70a\x72\x74\x73";${$cssnbmfj}=array_map("\x74r\x69\x6d",explode("-",${${"G\x4cOB\x41\x4cS"}["bv\x78\x75\x76\x64\x71\x6d\x66u"]},2));return${${"\x47\x4c\x4f\x42AL\x53"}["\x6ej\x68\x6b\x6d\x72q\x6exu\x6b"]}>=${$vjceplcr}[0]&&${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x73\x6f\x64\x6d\x6f\x66"]}<=${${"\x47L\x4f\x42\x41\x4c\x53"}["\x73l\x69\x69\x67\x78"]}[1];}public function isInIncrementsOfRanges($dateValue,$value){$eyhrsvvjm="\x70ar\x74\x73";${"\x47\x4c\x4fB\x41L\x53"}["\x67s\x71\x79c\x79\x62\x68v\x6b"]="\x72\x61\x6e\x67\x65";${"G\x4c\x4fBALS"}["v\x6d\x70\x6c\x61\x78\x79cq\x67"]="\x70\x61\x72\x74\x73";${"G\x4c\x4f\x42\x41\x4c\x53"}["\x79\x6a\x62\x79\x6b\x66\x64\x76\x79\x73\x6a"]="\x73t\x65\x70\x53\x69\x7a\x65";$ppxnrckxmz="i";${"G\x4c\x4f\x42\x41\x4cS"}["u\x63\x71\x63qi\x79ja\x65wr"]="\x69";${$eyhrsvvjm}=array_map("tr\x69\x6d",explode("/",${${"\x47\x4c\x4f\x42\x41L\x53"}["\x62vx\x75vd\x71\x6d\x66\x75"]},2));${"G\x4c\x4fBA\x4cS"}["\x6et\x66\x72jt\x70x\x70"]="\x72\x61\x6e\x67\x65";${"\x47\x4c\x4fBALS"}["\x6f\x6e\x61\x77q\x79\x67\x65"]="\x70a\x72\x74s";${${"\x47\x4c\x4f\x42\x41L\x53"}["\x79\x6aby\x6bf\x64\x76\x79s\x6a"]}=isset(${${"\x47LO\x42\x41\x4cS"}["\x66\x76\x66wl\x6a\x76\x75u"]}[1])?${${"G\x4cO\x42\x41LS"}["\x76\x6d\x70l\x61\x78\x79c\x71\x67"]}[1]:0;$bxnnanwt="p\x61\x72\x74\x73";$xgmcwxytpf="\x64\x61\x74\x65\x56a\x6cu\x65";$gymclcvtdllk="\x64ateVa\x6cu\x65";if(${$bxnnanwt}[0]=="*"||${${"\x47L\x4fBA\x4c\x53"}["\x66vf\x77l\x6av\x75u"]}[0]==="\x30"){${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x75\x63\x65\x77\x63\x61\x6f\x64\x77l\x6d\x71"]="\x73\x74\x65\x70Siz\x65";return(int)${${"\x47L\x4f\x42\x41LS"}["\x73\x6fd\x6do\x66"]}%${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x75\x63\x65\x77\x63\x61\x6f\x64\x77\x6c\x6d\x71"]}==0;}${"\x47\x4c\x4f\x42\x41\x4cS"}["o\x65\x6d\x73\x6fc\x64\x73"]="t\x6f";$nvmrscriiix="t\x6f";${${"G\x4c\x4f\x42\x41\x4cS"}["w\x73\x67hldp\x6blm\x65"]}=explode("-",${${"\x47\x4cOB\x41\x4c\x53"}["\x6fnawqy\x67e"]}[0],2);${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x64yo\x79u\x69s"]}=${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["n\x74f\x72\x6a\x74\x70\x78p"]}[0];${$nvmrscriiix}=isset(${${"GL\x4f\x42ALS"}["\x77\x73\x67\x68\x6cdp\x6b\x6cm\x65"]}[1])?${${"G\x4c\x4fB\x41L\x53"}["g\x73qy\x63\x79\x62\x68\x76\x6b"]}[1]:${$xgmcwxytpf};if(${$gymclcvtdllk}<${${"G\x4cOBAL\x53"}["\x64yo\x79u\x69\x73"]}||${${"G\x4c\x4f\x42\x41\x4c\x53"}["\x73\x6f\x64\x6dof"]}>${${"\x47L\x4f\x42\x41\x4c\x53"}["\x6fe\x6d\x73\x6fcds"]}){return false;}for(${${"\x47L\x4f\x42\x41\x4cS"}["\x75\x63q\x63q\x69y\x6a\x61\x65w\x72"]}=${${"\x47\x4c\x4f\x42\x41L\x53"}["d\x79oyu\x69s"]};${${"GL\x4f\x42\x41L\x53"}["\x76k\x6f\x6ds\x69\x77\x71\x73"]}<=${${"\x47\x4c\x4f\x42A\x4c\x53"}["\x65b\x76\x65r\x70n\x68z"]};${$ppxnrckxmz}+=${${"G\x4cO\x42ALS"}["\x66\x70\x68hm\x74n\x72\x62g"]}){if(${${"GLO\x42\x41\x4c\x53"}["\x76\x6b\x6f\x6d\x73i\x77\x71s"]}==${${"\x47\x4cOB\x41\x4c\x53"}["\x73\x6f\x64\x6d\x6f\x66"]}){return true;}}return false;}}
?>