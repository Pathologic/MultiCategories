<script type="text/javascript">
var mcConfig = {
	rid:[+id+],
	_xtAjaxUrl:'[+url+]',
	mcLoaded:false
};
(function($){
    $('#documentPane').on('click','#mc-tab',function(){
        if (!mcConfig.mcLoaded) {
            mcHelper.init();
            mcConfig.mcLoaded = true;
        }
    });
    $(window).on('load', function(){
        if ($('#mc-tab')) {
            $('#mc-tab.selected').trigger('click');
        }
    });
})(jQuery)
</script>
<div id="MultiCategories" class="tab-page">
<h2 class="tab" id="mc-tab">[+tabName+]</h2>
<input type="hidden" name="__multicategories" value="[+categories+]">
<ul id="multiCategoriesTree"></ul>
</div>
