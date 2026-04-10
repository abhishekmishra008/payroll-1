"use strict";
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});


// Role Js Start --
 "use strict";
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});


// Role Js Start --
    // function fetchRoles() {
    //     $.ajax({
    //         url: 'admin/role',
    //         method: "GET",
    //         success: function(res) {
    //             let tbody = '';
    //             if(res) {
    //                 $.each(res, function(_, role) {
    //                     tbody += '<tr>';
    //                     tbody += '<td class="text-center">' + role.id + '</td>';
    //                     tbody += '<td class="text-center">' + role.name + '</td>';
    //                     tbody += '<td class="text-center">';
    //                     tbody += '<button class="btn btn-sm btn-primary editRole" data-id="'+role.id+'">Edit</button> ';
    //                     tbody += '<button class="btn btn-sm btn-danger deleteRole" data-id="'+role.id+'">Delete</button>';
    //                     tbody += '</td>';
    //                     tbody += '</tr>';
    //                 });
    //             } else {
    //                 tbody = '<tr><td colspan="3" class="text-center">No roles found.</td></tr>';
    //             }
    //             $('#kt_table tbody').html(tbody);
    //             $('#completeValue').text(res.total || res.length);
    //         }   
    //     });
    // }

    function saveRole(e) {
        e.preventDefault();
        $('.roleBtn').prop('disabled', true);
        let roleName = $("#roleName").val();
        if (roleName === '') {
            validationAlert('Missing role name', 'Please enter a role name.', 'error', 2000, 'OK');
            $('.roleBtn').prop('disabled', false);
            return false;
        }
        submitRole(roleName)
    }

    function submitRole(roleName) {
        let role = roleName;
        let url = 'admin/role/save';
        $('#addRole').modal('hide');
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                role: role
            },
            success: function(res) {
                if(res.success) {
                    $('.roleBtn').prop('disabled', true);
                    $('#roleName').val('');
                    validationAlert('Role created', 'Successfully created a new role.', 'success', 2000, 'OK');
                    setTimeout(function() {
                        // fetchRoles();
                    }, 200);
                }
            },
            error: function(xhr) {
                $('.roleBtn').prop('disabled', false);
                if(xhr.responseJSON) {
                    validationAlert('Already exists ', xhr.responseJSON.message.role, 'error', 5000, "OOP's");
                    console.log(xhr.responseJSON.message.role);
                }
            }

        });
    }
    
    function editRole(button) {
        var roleId = button.getAttribute('data-id')
        var roleName = button.getAttribute('data-name')
        document.getElementById('roleId').value = roleId;
        document.getElementById('updateRoleName').value = roleName;
    }

    function editRoleBtn(e) {
        e.preventDefault();
        $('#updateRoleBtnId').prop('disabled', true);
        let id = $("#roleId").val();
        let roleName = $("#updateRoleName").val();
        if (roleName === '') {
            validationAlert('Missing role name', 'Please enter a role name.', 'error', 2000, 'OK');
            $('#updateRoleBtnId').prop('disabled', false);
            return false;
        }
        // alert('abhishek mishra' + '\n' + roleName + '\n' + id);
        updateRole(id, roleName)
    }

    function updateRole(id, roleName) {
        let role = roleName;
        let url = 'admin/role/update';
        $('#editRole').modal('hide');
        // alert('abhishek mishra' + '\n' + role + '\n' + url);
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                role: role,
                id: id
            },
            success: function(res) {
                if(res.success) {
                    $('#updateRoleBtnId').prop('disabled', true);
                    $('#updateRoleName').val('');
                    validationAlert('Role updated', 'Successfully updated the role.', 'success', 2000, false);
                    setTimeout(function() {
                        // fetchRoles();
                    }, 200);
                }
            },
            error: function(xhr) {
                $('#updateRoleBtnId').prop('disabled', false);
                if(xhr.responseJSON) {
                    validationAlert('Already exists ', xhr.responseJSON.message.role, 'error', 5000, false);
                    console.log(xhr.responseJSON.message.role);
                }
            }

        });
    }
// Role Js End --


