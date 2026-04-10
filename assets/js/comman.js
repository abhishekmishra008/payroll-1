function swalMessage(title, text, icon) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonText: "OK",
        customClass: {
            popup: 'swal-zindex'
        }
    }).then(() => {
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    });
}


function flatPicker(el) {
    if (el._flatpickr) {
        el._flatpickr.open();
        return;
    }
    flatpickr(el, {
        enableTime: true,
        dateFormat: "d F Y",
        onChange: function (selectedDates) {
            if (el.id === 'start_date') {
                let startDate = selectedDates[0];
                let maxEndDate = new Date(startDate); // start_date + 5 days
                maxEndDate.setDate(maxEndDate.getDate() + 5);
                let endInput = document.querySelector('#end_date');
                if (endInput._flatpickr) {
                    endInput._flatpickr.set({
                        minDate: startDate,
                        maxDate: maxEndDate
                    });
                } else {
                    flatpickr(endInput, {
                        minDate: startDate,
                        maxDate: maxEndDate,
                        dateFormat: "d F Y"
                    });
                }
            }
        }
    });
    el._flatpickr.open();
}


function downloadSample(button) {
    const url = button.getAttribute('data-url');
    let id = button.getAttribute('data-id');
    window.location.href = url + '?id=' + id;
}

function showImportModal(button) {
    const type = button.getAttribute('data-type');
    const url = button.getAttribute('data-upload-url');
    const modal = document.getElementById('excel_import_modal');
    modal.setAttribute('data-upload-url', url);
    modal.setAttribute('data-type', type);
    document.getElementById('excel_input_file').value = '';
    $('#excel_import_modal').modal('show');
}


function sampleExcelUpload() {
    event.preventDefault();
    const modal = document.getElementById('excel_import_modal');
    const url = modal.getAttribute('data-upload-url');
    const type = modal.getAttribute('data-type');
    if (!url) {
        swalMessage("Error", "Upload URL missing", "error");
        return;
    }

    let fileInput = document.getElementById('excel_input_file');
    if (!fileInput.files.length) {
        swalMessage("Error", "Please select an Excel file.", "error");
        return;
    }

    let formData = new FormData(document.getElementById('excel_form_upload'));
    formData.append('type', type); // optional (server side use)
    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            swalMessage("Success", "Holiday data uploaded successfully.", "success");
            $('#excel_import_modal').modal('hide');
        },
        error: function (xhr) {
            swalMessage("Error", "An error occurred while uploading the file.", "error");
        }
    });

}

