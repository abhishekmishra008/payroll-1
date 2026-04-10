"use strict";

const sleep = (ms) => new Promise((res) => setTimeout(res, ms));
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});


var KTModalAdd = function () {
    var t, e, o, n, r, i, submitBtn;
    return {
        init: function () {
            r = document.querySelector("#kt_sign_in_form"),
            submitBtn = $("#kt_sign_in_submit");
            t = r.querySelector("#kt_sign_in_submit"),
            n = FormValidation.formValidation(r, {
                fields: {
                    login: {
                        validators: {
                            notEmpty: {
                                message: "Login Id is required"
                            },
                            regexp: {
                                // Accepts email, 10-digit number, or username (alphanumeric, dots, underscores, min 3 chars)
                                regexp: /(^[^\s@]+@[^\s@]+\.[^\s@]+$)|(^[0-9]{10}$)|(^[a-zA-Z0-9._]{10,}$)/,
                                message: "Please enter a valid login id (email, phone, or username)"
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: "Password is required"
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger,
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            }),
            t.addEventListener("click", function (n) {
                n.preventDefault();
                n.stopPropagation();
                var loginn = document.getElementById("Login").value;
                var passwordd = document.getElementById("Password").value;
                $.ajax({
                    type: "POST",
                    url: "/auth",
                    data: {
                        login: loginn,
                        password: passwordd
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true && response.code == 200) {
                            $("#kt_sign_in_submit").prop('disabled', true);
                            swal.fire({
                                text: "Your credentials matches our record",
                                icon: "success",
                                showConfirmButton: false
                            }).then(function () {
                                KTUtil.scrollTop();
                            });
                            const work = async () => {
                                await sleep(1000);
                                swal.close();
                                if(response.data.otp_verified == 0) {
                                    $('#otpModal').modal('show');
                                    $('#loggedInUserId').val(response.data.user_id);
                                }
                            };
                            work();
                        } else if (response.success == false && response.code == 201) {
                            $("#kt_sign_in_submit").prop('disabled', false);
                            swal.fire({
                                text: "Password is incorrect",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Try Again",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                }
                            }).then(function () {
                                KTUtil.scrollTop();
                            });
                        } else if (response.success == false && response.code == 202) {
                            $("#kt_sign_in_submit").prop('disabled', false);
                            swal.fire({
                                text: "You have been deactivated from logging into the panel. Kindly contact the admin to reinstate your privileges.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Try Again",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                }
                            }).then(function () {
                                KTUtil.scrollTop();
                            });
                        } else if (response.success == false && response.code == 203) {
                            $("#kt_sign_in_submit").prop('disabled', false);
                            swal.fire({
                                text: "User not found or remove in our records",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Try Again",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                }
                            }).then(function () {
                                KTUtil.scrollTop();
                            });
                        } else if (response.success == false && response.code == 204) {
                            $("#kt_sign_in_submit").prop('disabled', false);
                            swal.fire({
                                text: "User is already logged in, please first logout than try again.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Try Again",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                }
                            }).then(function () {
                                KTUtil.scrollTop();
                            });
                        } else if (response.success == false && response.code == 203) {
                            $("#kt_sign_in_submit").prop('disabled', false);
                            swal.fire({
                                text: "You are not authorised to log into Admin Panel.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Try Again",
                                customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                }
                            }).then(function() {
                                KTUtil.scrollTop();
                            });
                        }
                    }
                });
            });
        }
    }
}();


KTUtil.onDOMContentLoaded(function () {
    KTModalAdd.init();
});


function verifyOtp() {
    let otp = document.getElementById('otpCode').value;
    let userId = document.getElementById('loggedInUserId').value;
    let verifyOtpUrl = window.verifyOtpUrl || '/admin/verify-otp'; 
    $.ajax({
        url: verifyOtpUrl,
        method: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            otp: otp,
            user_id: userId
        },
        success: function(res) {
            if(res.success) {
                Swal.fire({
                    text: "OTP Verified Successfully!",
                    icon: "success",
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "/admin/dashboard";
                });
            } else {
                Swal.fire({
                    text: res.message,
                    icon: "error",
                    showConfirmButton: true
                });
            }
        }
    });
}