// Module JS Start --
    function saveModule(e) {
        e.preventDefault();
        $('.moduleBtn').prop('disabled', true);
        let moduleName = $('#moduleName').val();
        if(moduleName === '') {
            validationAlert('Missing module name', 'Please enter a module name.', 'error', 2000, 'OK');
            $('.moduleBtn').prop('disabled', false);
            return false;
        }
        submitModule(moduleName)
    }

    function submitModule(moduleName) {
        $('.moduleBtn').prop('disabled', false);
        $('#moduleName').val('');
        let url = 'admin/module/save';
        let indexUrl = 'admin/module';
        let columns = ['id', 'name', 'action']; // yaha aapke module table ke columns
        let tableId = '#moduleTable'; // yaha aapke module table ka ID
        $('#addModule').modal('hide');
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: moduleName
            },
            success: function(res) {
                if(res.success === true) {
                    validationAlert('Role created', 'Successfully created a new module.', 'success', 2000, 'OK');
                    setTimeout(function() {
                        loadDatabaseRecord(
                            indexUrl, columns, tableId,
                            function (btn) {   // edit function
                                let id = $(btn).data('id');
                                let name = $(btn).data('name');
                                $('#editModuleId').val(id);
                                $('#editModuleName').val(name);
                            },
                            function (e, btn) {
                                let id = $(btn).data('id');
                                if(confirm('Delete this record?')) {
                                    // ajax call for delete
                                    console.log('Deleting', id);
                                }
                            },
                            function (e, btn) {  // show function
                                let id = $(btn).data('id');
                                let name = $(btn).data('name');
                                alert('Showing record: ' + id + ' - ' + name);
                            },
                            '#editModule' // edit modal id
                        );
                    }, 200);
                }
            },
            error: function(xhr) {
                let response = xhr.responseJSON;
                if (response) {
                    switch (response.error) {
                        case 1:
                            validationAlert('Validation Error', response.message.errors?.name?.[0] || 'Validation failed', 'error', 5000, "OOP's");
                            break;
                        case 2:
                            validationAlert('Error', 'Url name and module name are not match.', 'error', 5000, "OOP's");
                            break;
                        case 3:
                            validationAlert('Duplicate', 'Module name already exists, please try another one.', 'error', 5000, "OOP's");
                            break;
                        default:
                            validationAlert('Error', response.message || 'Something went wrong.', 'error', 5000, "OOP's");
                            break;
                    }
                } else {
                    validationAlert('Error', 'Unexpected server error', 'error', 5000, "OOP's");
                }
            }
        });
    }

// Module JS End --


