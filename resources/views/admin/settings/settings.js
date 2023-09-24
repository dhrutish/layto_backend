$("#imageupload").on('change', function() {
    "use strict";
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("imgupload").src = e.target.result;
            $("#imagepreview").hide();
            $("#imagepreview").fadeIn(650);
        };
        reader.readAsDataURL(this.files[0]);
    }
});
$("input[name=is_gst_included]:checked").on('change', function() {
    "use strict";
    managegst($(this).val())
}).change();
$("input[name=is_gst_included]").on('change', function() {
    "use strict";
    managegst($(this).val())
});
function managegst(val) {
    "use strict";
    if (val == 1) {
        $('.gst-content').show();
        $('.gst-content').find('input').attr('disabled', false).attr('required', true);
    } else {
        $('.gst-content').hide();
        $('.gst-content').find('input').attr('disabled', true);
    }
}
