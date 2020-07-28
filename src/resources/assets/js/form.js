var modal;

window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

function openLogin() {
    modal = document.getElementById("LoginForm");
    document.getElementById("LoginForm").style.display = "block";
}

function openSign() {
    modal = document.getElementById("SignForm");
    document.getElementById("SignForm").style.display = "block";
}

function openReport() {
    modal = document.getElementById("ReportForm");
    document.getElementById("ReportForm").style.display = "block";
}

function openEdit() {
    modal = document.getElementById("EventEdit");
    document.getElementById("EventEdit").style.display = "block";
}

function question1() {
    var x = document.getElementById("Question1");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

function question2() {
    var x = document.getElementById("Question2");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

function question3() {
    var x = document.getElementById("Question3");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

function question4() {
    var x = document.getElementById("Question4");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

function question5() {
    var x = document.getElementById("Question5");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}