// Permission Js Start --
    function savePermission(e) {
        e.preventDefault();
        $('.permissionBtn').prop('disabled', true);
        let permissionName = $("#permissionName").val();
        let appUrl = $("#appUrl").val();
        let moduleName = document.getElementById('moduleName').value;
        if (permissionName === '') {
            validationAlert('Missing permission name', 'Please enter a permission name.', 'error', 2000, 'OK');
            $('.permissionBtn').prop('disabled', false);
            return false;
        }
        if (appUrl === '') {
            validationAlert('Missing application url', 'Please enter a valid application url related to current permission.', 'error', 2000, 'OK');
            $('.permissionBtn').prop('disabled', false);
            return false;
        }
        if (moduleName === '') {
            validationAlert('Missing module name', 'Please enter a module name.', 'error', 2000, 'OK');
            $('.permissionBtn').prop('disabled', false);
            return false;
        }
        submitPermission(permissionName, appUrl, moduleName);
    }

    function submitPermission(permissionName, appUrl, moduleName) {
        let url = '/admin/permission/save';
        let indexUrl = 'admin/permission';
        let columns = ['id', 'name', 'action'];
        let tableId = '#permissionTable';
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                permission: permissionName,
                app_url: appUrl,
                module: moduleName
            },
            success: function(res) {
                if(res.success) {
                    $('.permissionBtn').prop('disabled', false);
                    $('#addPermission').modal('hide');
                    $('#permissionName').val('');
                    $("#appUrl").val('');
                    $('#moduleName').val('');
                    validationAlert('Permission created', 'Successfully created a new permission.', 'success', 2000, 'OK');
                    setTimeout(function() {
                        window.location.reload();
                        loadDatabaseRecord(
                            indexUrl, columns, tableId,
                            function (btn) {   // edit function
                                let id = $(btn).data('id');
                                let name = $(btn).data('name');
                                $('#editModuleId').val(id);
                                $('#editModuleName').val(name);
                            },
                            function (e, btn) {
                                let id = $(btn).data('id');
                                if(confirm('Delete this record?')) {
                                    // ajax call for delete
                                    console.log('Deleting', id);
                                }
                            },
                            function (e, btn) {  // show function
                                let id = $(btn).data('id');
                                let name = $(btn).data('name');
                                alert('Showing record: ' + id + ' - ' + name);
                            },
                            '#editModule' // edit modal id
                        );
                    }, 200);
                }
            },
            error: function(xhr) {
                $('.permissionBtn').prop('disabled', false);
                let response = xhr.responseJSON;
                console.log(xhr.responseJSON.message.permission);
                if(response) {
                    switch (response.error) {
                        case 1:
                            if(xhr.responseJSON.message != '') {
                                for(let key in xhr.responseJSON.message) {
                                    if(xhr.responseJSON.message.hasOwnProperty(key)) {
                                        if(xhr.responseJSON.message[key][0] != '') {
                                            validationAlert('Validation Error', xhr.responseJSON.message[key][0] || 'Validation failed', 'error', 5000, "OOP's");
                                        } else if(xhr.responseJSON.message[key][1] != '') {
                                            validationAlert('Validation Error', xhr.responseJSON.message[key][1] || 'Validation failed', 'error', 5000, "OOP's");
                                        } else {
                                            validationAlert('Validation Error', xhr.responseJSON.message[key][2] || 'Validation failed', 'error', 5000, "OOP's");
                                        }
                                    }
                                }
                            } else if(xhr.responseJSON.message.permission != '') {
                                validationAlert('Already exist', xhr.responseJSON.message.permission || 'Already exist', 'error', 5000, "OOP's");
                            }
                            break;
                        case 2:
                            validationAlert('Error', 'Invalid module name selected.', 'error', 5000, "OOP's");
                            break;
                        default:
                            validationAlert('Error', response.message || 'Something went wrong.', 'error', 5000, "OOP's");
                            break;
                    }
                } else {
                    validationAlert('Error', 'Unexpected server error', 'error', 5000, "OOP's");
                }
            }
        })

    }

    function editPermission(button) {
        let permissionId = button.getAttribute('data-id');
        let permissionName = button.getAttribute('data-name');
        let appUrl = button.getAttribute('data-app-url');
        let moduleName = button.getAttribute('data-module-id') || '';
        let $moduleSelect = $('#updateModuleName');
        if ($moduleSelect.is('select')) {
            if ($moduleSelect.find('option[value="' + moduleName + '"]').length > 0) {
                $moduleSelect.val(moduleName).trigger('change');
            } else {
                $moduleSelect.val('').trigger('change');
            }
        } else {
            document.getElementById('updateModuleName').value = moduleName;
        }
        document.getElementById('hiddenPremissionId').value = permissionId;
        document.getElementById('updatePermissionName').value = permissionName;
        document.getElementById('updateAppUrl').value = appUrl;
    }

    function updatePermission(e) {
        e.preventDefault();
        $('#updatePermissionBtn').prop('disabled', true);
        let permissionId = $("#hiddenPremissionId").val();
        let permissionName = $("#updatePermissionName").val();
        let updateAppUrl = $("#updateAppUrl").val();
        let moduleName = $("#updateModuleName").val();
        if (permissionName === '') {
            validationAlert('Missing permission name', 'Please enter a permission name.', 'error', 2000, 'OK');
            $('#updatePermissionBtn').prop('disabled', false);
            return false;
        }

        if (updateAppUrl === '') {
            validationAlert('Missing application url', 'Please enter a valid application url related to current permission.', 'error', 2000, 'OK');
            $('#updatePermissionBtn').prop('disabled', false);
            return false;
        }

        if (moduleName === '') {
            validationAlert('Missing module name', 'Please enter a route name.', 'error', 2000, 'OK');
            $('#updatePermissionBtn').prop('disabled', false);
            return false;
        }
        updatePermissionAjax(permissionId, permissionName, updateAppUrl, moduleName);
    }

    function updatePermissionAjax(permissionId, permissionName, updateAppUrl, moduleName) {
        let url = '/admin/permission/update';
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                permission: permissionName,
                app_url: updateAppUrl,
                module: moduleName,
                id: permissionId
            },
            success: function(res) {
                if(res.success) {
                    $('#updatePermissionBtn').prop('disabled', false);
                    $('#editPermission').modal('hide');
                    $('#updatePermissionName').val('');
                    $('#updateModuleName').val('');
                    $('#updateAppUrl').val('');
                    validationAlert('Permission updated', 'Successfully updated the permission.', 'success', 2000, 'OK');
                }
            },
            error: function(xhr) {
                $('#updatePermissionBtn').prop('disabled', false);
                let response = xhr.responseJSON;
                console.log(xhr.responseJSON.message.permission);
                if(response) {
                    switch (response.error) {
                        case 1:
                            if(xhr.responseJSON.message != '') {
                                for(let key in xhr.responseJSON.message) {
                                    if(xhr.responseJSON.message.hasOwnProperty(key)) {
                                        if(xhr.responseJSON.message[key][0] != '') {
                                            validationAlert('Validation Error', xhr.responseJSON.message[key][0] || 'Validation failed', 'error', 5000, "OOP's");
                                        } else if(xhr.responseJSON.message[key][1] != '') {
                                            validationAlert('Validation Error', xhr.responseJSON.message[key][1] || 'Validation failed', 'error', 5000, "OOP's");
                                        } else {
                                            validationAlert('Validation Error', xhr.responseJSON.message[key][2] || 'Validation failed', 'error', 5000, "OOP's");
                                        }
                                    }
                                }
                            } else if(xhr.responseJSON.message.permission != '') {
                                validationAlert('Already exist', xhr.responseJSON.message.permission || 'Already exist', 'error', 5000, "OOP's");
                            }
                            break;
                        case 2:
                            validationAlert('Error', 'Invalid module name selected.', 'error', 5000, "OOP's");
                            break;
                        default:
                            validationAlert('Error', response.message || 'Something went wrong.', 'error', 5000, "OOP's");
                            break;
                    }
                } else {
                    validationAlert('Error', 'Unexpected server error', 'error', 5000, "OOP's");
                }
            }
        });
    }

