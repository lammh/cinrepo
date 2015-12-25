function removeBlock(block_id) {
    if(confirm("Realy delete this block?") == false)
        return;

    opener.removeBlock("block__" + block_id);
    window.close();
}
function duplicateBlock(block_id) {
    opener.addBlock(0, 0, block_id);
 //   window.close();
}

function rewriteBiggerTextarea(field_id) {
    doCESave("pageOverlayTextArea");

    var current_value = jQuery("#pageOverlayTextArea").val();
    jQuery("#" + field_id).val(current_value);

    closePageOverlay(field_id);
}
function closePageOverlay(button, instant) {
    if(typeof instant == "undefined") {
        instant = false;
    }
    if(typeof button == "undefined" || jQuery("#" + button).length == 0) {
        jQuery("#pageOverlay").hide("fast");
        return;
    }

    var ele = jQuery("#pageOverlay");
    var eleContent = jQuery("#pageOverlayContent");

    if(instant == true) {
        ele.hide();
        return;
    }

    ele.animate({ opacity:0 });
    eleContent.effect( "transfer", { to: jQuery("#" + button) }, 250, function() {
        eleContent.css('display', 'none');
        ele.css('display', 'none');
    });
}
jQuery(function() {
    jQuery("#pageOverlayContent").bind("click", function(e) {
        e.stopPropagation();
    });
});
function openPageOverlay(html, width, button) {
    if(typeof button == "undefined") {
        button = false;
    }

    var ele = jQuery("#pageOverlay");
    var eleContent = jQuery("#pageOverlayContent");
    html = '<img src="modules/Workflow2/icons/cross-button.png" style="position:absolute;right:-5px;top:-5px;cursor:pointer;" onclick="closePageOverlay();">' + html;

    if(ele.css('display') == 'none') {
        eleContent.css("width", width + "px");
        eleContent.css("marginLeft", (-1 * (width / 2)) + "px");

        eleContent.css('visibility', 'hidden');
        ele.css('opacity', '0');

        eleContent.show();
        ele.show();

        eleContent.html(html);
        //eleContent.slideDown("fast");

        if(button != false && jQuery("#" + button).length > 0) {
            ele.animate({ opacity:1 });
            jQuery("#" + button).effect( "transfer", { to: eleContent }, 250, function() {
                eleContent.css('visibility', 'visible');
                // ele.css('visibility', 'visible');
            });
        } else {
            eleContent.css('visibility', 'visible');
            ele.css('opacity', '1');

            ele.show();
        }

    } else {
        eleContent.html(html);
        eleContent.animate({
            width:width + "px",
            marginLeft: (-1 * (width / 2)) + "px"
        }, "fast", function() {

        });
    }

    jQuery('#pageOverlayContent').on('click.select2', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
    jQuery('#pageOverlayContent').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

}
jQuery.fn.sortDivs = function sortDivs() {
    jQuery("> div", this[0]).sort(dec_sort).appendTo(this[0]);
    function dec_sort(a, b){ return (jQuery(b).data("sort")) < (jQuery(a).data("sort")) ? 1 : -1; }
}