
"use strict";
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});

// Bank Details Management
function saveUserForm(event) {
    event.preventDefault();
    let form = {
        role_id: document.getElementById('roleId'),
        username: document.getElementById('username'),
        name: document.getElementById('name'),
        phone: document.getElementById('phone'),
        email: document.getElementById('email'),
        date_of_birth: document.getElementById('date_of_birth'),
        address: document.getElementById('address'),
        gender: document.getElementById('gender')
    };

    let flag = false;
    for (var key in form) {
        let element = form[key];
        if (!element) {
            console.warn("Element not found for key:", key);
            continue;
        }
        if (element.tagName === 'SELECT') {
            if (!element.value || element.value === '' || element.value === '0') {
                Swal.fire({
                    title: 'Missing fields',
                    text: "Please select " + (element.getAttribute('data-label') || key) + ".",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                flag = true;
                return;
            }
        } else if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
            if (!element.value || element.value.trim() === '') {
                Swal.fire({
                    title: 'Missing ' + key,
                    text: "Please enter " + (element.placeholder || key) + ".",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                flag = true;
                return;
            }
        }
    }

    if (flag) {
        return;
    }
    submitFormData(form);
}

function submitFormData(form) {
    let role_id = form.role_id.value;
    let username = form.username.value;
    let name = form.name.value;
    let phone = form.phone.value;
    let email = form.email.value;
    let date_of_birth = form.date_of_birth.value;
    let address = form.address.value;
    
    $.ajax({
        url: 'admin/user/save',
        type: 'POST',
        data: {
            role_id: role_id,
            username: username,
            name: name,
            phone: phone,
            email: email,
            date_of_birth: date_of_birth,
            address: address,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    title: 'Success',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    fetchUserData();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while saving the user data.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

function fetchUserData() {
    $.ajax({
        url: 'admin/user/save',
        method: 'GET',
        success: function(res) {
            
        }
    })
}

let addMoreRows=2;
function showUploadDocumentImage(input) {
    let files = input.files[0];
    let previewContainer = input.closest('.previewContainer');
    let imagePreview = previewContainer.querySelectorAll('.imagePreview')[0];
    let docPreview = previewContainer.querySelectorAll('.docPreview')[0];
    let iconPreview = previewContainer.querySelectorAll('.iconPreview')[0];
}


function addMoreBankDetails(e) {
    e.preventDefault();
    let newBankDetails = `
            <tr class="bankDetails_${addMoreRows}">
                <td >
                    <div class="btn btn-danger mt-1"><a type="button" id="nextButton" class="fa-solid fa-user-minus removeTr" onclick='removeTr(${addMoreRows})'></a><div>
                </td>
                <td><input type="text" name="bank_name[]" id="bank_name_${addMoreRows}" oninput="stringValidation(event)" class="form-control" placeholder="bank name"></td>
                <td><input type="text" name="branch_name[]" id="branch_name_${addMoreRows}" oninput="stringValidation(event)" class="form-control" placeholder="branch name"></td>
                <td><input type="text" name="account_holder_name[]" id="account_holder_name_${addMoreRows}" oninput="stringValidation(event)" class="form-control" placeholder="account holder name"></td>
                <td><input type="text" name="account_number[]" id="account_number_${addMoreRows}" oninput="acceptOnlyNumber(event)" class="form-control" placeholder="account number"></td>
                <td><input type="text" name="ifsc_code[]" id="ifsc_code_${addMoreRows}" oninput="stringValidation(event)" class="form-control" placeholder="IFSC code"></td>
                <td><input type="text" name="beneficiary_name[]" id="beneficiary_name_${addMoreRows}" oninput="stringValidation(event)" class="form-control" placeholder="beneficiary name"></td>
                <td>
                    <input type="file" name="documents[]" class="form-control fileInput" onchange="showUploadDocumentImage(this)" placeholder="Upload document">
                    <div class="previewContainer" style="display: none;">
                        <img class="imagePreview" style="width: 100px; height: 100px; display: none;" />
                        <span onclick="removePreview(this)" style="position: absolute; top: -10px; right: -10px; cursor: pointer; background: red; color: white; border-radius: 50%; padding: 0 5px;">X</span>
                        <iframe class="docPreview" style="width: 100px; height: 100px; display: none;"></iframe>
                        <div class="iconPreview" style="width: 100px; height: 100px; text-align: center; line-height: 100px; border: 1px solid #ccc;">📄</div>
                    </div>
                </td>
            </tr>
    `;
    $('#bankDetailsTable tbody').append(newBankDetails);
    addMoreRows++;
    document.querySelectorAll('.removeTr').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('tr').remove();
        });
    });
}


function addMoreExperienceDetails(e) {
    e.preventDefault();
    let newExperienceDetails = `
            <tr class="experienceDetails_${addMoreRows}">
                <td>
                    <div class="btn btn-danger mt-1"><a type="button" id="nextButton" class="fa-solid fa-user-minus removeTr" onclick='removeTr(${addMoreRows})'></a><div>
                </td>
                <td><input type="text" name="company_name[]" id="company_name_${addMoreRows}" oninput="stringValidation(event)" oninput="stringValidation(event)"  class="form-control" placeholder="previous company name"></td>
                <td><input type="text" name="previous_role[]" id="previous_role_${addMoreRows}" oninput="stringValidation(event)"  class="form-control" placeholder="previous role"></td>
                <td><input type="text" name="experience[]" id="experience_${addMoreRows}" oninput="acceptOnlyNumber(event)" class="form-control" placeholder="previous experience"></td>
                <td><input type="text" name="previous_doj[]" id="previous_doj_${addMoreRows}" onclick="openFlatpickr(event)" class="form-control" placeholder="previous joining working date"></td>
                <td><input type="text" name="previous_doe[]" id="previous_doe_${addMoreRows}" onclick="openFlatpickr(event)" class="form-control" placeholder="previous last working date"></td>
                <td><input type="number" name="previous_salary[]" id="previous_salary_${addMoreRows}" oninput="acceptOnlyNumber(event)" class="form-control" placeholder="previous salary"></td>
                <td><input type="text" name="previous_hr_name[]" id="previous_hr_name_${addMoreRows}" oninput="stringValidation(event)" class="form-control" placeholder="previous hr name"></td>
                <td><input type="text" name="previous_hr_contact[]" id="previous_hr_contact_${addMoreRows}" oninput="acceptOnlyNumber(event)"  class="form-control" placeholder="previous hr contact"></td>
                <td><input type="text" name="previous_project[]" id="_${addMoreRows}" oninput="stringValidation(event)"  class="form-control" placeholder="previous project"></td>
                <td><input type="text" name="previous_technology[]" id="previous_project_${addMoreRows}"  oninput="stringValidation(event)"  class="form-control" placeholder="previous technology use"></td>
                <td>
                    <input type="file" name="experience_documents[]" id="experience_documents_${addMoreRows}" class="form-control fileInput" onchange="showUploadDocumentImage(this)" placeholder="Upload document">
                    <div class="previewContainer" style="display: none;">
                        <img class="imagePreview" style="width: 100px; height: 100px; display: none;" />
                        <span onclick="removePreview(this)" style="position: absolute; top: -10px; right: -10px; cursor: pointer; background: red; color: white; border-radius: 50%; padding: 0 5px;">X</span>
                        <iframe class="docPreview" style="width: 100px; height: 100px; display: none;"></iframe>
                        <div class="iconPreview" style="width: 100px; height: 100px; text-align: center; line-height: 100px; border: 1px solid #ccc;">📄</div>
                    </div>
                </td>
            </tr>
    `;
    $('#experienceDetailsTable tbody').append(newExperienceDetails);
    addMoreRows++;
    document.querySelectorAll('.removeTr').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('tr').remove();
        }); 
    });
}