// Permission Js End --



// Role permission mapping js start --
    function saveRolePermissionMapping(event) {
        event.preventDefault();
        let permissionIds = [];
        let routeUrls = [];
        let roleId = document.getElementById('roleId').value;
        $('#createRolePermissionMappingBtn').prop('disabled', true);

        document.querySelectorAll('input[name="permission_id[]"]:checked').forEach(function(checkbox) {
            permissionIds.push(checkbox.value);
        });

        document.querySelectorAll('input[name="route_url[]"]:checked').forEach(function(checkbox) {
            routeUrls.push(checkbox.value);
        });

        if (roleId === '') {
            validationAlert('Missing role', 'Please select a role.', 'error', 2000, 'OK');
            $('#createRolePermissionMappingBtn').prop('disabled', false);
            return false;
        }

        if (permissionIds.length === 0) {
            validationAlert('Missing permission', 'Please select at least one permission.', 'error', 2000, 'OK');
            $('#createRolePermissionMappingBtn').prop('disabled', false);
            return false;
        }

        if (routeUrls.length === 0) {
            validationAlert('Missing route url', 'Please select route url related permission.', 'error', 2000, 'OK');
            $('#createRolePermissionMappingBtn').prop('disabled', false);
            return false;
        }

        saveRolePermission(roleId, permissionIds, routeUrls);
    }

    function saveRolePermission(roleId, permissionIds, routeUrls) {
        $('#createRolePermissionMappingBtn').prop('disabled', false);
        let url = 'admin/mapping-role-permission/save';
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                role_id: roleId,
                permission_id: permissionIds,
                route_url: routeUrls
            },
            success: function(res) {
                $('#createRolePermissionMappingBtn').prop('disabled', false);
                if (res.success === true) {
                    validationAlert('Role Permission Mapping', 'Successfully assigned permissions to the role.', 'success', 2000, 'OK');
                    document.getElementById('roleId').value = '';

                    document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
                        checkbox.checked = false;
                    });

                    document.querySelectorAll('input[name="route_url[]"]').forEach(function(checkbox) {
                        checkbox.checked = false;
                    });

                    $('#addRolePermissionMapping').modal('hide');
                }
            },
            error: function(xhr) {
                $('#createRolePermissionMappingBtn').prop('disabled', false);
                let response = xhr.responseJSON;
                console.log(xhr.responseJSON.message.permission);
                if(response) {
                    switch (response.error) {
                        case 1:
                            if(xhr.responseJSON.message != '') {
                                for(let key in xhr.responseJSON.message) {
                                    if(xhr.responseJSON.message.hasOwnProperty(key)) {
                                        if(xhr.responseJSON.message[key][0] != '') {
                                            validationAlert('Validation Error', xhr.responseJSON.message[key][0] || 'Validation failed', 'error', 5000, "OOP's");
                                        } else if(xhr.responseJSON.message[key][1] != '') {
                                            validationAlert('Validation Error', xhr.responseJSON.message[key][1] || 'Validation failed', 'error', 5000, "OOP's");
                                        } else {
                                            validationAlert('Validation Error', xhr.responseJSON.message[key][2] || 'Validation failed', 'error', 5000, "OOP's");
                                        }
                                    }
                                }
                            } else if(xhr.responseJSON.message.permission != '') {
                                validationAlert('Already exist', xhr.responseJSON.message.permission || 'Already exist', 'error', 5000, "OOP's");
                            }
                            break;
                        case 2:
                            validationAlert('Error', 'Invalid module name selected.', 'error', 5000, "OOP's");
                            break;
                        default:
                            validationAlert('Error', response.message || 'Something went wrong.', 'error', 5000, "OOP's");
                            break;
                    }
                } else {
                    validationAlert('Error', 'Unexpected server error', 'error', 5000, "OOP's");
                }
            }
        });
    }

