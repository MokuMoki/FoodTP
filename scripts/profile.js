// This file is use solely in profile.js //

//jquery to look for links under tabs id, when the button is clicked, the div id under href of the link will be shown.
$("#tabs a").click(function(e) {
    e.preventDefault();
    $(".toggle").hide();
    var toShow = $(this).attr('href');
    $(toShow).show();
    $(e).addClass("active");
});

//Rating Function
$(document).ready(function() {
    $(".rating input:radio").attr("checked", false);

    $('.rating input').click(function() {
        var previous = "#" + $(this).parent().parent().children(".checked").children().attr('id');
        console.log(previous);

        $(previous).parent().removeClass('checked');
        console.log($(previous).parent());
        $(this).parent().addClass('checked');
    });

    $('input:radio').change(
        function() {
            var userRating = this.value;
            var RateUserID = $(this).parent().parent().attr('data-userid');
            var rated = $(this).parent().parent().attr('data-rated');
            var id = $(this).attr('id');
            var type = id.split("");

            $.post("rate.php", {
                    rating: userRating,
                    type: type[0],
                    rated: rated,
                    val: RateUserID
                })
                .done(function(data) {
                    alert(data);
                    location.reload(true);
                });;
        });
});

$('.statusButton div').click(
    function() {
        var orderID = $(this).parent().attr("data-orderID");
        var status = $(this).parent().attr("data-status");

        console.log(orderID, status);
        $.post("update_status.php", {
                orderID: orderID,
                status: status
            })
            .done(function(data) {
                alert(data);
                location.reload(true);
            });;
    });

function generateFoodID(length) {
    var text = "";
    var possible = "abcdef0123456789";

    for (var i = 0; i < length; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

if (document.getElementById('dob')) { //Min age 18
    var date = new Date();

    document.getElementById('dob').max = formatDate(date);

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear() - 18;

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
}

function generateSelect(list, id) { //Generate select options from array specified
    var html = "<option value=\"\" selected disabled>Please Select</option>";
    for (i = 0; i < list.length; i++) {
        html += "<option value=\"" + (i + 1) + "\">" + list[i] + "</option>";
    }
    document.getElementById(id).innerHTML = html;
    document.getElementById(id).selectedIndex = document.getElementById(id).getAttribute("data-value");
}

if (document.getElementById('gender')) {
    var gender = ["Male", "Female", "Others"];
    generateSelect(gender, 'gender');
}

if (document.getElementById('location')) {
    var accom = ["APU On Campus", "Vista Komenwel A", "Vista Komenwel B", "Vista Komenwel C", "Endah Promenade", "Endah Regal", "Fortune Park", "Academia", "Others"];
    generateSelect(accom, 'location');
}

if (document.getElementById('territory')) {
    var territory = ["Bukit Jalil", "Sri Petaling"];
    generateSelect(territory, 'territory');
}

if (document.getElementById('transport')) {
    var transport = ["Car", "Motorbike"];
    generateSelect(transport, 'transport');
}

function areTrue(criteria) {
    return criteria === true;
}

function enableChangePassword() {
    var criterias = [];

    criterias[0] = (document.getElementById('inputOldPassword').value != "");
    criterias[1] = password_strength('inputNewPassword');
    criterias[2] = password_match('inputNewPassword', 'inputNewPassword2');

    if (criterias.every(areTrue) === true) {
        document.getElementById('changepassword').disabled = false;
    } else {
        document.getElementById('changepassword').disabled = true;
    }
}

$(document).ready(function() {
    $('#upload').click(function() {

        var fd = new FormData();
        var files = $('#file')[0].files[0];
        fd.append('file', files);

        // AJAX request
        $.ajax({
            url: 'img_upload.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response != 0) {
                    alert("File uploaded!");
                } else {
                    alert('file not uploaded');
                }
            }
        });
    });

    $('#editFood').click(function() {
        var foodid = $('#editFoodID').val();
        var foodname = $('#editFoodName').val();
        var foodprice = $('#editFoodPrice').val();
        var fd = new FormData();
        var filelength = $("#editfile")[0].files.length;
        if (filelength != 0) {
            var files = $('#editfile')[0].files[0];

            fd.append('file', files);
            fd.append('foodid', foodid);

            $.ajax({
                url: 'img_upload.php',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response != 0) {
                        alert("Food image uploaded!");
                    } else {
                        alert("Error in updating food image.");
                    }
                }
            });
        }

        $.post("profile_core.php", {
                foodname: foodname,
                foodprice: (foodprice * 100),
                foodid: foodid
            })
            .done(function(data) {
                alert(data);
                location.reload(true);
            });;
    });

    $('#addFood').click(function() {
        var foodname = $('#addFoodName').val();
        var foodprice = $('#addFoodPrice').val();
        var foodid = generateFoodID(32);

        var fd = new FormData();
        var files = $('#addfile')[0].files[0];
        var filelength = $("#addfile")[0].files.length;
        if (filelength != 0) {
            fd.append('file', files);
            fd.append('foodid', foodid);

            $.ajax({
                url: 'img_upload.php',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response != 0) {
                        $.post("profile_core.php", {
                                add: 1,
                                foodname: foodname,
                                foodprice: (foodprice * 100),
                                foodid: foodid
                            })
                            .done(function(data) {
                                alert(data);
                                location.reload(true);
                            });;
                        alert("Food image uploaded!");
                    } else {
                        alert("Error in updating food image.");
                    }
                }
            });
        } else {
            alert("Please fill in every field!");
        };
    });

    $('#deleteFood').click(function() {
        var foodname = $('#editFoodName').val();
        var foodid = $('#editFoodID').val();
        var question = "Are you sure you want to remove " + foodname + "?";

        if (confirm(question)) {
            $.post("profile_core.php", {
                    remove: 1,
                    foodid: foodid
                })
                .done(function(data) {
                    alert(data + foodname + ".");
                    location.reload(true);
                });;
        } else {
            // Do nothing!
        }
    });

    $('.edit_button').click(function() {
        var foodid = $(this).attr('data-id');
        var foodname = $(this).attr('data-name');
        var foodprice = ($(this).attr('data-price')) / 100;

        $('#editFoodID').val(foodid);
        $('#editFoodName').val(foodname);
        $('#editFoodPrice').val(foodprice);
    });

    var q = encodeURIComponent($('#address').text());
    $('#map').attr('src', 'https://www.google.com/maps/embed/v1/place?key=AIzaSyAhrb0FsKEPTIK-3EaBA8ClEgxSHpLWXQ0&q=' + q);
});