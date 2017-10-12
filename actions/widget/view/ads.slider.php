  <div class="fullSlide">
    <div class="bd">
      <ul>
        <?php
             foreach($images as $info){
         ?>
        <li _src="url(<?=$info?>)" style="background:#ffffd6 center 0 no-repeat;"></li>
        <?php
             }
         ?>
      </ul>
    </div>
    <div class="hd">
      <ul>
      </ul>
    </div>
    <span class="prev"></span> <span class="next"></span> </div>
  <script type="text/javascript">
jQuery(".fullSlide").hover(function() {
    jQuery(this).find(".prev,.next").stop(true, true).fadeTo("show", 0.5)
},
function() {
    jQuery(this).find(".prev,.next").fadeOut()
});
jQuery(".fullSlide").slide({
    titCell: ".hd ul",
    mainCell: ".bd ul",
    effect: "fold",
    autoPlay: true,
    autoPage: true,
    trigger: "click",
    startFun: function(i) {
        var curLi = jQuery(".fullSlide .bd li").eq(i);
        if ( !! curLi.attr("_src")) {
            curLi.css("background-image", curLi.attr("_src")).removeAttr("_src")
        }
    }
});
</script> 