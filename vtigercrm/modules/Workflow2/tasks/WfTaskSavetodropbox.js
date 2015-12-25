function addTree(id) {

    jQuery("#" + id)
       .jstree({
            // List of active plugins
            "plugins" : [
                "themes","json_data","ui","crrm","dnd","search","contextmenu","sort"
            ],
            "core" : {
//                "initially_open" : ["node_1",initId!=0?"node_" + initId:0]
            },

            "themes" : {
          			"theme" :   "default",
                    "url"   :   'modules/Workflow2/extends/additionally/savetodropbox/js/jstree/themes/default/style.css'
          		},
            // I usually configure the plugin that handles the data first
            // This example uses JSON as it is most common
            "json_data" : {
                // This tree is ajax enabled - as this is most common, and maybe a bit more complex
                // All the options are almost the same as jQuery's AJAX (read the docs)
                "ajax" : {
                    // the URL to fetch the data
                    "url" : "modules/Workflow2/extends/additionally/savetodropbox/php/jstree.php",
                    // the `data` function is executed in the instance's scope
                    // the parameter is the node being loaded
                    // (may be -1, 0, or undefined when loading the root nodes)
                    "data" : function (n) {
                        // the result is fed to the AJAX request `data` option
                        return {
                            "operation" : "get_children",
                            "path" : n.attr ? n.attr("path") : "/",
                            "id" : n.attr ? n.attr("id").replace("node_","") : 0
                        };
                    }
                }
            },

            // UI & core - the nodes to initially select and open will be overwritten by the cookie plugin

            // the UI plugin - it handles selecting/deselecting/hovering nodes
            "ui" : {
                // this makes the node with ID node_4 selected onload
//                "initially_select" : [ "node_4" ]
            }
        }).bind("select_node.jstree", function (event, data) {
            jQuery("#filepath").val(data.rslt.obj.attr("path"));
            // `data.rslt.obj` is the jquery extended node that was clicked
            //alert(data.rslt.obj.attr("id"));
            //jQuery("#document_cp_path_id").val(node.attr("id").replace("node_",""));
//            console.log(uploaderItem);
//            if(uploaderItem != false) {
//                console.log(uploaderItem);
//                uploaderItem.settings.multipart_params.folderid =
//            }
        })
    ;   		// 1) if using the UI plugin bind to select_node3

    // jQuery("#" + id).slideDown("fast");
}

jQuery(document).ready(function() {
    addTree("jstree_container");
});