// Role permission mapping js end --





// Route permission mapping js start --
    $(document).ready(function() {
        $('#routeId').select2({
            placeholder: "Select Routes",
            closeOnSelect: false,
            allowClear: true
        });

        $('#routeUpdateId').select2({
            placeholder: "Select Routes",
            closeOnSelect: false,
            allowClear: true
        });
    });

    function saveRoutePermissionMapping(event) {
        event.preventDefault();
        $('#createRoutePermissionMappingBtn').prop('disabled', true);
        let permissionId = document.getElementById('permissionId').value;
        let routeIds = $('#routeId').val() || [];

        if (permissionId === '') {
            validationAlert('Missing permission', 'Please select a permission.', 'error', 2000, 'OK');
            $('#createRoutePermissionMappingBtn').prop('disabled', false);
            return false;
        }
        if (routeIds.length === 0) {
            validationAlert('Missing route', 'Please select at least one route.', 'error', 2000, 'OK');
            $('#createRoutePermissionMappingBtn').prop('disabled', false);
            return false;
        }

        saveRoutePermission(permissionId, routeIds);
    }

    function saveRoutePermission(permissionId, routeIds) {
        $('#addRolePermissionMapping').modal('hide');
        let url = 'admin/route-permission-mapping/save';
        $.ajax({
            url: url,
            method: "POST",
             data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                permission_id: permissionId,
                route_name: routeIds
            },
            success: function(res) {
                if (res.status === 'success') {
                    $('#createRoutePermissionMappingBtn').prop('disabled', false);
                    validationAlert('Route Permission Mapping', 'Successfully assigned routes to the permission.', 'success', 2000, "OK");
                    $('#routeId').val('').trigger('change');
                    document.getElementById('permissionId').value = '';
                    document.querySelectorAll('input[name="route_name[]"]').forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                    $('#addRoutePermissionMapping').modal('hide');
                }
            },
            error: function(xhr) {
                $('#createRolePermissionMappingBtn').prop('disabled', false);
                if (xhr.responseText) {
                    validationAlert('Error', xhr.responseJSON.message, 'error', 5000, "OOP's");
                }
            }
        });
    }

    function editRoutePermissionMapping(button) {
        var id = button.getAttribute('data-id');
        var permissionId = button.getAttribute('data-permission_id');
        var routeNames = JSON.parse(button.getAttribute('data-route_name')) || [];
        // document.getElementById('hiddenRoutePermissionId').value = id;
        // document.getElementById('permissionUpdateId').value = permissionId;
        // $('#routeUpdateId').val(routeNames).trigger('change');

        // Hidden input me id set karo
        document.querySelector("#editRoutePermission input[name='id']").value = id;

        // Permission select set karo
        $("#permissionUpdateId").val(permissionId).trigger("change");

        // Route select set karo (multiple)
        $("#routeUpdateId").val(routeNames).trigger("change");
    }
// Route permission mapping js end --



// Document Js Start --
    function addRow() {
        let html = `
        <div class="row documentRow">
            <div class="col-md-5 mb-3">
                <select name="file_type[]" class="form-control">
                    <option value="">Select</option>
                    <option value="marksheet">Marksheet</option>
                    <option value="aadhar">Aadhar</option>
                    <option value="pan_card">Pan Card</option>
                    <option value="bank_details">Bank Details</option>
                    <option value="address_proof">Address Proof</option>
                    <option value="licence">Licence</option>
                </select>
            </div>
            <div class="col-md-5 mb-3">
                <input type="file" name="document[]" class="form-control">
            </div>
            <div class="col-md-2 mt-2">
                <button type="button" class="btn btn-danger removeRow">Remove</button>
            </div>
        </div>
        `;
        $('#documentContainer').append(html);
    }

    $(document).on('click', '.removeRow', function () {
        $(this).closest('.documentRow').remove();
    });

    function saveAllDocuments(event) {
        event.preventDefault();

        let formData = new FormData(document.getElementById("documentForm"));

        $.ajax({
            url: '{{ route("your.route.name") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                validationAlert('Success', 'Documents uploaded successfully', 'success', 2000, false);
            },
            error: function (xhr) {
                validationAlert('Error', 'Failed to upload documents', 'error', 5000, false);
            }
        });
    }
