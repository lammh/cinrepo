/**
 * Created by JetBrains PhpStorm.
 * User: Stefan Warnat <support@stefanwarnat.de>
 * Date: 14.01.15 20:25
 * You must not use this file without permission.
 */
jQuery(function() {
    initEvents();
});

function initEvents() {
    jQuery('#addWorkflow').select2();

    jQuery('#addWorkflowButton').on('click', function() {
        jQuery.post('index.php',
            {
                module: 'Workflow2',
                parent: 'Settings',
                action: 'FrontendAddWorkflow',
                workflowId: jQuery('#addWorkflow').val()
            }, function() {
                reloadFrontendManager();
            });
    });

    jQuery('.SaveConfigOnBlur').on('change', function(e) {
        var ele = jQuery(this);

        if(ele.attr('type') == 'checkbox') {
            val = ele.prop('checked') ? 1 : 0;
        } else {
            val = ele.val();
        }

        jQuery.post('index.php',
            {
                module: 'Workflow2',
                parent: 'Settings',
                action: 'FrontendSetConfigValue',
                moduleName: ele.data('module'),
                field: ele.data('field'),
                value: val
            }, function() {
                ele.effect( 'pulsate', {times:3}, 100 );
            });

    });

    jQuery('.saveOnBlur').on('change', function(e) {
        var element = jQuery(this);
        var val = element.val();

        if(element.data('field') == 'position') {
            if(val == 'sidebar') {
                jQuery('#config_'+jQuery(this).data('id')+'_color').show();
            } else {
                jQuery('#config_'+jQuery(this).data('id')+'_color').hide();
            }
        }
        jQuery.post('index.php',
            {
                module: 'Workflow2',
                parent: 'Settings',
                action: 'FrontendSetValue',
                id: jQuery(this).data('id'),
                field: jQuery(this).data('field'),
                value: val
            }, function() {
                element.effect( 'highlight', { color: '#00df25' }, 500 );
            });
    });

    jQuery('.removeFrontendManagerOnClick').on('click', function() {
        jQuery.post('index.php',
            {
                module: 'Workflow2',
                parent: 'Settings',
                action: 'FrontendDelWorkflow',
                id: jQuery(this).data('id'),
            });
        jQuery(this).closest('tr').fadeOut('fast');

    });
    jscolor.init();

    jQuery('.blockHeader').on('click', function() {
        var header = jQuery(this).closest('.blockHeader');
        var target = header.data('target');
        var visible = jQuery('#workflowList' + target).css('display') != 'none';

        if(visible == false) {
            jQuery('#workflowList' + target).show();
            jQuery('.toggleImageExpand', header).hide();
            jQuery('.toggleImageCollapse', header).show();
        } else {
            jQuery('#workflowList' + target).hide();
            jQuery('.toggleImageExpand', header).show();
            jQuery('.toggleImageCollapse', header).hide();
        }

    });

    jQuery('.frontendManagerTable tbody').sortable({
          forcePlaceholderSize:true,
          distance:15,
          containment: "parent",
          update:function() {
              var sorted = jQuery( this ).sortable( "toArray", { 'attribute':'data-index' });

              jQuery.post('index.php', { 'module': 'Workflow2', 'parent':'Settings', 'action':'FrontendSaveOrder', 'indexes[]': sorted});

              Vtiger_Helper_Js.showPnotify({type:'success', text:app.vtranslate('LBL_SAVED_SUCCESSFULLY')});
          }

      });
      jQuery( "ImageManagerInteral" ).disableSelection();

}

function reloadFrontendManager() {
    var params = {
        module: 'Workflow2',
        view: 'FrontendManager',
        parent: 'Settings'
    };

    AppConnector.request(params).then(function(data) {
        jQuery(jQuery(".contentsDiv")[0]).html(data);
        initEvents();
    });
}