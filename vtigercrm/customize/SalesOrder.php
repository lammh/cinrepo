<?php

chdir('..');
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once('modules/PickList/DependentPickListUtils.php');


$module = Vtiger_Module::getInstance('SalesOrder');

$blockInstance = new Vtiger_Block();
// label���Ŋ����u���b�N���擾����
//$blockInstance->label = 'LBL_ACCOUNT_INFORMATION';
//$module->addBlock($blockInstance);
$blockInstance = $blockInstance->getInstance(61);


/* �v��T */
$field = new Vtiger_Field();
$field->name = 'closing_week';
$field->table = $module->basetable;
$field->column = 'closing_week';
$field->columntype = 'INT(10)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array (1, 2, 3, 4, 5, 6) );


/* ���� */
$field = new Vtiger_Field();
$field->name = 'division';
$field->table = $module->basetable;
$field->column = 'division';
$field->columntype =  'VARCHAR(240)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("101:���l�L��","102:�l�ޏЉ�","103:�l�ޔh��","201:���]�R���T��","202:Web�R�~��","203:�T���R��","204:E�R�}�[�X") );

/* ���͏Љ� */
$field = new Vtiger_Field();
$field->name = 'selforintro';
$field->table = $module->basetable;
$field->column = 'selforintro';
$field->columntype =  'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("����","�Љ�","���p��","���̑�") );


/* �G���A */
$field = new Vtiger_Field();
$field->name = 'closingarea';
$field->table = $module->basetable;
$field->column = 'closingarea';
$field->columntype =  'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("����","���","���̑�") );





/* ���ʊ */
$field = new Vtiger_Field();
$field->name = 'outcome';
$field->table = $module->basetable;
$field->column = 'outcome';
$field->columntype =  'VARCHAR(100)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("�Œ�","����") );



/* ����z */
$field = new Vtiger_Field();
$field->name = 'salesamount';
$field->table = $module->basetable;
$field->column = 'salesamount';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* ��� */
$field = new Vtiger_Field();
$field->name = 'kikaku';
$field->table = $module->basetable;
$field->column = 'kikaku';
$field->columntype = 'VARCHAR(250)';
$field->uitype = 1;
$field->sequence = 10;
$field->displaytype= 1;
$field->typeofdata = 'V~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* ���� */
$field = new Vtiger_Field();
$field->name = 'costall';
$field->table = $module->basetable;
$field->column = 'costall';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* �Љ */
$field = new Vtiger_Field();
$field->name = 'costinfo';
$field->table = $module->basetable;
$field->column = 'costinfo';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);


/* ������p */
$field = new Vtiger_Field();
$field->name = 'costnew';
$field->table = $module->basetable;
$field->column = 'costnew';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* �}�� */
$field = new Vtiger_Field();
$field->name = 'baitai';
$field->table = $module->basetable;
$field->column = 'baitai';
$field->columntype =  'VARCHAR(240)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("TW","TW�Ј�","TW���oS","an","�t�����`�i�r","�o�C�g��","�}�C�i�r�o�C�g","�A�C�f��","DOMO","���N�i�r�l�N�X�g","�}�C�i�r�]�E","�Ƃ�΁[��"
//,"�͂��炢��","DODA","���N�i�r�V��","�}�C�i�r�V��","�C�[�L�����A","@type","���̓]�E","���̑�","�L�b�N�o�b�N�萔��"

//) );



/* �^�C�v */
$field = new Vtiger_Field();
$field->name = 'acfrom';
$field->table = $module->basetable;
$field->column = 'acfrom';
$field->columntype =  'VARCHAR(240)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("�X�g�b�N","�V���b�g") );





/* �Г����� */
$field = new Vtiger_Field();
$field->name = 'costsyanai';
$field->table = $module->basetable;
$field->column = 'costsyanai';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* �Г������敪 */
$field = new Vtiger_Field();
$field->name = 'costsyanaikb';
$field->table = $module->basetable;
$field->column = 'costsyanaikb';
$field->columntype ='VARCHAR(200)';
$field->uitype = 15;
$field->sequence = 11;
$field->displaytype= 1;
$field->typeofdata = 'I~O';
$field->label = $field->column;
$blockInstance->addField($field);
//$field->setPicklistValues( array ("1:�Z�p","2:�}�[�P","3:����","4:�f�B���N�V����","5:���]�Z�p") );



/* �ڕW */
$field = new Vtiger_Field();
$field->name = 'mokuhyou';
$field->table = $module->basetable;
$field->column = 'mokuhyou';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);



/* �e�� */
$field = new Vtiger_Field();
$field->name = 'amount';
$field->table = $module->basetable;
$field->column = 'amount';
$field->columntype = 'INT(10)';
$field->uitype = 71;
$field->displaytype= 1;
$field->typeofdata = 'N~O';
$field->label = $field->column;
$blockInstance->addField($field);

?>