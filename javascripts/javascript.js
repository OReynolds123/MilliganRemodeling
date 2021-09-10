// VARS
const documentBody = document;

// FUNCTIONS
function menuOverlay_open() {
    document.getElementsByClassName("menuOverlay")[0].style.width = "100%";
    document.getElementsByClassName("menuOverlay_closebtn")[0].classList.remove("fade");
    document.getElementsByClassName("menuOverlay_optionDiv")[0].classList.remove("fade");
}
function menuOverlay_close() {
    document.getElementsByClassName("menuOverlay")[0].style.width = "0%";
    document.getElementsByClassName("menuOverlay_closebtn")[0].classList.add("fade");
    document.getElementsByClassName("menuOverlay_optionDiv")[0].classList.add("fade");
}
documentBody.addEventListener('click', function (e) {
    if (e.target.className == "menuOverlay" || e.target.className == "menuOverlay_optionDiv") {
        menuOverlay_close();
    }
});