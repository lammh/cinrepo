var Scheduler = {
    newScheduler: function() {
        jQuery.post('index.php', {module:'Workflow2', parent:'Settings', action:'SchedulerAdd'}, function() {
            Scheduler.refreshList();
        });
    },
    delScheduler: function(id) {
        if(!confirm('Really delete?')) return;

        jQuery.post('index.php', { module:'Workflow2', parent:'Settings', action:'SchedulerDel', scheduleId: id }, function() {
            jQuery('.cronRow_' + id).slideUp();
        });

    },
    refreshList: function() {
            var params = {
                module: 'Workflow2',
                view: 'SettingsScheduler',
                parent: 'Settings'
            };
            AppConnector.request(params).then(function(data) {
                jQuery(jQuery(".contentsDiv")[0]).html(data);
            });
    }

};
jQuery(function() {
    jQuery('.cronRow input, .cronRow select').bind('change', function(event) {
        var target = jQuery(event.target);

        target.attr('disabled', 'disabled');
        if(target.val() == '') {
            target.val('*')
        }
        jQuery.post('index.php', { module:'Workflow2', parent:'Settings', action:'SchedulerUpdate', scheduleId: target.data('sid'), field: target.data('field'), value: target.val() }, function() {
            target.removeAttr('disabled');
        });
    });

    //jQuery(".select2").select2()
});