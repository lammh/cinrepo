function refreshLicense() {
    jQuery.post('index.php', {module:'Workflow2', parent:'Settings', action:'LicenseRefresh'}, function() {
        window.location.reload();
    });
}
function removeLicense() {
    jQuery.post('index.php', {module:'Workflow2', parent:'Settings', action:'LicenseRemove'}, function() {
        window.location.reload();
    });
}