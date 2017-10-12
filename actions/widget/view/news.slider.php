<script>
    var pics = '<?php echo implode('|', $images) ?>';
    var links = '<?php echo implode('|', $links) ?>';
    var texts = '';
    var focus_width=303
    var focus_height=221
    var text_height=0
    var swf_height = focus_height+text_height

    var flashCode = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/hotdeploy/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">';
    flashCode = flashCode + '<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="pic/focus.swf"><param name="quality" value="high"><param name="bgcolor" value="#F0F0F0">';
    flashCode = flashCode + '<param name="menu" value="false"><param name=wmode value="opaque">';
    flashCode = flashCode + '<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'">';
    flashCode = flashCode + '<embed src="pic/focus.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'+ focus_width +'" height="'+ swf_height +'" FlashVars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'"></embed>';
    flashCode = flashCode + '</object>';
    document.write(flashCode)
</script>