// Document Js End --



// Meeting Js Start --
    function saveMeeting(e) {
        e.preventDefault();
        $('.meetingBtn').prop('disabled', true);
        let meetingLocation = $("#location").val();
        let clientName = $("#clientName").val();
        let meetingTime = $("#meetingTime").val();
        let meetingDate = $("#meetingDate").val();
        let distanceInKm = $("#distanceInKm").val();
        let durationInMinutes = $("#durationInMinutes").val();
        if (meetingLocation === '') {
            validationAlert('Missing meeting location', 'Please enter a meeting location.', 'error', 2000, 'OK');
            $('.meetingBtn').prop('disabled', false);
            return false;
        }
        if (clientName === '') {
            validationAlert('Missing client name', 'Please enter a client name.', 'error', 2000, 'OK');
            $('.meetingBtn').prop('disabled', false);
            return false;
        }
        if (meetingTime === '') {
            validationAlert('Missing meeting time', 'Please enter a valid meeting time.', 'error', 2000, 'OK');
            $('.meetingBtn').prop('disabled', false);
            return false;
        }
        if (meetingDate === '') {
            validationAlert('Missing meeting date', 'Please enter a valid meeting date.', 'error', 2000, 'OK');
            $('.meetingBtn').prop('disabled', false);
            return false;
        }
        // if (distanceInKm === '') {
        //     validationAlert('Missing distance', 'Please enter distance in km.', 'error', 2000, 'OK');
        //     $('.meetingBtn').prop('disabled', false);
        //     return false;
        // }
        // if (durationInMinutes === '') {
        //     validationAlert('Missing duration', 'Please enter duration in minutes.', 'error', 2000, 'OK');
        //     $('.meetingBtn').prop('disabled', false);
        //     return false;
        // }
        submitMeeting(meetingLocation, clientName, meetingTime, meetingDate, distanceInKm=null, durationInMinutes=null);
    }   

    function submitMeeting(meetingLocation, clientName, meetingTime, meetingDate, distanceInKm, durationInMinutes) {
        let url = 'admin/meeting/save';
        $('#addMeeting').modal('hide');
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                location: meetingLocation,
                client_name: clientName,
                meeting_time: meetingTime,
                meeting_date: meetingDate,
                distance_in_km: distanceInKm,
                duration_in_minutes: durationInMinutes
            },
            success: function(res) {
                if(res.success) {
                    $('.meetingBtn').prop('disabled', false);
                    $('#location').val('');
                    $('#clientName').val('');
                    $('#meetingTime').val('');
                    $('#meetingDate').val('');
                    $('#distanceInKm').val('');
                    $('#durationInMinutes').val('');
                    validationAlert('Meeting created', 'Successfully created a new meeting.', 'success', 2000, 'OK');
                    setTimeout(function() {
                        window.location.reload();
                    } , 200);
                }
            },
            error: function(xhr) {
                $('.meetingBtn').prop('disabled', false);
                if(xhr.responseJSON) {
                    validationAlert('Error ', xhr.responseJSON.message, 'error', 5000, false);
                    console.log(xhr.responseJSON.message);
                }
            }
        });
    }
// Meeting Js End --



