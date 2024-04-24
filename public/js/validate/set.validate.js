//$.validator.setDefaults( {
//submitHandler: function () {
//$.get( "contact", function() {
//			alert("Server Returned: ");
//});
//	}
///	} );

$(document).ready(function () {

    $("#contactForm").validate({
        ignore: [],
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            surname: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                minlength: 3
            },
            country: {
                required: true
            },
            county: {
                required: true
            },
            job: {
                required: true
            },
            industry: {
                required: true
            },
            how_about: {
                required: true
            },
            message: {
                required: true
            },
            hiddenRecaptcha: {
                required: function () {
                    console.log('CaptchaContact');
                    if (grecaptcha.getResponse() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        },
        messages: {
            name: {
                required: "Please enter your first name",
                minlength: "Your first name must be at least 3 characters long"
            },
            surname: {
                required: "Please provide your last name",
                minlength: "Your last name must be at least 3 characters long"
            },
            email: "Please enter a valid email address",
            phone: {
                required: "Please enter your phone number (ex: 0123456789)"
            },
            country: {
                required: "Please provide your country"
            },
            county: {
                required: "Please enter your county"
            },
            job: {
                required: "Please enter your job title"
            },
            industry: {
                required: "Please enter your industry"
            },
            how_about: {
                required: "Please tell us how did you hear about us"
            },
            message: {
                required: "Please enter a message"
            },
            hiddenRecaptcha: {
                required: "Please validate the captcha"
            },
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-sm-6").addClass("has-feedback");
            element.parents(".col-sm-12").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[0]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[0]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-6", ".col-sm-12").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-6", ".col-sm-12").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");

        }
    });
});
