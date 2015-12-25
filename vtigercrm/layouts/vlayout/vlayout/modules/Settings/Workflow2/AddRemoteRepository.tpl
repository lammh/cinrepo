<br/>
<link rel="stylesheet" href="modules/Workflow2/adminStyle.css" type="text/css" media="all" />
<div class="container-fluid" id="">

    <div class="row-fluid">
        <div class="span12">
            <h3>
                <b>
                    <a href="index.php?module=Workflow2&view=Index&parent=Settings">{vtranslate('Workflow2', 'Workflow2')}</a> &raquo;
                    {vtranslate('LBL_TASK_MANAGEMENT', 'Settings:Workflow2')} &raquo
                    {vtranslate('LBL_ADD_REPOSITORY', 'Settings:Workflow2')}
                </b>
            </h3>
        </div>
    </div>
    <hr>

    <form method="POST" id="popupForm" action="index.php?module=Workflow2&parent=Settings&action=TaskRepoSave" enctype="multipart/form-data">
        <input type="hidden" name="_nonce" value="{$nonce}" />
        <input type="hidden" name="repo_url" value="{$data.url}" />
        <div class="modal-header contentsBackground">
            <h3>{$data.title}</h3>
        </div>
        <div style="padding: 10px;">{* Content Start *}
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td class='dvtCellLabel' style="width:200px;">{vtranslate('LBL_REPO_URL','Settings:Workflow2')}</td>
                    <td class='dvtCellInfo'><strong>{$data.url}</strong></td>
                </tr>
                <tr id="licenseColumn">
                    <td class='dvtCellLabel' style="width:200px;"><br/>{vtranslate('LBL_REPO_LICENSEKEY','Settings:Workflow2')}</td>
                    <td class='dvtCellInfo'><br/>{if $data.needLicense == true}<input type="text" name="repo_license" value="" style="width:300px;">{else}---{/if}</td>
                </tr>
            </table>
        </div> {* Content Ende *}
        <div class="modal-footer quickCreateActions">
            <button class="btn btn-success" style="float:none;" type="submit" id="modalSubmitButton" ><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
        </div>
    </form>
</div>