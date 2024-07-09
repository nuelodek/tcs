function validateForm() {
var username = document.getElementById("username").value;
var password = document.getElementById("password").value;

if (username == "" || password == "") {
alert("Please fill in all fields");
return false;
}

var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]).{8,}$/;
if (!passwordRegex.test(password)) {
alert("Password must have at least 8 characters, 1 digit, 1 lowercase letter, 1 uppercase letter, and 1 special character");
return false;
}

return true;
}