// Holiday Js Start --
    function saveHoliday(e) {
        e.preventDefault();
        $('.saveHolidayBtn').prop('disabled', true);
        let name_of_holiday = $("#name_of_holiday").val();
        let firm_id = $("#firm_id").val();
        let day_for_holiday = $("#day_for_holiday").val();
        let holiday_month = $("#holiday_month").val();
        let holiday_year = $("#holiday_year").val();
        let color = $("#color").val();
        let description = $("#description").val();
        let holiday_start_date = $("#holiday_start_date").val();
        let holiday_end_date = $("#holiday_end_date").val();
        let category = $("#category_name").val();
        let holiday_image = $("#holiday_image").val();
        if (firm_id === '') {
            validationAlert('Missing firm', 'Please enter a firm name.', 'error', 2000, 'OK');
            $('.saveHolidayBtn').prop('disabled', false);
            return false;
        }
        if (name_of_holiday === '') {
            validationAlert('Missing holiday name', 'Please enter a holiday name.', 'error', 2000, 'OK');
            $('.saveHolidayBtn').prop('disabled', false);
            return false;
        }
        if (day_for_holiday === '') {
            validationAlert('Missing holiday day', 'Please enter a holiday day.', 'error', 2000, 'OK');
            $('.saveHolidayBtn').prop('disabled', false);
            return false;
        }
        if (holiday_month === '') {
            validationAlert('Missing holiday month', 'Please enter a valid holiday month.', 'error', 2000, 'OK');
            $('.saveHolidayBtn').prop('disabled', false);
            return false;
        }
        if (holiday_year === '') {
            validationAlert('Missing holiday year', 'Please enter a valid holiday year.', 'error', 2000, 'OK');
            $('.saveHolidayBtn').prop('disabled', false);
            return false;
        }
        if (color === '') {
            validationAlert('Missing color code', 'Please enter color code.', 'error', 2000, 'OK');
            $('.saveHolidayBtn').prop('disabled', false);
            return false;
        }
        if (holiday_start_date === '') {
            validationAlert('Missing holiday start date', 'Please enter holiday start date.', 'error', 2000, 'OK');
            $('.saveHolidayBtn').prop('disabled', false);
            return false;
        }
        if (holiday_end_date === '') {
            validationAlert('Missing holiday end date', 'Please enter holiday end date.', 'error', 2000, 'OK');
            $('.saveHolidayBtn').prop('disabled', false);
            return false;
        }
        if (category === '') {
            validationAlert('Missing holiday category', 'Please enter holiday category.', 'error', 2000, 'OK');
            $('.saveHolidayBtn').prop('disabled', false);
            return false;
        }
        submitHoliday(firm_id, name_of_holiday, day_for_holiday, holiday_month, holiday_year, color, holiday_start_date, holiday_end_date, description, category, holiday_image);
    }

    function submitHoliday(firm_id, name_of_holiday, day_for_holiday, holiday_month, holiday_year, color, holiday_start_date, holiday_end_date, description, category, holiday_image) {
        let url = 'admin/holiday/save';
        $('#addHoliday').modal('hide');
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                firm_id: firm_id,
                holiday_name: name_of_holiday,
                day_of_holiday: day_for_holiday,
                month_of_holiday: holiday_month,
                year_of_holiday: holiday_year,
                color: color,
                holiday_start_date: holiday_start_date,
                holiday_end_date: holiday_end_date,
                description: description,
                category: category,
                holiday_image: holiday_image,
            },
            success: function(res) {
                if(res.success) {
                    $('.saveHolidayBtn').prop('disabled', true);
                    $("#firm_id").val('');
                    $("#name_of_holiday").val('');
                    $("#day_for_holiday").val('');
                    $("#holiday_month").val('');
                    $("#holiday_year").val('');
                    $("#color").val('');
                    $("#description").val('');
                    $("#holiday_start_date").val('');
                    $("#holiday_end_date").val('');
                    $("#category_name").val('');
                    $("#holiday_image").val('');
                    validationAlert('Holiday created', 'Successfully created a new holiday.', 'success', 2000, 'OK');
                    setTimeout(function() {

                    } , 200);
                }
            },
            error: function(xhr) {
                $('.saveHolidayBtn').prop('disabled', false);
                if(xhr.responseJSON) {
                    validationAlert('Error ', xhr.responseJSON.message, 'error', 5000, false);
                    console.log(xhr.responseJSON.message);
                }
            }
        });
    }


    // Holiday Export Start --
        $('#sampleExcelImportId').hide();
        $('#sampleExcelDownloadId').show();

        const sampleExcelDownload = (event) => {
            $.ajax({
                url: 'admin/holiday/excel-generate',
                method: 'GET',
                success: function(response) {
                    if (response.success == true && response.code == 0) {
                        validationAlert('Holiday excel generate', 'Successfully generate sample holiday excel sheet.', 'success', 2000, 'OK');
                        $('#sampleExcelImportId').show();
                        $('#sampleExcelDownloadId').hide();
                        // Actual download trigger
                        window.location.href = response.download_url;
                    } else {
                        $('#sampleExcelImportId').hide();
                        $('#sampleExcelDownloadId').show();
                    }
                }
            })
        }
    // Holiday Export End --
    
    // Holiday Import Start --
        const triggerHolidayFile = () => {
            document.getElementById('holidayExcelFile').click();
        }
        // Jab file select ho jaye
        document.getElementById('holidayExcelFile').addEventListener('change', function () {
            let file = this.files[0];
            if (!file) return;

            let formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: 'admin/holiday/excel-import',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success === true && response.code === 0) {
                        validationAlert('Holiday excel upload', 'Successfully upload holiday excel sheet.', 'success', 2000, 'OK');
                        $('#sampleExcelImportId').hide();
                        $('#sampleExcelDownloadId').show();
                    } else {
                        $('#sampleExcelImportId').show();
                        $('#sampleExcelDownloadId').hide();
                    }
                }
            });
        });
    // Holiday Import Start End --
