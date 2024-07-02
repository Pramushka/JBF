document.addEventListener("DOMContentLoaded", function() {
    var modal = document.getElementById("editProfileModal");
    var btn = document.querySelector(".add-profile-section");
    var span = document.getElementsByClassName("close-button")[0];

    btn.onclick = function() {
        modal.style.display = "flex";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});


