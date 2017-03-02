/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */

/* global SCHEMES_URL, IMAGE_SRC, URL */

$(document).ready(function () {
    $(".insured input[type=radio]").click(function () {
        toggle_schemes();
    });
    function toggle_schemes() {
        if ($('#s_schemes').is(':checked')) {
            if ($("#schemes").hasClass("hidden")) {
                $("#schemes").removeClass("hidden");
            }
        } else {
            if (!$("#schemes").hasClass("hidden")) {
                $("#schemes").addClass("hidden");
            }
        }
    }
    toggle_schemes();
    $("#s_schemes,#h_schemes").click(function () {
        toggle_schemes();
    });
    function apply_schemes(that) {
        //initialize
        $("#" + id + " .scheme").empty();
        var options = "";
        var id = $(that).parent().parent().parent().parent().attr('id');
        var val = $(that).val();
        if (!val) {
            return;
        }

        $.ajax({
            url: SCHEMES_URL,
            data: {'id': val},
            success: function (data) {
                $.each(data, function (key, value) {
                    options += '<option value="' + key + '">' + value + '</option>';
                });
                $("#" + id + " .scheme").html(options);
            }});
    }
    $(".company").change(function () {
        apply_schemes(this);
    });
    //new fields
    var max_fields = 5; //maximum input boxes allowed
    var wrapper = $(".schemes"); //Fields wrapper
    var add_button = $(".add_button"); //Add button ID

    var x = 1; //initlal text box count
    var html_template = $('#wrapper1').html();
    $(add_button).click(function (e) { //on add input button click
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
            x++;
            var ids = 'wrapper' + x;
            var title = 'Insurance ' + x;
            $(wrapper).append("<h5>" + title + "</h5><div id='" + ids + "'>" + html_template + "</div>");
            $(".date").datepicker({
                dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, defaultDate: '-20y', yearRange: "1900:+0"}
            );
            $(".company").change(function () {
                apply_schemes(this);
            });
        }
    });
    $(".date").datepicker({
        dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, defaultDate: '-20y', yearRange: "1900:+0"}
    );
    $('form').submit(function (e) {
        var form = $(this);
        var input = $("<input>")
                .attr("type", "hidden")
                .attr("name", "imagesrc").val(IMAGE_SRC);
        form.append($(input));
    });
    $('#photo').change(function (e) {
        var image_path = URL.createObjectURL(e.target.files[0]);
        $('#thephoto').attr("src", image_path);
        toDataUrl(image_path, function (base64Img) {
            IMAGE_SRC = base64Img;
        });
    });

    function toDataUrl(src, callback) {
        var img = new Image();
        img.crossOrigin = 'Anonymous';
        img.onload = function () {
            var canvas = document.createElement('CANVAS');
            var ctx = canvas.getContext('2d');
            var dataURL;
            canvas.height = this.height;
            canvas.width = this.width;
            ctx.drawImage(this, 0, 0);
            dataURL = canvas.toDataURL('image/png');
            callback(dataURL);
        };
        img.src = src;
    }


    $('#nok_relation').change(function (e) {
        alert('yea')
    });
});