function addMoreDocumentDetails(e) {
    e.preventDefault();
    let newRowDetails = `
            <tr class="documentDetails_${addMoreRows}">
                <td class="col-lg-1">
                    <div class="btn btn-danger mt-1"><a type="button" id="nextButton" class="fa-solid fa-user-minus removeTr" onclick='removeTr(${addMoreRows})'></a><div>
                </td>
                <td class="col-lg-3">
                    <select name="document_type" class="form-select" id="document_type" data-control="select2" data-placeholder="Select document type" >
                        <option></option>
                        <option value="marksheet">{{ __('Marksheet')}}</option>
                        <option value="certification">{{ __('Certification')}}</option>
                        <option value="pan_card">{{ __('Pan Card')}}</option>
                        <option value="aadhar_card">{{ __('Aadhar Card')}}</option>
                        <option value="room_rent_agreement">{{ __('Room Rent Agreement')}}</option>
                    </select>
                </td>
                <td class="col-lg-5">
                    <input type="file" name="employee_personal_documents[]" id="employee_personal_documents" class="form-control fileInput" onchange="showUploadDocumentImage(this)" placeholder="Upload document">
                    <div class="previewContainer" style="display: none;">
                        <img class="imagePreview" style="width: 100px; height: 100px; display: none;" />
                        <span onclick="removePreview(this)" style="position: absolute; top: -10px; right: -10px; cursor: pointer; background: red; color: white; border-radius: 50%; padding: 0 5px;">X</span>
                        <iframe class="docPreview" style="width: 100px; height: 100px; display: none;"></iframe>
                        <div class="iconPreview" style="width: 100px; height: 100px; text-align: center; line-height: 100px; border: 1px solid #ccc;">📄</div>
                    </div>
                </td>
            </tr>
    `;
    $('#documentDetailsTable tbody').append(newRowDetails);
    addMoreRows++;
    document.querySelectorAll('.removeTr').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('tr').remove();
        });
    });
}


// Attendence Management
document.querySelectorAll('input[name="choice"]').forEach((elem) => {
    elem.addEventListener("change", function(event) {
        if (event.target.value === "punchValue1") {
            document.getElementById("content1").style.display = "block";
            document.getElementById("content2").style.display = "none";
        } else {
            document.getElementById("content1").style.display = "none";
            document.getElementById("content2").style.display = "block";
        }
    });
});



document.addEventListener("DOMContentLoaded", function () {
    // Initially hide save button
    const saveBtn = document.getElementById("userFormBtn");
    saveBtn.style.display = "none";

    // On input/change in required fields, check if all filled
    const form = document.getElementById("quickForm");

    form.querySelectorAll("[required], input, select, textarea").forEach(field => {
        field.addEventListener("input", function () {
            checkRequiredFields("quickForm", "userFormBtn");
        });
        field.addEventListener("change", function () {
            checkRequiredFields("quickForm", "userFormBtn");
        });
    });

    // Attach submit validation
    validateBeforeSubmit("quickForm", "userFormBtn");
});





