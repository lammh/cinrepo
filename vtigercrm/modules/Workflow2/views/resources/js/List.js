var UserQueue = {
    run: function(exec_id, block_id) {
        var Execution = new WorkflowExecution();
        Execution

        Execution.setContinue(exec_id, block_id);

        Execution.execute();
    }
}

var WorkflowPermissions = {
    submit: function(execID, confID, hash, result) {
        if(jQuery('#row_' + confID).data('already') == '1') {
            if(!confirm('Permission already set. Set again?')) {
                return;
            }
        }

        execution = new WorkflowExecution();
        execution.setCallback(function(response) { console.log(response); });

        execution.setContinue(execID, 0);
        //execution.enableRedirection(false);
        execution.submitRequestFields('authPermission', [{name:'permission', value: result}, {name:'confid', value: confID}, {name:'hash', value: hash}]);

        var row = jQuery('#row_' + confID);
        jQuery('.btn.decision', row).removeClass('pressed').addClass('unpressed');
        jQuery('.btn.decision_' + result, row).addClass('pressed').removeClass('unpressed');

        return false;
    }
}