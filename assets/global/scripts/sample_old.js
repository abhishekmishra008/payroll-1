
/* When the user clicks on the button, toggle between hiding and showing the dropdown content */
let lang = $("#language").val();
if (lang == '' || lang == 'en') {
    setCookie('googtrans', '', -1, 'ecovisrkca.com');
} else {
    function googleTranslateElementInit() {
        setCookie('googtrans', '/en/' + lang, 1);
        new google.translate.TranslateElement({
            pageLanguage: 'ES',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }
}

function setCookie(key, value, expiry) {
    var expires = new Date();
    expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
}

function myFunctionDropDown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

function myFunctionDropDown1() {
    document.getElementById("myDropdowndata").classList.toggle("show");
    var divsToHide = document.getElementsByClassName("sameSubmenuClass");
    for (var i = 0; i < divsToHide.length; i++) {

        divsToHide[i].style.display = "none";
    }
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {

        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
    if (!event.target.matches('.dropbtn1')) {

        var dropdowns = document.getElementsByClassName("dropdown-content1");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

function hideShowMenuDiv(id) {
    var divsToHide = document.getElementsByClassName("sameSubmenuClass");
    for (var i = 0; i < divsToHide.length; i++) {

        divsToHide[i].style.display = "none";
    }

    var div_id = document.getElementById(id);
    div_id.style.display = "block";
}

let execution_url = "https://execution.ecovisrkca.com/";
function goToHrms(id) {
    if (id == "execution") {
        window.location.href = execution_url + "dashboard";
    }
}

let rmt_url = "https://rmt.ecovisrkca.com/";
function goToRmt(id) {
    if (id == "mail") {
        window.location.href = rmt_url + "email_client1";
    }
    if (id == "projectManagement") {
        window.location.href = rmt_url + "projectManagementHome";
    }
    if (id == "chat") {
        window.location.href = rmt_url + "messenger";
    }
    if (id == "folder") {
        window.location.href = rmt_url + "folder_desktop_view";
    }
    if (id == "board") {
        window.location.href = rmt_url + "MobileViewController/board";
    }
    if (id == "customer") {
        window.location.href = rmt_url + "viewcustomerMobile";
    }
    if (id == "employee") {
        window.location.href = rmt_url + "view_employeeMobile";
    }
    if (id == "task") {
        window.location.href = rmt_url + "task_mobile";
    }
    if (id == "service") {
        window.location.href = rmt_url + "service_mobile";

    }
    if (id == "view_officeMobile") {
        window.location.href = rmt_url + "view_officeMobile";
    }
    if (id == "designation_mobile") {
        window.location.href = rmt_url + "designation_mobile";
    }
    if (id == "customerserviceMobile") {
        window.location.href = rmt_url + "customerserviceMobile";
    }
    if (id == "PermissionMobile") {
        window.location.href = rmt_url + "PermissionMobile";
    }
    if (id == "BoardProject") {
        window.location.href = rmt_url + "BoardProject";
    }
}

function goToPayroll(id) {
    var payroll_url = '<?= base_url() ?>';
    window.location.href = payroll_url + id;
}

let amgt_url = 'https://amgt.ecovisrkca.com/';
function goToCrm(id) {
    var email = $("#email_emp").val();
    window.location.href = amgt_url + "survey/LoginController/login_api/" + email + "/" + id;
}
let tally_url = 'https://actai_gbt.ecovisrkca.com/';
function goToTally() {
    var email = $("#email_emp").val();
    window.location.href = tally_url + "LoginController/loginFromOtherWebsite?index=1&username=" + email;
}

function goToGlobalAct() {
    window.open('https://act.ecovisrkca.com/', '_blank');
}

function scheduleTaskTimeSheet(sheet) {
    var email = $("#email_emp").val();
    var base_url1 = '<?= base_url() ?>';
    window.location.href = base_url1 + "scheduleTask";
}
function goToBoardProject() {
    window.location.href = rmt_url + "BoardProject";
}
function goToTicketEngine() {
    var email = $("#email_emp").val();
    window.open('https://ticket.ecovisrkca.com/LoginController/login_api/' + email, '_blank');
}

function goToActAiJapan() {
    window.location.href = act_japan_url;
}
let glenmark = 'https://act.ecovisrkca.com/';
function goToConsollidation() {
    window.location.href = glenmark;
}

function goToRepository() {
    window.location.href = "https://rmt.ecovisrkca.com/intranet";
}
function goToGoals(id) {
    window.location.href = rmt_url + "goals";
}
function wordReport(word_report) {
    var email = $("#email_emp").val();
    var base_url1 = '<?= base_url() ?>';
    window.open(base_url1 + 'word_report', '_blank');
    //window.location.href = base_url1+"word_report";
}

// sidemenu script
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

function toggleNav() {
    var sidebar = document.getElementById("mySidenav");
    var sidebarWidth = sidebar.style.width;

    if (sidebarWidth === "0px" || sidebarWidth === "") {
        // If the sidebar is closed or not set
        sidebar.style.width = "250px"; // Open the sidebar
    } else {
        // If the sidebar is open
        sidebar.style.width = "0"; // Close the sidebar
        $('.collapse').removeClass('show')
    }
}

function loginTicketManagement() {
    let firm = $("#firm_id").val();
    let email = $("#email_emp").val();
    if (!email) {
        alert('Email not found');
        return;
    }
    $.post("<?= base_url('ticket_login_by_payroll') ?>", {
        email: email,
        firm_id: firm
    }, function (res) {
        if (res.status === true) {
            window.location.href =
                "http://localhost/ticket/login_from_payroll?token=" + res.token;
            // "https://ticket.ecovisrkca.com/login_from_payroll?token=" + res.token;
        } else {
            alert(res.message ?? 'Login failed');
        }
    }, 'json')
        .fail(function () {
            alert('Server error, please try again');
        });
}




