<?php
class Assets extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Emp_model');
		$this->load->model('emp_model');
		$this->load->model('firm_model');
		$this->load->model('email_sending_model');
		$this->load->model('customer_model');
		$this->load->model('MasterModel');
		$this->db2 = $this->load->database('db2', TRUE);
		$this->load->helper('dump_helper');
	}

	public function add_assets_details() {
		try {
			$id = $this->input->post('rowId');
			$user_data = $this->session->userdata('login_session');
			$user_id = $user_data['emp_id'];
			$emp_id = $this->input->post('emp_id');
			$asset_type = (int) $this->input->post('assets_type');
			$assets_details = (int) $this->input->post('assets_details');
			$brand_name = trim($this->input->post('brand_name'));
			$model_name = trim($this->input->post('model_name'));
			$specification = trim($this->input->post('specification'));
			$descrption = trim($this->input->post('descrption'));
			$specification = trim($this->input->post('specification'));
			$asset_code = trim($this->input->post('asset_code'));
			$pur_mnf = trim($this->input->post('pur_mnf'));

			$sql = "SELECT asset_type FROM asset_types WHERE id = $asset_type";
			$asset = $this->db->query($sql)->row();
			$sql1 = "SELECT sub_asset_type FROM asset_sub_types WHERE id = $assets_details";
			$sub_asset = $this->db->query($sql1)->row();

			$commonFields = [
				'user_id'       => $emp_id,
				'asset_code'    => $asset_code,
				'asset_type'    => trim($asset->asset_type),
				'secondary_type'=> trim($sub_asset->sub_asset_type),
				'brand_name'    => $brand_name,
				'model_name'    => $model_name,
				'specification' => $specification,
				'description'   => $descrption,
				'purchase_date' => $pur_mnf,
				'created_on'    => date('Y-m-d H:i:s'),
				'created_by'    => $user_id,
				'status'        => 1
			];

			$software = [
				'system_name', 'version', 'license_key', 'ms_office',
				'adobe', 'license_type', 'subscription_info', 'expiry_date', 'active_devices',
				'database_type', 'instance_count', 'license_info',
				'serial_number', 'asset_location', 'os_name', 'warranty',
				'storage', 'imei_number', 'phone_number', 'capacity', 'hdd_sdd_type',
				'usages', 'mouse', 'keyboard', 'docking_station'
			];

			$this->assetValidation($asset_type, $assets_details, $software);

			$vehical = [
				'serial_number', 'asset_location'
			];

			$extraFields = [];
			$requiredFields = [];
			if ($asset_type == 5) { // Software
				foreach($software as $value) {
					$extraFields[$value] = trim($this->input->post($value));
				}
			} 

			$data = array_merge($commonFields, $extraFields);

			if (empty($id)) {
				if(!empty($data)) {
					$result = $this->MasterModel->_insert('assets_management_mapping', $data);
					$message = $result ? "Assets Data inserted Successfully" : "Something Went Wrong, Data Not inserted";
				} else {
					echo json_encode([
						'status' => false,
						'code'   => 204,
						'msg'    => "Please enter Brand Name/ Model Name/ Specification/ Descrption/ Manufacturing Date "
					]);
					return;
				}
			} else {
				$result = $this->MasterModel->_update('assets_management_mapping', ['id' => $id], $data);
				$message = $result ? "Assets Data updated Successfully" : "Something Went Wrong, Data Not updated";
			}

			echo json_encode([
				'status' => $result ? true : false,
				'code'   => $result ? 200 : 204,
				'msg'    => $message
			]);

		} catch(Exception $e) {

		}
	}

	private function assetValidation($asset_type, $assets_details, $data) {
		try {
			$errors = [];
			$commonRequired = ['brand_name', 'model_name', 'specification', 'description', 'purchase_date'];

			foreach ($commonRequired as $field) {
				if (empty($data[$field])) {
					$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
				}
			}
			if ($asset_type == 5) { 
				$itRequired = [
					'system_name', 'version', 'license_key', 'ms_office',
					'adobe', 'license_type', 'subscription_info', 'expiry_date', 'active_devices',
					'database_type', 'instance_count', 'license_info', 'asset_code',
					'serial_number', 'asset_location', 'os_name', 'warranty',
					'storage', 'imei_number', 'phone_number', 'capacity', 'hdd_sdd_type',
					'usages', 'mouse', 'keyboard', 'docking_station'
				];
				if ($assets_details == 1) { 
					$laptopFields = ['system_name', 'version', 'license_key'];
					foreach ($laptopFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Operating System.";
						}
					}
				} elseif ($assets_details == 2) {
					$mobileFields = ['ms_office', 'adobe', 'license_type', 'subscription_info'];
					foreach ($mobileFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Productivity Tool.";
						}
					}
				} elseif ($assets_details == 3) {
					$mobileFields = ['expiry_date', 'active_devices'];
					foreach ($mobileFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Antivirus Tool.";
						}
					}
				} elseif ($assets_details == 4) {
					$mobileFields = ['database_type', 'instance_count', 'license_info'];
					foreach ($mobileFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Database System.";
						}
					}
				} elseif ($assets_details == 5) {
					$mobileFields = ['asset_code', 'serial_number', 'asset_location', 'os_name', 'warranty'];
					foreach ($mobileFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Desktop.";
						}
					}
				} elseif ($assets_details == 6) {
					$mobileFields = ['asset_code', 'serial_number', 'asset_location', 'os_name', 'warranty'];
					foreach ($mobileFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Mobile.";
						}
					}
				} elseif ($assets_details == 7) {
					$mobileFields = ['asset_code', 'serial_number', 'asset_location', 'os_name', 'storage'];
					foreach ($mobileFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Mobile.";
						}
					}
				} elseif ($assets_details == 8) {
					$mobileFields = ['imei_number', 'serial_number', 'phone_number', 'os_name'];
					foreach ($mobileFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Smart Phones.";
						}
					}
				} elseif ($assets_details == 9) {
					$mobileFields = ['capacity', 'hdd_sdd_type', 'usages', 'serial_number'];
					foreach ($mobileFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Mobile.";
						}
					}
				} elseif ($assets_details == 10) {
					$mobileFields = ['mouse', 'serial_number', 'keyboard', 'docking_station'];
					foreach ($mobileFields as $field) {
						if (empty($data[$field])) {
							$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for Accessories.";
						}
					}
				} else {
					
				}
				
			} elseif ($asset_type == 3) { // Vehicle
				$vehicleRequired = ['serial_number', 'asset_location'];
				foreach ($vehicleRequired as $field) {
					if (empty($data[$field])) {
						$errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required for vehicle.";
					}
				}
			}

			// Return or throw errors if found
			return $errors;

		} catch(Exception $e) {

		}
	}
	
	public function assetsDetails() {
		// dd("abhishek mishra");
		$data = array();
		$serach_key = $_POST['search']['value'];
		$emp_id = $this->input->post('emp_id');
		$userData = $this->session->userdata('login_session');
		$userType = $userData['user_type'];
		if(!empty($emp_id)){
			if ($serach_key != '') {
				$memData = $this->db->query("select *, (select uha.user_name from user_header_all uha where uha.user_id = amm.created_by) as user_name 
												from assets_management_mapping amm where (user_id='" . $emp_id . "') AND 
												(amm.brand_name LIKE '%" . $serach_key . "%' OR amm.model_name LIKE '%" . $serach_key . "%' 
												OR amm.specification LIKE '%" . $serach_key . "%' OR amm.description LIKE '%" . $serach_key . "%' or amm.purchase_date LIKE '%" . $serach_key . "%')")
							->result();
				
			} else {
				$memData = $this->db->query("select *, (select uha.user_name from user_header_all uha where uha.user_id = amm.created_by) as user_name from assets_management_mapping amm where user_id='" . $emp_id . "'")->result();
			}
			$count = 1;
			if($memData) {
				foreach ($memData as $row) {
					$serialNumer = isset($row->serial_number) ? $row->serial_number : '-';
					$data[] = array($serialNumer, $row->asset_type, $row->secondary_type, $row->brand_name,
						$row->model_name, $row->specification, $row->description, $row->purchase_date, $row->user_name, $row->status, $row->id, $userType);
					$count++;
				}
			}
		}
		$results = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => count($data),
			"recordsFiltered" => count($data),
			"data" => $data,
		);
		echo json_encode($results);
	}


	public function changeAssetStatus(){
		$status = $this->input->post('status');
		$id = $this->input->post('id');
		$data = array('status'=>$status);
		$where = array('id'=>$id);
		$update = $this->MasterModel->_update('assets_management_mapping',$data,$where);
		if($update){
			$response['code']=200;
			$response['status']='true';
			$response['msg'] = 'status changed Successfully';
		}else{
			$response['code']=200;
			$response['status']='false';
			$response['msg'] = 'status not changed';
		}
		echo json_encode($response);
	}

	public function deleteAssets(){
		$rowId = $this->input->post('rowID');
		$where=array('id'=>$rowId);
		if($rowId!='' && $rowId!=null){
			$delete = $this->MasterModel->_delete('assets_management_mapping',$where);
			if($delete){
				$response['code']=201;
				$response['status'] = true;
				$response['msg'] = "Asset Deleted Successfully";
			}else{
				$response['code']=201;
				$response['status'] = true;
				$response['msg'] = "Asset Deleted Successfully";
			}
		}else{
			$response['code']=400;
			$response['status'] = false;
			$response['msg'] = "Something Went Wrong";
		}
		echo json_encode($response);
	}

	public function getAssetData(){
		$rowId = $this->input->post('rowID');
		$getAssetsDetails = $this->db->query("select * from assets_management_mapping where id='".$rowId."'")->row();
		if($getAssetsDetails){
			$response['msg'] = $getAssetsDetails;
			$response['status'] = true;
			$response['code'] = 200;
		}else{
			$response['msg'] = "data not found";
			$response['status'] = false;
			$response['code'] = 201;
		}
		echo json_encode($response);
	}

	public function createAssetType() {
		try {
			$user = $this->session->userdata('login_session');
			$asset_type = $this->input->post('asset_type');
			if (empty(trim($asset_type))) {
				$response['status'] = false;
				$response['code'] = 400;
				$response['msg'] = 'Asset type is required';
				echo json_encode($response);
				return;
			}
			$sql = "SELECT * FROM asset_types WHERE LOWER(asset_type) = ?";
			$alreadyExist = $this->db->query($sql, [strtolower($asset_type)])->row();
			if ($alreadyExist) {
				$response['status'] = false;
				$response['code'] = 409;
				$response['msg'] = 'Asset type already exists';
				echo json_encode($response);
				return;
			}

			$data = [
				'user_id' => $user['emp_id'],
				'asset_type' => $asset_type,
				'created_at' => date('Y-m-d H:i:s'),
				'status' => 1
			];

			$insert = $this->MasterModel->_insert('asset_types', $data);

			if ($insert) {
				$response['status'] = true;
				$response['code'] = 200;
				$response['msg'] = 'Asset Type created successfully';
			} else {
				$response['status'] = false;
				$response['code'] = 500;
				$response['msg'] = 'Failed to create Asset Type';
			}

			echo json_encode($response);

		} catch(Exception $e) {
			return $e->getTraceAsString();
		}
	}

	public function createSubAssetType() {
		try {
			$user = $this->session->userdata('login_session');
			$assetId = $this->input->post('asset_type');
			$subAssetType = $this->input->post('sub_asset_type');

			if (empty(trim($subAssetType))) {
				$response['status'] = false;
				$response['code'] = 400;
				$response['msg'] = 'Sub asset type is required';
				echo json_encode($response);
				return;
			}

			$sql = "SELECT * FROM asset_sub_types WHERE LOWER(sub_asset_type) = ?";
			$alreadyExist = $this->db->query($sql, [strtolower($subAssetType)])->row();
			if ($alreadyExist) {
				$response['status'] = false;
				$response['code'] = 409;
				$response['msg'] = 'Sub Asset type already exists';
				echo json_encode($response);
				return;
			}

			$data = [
				'user_id' => $user['emp_id'],
				'asset_id' => $assetId,
				'sub_asset_type' => $subAssetType,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => null,
				'status' => 1
			];

			$insert = $this->MasterModel->_insert('asset_sub_types', $data);

			if ($insert) {
				$response['status'] = true;
				$response['code'] = 200;
				$response['msg'] = 'Sub Asset Type created successfully';
			} else {
				$response['status'] = false;
				$response['code'] = 500;
				$response['msg'] = 'Failed to create Sub Asset Type';
			}

			echo json_encode($response);

		} catch(Exception $e) {
			
		}
	}

	public function getAssetTypeRecord() {
		$this->db->where('status', 1);
		$result = $this->db->get('asset_types')->result();
		if ($result) {
			$response['status'] = true;
			$response['code'] = 200;
			$response['data'] = $result;
		} else {
			$response['status'] = false;
			$response['code'] = 204;
			$response['msg'] = 'No asset types found';
		}
		echo json_encode($response);
	}

	public function getSubAssetTypeRecord() {
		try {
			$assetType = $this->input->get('assets_type');
			$this->db->select('id, sub_asset_type');
			$this->db->from('asset_sub_types');
			$this->db->where('asset_id', $assetType);
			$this->db->where('status', 1);
			$query = $this->db->get();
			$result = $query->result();
			if ($result) {
				$response['status'] = true;
				$response['code'] = 200;
				$response['data'] = $result;
			} else {
				$response['status'] = false;
				$response['code'] = 204;
				$response['msg'] = 'No sub asset types found';
			}

			echo json_encode($response);
		} catch(Exception $e) {

		}
	}

	public function showAssetData() {
		try {
			$id = $this->input->get('id');
			$query = $this->db->query("SELECT amm.*, uha.user_name, (SELECT uh.user_name FROM user_header_all uh WHERE uh.user_id = amm.created_by) AS created_user_name, (SELECT ast.id FROM asset_types ast WHERE ast.asset_type = amm.asset_type ) AS db_asset_id, (SELECT asst.id FROM asset_sub_types asst WHERE asst.sub_asset_type = amm.secondary_type ) AS db_sub_asset_id  FROM assets_management_mapping amm
							LEFT JOIN user_header_all uha ON uha.user_id = amm.user_id 
							WHERE amm.id = $id")->row();
			// dd($query);
			if($query){
				$response['status'] = true;
				$response['code'] = 200;
				$response['data'] = $query;
			} else {
				$response['status'] = false;
				$response['code'] = 201;
				$response['data'] = "data not found";
			}
			echo json_encode($response);
		} catch(Exception $e) {
			log_message('error', 'Asset fetch error: ' . $e->getMessage());
			echo json_encode(['status' => false, 'code' => 500, 'data' => 'Internal error']);
		}
	}

	public function getUpdateAssetTypeRecord() {
		$this->db->select('id, asset_type');
		$this->db->from('asset_types');
		$this->db->where('status', 1);
		$result = $this->db->get()->result();

		if ($result) {
			$response = [
				'status' => true,
				'code'   => 200,
				'data'   => $result
			];
		} else {
			$response = [
				'status' => false,
				'code'   => 204,
				'msg'    => 'No asset types found'
			];
		}
		echo json_encode($response);
	}

	public function getUpdateSubAssetTypeRecord() {
		$assetType = $this->input->get('asset_id');
		$this->db->select('id, sub_asset_type');
		$this->db->from('asset_sub_types');
		$this->db->where('asset_id', $assetType);
		$this->db->where('status', 1);
		$result = $this->db->get()->result();
		if ($result) {
			$response = [
				'status' => true,
				'code'   => 200,
				'data'   => $result
			];
		} else {
			$response = [
				'status' => false,
				'code'   => 204,
				'msg'    => 'No sub asset types found'
			];
		}
		echo json_encode($response);
	}

	public function update_assets_details() {
		$mappingId = $this->input->post('rowId');
		if (empty($mappingId)) {
			echo json_encode([
				'status' => false,
				'code'   => 400,
				'msg'    => "Row ID is missing"
			]);
			return;
		}
		$assetId = trim($this->input->post('assets_type'));
		$subAssetId = trim($this->input->post('assets_details'));
		$sql = "SELECT asset_type FROM asset_types WHERE id = $assetId";
		$asset = $this->db->query($sql)->row();
		$sql1 = "SELECT sub_asset_type FROM asset_sub_types WHERE id = $subAssetId";
		$sub_asset = $this->db->query($sql1)->row();
		$oldData = $this->db->query("SELECT * FROM assets_management_mapping WHERE id = $mappingId")->row();
		$user_data = $this->session->userdata('login_session');
		$data = [
			'user_id'        => $this->input->post('user_id'),
			'asset_type'     => $asset->asset_type ?? $oldData->asset_type,
			'secondary_type' => $sub_asset->sub_asset_type ?? $oldData->secondary_type,
			'brand_name'     => $this->input->post('brand_name') ?? $oldData->brand_name,
			'model_name'     => $this->input->post('model_name') ?? $oldData->model_name,
			'specification'  => $this->input->post('specification') ?? $oldData->specification,
			'description'    => $this->input->post('descrption') ?? $oldData->description,
			'purchase_date'  => $this->input->post('pur_mnf') ?? $oldData->purchase_date,
			'created_on'     => date('Y-m-d H:i:s'),
			'created_by'     => $user_data['emp_id'],
			'asset_code'     => $this->input->post('asset_code') ?: $oldData->asset_code,
			'system_name'    => $this->input->post('system_name') ?: $oldData->system_name,
			'version'        => $this->input->post('version') ?: $oldData->version,
			'license_key'    => $this->input->post('license_key') ?: $oldData->license_key,
			'ms_office'      => $this->input->post('ms_office') ?: $oldData->ms_office,
			'adobe'          => $this->input->post('adobe') ?: $oldData->adobe,
			'license_type'   => $this->input->post('license_type') ?: $oldData->license_type,
			'subscription_info' => $this->input->post('subscription_info') ?: $oldData->subscription_info,
			'expiry_date'    => $this->input->post('expiry_date') ?: $oldData->expiry_date,
			'active_devices' => $this->input->post('active_devices') ?: $oldData->active_devices,
			'database_type'  => $this->input->post('database_type') ?: $oldData->database_type,
			'instance_count' => $this->input->post('instance_count') ?: $oldData->instance_count,
			'license_info'   => $this->input->post('license_info') ?: $oldData->license_info,
			'serial_number'  => $this->input->post('serial_number') ?: $oldData->serial_number,
			'asset_location' => $this->input->post('asset_location') ?: $oldData->asset_location,
			'os_name'        => $this->input->post('os_name') ?: $oldData->os_name,
			'warranty'       => $this->input->post('warranty') ?: $oldData->warranty,
			'storage'        => $this->input->post('storage') ?: $oldData->storage,
			'imei_number'    => $this->input->post('imei_number') ?: $oldData->imei_number,
			'phone_number'   => $this->input->post('phone_number') ?: $oldData->phone_number,
			'capacity'       => $this->input->post('capacity') ?: $oldData->capacity,
			'hdd_sdd_type'   => $this->input->post('hdd_sdd_type') ?: $oldData->hdd_sdd_type,
			'usages'         => $this->input->post('usages') ?: $oldData->usages,
			'mouse'          => $this->input->post('mouse') ?: $oldData->mouse,
			'keyboard'       => $this->input->post('keyboard') ?: $oldData->keyboard,
			'docking_station'=> $this->input->post('docking_station') ?: $oldData->docking_station,
			'status'         => 1
		];
		// dd($data);
		$this->db->where('id', $mappingId);
		$result = $this->db->update('assets_management_mapping', $data);
		if ($result) {
			echo json_encode([
				'status' => true,
				'code'   => 200,
				'msg'    => "Assets Data updated Successfully"
			]);
		} else {
			echo json_encode([
				'status' => false,
				'code'   => 500,
				'msg'    => "Something Went Wrong, Data Not Updated"
			]);
		}
	}


}
?>