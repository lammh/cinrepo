<div class="container-fluid" id="moduleManagerContents">

        <div class="widget_header row-fluid">
            <div class="span12">
                <h3>
                    <b>
                        <a href="index.php?module=Workflow2&view=Index&parent=Settings">Workflow Designer</a> &raquo;
                        HTTP Handler Security
                    </b>
                </h3>
            </div>
        </div>
        <hr>


           <form method="POST" action="index.php?module=Workflow2&action=settingsLogging&parenttab=Settings">
                   <br>
                   <div class="settingsUI" style="width:95%;padding:10px;margin-left:10px;">
                        <p>
                            <button type="button" class="btn btn-primary pull-right" name="" onclick="addHandler();" value="">{vtranslate('LBL_ADD_HTTP_LIMIT','Settings:Workflow2')}</button>
                            {vtranslate('LBL_LIMIT_HTTP_ACCESS_IP_HEAD','Settings:Workflow2')}
                        </p>
                       <br/>
                        <table class="table">
                               {foreach from=$limits item=limit}
                                   <tr onclick="editHandler({$limit.id});" style="cursor: pointer;">
                                       <td class="dvtCellInfo" style="width:25px;"><img src="modules/Workflow2/icons/pencil.png"></td>
                                       <td class="dvtCellInfo" style="width:150px;font-weight:bold;vertical-align:top;">{$limit.name}</td>

                                       <td class="dvtCellInfo" style="width:250px;vertical-align:top;">{'<br />'|implode:$limit.ips}</td>

                                       <td class="dvtCellInfo" style="width:350px;vertical-align:top;">{'<br />'|implode:$limit.items}</td>
                                   </tr>
                               {/foreach}
                           </table>
                        </div>

                     <link href="modules/Workflow2/views/resources/js/notifications/main.css" rel="stylesheet" type="text/css" media="screen" />
                     <script src="modules/Workflow2/views/resources/js/notifications/js/notification-min.js"></script>

             </form>
</div>
