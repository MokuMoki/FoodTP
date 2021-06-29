function password_strength(inputPassword) {
    var password = document.getElementById(inputPassword).value;
    if (password != "") {
        let strengthValue = {
            'caps': false,
            'length': false,
            'special': false,
            'numbers': false,
            'small': false
        };

        if (password.length >= 8) {
            strengthValue.length = true;
        }

        for (let index = 0; index < password.length; index++) {
            let char = password.charCodeAt(index);
            if (!strengthValue.caps && char >= 65 && char <= 90) {
                strengthValue.caps = true;
            } else if (!strengthValue.numbers && char >= 48 && char <= 57) {
                strengthValue.numbers = true;
            } else if (!strengthValue.small && char >= 97 && char <= 122) {
                strengthValue.small = true;
            } else if (!strengthValue.numbers && char >= 48 && char <= 57) {
                strengthValue.numbers = true;
            } else if (!strengthValue.special && (char >= 33 && char <= 47) || (char >= 58 && char <= 64)) {
                strengthValue.special = true;
            }
        }

        let strengthIndicator = 0;
        for (let metric in strengthValue) {
            if (strengthValue[metric] === true) {
                strengthIndicator++;
            }
        }

        switch (strengthIndicator) {
            case 0:
                document.getElementById('password_strength').innerHTML = "";
                break;
            case 1:
                document.getElementById('password_strength').innerHTML = "<div class=\"alert alert-danger\">Your password is very weak.</div>";
                break;
            case 2:
                document.getElementById('password_strength').innerHTML = "<div class=\"alert alert-danger\">Your password is weak.</div>";
                break;
            case 3:
                document.getElementById('password_strength').innerHTML = "<div class=\"alert alert-warning\">Your password is okay.</div>";
                break;
            case 4:
                document.getElementById('password_strength').innerHTML = "<div class=\"alert alert-success\">Your password is strong.</div>";
                break;
            case 5:
                document.getElementById('password_strength').innerHTML = "<div class=\"alert alert-success\">Your password is very strong.</div>";
                break;
        }

        if (strengthIndicator == 3 || strengthIndicator == 4 || strengthIndicator == 5) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function password_match(password, confirmpassword) { //Check if both password field value matches
    var password1 = document.getElementById(password).value;
    var password2 = document.getElementById(confirmpassword).value;

    if (password1 == "" || password2 == "") {
        document.getElementById('password_match').innerHTML = "";
        return false;
    } else if (password1 == password2) {
        document.getElementById('password_match').innerHTML = "<div class=\"alert alert-success\">Your password matches.</div>";
        return true;
    } else {
        document.getElementById('password_match').innerHTML = "<div class=\"alert alert-danger\">Your password doesn't match.</div>";
        return false;
    }
}