// Сортировка

var sortProcess = null;
function initSort(_elements, _postUri)
{
    if (_elements.length == 0) return;

    _elements.disableSelection();
    _elements.sortable({
        update: function(_event, _ui) {
            var request = "";
            var parent = _ui.item.parent();

            parent.find("input[type = \"hidden\"]").each(function() {
                if (this.parentNode.parentNode == parent[0]) {
                    request += "&" + this.name + "=" + this.value;
                }
            });

            if (request != "") {
                if (sortProcess) window.clearTimeout(sortProcess);

                sortProcess = window.setTimeout(function() {
                    $.post(_postUri, request.substr(1));
                }, 500);
            }
        }
    });
}


// Формы


// Запоминание текущего таба в куках

$(function() {
    $("ul.nav.nav-tabs a[data-toggle=\"tab\"]").each(function() {
        $(this).click(function() {
            $.cookie(
                "cms-open-tab",
                this.getAttribute("href").match(/^#tab-(.+)/)[1],
                {
                    expires: 7,
                    path: window.location.pathname.split("/", 3).join("/")
                }
            );
        });
    });
});

//function checkInputFileSize(_input, _max)
//{
//    var amount = 0;
//    var isError = false;
//
//    for (var i = 0; i < _input.files.length; i++) {
//        amount += _input.files[i].size;
//
//        if (amount > _max) {
//            isError = true;
//            break;
//        }
//    }
//
//    $(_input).closest("div.form-group").toggleClass("has-error", isError);
//    $(_input).closest("form").find(":submit[name!='delete']").prop(
//        "disabled",
//        isError
//    );
//
//    return isError;
//}

