$(document).ready(function () {
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (form.checkValidity() === false) {
                        form.classList.add('was-validated');
                    }else{
                        $.ajax({
                            type: "POST",
                            url: "sentMail.php",
                            data: {
                                name: $("#name").val(),
                                email: $("#email").val(),
                                phone: $("#phone").val(),
                                comments: $("#comments").val()
                            },
                            success: function (response) {
                                console.log(response);
                                if (response == "1") {
                                    $("#exampleModal").modal("show");
                                    $("#contact_form").trigger("reset");
                                    $("#contact_form").removeClass("was-validated");
                                }
                            }
                        });
                    }
                }, false);
            });
        }, false);
    })();
});