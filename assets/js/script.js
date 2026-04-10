    $(document).ready(function(){
        getAssetsData();
    });

    function getAssetsData() {
        let emp_id = $("#emp_id").val();
        $("#assets_datatable").DataTable({
            destroy: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": baseUrl+"assetsDetails",
                "type": "POST",
                "data":{emp_id:emp_id},
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                for (let x = 0; x < aData.length; x++) {
                    if (x === 1 && aData[x]) {
                        $('td:eq(1)', nRow).html(aData[x]);
                    }
                    if (x === 2 && aData[x]) {
                        $('td:eq(2)', nRow).html(aData[x]);
                    }
                }
                let status = '';
                if(aData[11]==5){
                    if(aData[9]==1){
                        status = '<span class="badge badge-primary" onclick="change_status(2,'+aData[10]+');">Given To User</span>';
                    }else if(aData[9]==2){
                        // status = "Return From User";
                        status = '<span class="badge badge-success" onclick="change_status(1,'+aData[10]+');">Return From User</span>';
                    }
                    // $('td:eq(10)',nRow).html('<button class="btn btn-info" onclick="getAssetData('+aData[10]+')"> <i class="fa fa-edit"></i> </button>' +
                    $('td:eq(10)',nRow).html('<button class="btn btn-info" data-id="'+(aData[10])+'" data-toggle="modal" data-target="#editAssetData" onclick="editAsset('+aData[10]+')"> <i class="fa fa-edit"></i> </button>' +
                        '<button class="btn btn-info " onclick="deleteAssetData('+aData[10]+')"><i class="fa fa-trash"></i></button>' +
                        '<button type="button" class="btn btn-info showAssetBtn" data-id="'+aData[10]+'" data-toggle="modal" data-target="#showAssetData"> <i class="fa fa-eye"></i></button>');

                }

                if(aData[11]==4){
                    if(aData[9]==1){
                        status = '<span class="badge badge-success"> Receive </span>';
                    }else if(aData[9]==2){
                        status = '<span class="badge badge-success"> Return </span>';
                    }
                }
                $('td:eq(9)', nRow).html(status);
            }
        });
    }

    function change_status(status,id){
        $.ajax({
            type:'POST',
            url:baseUrl+'changeAssetStatus',
            data:{status:status, id:id},
            dataType:'json',
            success:function (result){
                console.log(result);
                if(result.status==true){
                    alert(result.msg);
                    getAssetsData();
                }else{
                    alert(result.msg);
                    getAssetsData();
                }
            },
            error:function(result){
                console.log(result);
            }
        });
    }

    function deleteAssetData(rowid){
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this Assets!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type:'POST',
                        url: baseUrl+'deleteAssets',
                        dataType:'Json',
                        data:{rowID:rowid},
                        success:function(result){
                            console.log(result);
                            if(result.status==true){
                                alert(result.msg);

                                getAssetsData();
                            }else{
                                alert(result.msg);
                            }
                        },
                        error:function(result){
                            if(result.status==false && result.code==400){
                                alert(result.msg);
                                getAssetsData();
                            }
                        }

                    });
                    // swal("Poof! Your imaginary file has been deleted!", {
                    // 	icon: "success",
                    // });
                } else {
                    swal("Your Assets is safe!");
                }
            });
        console.log(rowid);
        console.log(baseUrl);
    }

    function getAssetData(rowID){
        $.ajax({
            url:baseUrl+'getAssetData',
            type:'post',
            dataType:'json',
            data:{rowID:rowID},
            success:function(result){
                console.log(result);
                if(result.status==true){
                    console.log(result.msg.secondary_type);
                    $("#rowId").val(result.msg.id);
                    $("#brand_name").val(result.msg.brand_name);
                    $("#model_name").val(result.msg.model_name);
                    $("#specification").val(result.msg.specification);
                    $("#descrption").val(result.msg.description);
                    var yourFormattedDate= moment(result.msg.purchase_date).format('YYYY-MM-DD');
                    $('#pur_mnf').val(yourFormattedDate);
                    $("#assets_type").val(result.msg.asset_type);
                    $("#assets_type").trigger('change');
                    $("#assets_details").val(result.msg.secondary_type)
                }
            },
            error:function(result){
                console.log(result);
            }
        });
    }


    $(document).on('click', '.showAssetBtn', function() {
        let id = $(this).data('id');
        // console.log("Abhishk Mishra : Fetching details for asset ID:", id);
        $.ajax({
            url: baseUrl + 'show_asset_data',
            method: 'GET',
            data: { id: id },
            success: function(resp) {
                resp = JSON.parse(resp)
                if(resp.status == true && resp.code == 200) {
                    let data = resp.data;
                    let assetStatus = '';
                    if(data.status == 1) {
                        assetStatus = 'Given to user';
                    } else if(data.status == 2) {
                        assetStatus = 'Return from user';
                    } else if(data.status == 3) {

                    } else {
                        
                    }
                    console.log(data);
                    
                    $('#fetchDetail').html('');
                    let row = `<div class="container">
                                <div class="row mb-2">
                                    <div class="col-md-3"><p>Assets Type : ${data.asset_type || ''}</p></div>
                                    <div class="col-md-3"><p>Assets Details : ${data.secondary_type || ''}</p></div>
                                    <div class="col-md-3"><p>Brand Name : ${data.brand_name || ''}</p></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><p>Model Name : ${data.model_name || ''}</p></div>
                                    <div class="col-md-3"><p>Specification : ${data.specification || ''}</p></div>
                                    <div class="col-md-3"><p>Description : ${data.description || ''}</p></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><p>Purchase Date : ${data.purchase_date || ''}</p></div>
                                    <div class="col-md-3"><p>Asset Code : ${data.asset_code || '-'}</p></div>
                                    <div class="col-md-3"><p>System Name : ${data.system_name || ''}</p></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><p>Serial Number : ${data.serial_number || ''}</p></div>
                                    <div class="col-md-3"><p>Asset Location : ${data.asset_location || ''}</p></div>
                                    <div class="col-md-3"><p>MS Office : ${data.ms_office || ''}</p></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><p>Docking Station : ${data.docking_station || ''}</p></div>
                                    <div class="col-md-3"><p>License Type : ${data.license_type || ''}</p></div>
                                    <div class="col-md-3"><p>Subscription Info : ${data.subscription_info || ''}</p></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><p>License Key : ${data.license_key || ''}</p></div>
                                    <div class="col-md-3"><p>OS Name : ${data.os_name || ''}</p></div>
                                    <div class="col-md-3"><p>Warranty : ${data.warranty || ''}</p></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><p>Storage : ${data.storage || ''}</p></div>
                                    <div class="col-md-3"><p>IMEI Number : ${data.imei_number || ''}</p></div>
                                    <div class="col-md-3"><p>Phone Number : ${data.phone_number || ''}</p></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><p>Capacity : ${data.capacity || ''}</p></div>
                                    <div class="col-md-3"><p>Usages : ${data.usages || ''}</p></div>
                                    <div class="col-md-3"><p>Mouse : ${data.mouse || ''}</p></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><p>Keyboard : ${data.keyboard || ''}</p></div>
                                    <div class="col-md-3"><p>Created By : ${data.created_user_name || ''}</p></div>
                                    <div class="col-md-3"><p>Status : ${assetStatus || ''}</p></div>
                                </div>
                            </div>
                        `;
                    $('#fetchDetail').append(row);
                } else {
                    $('#fetchDetail').html('<div><p>No data found</p></div>');
                }
            },
            error: function() {
                $('#fetchDetail').html('<div><p>Error loading data</p></div>');
            }
        });
    });

    function stringValidation(e) {
        
    }

    $("#add_assets_details").on('click', function(e) {
        e.preventDefault();
        // Validation
        let requiredFields = [
            { id: 'brand_name', label: 'Brand Name' },
            { id: 'model_name', label: 'Model Name' },
            { id: 'specification', label: 'Specification' },
            { id: 'descrption', label: 'Description' },
            { id: 'vendor', label: 'System Vendor' },
            { id: 'pur_mnf', label: 'Purchase/Manufacturing Date' }
        ];
        let isValid = true;
        let errorMsg = '';
        for (let i = 0; i < requiredFields.length; i++) {
            let val = $('#' + requiredFields[i].id).val();
            if (!val) {
                errorMsg = requiredFields[i].label + ' is required.';
                isValid = false;
                break;
            }
        }

        let assets_type = $('#assets_type').val();
        let assets_details = $('#assets_details').val();
        let checkExtra = false;
        if (assets_type == '5') {
            checkExtra = true;
        }
        if (isValid && checkExtra) {
            $('#extra_fields_container .extra-required').each(function() {
                if (!$(this).val()) {
                    let label = $(this).closest('.col-md-4').find('label').text();
                    errorMsg = (label ? label : 'Field') + ' is required.';
                    isValid = false;
                    return false;
                }
            });
        }

        // Agar validation fail hai to error dikhega
        if (!isValid) {
            Swal.fire({
                icon: "error",
                title: "Required Field",
                text: errorMsg,
                timer: 2000
            });
            return false;
        }

        // Ajax Call (sirf agar valid hai)
        $.ajax({
            type:'POST',
            dataType:'json',
            url: baseUrl + 'Assets/add_assets_details',
            data: $("#assets_form").serialize(),
            success: function (result){
                if(result.status === true){
                    if(result.update === 1){
                        $("#rowId").val('');
                    }
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: result.msg,
                        timer: 2000
                    });
                    $("#assets_form")[0].reset();
                    getAssetsData();
                }else{
                    $("#" + result.id + '_error').html(result.error);
                    getAssetsData();
                }
            },
            error:function(result){
                $("#" + result.id + '_error').html(result.error);
            }
        });

    });






