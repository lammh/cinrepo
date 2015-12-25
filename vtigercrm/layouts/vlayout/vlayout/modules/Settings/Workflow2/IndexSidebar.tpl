<div class="sidebarTitleBlock">
	<h3 class="titlePadding themeTextColor unSelectedQuickLink cursorPointer"><a href="index.php?module=Vtiger&parent=Settings&view=Index">{vtranslate('LBL_SETTINGS', $QUALIFIED_MODULE)}</a></h3>
</div>

<div class="quickWidgetContainer accordion" id="settingsQuickWidgetContainer">
    <div class="quickWidget">
        <div class="accordion-heading quickWidgetHeader">
            <span class="pull-left"><img class="imageElement" data-rightimage="{vimage_path('rightArrowWhite.png')}" data-downimage="{vimage_path('downArrowWhite.png')}" src="{vimage_path('downArrowWhite.png')}" /></span>
            <h5 class="title paddingLeft10px widgetTextOverflowEllipsis" title="Einstellungen">Einstellungen</h5>
            <div class="clearfix"></div>
        </div>
        <div  style="border-bottom: 1px solid black;" class="widgetContainer accordion-body in  collapse" id="Settings_sideBar_WorkflowDesigner">
            <div class="{if $VIEW eq 'Index'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=Index&parent=Settings" data-id="Taskmanagement" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('LBL_WORKFLOW2', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="{if $VIEW eq 'HttpHandlerManager'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=HttpHandlerManager&parent=Settings" data-id="Taskmanagement" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('LBL_SETTINGS_HTTPHANDLER', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="{if $VIEW eq 'SettingsLogging'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=SettingsLogging&parent=Settings" data-id="Taskmanagement" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('LBL_SETTINGS_LOGGING', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="{if $VIEW eq 'FrontendManager'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=FrontendManager&parent=Settings" data-id="FrontendManager" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('Frontend Manager', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="{if $VIEW eq 'SettingsDBCheck'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=SettingsDBCheck&parent=Settings" data-id="Taskmanagement" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('LBL_SETTINGS_DB_CHECK', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="{if $VIEW eq 'SettingsScheduler'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=SettingsScheduler&parent=Settings" data-id="Taskmanagement" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('LBL_SETTINGS_SCHEDULER', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="{if $VIEW eq 'LicenseManager'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=LicenseManager&parent=Settings" data-id="LicenseManager" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('LBL_LICENSE_MANAGER', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="{if $VIEW eq 'ErrorReport'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=ErrorReport&parent=Settings" data-id="ErrorReport" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('LBL_ERROR_REPORT', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            {*<div class="{if $VIEW eq 'CoreWFImport'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=CoreWFImport&parent=Settings" data-id="ErrorReport" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('import internal workflows', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>*}
        </div>
        <div class="accordion-heading quickWidgetHeader">
            <span class="pull-left"><img class="imageElement" data-rightimage="{vimage_path('rightArrowWhite.png')}" data-downimage="{vimage_path('downArrowWhite.png')}" src="{vimage_path('downArrowWhite.png')}" /></span>
            <h5 class="title paddingLeft10px widgetTextOverflowEllipsis" title="Einstellungen">{vtranslate('LBL_TASK_MANAGEMENT','Settings:Workflow2')}</h5>
            <div class="clearfix"></div>
        </div>
        <div  style="border-bottom: 1px solid black;" class="widgetContainer accordion-body in  collapse" id="Settings_sideBar_WorkflowDesigner">
            <div class="{if $VIEW eq 'TaskManagement'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    {if $AVAILABLE_TASK_UPDATE eq true}
                        <img class="pull-right" style="margin-right:10px;" alt="There are Updates available!" src="modules/Workflow2/views/resources/img/new.png" />
                    {/if}
                    <a href="index.php?module=Workflow2&view=TaskManagement&parent=Settings" data-id="Taskmanagement" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('LBL_TASK_MANAGEMENT', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="{if $VIEW eq 'TaskRepoManager'} selectedMenuItem selectedListItem{/if}" style='padding-left:10px;border-top:0px;padding-bottom: 5px'>
                <div class="row-fluid menuItem">
                    <a href="index.php?module=Workflow2&view=TaskRepoManager&parent=Settings" data-id="Taskmanagement" class="span9 menuItemLabel" data-menu-item="true" >{vtranslate('LBL_TASK_REPO_MANAGEMENT', $QUALIFIED_MODULE)}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class='WF2footerSidebar'>
        Workflow Designer {$VERSION}
    </div>
</div>