// Holiday Js End --



// Subject Js Start --
    function saveSubject(event) {
        event.preventDefault();
        $('.saveSubjectBtn').prop('disabled', true);
        let subjectName = document.getElementById('name_of_subject').value;
        let description = document.getElementById('description').value;
        if (subjectName === '') {
            validationAlert('Missing subject', 'Please enter a subject.', 'error', 2000, 'OK');
            $('.saveSubjectBtn').prop('disabled', false);
            return false;
        }
        if (description === '') {
            validationAlert('Missing description', 'Please enter a description.', 'error', 2000, 'OK');
            $('.saveSubjectBtn').prop('disabled', false);
            return false;
        }
        submitSubject(subjectName, description);
    }

    function submitSubject(subjectName, description) {
        let url = 'admin/interview/subject/save';
        $('#addSubject').modal('hide');
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                subject_name: subjectName,
                description: description
            },
            success: function(res) {
                if(res.success) {
                    $('.saveSubjectBtn').prop('disabled', false);
                    $('#name_of_subject').val('');
                    $('#description').val('');
                    validationAlert('Subject created', 'Successfully created a new subject.', 'success', 2000, 'OK');
                    setTimeout(function() {
                        window.location.reload();
                    } , 200);
                }
            },
            error: function(xhr) {
                $('.saveSubjectBtn').prop('disabled', false);
                if(xhr.responseJSON) {
                    validationAlert('Error ', xhr.responseJSON.message, 'error', 5000, false);
                    console.log(xhr.responseJSON.message);
                }
            }
        });
    }
// Subject Js End --



// Subject Js Start --
    function saveInterview(event) {
        event.preventDefault();
        $('.saveInterviewBtn').prop('disabled', true);
        let subject_id = document.getElementById('subject_id').value;
        let interview_name = document.getElementById('interview_name').value;
        let interview_time = document.getElementById('interview_time').value;
        let interview_date = document.getElementById('interview_date').value;
        let attempted = document.getElementById('attempted').value;
        if (subject_id === '') {
            validationAlert('Missing subject', 'Please select a subject name.', 'error', 2000, 'OK');
            $('.saveInterviewBtn').prop('disabled', false);
            return false;
        }
        if (interview_name === '') {
            validationAlert('Missing interview name', 'Please enter a interview name.', 'error', 2000, 'OK');
            $('.saveInterviewBtn').prop('disabled', false);
            return false;
        }
        if (interview_time === '') {
            validationAlert('Missing interview time', 'Please enter a interview time.', 'error', 2000, 'OK');
            $('.saveInterviewBtn').prop('disabled', false);
            return false;
        }
        if (interview_date === '') {
            validationAlert('Missing interview date', 'Please enter a interview date.', 'error', 2000, 'OK');
            $('.saveInterviewBtn').prop('disabled', false);
            return false;
        }
        if (attempted === '') {
            validationAlert('Missing attempted', 'Please enter a interview attempted.', 'error', 2000, 'OK');
            $('.saveInterviewBtn').prop('disabled', false);
            return false;
        }
        submitInterview(subject_id, interview_name, interview_time, interview_date, attempted);
    }

    function submitInterview(subject_id, interview_name, interview_time, interview_date, attempted) {
        let url = 'admin/interview/exam/save-interview';
        $('#addSubject').modal('hide');
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                subject_id: subject_id,
                interview_name: interview_name,
                interview_time: interview_time,
                interview_date: interview_date,
                attempted: attempted,
            },
            success: function(res) {
                if(res.success) {
                    $('.saveInterviewBtn').prop('disabled', false);
                    $('#subject_id').val('');
                    $('#interview_name').val('');
                    $('#interview_time').val('');
                    $('#interview_date').val('');
                    $('#attempted').val('');
                    validationAlert('Subject created', 'Successfully created a new subject.', 'success', 2000, 'OK');
                    setTimeout(function() {
                        window.location.reload();
                    } , 200);
                }
            },
            error: function(xhr) {
                $('.saveInterviewBtn').prop('disabled', false);
                if(xhr.responseJSON) {
                    validationAlert('Error ', xhr.responseJSON.message, 'error', 5000, false);
                    console.log(xhr.responseJSON.message);
                }
            }
        });
    }
// Subject Js End --






