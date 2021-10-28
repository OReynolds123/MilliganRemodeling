init();

// Init
function init() {
    if (window.location.href.includes("?")) {
        var checkurl = window.location.href.split("?")[1].split("#")[0];
        if (checkurl == "success=1") {
            document.getElementsByClassName("contactLandingDivLeft")[0].style.marginTop = "40px";
            document.getElementsByClassName("contactLandingDivRight")[0].style.marginTop = "40px";
            document.getElementsByClassName("contactLandingSuccessBanner")[0].style.height = "50px";
            document.getElementsByClassName("contactLandingSuccessBanner")[0].innerHTML = "Success! Contact Form Submitted.";
            document.getElementsByClassName("contactLandingSuccessBanner")[0].style.color = "#000000";
        } else if (checkurl == "success=0") {
            document.getElementsByClassName("contactLandingDivLeft")[0].style.marginTop = "40px";
            document.getElementsByClassName("contactLandingDivRight")[0].style.marginTop = "40px";
            document.getElementsByClassName("contactLandingSuccessBanner")[0].style.height = "50px";
            document.getElementsByClassName("contactLandingSuccessBanner")[0].style.backgroundColor = "#bb000080";
            document.getElementsByClassName("contactLandingSuccessBanner")[0].innerHTML = "Error! Please Try Again.";
        }
    }
}