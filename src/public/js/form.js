var modal;

window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

function openLogin() {
    $('#login').modal();
}

function openSign() {
    $('#signup').modal();
}

function openReport() {
    modal = document.getElementById("ReportForm");
    document.getElementById("ReportForm").style.display = "block";
}

function openReport2(id) {
    modal = document.getElementById("ReportForm2");
    document.getElementById("ReportForm2").style.display = "block";
    document.getElementById("CommentId").value = id;
}

function openEdit() {
    modal = document.getElementById("EventEdit");
    document.getElementById("EventEdit").style.display = "block";
}

function openBannedPopUp() {
    modal = document.getElementById("BannedPopUp");
    document.getElementById("BannedPopUp").style.display = "block";
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