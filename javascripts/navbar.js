// VARS
const documentBody = document;

// FUNCTIONS
function menuOverlay_open() {
    // Shows the navbar menu for small devices when pressed
    document.getElementsByClassName("menuOverlay")[0].style.width = "100%";
    document.getElementsByClassName("menuOverlay_closebtn")[0].classList.remove("fade");
    document.getElementsByClassName("menuOverlay_optionDiv")[0].classList.remove("fade");
}
function menuOverlay_close() {
    // Closes the navbar menu for small devices when pressed
    document.getElementsByClassName("menuOverlay")[0].style.width = "0%";
    document.getElementsByClassName("menuOverlay_closebtn")[0].classList.add("fade");
    document.getElementsByClassName("menuOverlay_optionDiv")[0].classList.add("fade");
}
documentBody.addEventListener('click', function (e) {
    // Shows the navbar menu for small devices when pressed
    if (e.target.className == "menuOverlay" || e.target.className == "menuOverlay_optionDiv") {
        menuOverlay_close();
    }
});

// Functions that control the look of a button when it is hovered over
function moveBtnBkgRight(e) {
    e.parentElement.children[0].style.left = "0";
}
function moveBtnBkgLeft(e) {
    e.parentElement.children[0].style.left = "-100%";
}