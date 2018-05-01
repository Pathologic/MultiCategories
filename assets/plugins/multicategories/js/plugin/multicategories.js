var mcHelper = {};
(function ($) {
    mcHelper = {
        init: function () {
            $('#multiCategoriesTree').tree({
                url:mcConfig._xtAjaxUrl,
                cascadeCheck: false,
                queryParams: {
                    'rid':mcConfig.rid
                },
                checkbox: function(node) {
                    var parent = mcHelper.getParent();
                    if (node.id == parent) {
                        return false;
                    }
                    return true;
                },
                onBeforeSelect: function() {
                    return false;
                },
                onCheck:function(node, checked) {
                    var parent = mcHelper.getParent();
                    if (node.id == parent) {
                        return false;
                    }
                    var save = [];
                    var checkedItems = $(this).tree('getChecked');
                    if (checkedItems.length) {
                        $.each(checkedItems, function(index, value){
                            save.push(value.id);
                        });
                    }
                    $('input[name="__multicategories"]').val(save.join());
                },
                onLoadError:function(xhr) {
                    if (xhr.status != 200) {
                        $.messager.alert(_mcLang['error'], _mcLang['server_error'] + xhr.status + ' ' + xhr.statusText, 'error');
                    }
                }
            });
        },
        getParent: function() {
            return $('input[name="parent"]').val();
        },
    }
})(jQuery);
