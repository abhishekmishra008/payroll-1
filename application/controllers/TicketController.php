<?php

require APPPATH . '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Webklex\PHPIMAP\ClientManager;

date_default_timezone_set("Asia/Kolkata");

class TicketController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('emp_model');
        $this->load->model('hr_model');
        $this->load->model('email_sending_model');
        $this->load->model('Nas_modal');
        $this->load->helper('dump_helper');
    }


    public function index() {
        try {
			$rmt = $this->load->database('db2', TRUE);
            $data['prev_title'] = "Ticket Management";
            $data['page_title'] = "Ticket Management";
            $user = $this->session->userdata('login_session');
            if( empty($user)) {
                $data['user'] = '';
            } else {
                $data['user'] = $user;
            }
            
            $result = $this->firmId();
            if ($result !== false) {
                $data['firm_id'] = $result['firm_id'];
			} else {
				$data['firm_id'] = '';
			}

            $activeUsers = $this->db->query("SELECT user_id,email,user_name FROM `user_header_all` WHERE activity_status=1")->result();
            if(!empty($activeUsers)) {
                $data['active_users'] = $activeUsers;
            } else {
                $data['active_users'] = [];
            }

			$query = $rmt->query("SELECT id, department FROM ticket_department_table WHERE status = 1");
			if($query->num_rows() > 0) {
				$data['departments'] = $query->result();
			} else {
				$data['departments'] = [];
			}

			$this->load->view('human_resource/ticket', $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


	public function getListMessages() {
		try {
			// https://github.com/Vashi-GBTech/accountsanalysis.ecovisrkca.com.git
			$rmt = $this->load->database('db2', TRUE);
			$user_id = "";
			if($this->session->userdata('login_session')) {
				$user_id = $this->session->userdata('login_session')['emp_id'];
			} else {
				$user_id = $this->session->userdata('login_session')['user_id'];
			}
			
			$query = $rmt->query("SELECT tga.*, tta.*, tdt.*  FROM rmt.ticket_generation_all tga
								JOIN rmt.ticket_transaction_all tta ON tta.ticket_id = tga.ticket_id
								JOIN ticket_department_table tdt ON tdt.id = tta.department
								WHERE tga.created_by = ? ORDER BY tta.date DESC" , $user_id
							);
			$result = $query->result();
			if(!empty($result)) {
				$data['tickets'] = $result;
			} else {
				$data['tickets'] = '';
			}
			echo json_encode($data);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			echo "Error fetching get email: ". $e->getMessage();
			return false;
		}
	}


    public function firmId($user_id="") {
        try {
			$rmt = $this->load->database('db2', TRUE);
            if($user_id == "") {
                $sessionData = $this->session->userdata('login_session');
                if (is_array($sessionData)) {
                    $data['session_data'] = $sessionData;
                    $user_id = ($sessionData['emp_id']);
                } else {
                    $user_id = $this->session->userdata('login_session');
                }
            } else {
                
            }
            $result = $this->db->query("SELECT * FROM `user_header_all` WHERE `user_id`='$user_id' and activity_status=1");
            if($result->num_rows() > 0) {
                $data = $result->row();
                $user = array(
                    'id' => $data->id,
                    'user_name' => $data->user_name,
                    'user_id' => $data->user_id,
                    'firm_id' => $data->firm_id,
                    'user_type' => $data->user_type,
                    'boss_id' => $data->linked_with_boss_id,
                    'firm_logo' => $data->firm_logo
                );
                return $user;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


	public function fetchEmailFromWebmail() {
		try {
			// dd("Abhishek Kumar Mishra");
			$rmt = $this->load->database('db2', TRUE);
			$ticketId = $this->input->post('ticket_id');
			$query = $rmt->query('SELECT * FROM ticket_generation_all WHERE ticket_id = ?', [$ticketId]);
			if($query->num_rows() == 0) {
				echo json_encode(['status' => 404]);
				return;
			} else {
				$ticket = $query->row();
			}

			$hostname = "{Vashi.rkca.net:993/imap/ssl}INBOX";
			$username = "support@gbtech.in";
			$password = "supportGBtech@123$";

			$inbox = imap_open($hostname, $username, $password);
			if (!$inbox) {
				echo json_encode(['status' => 500, 'error' => imap_last_error()]);
				return;
			}

			$emails = imap_search($inbox, 'ALL');
			$messages = [];
			if ($emails) {
				foreach ($emails as $email_number) {
					$overview = imap_fetch_overview($inbox, $email_number, 0);
					if(!isset($overview[0])) {
						continue;
					}

					// fetch data from imap/cpanel 
					$email_uid = $overview[0]->uid ?? '';
					$email_subject = trim($overview[0]->subject ?? '');
					$email_from = trim($overview[0]->from ?? '');
					$date = trim($overview[0]->date ?? '');
					$email_date = date('Y-m-d', strtotime($date));

					// fetch data from database
					$db_subject = trim($ticket->original_sub);
					$db_email = trim($ticket->requester_email_id);
					$db_date = date('Y-m-d', strtotime($ticket->date));

					// start condition for database and imap
					$isMatch = false;
					preg_match('/<(.+?)>/', $email_from, $matches);
					$fromEmail = $matches[1] ?? $email_from;
					$clean_subject = preg_replace('/^(Re:|Fwd:)\s*/i', '', $email_subject); // Normalize subject (remove Re:, Fwd:)
					if (!empty($ticket->u_id) && $email_uid == $ticket->u_id) {
						$isMatch = true;
					} elseif (stripos($clean_subject, $db_subject) !== false) {
						$isMatch = true;
					} elseif($fromEmail == $db_email) { // From + Date match
						$isMatch = true;
					}
					

					if ($isMatch) {
						$structure = imap_fetchstructure($inbox, $email_number);
						if (!isset($structure->parts)) {
							$body = imap_body($inbox, $email_number);
						} else {
							$body = imap_fetchbody($inbox, $email_number, 1.1);
							if (empty($body)) {
								$body = imap_fetchbody($inbox, $email_number, 1);
							}
						}

						$messages[] = [
							'sender_id' => (strpos($email_from, 'support@gbtech.in') !== false) ? 'support' : 'user',
							'from' => $email_from,
							'message' => quoted_printable_decode($body),
							'timestamp' => $email_date,
						];
					}
				}
			}
			// dd('', $messages);
			imap_close($inbox);
			echo json_encode([
				'status' => 200,
				'messages' => $messages
			]);
		} catch (Exception $e) {
			echo json_encode([
				'status' => 500,
				'error' => $e->getMessage()
			]);
		}
	}


	public function createTicket($user_id= '') {
		try {


		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			echo "Error fetching conversation: " . $e->getMessage();
			return false;
		}

	}


	public function getConversation() {
		try {
			$rmt = $this->load->database("db2", TRUE);
			$userDetails = $this->session->userdata('login_session');
			$messageId = $this->input->post_get('message_id');
			$message = $this->input->post_get('message');
			$emailId = $this->input->post_get('email_id');
			$subject = $this->input->post_get('subject');
			$ticketId = $this->input->post_get('ticket_id');
			$userDropdown1 = $this->input->post_get('userDropdown1');
			$forwEmailStatus = $this->input->post_get('forw_email');
			$originalSub = $this->input->post_get('original_sub');
			$userList = $this->input->post_get('user_list');
			$requesterName = $this->input->post_get('requester_name');
			$priousMsg = $this->input->post_get('newMsg');
			$isForwReq = $this->input->post_get('is_forw_req');
			$replyId = $this->input->post_get('reply_id');
			$forwEmailId = $this->input->post_get('forw_emailId');
			$sessionEmail = $userDetails['user_id'];
			$firmId = $userDetails['firm_id'];
			$userId = $userDetails['emp_id'];
			$userType = $userDetails['user_type'];
			$referanceId = $this->input->post_get('referance_id');
			$statusId = $this->input->post_get('status_id');
			$fromEmail = 'support@gbtech.in';
			$to = "support@gbtech.in";
			$requestType = '';
			$notification_status = 0;
			if(!empty($ticketId) && !empty($messageId) && !empty($emailId) && !empty($subject)) {
				$notification_status = 0;
				$requestType = 2;
				if(strstr($originalSub, $ticketId)) {
					$originalSub = $originalSub;
				} else {
					$originalSub = $ticketId . ':' . $originalSub;
				}

				if($userType !== 8) {
					$requestType = 1;
					if($forwEmailStatus == 1 && $isForwReq != '' && $replyId != NULL) {
						$requesterName = $requesterName;
					} else {
						$requesterName = 'support@gbtech.in';
					}
				}

				$serverData = $this->getMailFrom($userId);
				if ($serverData == '' && $serverData == null) {
					$serverData = $rmt->query('SELECT email FROM user_header_all WHERE user_id="' . $userId . '"')->row();
				}
				
				if ($serverData != null && $serverData != "") {
					if ($forwEmailStatus == 1 && $isForwReq != '' && $isForwReq != NULL) {
						$fromEmail = "support@gbtech.in";
						$requestType = 2;
					} else {
						$fromEmail = $serverData->email;
					}
				}

				if ($forwEmailStatus == 1 && $isForwReq != '' && $isForwReq != NULL) {
					$ticketRequesterData = $this->getDataByTicketId($ticketId);
					if ($ticketRequesterData != null) {
						$to = $this->input->post_get('email_id');
					}
				}

			} else {
				$ticketRequesterData = $this->getDataByTicketId($ticketId);
				if ($ticketRequesterData != null) {
					$to = $ticketRequesterData->requester_email_id;
				}
			}
			
			$data12 = array();
			$this->load->library('email');
			$new_message_id = $this->rcmail_gen_message_id($fromEmail);
			$uid = rand(10, 1000);

			if (preg_match('/<\s?[^\>]*\/?\s?>/i', $to)) {
				$results = preg_match_all('~<([^/][^>]*?)>~', $to, $match);
				if (is_array($match)) {
					$matchdata = $match[1];
					if (is_array($match[1])) {
						$to = $match[1][0];
					}
				}
			}

			$data = array();
			$replyMail_format = '';
			$replyMail_format .= 'Hi <b>' . $requesterName . '</b>,<br><br> <p>' . $message . '</p>';

			if ($userType == 8) {
				$replyMail_format .= 'Regards,<br> <b> IT Support </b>';
			}

			if ($messageId != '' && $messageId != NULL) {
				$replyData = array();
				$data = new stdClass();
				$getReplyData = $rmt->query('SELECT * FROM  ticket_transaction_all WHERE message_id="' . $messageId . '"')->row();
				$data->msg = $getReplyData->message;
				$data->user_name = $getReplyData->email_from;
				$data->reply_id = $getReplyData->reply_id;
				array_push($replyData, $data);
				if ($getReplyData->reply_id != '') {
					$obj = new stdClass();
					$newArr = array();
					$getReplyData = $this->getReplyData($getReplyData->reply_id);
					for ($i = 0; $i < count($getReplyData); $i++) {
						$replyData[] = $getReplyData[$i];
					}
				}

				if ($replyData) {
					foreach ($replyData as $rData) {
						if ($rData->user_name == 'support@gbtech.in') {
							$replyMail_format .= '<hr>
											<blockquote style="color: rgb(0, 106, 157);border-right: 2px solid #006a9d;"> <p>' . $rData->user_name . '</p>
											<p class="text-right" style="color:#2323d4">' . $rData->msg . '</p></blockquote>';
						} else {
							$replyMail_format .= '<hr>
											<blockquote> <p>' . $rData->user_name . '</p>
											<p class="text-right" style="color:#2323d4">' . $rData->msg . '</p></blockquote>';
						}
					}
				}
			}
			
			$mail =  new PHPMailer(true);
			$mail->isSMTP();
			$mail->host = 'Vashi.rkca.net';
			$mail->SMTPAuth = true;
			$mail->Username = "noreply@gbtech.in";
			$mail->Password = "Gbtech@123$";
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port = 587;
			$mail->AllowEmpty = true;
			$mail->Debugoutput = function($str, $level) {
				$log = "SMTP DEBUG - Level: $level; Message: $str";
				log_message('debug', $log);
			};
			$mail->addCustomHeader('In-Reply-To', $messageId);
			$mail->addCustomHeader('References', $referanceId);
			$mail->setFrom($fromEmail);
			$mail->addBcc("support@gbtech.in");
			$mail->addAddress($to);

			if(!empty($userDropdown1) && $userDropdown1 != '-1' && $userDropdown1 != null) {
				$mail->addCC($userDropdown1);
			}

			$mail->Body = $replyMail_format;
			$mail->isHTML(true);
			$mail->Subject = $originalSub;

			if($fromEmail == "noreply@gbtech.in") { 
				$replyTo = "support@gbtech.in";
			} else {
				if($forwEmailStatus == 1 && $fromEmail == $forwEmailId) {
					$replyTo = "support@gbtech.in";
				} else {
					$replyTo = $fromEmail;
				}
			}
			$mail->addReplyTo($replyTo);
			if($userList != '' && $userList != null) {
				$userListArr = explode(',', $userList);
				foreach($userListArr as $ccEmail) {
					if ($ccEmail != $to && $ccEmail != $sessionEmail) {
						$mail->AddCC($ccEmail);
					}
				}

			}

			// $attchmentArray = array();
			// if ($_FILES['userfile']['name'][0] != null && $_FILES['userfile']['name'][0] != '') {
			// 	$des_path = "uploads";
			// 	$data = $this->uploadMultipleFileNew($des_path, 'userfile', 2);
			// 	if (is_array($data)) {
			// 		if (count($data) > 0) {
			// 			if ($data['status'] == 200) {
			// 				for ($j = 0; $j < count($data['body']); $j++) {
			// 					array_push($attchmentArray, $data['body'][$j]);
			// 				}
			// 			}
			// 		}
			// 	}
			// }

			// $attachment_files = "";
			// $is_attachment = 0;
			// if (!empty($attchmentArray) && $attchmentArray != null) {
			// 	if (count($attchmentArray) > 0) {
			// 		foreach ($attchmentArray as $file) {
			// 			$mail->addAttachment($file);
			// 		}
			// 		$attachment_files = implode(',', $attchmentArray);
			// 		$is_attachment = 1;
			// 	}
			// }
			
			// $mail->send();
			// $response["files"] = $attchmentArray;

			if($mail->ErrorInfo == NULL) {
				$new_message_id = $mail->getLastMessageID();
				if($forwEmailStatus == 1) {
					if($forwEmailId == $sessionEmail) {
						$requestType = 2;
					} 
				}

				$data = array(
					"ticket_id" => $ticketId,
					"u_id" => $uid,
					"subject" => $subject,
					"reference_id" => $referanceId,
					"email_from" => $fromEmail,
					"date" => date('Y-m-d H:i:s'),
					"message" => $message,
					"request_type" => $requestType,
					"status" => 1,
					// "attachment" => $attachment_files,
					// "is_attachment" => $is_attachment,
					"ticket_generate_type" => 0,
					"ticket_generation_user" => $userId,
					"message_id" => $new_message_id,
					"reply_id" => $messageId,
					"original_sub" => $originalSub,
					"user_list" => $userList,
					"notification_status" => $notification_status
				);

				$response['mail_details'] = $mail;
				$response['mail_from'] = $fromEmail;
				$response['mail_data'] = $this->email;
				if ($userType != 8 || $userType == 8) {
					if ($this->addTranceData($data, null, $ticketId)) {
						$response["status"] = true;
						$response["body"] = "Successfully send message ";
						$response["echo"] = 1;
					} else {
						$response["status"] = false;
						$response["body"] = "Failed to send email";
					}
				} else {
					$response["status"] = true;
					$response["body"] = "Successfully send message";
					$response["echo"] = 2;
				}
			} else {
				$response["status"] = false;
				$response["body"] = "Failed to send emails";
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			echo "Error fetching conversation: " . $e->getMessage();
			return false;
		}
	}


	public function getMailFrom($user_id) {
		try {
			$rmt = $this->load->database("db2", TRUE);
			$query = $rmt->query("SELECT email FROM ticket_email_configuration WHERE user_id = ? AND status = ? AND is_default = ?", array($user_id, 1, 1));
			if($query->num_rows() > 0) {
				return $query->row();
			} else {
				return null;
			}
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			echo 'Message'. $exc->getMessage();
			return null;
		}
	}


	public function getDataByTicketId($ticket_Id) {
		try {
			$rmt = $this->load->database("db2", TRUE);
			$query = $rmt->query("SELECT requester_email_id FROM ticket_generation_all WHERE ticket_id = ?", array($ticket_Id));
			if($query->num_rows() > 0) {
				return $query->row();
			} else {
				return null;
			}
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			echo 'Message' . $exc->getMessage();
			return null;
		}
	}


	public function rcmail_gen_message_id($domain){
		$local_part = md5(uniqid('rcmail' . mt_rand(), true));
		$domain_part = $domain; //$RCMAIL->user->get_username('domain');
		if (!preg_match('/\.[a-z]+$/i', $domain_part)) {
			if (($host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']))
				&& preg_match('/\.[a-z]+$/i', $host)) {
				$domain_part = $host;
			} else if (($host = preg_replace('/:[0-9]+$/', '', $_SERVER['SERVER_NAME']))
				&& preg_match('/\.[a-z]+$/i', $host)) {
				$domain_part = $host;
			}
		}
		return sprintf('<%s@%s>', $local_part, $domain_part);
	}


	public function getReplyData($reply_id){
		$rmt = $this->load->database("db2", TRUE);
		$replyData = array();
		$getReplyData = $rmt->query('SELECT * FROM ticket_transaction_all WHERE message_id="' . $reply_id . '"')->row();
		if ($getReplyData) {
			$data = new stdClass();
			$data->msg = $getReplyData->message;
			$data->user_name = $getReplyData->email_from;
			$data->reply_id = $getReplyData->reply_id;
			$replyData[] = $data;
			if ($getReplyData->reply_id != '') {
				$data1 = $this->getReplyData($getReplyData->reply_id);
				foreach ($data1 as $row) {
					$replyData[] = $row;
				}
			}
		}
		return $replyData;
	}


	public function uploadMultipleFileNew($upload_path, $inputname, $combination = "") {
		$combination = (explode(",", $combination));
		$check_file_exist = $this->check_file_exist($upload_path);
		if (isset($_FILES[$inputname]) && $_FILES[$inputname]['error'] != '4') {
			$files = $_FILES;
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = '*';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$this->load->library('upload', $config);

			if (is_array($_FILES[$inputname]['name'])) {
				$count = count($_FILES[$inputname]['name']); // count element
				$files = $_FILES[$inputname];
				$images = array();
				$dataInfo = array();
				if ($count > 0) {
					if (in_array("1", $combination)) {
						for ($j = 0; $j < $count; $j++) {
							$fileName = $files['name'][$j];
							if (in_array($fileName, $check_file_exist)) {
								$response['status'] = 201;
								$response['body'] = $fileName . " Already exist";
								return $response;
							}
						}
					}

					$inputname = $inputname . "[]";
					for ($i = 0; $i < $count; $i++) {
						$_FILES[$inputname]['name'] = $files['name'][$i];
						$_FILES[$inputname]['type'] = $files['type'][$i];
						$_FILES[$inputname]['tmp_name'] = $files['tmp_name'][$i];
						$_FILES[$inputname]['error'] = $files['error'][$i];
						$_FILES[$inputname]['size'] = $files['size'][$i];
						$fileName = $files['name'][$i];
						// get system generated File name CONCATE datetime string to Filename
						if (in_array("2", $combination)) {
							$date = date('Y-m-d H:i:s');
							$randomdata = strtotime($date);
							$fileName = $randomdata . $fileName;
						}
						$images[] = $fileName;
						$config['file_name'] = $fileName;
						$this->upload->initialize($config);
						$up = $this->upload->do_upload($inputname);
						$dataInfo[] = $this->upload->data();
					}

					$file_with_path = array();
					foreach ($dataInfo as $row) {
						$raw_name = $row['raw_name'];
						$file_ext = $row['file_ext'];
						$file_name = $raw_name . $file_ext;
						if(!empty($file_name)){
							$file_with_path[] = $upload_path . "/" . $file_name;
						}
					}
					if(count($file_with_path) > 0) {
						$response['status'] = 200;
						$response['body'] = $file_with_path;
					} else {
						$response['status'] = 201;
						$response['body'] = $file_with_path;
					}
					return $response;
				} else {
					$response['status'] = 201;
					$response['body'] = array();
					return $response;
				}
			} else {
				$response['status'] = 201;
				$response['body'] = array();
				return $response;
			}
		} else {
			$response['status'] = 201;
			$response['body'] = array();
			return $response;
		}
	}


	function check_file_exist($upload_path) {
		$filesnames = array();
		foreach (glob('./' . $upload_path . '/*.*') as $file_NAMEEXISTS) {
			$file_NAMEEXISTS;
			$filesnames[] = str_replace("./" . $upload_path . "/", "", $file_NAMEEXISTS);
		}
		return $filesnames;
	}


	public function addTranceData($reply_data, $data12, $ticket_id){
		try {
			$rmt = $this->load->database('db2', TRUE);
			$rmt->trans_start();
			$insert=$rmt->insert('ticket_transaction_all', $reply_data);

			if ($data12 != null) {
				$rmt->where('ticket_id', $ticket_id);
				$rmt->update('ticket_generation_all', $data12);
			}

			if ($rmt->trans_status() === FALSE) {
				$rmt->trans_rollback();
				log_message('info', "insert user Transaction Rollback");
				$result = FALSE;
			} else {
				$rmt->trans_commit();
				log_message('info', "insert user Transaction Commited");
				$result = TRUE;
			}

			$rmt->trans_complete();
		}
		catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			$result = FALSE;
		}
		return $result;
	}


}
?>