function checker() {
    function checktext(id) { //Check if field is empty
        if (document.getElementById(id).value != "") {
            return true;
        } else {
            return false;
        }
    }

    function selected(id) { //Check if select input selected something other than value 0
        if (document.getElementById(id).selectedIndex != 0) {
            return true;
        } else {
            return false;
        }
    }

    function agreeterm() { //Check if agreeterm is checked
        if (document.getElementById('agreeterm').checked) {
            return true;
        } else {
            return false;
        }
    }

    function areTrue(criteria) { //Check if all criteria are true
        return criteria === true;
    }

    var role = document.getElementById('register_type').value;
    var criterias = [];

    if (role == 1) {
        criterias[0] = checktext('inputRealname');
        criterias[1] = checktext('inputNickname');
        criterias[2] = checktext('inputEmail');
        criterias[3] = password_strength('inputPassword');
        criterias[4] = password_match('inputPassword', 'inputPassword2');
        criterias[5] = selected('gender');
        criterias[6] = checktext('dob');
        criterias[7] = selected('location');
        criterias[8] = agreeterm();
    } else if (role == 2) {
        criterias[0] = checktext('inputRestaurant');
        criterias[1] = checktext('inputNickname');
        criterias[2] = checktext('inputEmail');
        criterias[3] = password_strength('inputPassword');
        criterias[4] = password_match('inputPassword', 'inputPassword2');
        criterias[5] = checktext('inputDesc');
        criterias[6] = checktext('inputStreet');
        criterias[7] = checktext('inputArea');
        criterias[8] = selected('territory');
        criterias[9] = checktext('inputCuisine');
        criterias[10] = agreeterm();
    } else if (role == 3) {
        criterias[0] = checktext('inputRealname');
        criterias[1] = checktext('inputNickname');
        criterias[2] = checktext('inputIC');
        criterias[3] = checktext('inputEmail');
        criterias[4] = password_strength('inputPassword');
        criterias[5] = password_match('inputPassword', 'inputPassword2');
        criterias[6] = selected('gender');
        criterias[7] = checktext('dob');
        criterias[8] = selected('transport');
        criterias[9] = checktext('inputLicense');
        criterias[10] = agreeterm();
    }

    if (criterias.every(areTrue) === true) {
        document.getElementById('register').disabled = false;
    } else {
        document.getElementById('register').disabled = true;
    }
}