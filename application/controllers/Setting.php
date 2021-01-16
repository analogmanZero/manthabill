<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * ManthaBill V.2.0
 *
 * Software Billing ini ditujukan untuk pemula hoster
 * Low Budget dan ingin memulai usaha selling hosting.
 *
 * Dikembangkan oleh: AlexistDev
 * Kontak: www.alexistdev.com
 *
 * Software ini gratis.Namun jika anda ingin support pengembangan software ini
 * Silahkan donasikan $1 ke paypal:alexistdev@gmail.com
 *
 * Terimakasih atas dukungan anda.
 *
 */
class Setting extends CI_Controller
{
	public $member;
	public $load;
	public $session;
	public $form_validation;
	public $input;
	public $idUser;
	public $token;
	public $tokenSession;
	public $tokenServer;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_member', 'member');
		/** Global scope idUser dan token */
		$this->idUser = $this->session->userdata('id_user');
		$this->tokenSession = $this->session->userdata('token');
		$this->tokenServer = $this->member->get_token_byId($this->idUser)->row()->token;
		if ($this->session->userdata('is_login_in') !== TRUE) {
			redirect('login');
		}
	}

	/** Template untuk memanggil view */
	private function _template($data, $view)
	{
		$this->load->view('user/view/' . $view, $data);
	}

	/** Prepare data */
	private function _dataMember()
	{

		/* Nama dan Gambar di Sidebar */
		$data['namaUser'] = $this->member->get_data_detail($idUser)->row()->nama_depan;
		$data['gambarUser'] = $this->member->get_data_detail($idUser)->row()->gambar;
		$data['title'] = "Product | ". $this->member->get_setting()->judul_hosting;
		return $data;
	}

	//khusus membuat captcha dan cek validasi captcha
	private function _create_captcha()
	{
		$config = array(
			'img_url' => base_url() . 'captcha/',
			'img_path' => './captcha/',
			'img_height' =>  50,
			'word_length' => 5,
			'img_width' => 150,
			'font_size' => 10,
			'expiration' => 300,
			'pool' => '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'
		);
		$cap = create_captcha($config);
		$image = $cap['image'];
		$this->session->set_userdata('captchaword', $cap['word']);
		return $image;
	}

	public function check_captcha($string)
	{
		if ($string == $this->session->userdata('captchaword')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function index()
	{
		$this->form_validation->set_rules('namaDepan', 'Nama Depan', 'trim');
		$this->form_validation->set_rules('namaBelakang', 'Nama Belakang', 'trim');
		$this->form_validation->set_rules('namaUsaha', 'Nama Perusahaan', 'trim');
		$this->form_validation->set_rules('email', 'Email', 'trim');
		$this->form_validation->set_rules('notelp', 'Nomor Telepon', 'trim');
		$this->form_validation->set_rules('alamat1', 'Alamat 1', 'trim');
		$this->form_validation->set_rules('alamat2', 'Alamat 2', 'trim');
		$this->form_validation->set_rules('kota', 'Kota', 'trim');
		$this->form_validation->set_rules('provinsi', 'Provinsi', 'trim');
		$this->form_validation->set_rules('kodepos', 'Kode Pos', 'trim');
		$this->form_validation->set_rules('negara', 'Negara', 'trim');
		if ($this->form_validation->run() === false) {
			$idUser = $this->session->userdata('id_user');
			$data = $this->_dataMember($idUser);
			$view = 'v_setting';
			//$data['daftarTicket'] = $this->member->tampil_ticketUser($idUser);
			$data['namaDepanUser'] = $this->member->getDetailUser($idUser)->nama_depan;
			$data['namaBlkUser'] = $this->member->getDetailUser($idUser)->nama_belakang;
			$data['namaUsaha'] = $this->member->getDetailUser($idUser)->nama_usaha;
			$data['notelp'] = $this->member->getDetailUser($idUser)->phone;
			$data['alamat1'] = $this->member->getDetailUser($idUser)->alamat;
			$data['alamat2'] = $this->member->getDetailUser($idUser)->alamat2;
			$data['kota'] = $this->member->getDetailUser($idUser)->kota;
			$data['provinsi'] = $this->member->getDetailUser($idUser)->provinsi;
			$data['negara'] = $this->member->getDetailUser($idUser)->negara;
			$data['kodepos'] = $this->member->getDetailUser($idUser)->kodepos;
			$data['emailUser'] = $this->member->getUser($idUser)->email;
			$data['CekSecPin'] = $this->member->getUser($idUser)->sec_pin;
			$cekWaktu = $this->member->getUser($idUser)->timepin;
			$waktu = strtotime(date("Y-m-d H:i:s"));
			if ($cekWaktu > $waktu) {
				$nW = 0;
			} else {
				$nW = 1;
			};
			$data['nW'] = $nW;
			$this->_template($data, $view);
		} else {
			$idUser = $this->session->userdata('id_user');
			$waktuSekarang = time();
			$cekWaktu = $this->member->getProfilUser($idUser)->time_req;
			if (($cekWaktu == 0) || ($waktuSekarang > $cekWaktu)) {
				//menambahkan waktu 5 menit ke database
				$waktuDimasukkan = strtotime('+5 minutes', $waktuSekarang);
				$namaDepan = $this->input->post('namaDepan', TRUE);
				$namaBelakang = $this->input->post('namaBelakang', TRUE);
				$namaUsaha = $this->input->post('namaUsaha', TRUE);
				$notelp = $this->input->post('notelp', TRUE);
				$alamat1 = $this->input->post('alamat1', TRUE);
				$alamat2 = $this->input->post('alamat2', TRUE);
				$kota = $this->input->post('kota', TRUE);
				$provinsi = $this->input->post('provinsi', TRUE);
				$kodepos = $this->input->post('kodepos', TRUE);
				$negara = $this->input->post('negara', TRUE);

				//mempersiapkan data detailuser
				$dataDetail = array(
					'nama_depan' => $namaDepan,
					'nama_belakang' => $namaBelakang,
					'nama_usaha' => $namaUsaha,
					'alamat' => $alamat1,
					'alamat2' => $alamat2,
					'kota' => $kota,
					'provinsi' => $provinsi,
					'negara' => $negara,
					'kodepos' => $kodepos,
					'phone' => $notelp,
					'time_req' => $waktuDimasukkan
				);
				$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data profil berhasil diperbaharui!</div>');
				$this->member->update_profil($dataDetail, $idUser);
				redirect('setting');
			} else {
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Anda baru saja merubah profil, silahkan dicoba 5 menit lagi!</div>');
				redirect('setting');
			}
		}
	}
	#################################################################
	#                                                               #
	#                  Update halaman profil user                   #
	#################################################################
	// function update($idUser = NULL)
	// {
	// 	//untuk validasi halaman login
	// 	$hashSes = $this->session->userdata('token');
	// 	$userSes = $this->session->userdata('username');
	// 	$userData = $this->m_user->get_userSession($userSes);
	// 	$hashKey = $this->m_user->get_token($hashSes);
	// 	//mendapatkan id user dan mengecek apakah ada
	// 	$getIdUser = $this->input->post('varidUser');
	// 	$cekIdUser = $this->m_user->cek_id($idUser);
	// 	if (($hashKey == 0) and ($userData == 0)) {
	// 		redirect('login');
	// 	} else {
	// 		if (($idUser == "") or ($cekIdUser < 1) or ($idUser == NULL) or ($getIdUser != $idUser)) {
	// 			redirect('setting');
	// 		} else {
	// 			$namaDepan = $this->input->post('namaDepan');
	// 			$namaBelakang = $this->input->post('namaBelakang');
	// 			$namaPerusahaan = $this->input->post('namaPerusahaan');
	// 			$noTelp = $this->input->post('noTelp');
	// 			$alamat1 = $this->input->post('alamat1');
	// 			$alamat2 = $this->input->post('alamat2');
	// 			$kota = $this->input->post('kota');
	// 			$provinsi = $this->input->post('provinsi');
	// 			$negara = $this->input->post('negara');
	// 			$kodepos = $this->input->post('kodepos');
	// 			$dataUpdate = array(
	// 				"nama_depan" => $namaDepan,
	// 				"nama_belakang" => $namaBelakang,
	// 				"nama_usaha" => $namaPerusahaan,
	// 				"phone" => $noTelp,
	// 				"alamat" => $alamat1,
	// 				"alamat2" => $alamat2,
	// 				"kota" => $kota,
	// 				"provinsi" => $provinsi,
	// 				"negara" => $negara,
	// 				"kodepos" => $kodepos
	// 			);
	// 			$settingSimpan = $this->m_user->update_profil($dataUpdate, $idUser);
	// 			$this->session->set_flashdata('item', array('pesan' => 'Data profil anda berhasil diperbaharui!'));
	// 			redirect('setting');
	// 		}
	// 	}
	// }

	// function ganti_password()
	// {
	// 	$hashSes = $this->session->userdata('token');
	// 	$userSes = $this->session->userdata('username');
	// 	$userData = $this->m_user->get_userSession($userSes);
	// 	$hashKey = $this->m_user->get_token($hashSes);
	// 	$idUser = $this->session->userdata('id_user');
	// 	//mengambil data username di database
	// 	$b['user'] = $this->m_user->loginok($idUser);
	// 	$b['detailUser'] = $this->m_user->getInfoUser($idUser);
	// 	$b['idUser'] = $idUser;
	// 	if (($hashKey == 0) and ($userData == 0)) {
	// 		redirect('login');
	// 	} else {
	// 		$this->load->view('user/v_settingReset', $b);
	// 	}
	// }
	// function perbaharui($idUser = NULL)
	// {
	// 	$hashSes = $this->session->userdata('token');
	// 	$userSes = $this->session->userdata('username');
	// 	$userData = $this->m_user->get_userSession($userSes);
	// 	$hashKey = $this->m_user->get_token($hashSes);
	// 	$idUser = $this->session->userdata('id_user');
	// 	//mengambil data username di database
	// 	$b['user'] = $this->m_user->loginok($idUser);
	// 	$b['detailUser'] = $this->m_user->getInfoUser($idUser);
	// 	if (($hashKey == 0) and ($userData == 0)) {
	// 		redirect('login');
	// 	} else {
	// 		$this->load->view('user/v_settingReset', $b);
	// 	}
	// }
	#################################################################
	#                                                               #
	#             Ajax Mengirimkan Email Pin Security               #
	#################################################################
	// function req_pin()
	// {
	// 	$hashSes = $this->session->userdata('token');
	// 	$userSes = $this->session->userdata('username');
	// 	$userData = $this->m_user->get_userSession($userSes);
	// 	$hashKey = $this->m_user->get_token($hashSes);
	// 	$idUser = $this->session->userdata('id_user');
	// 	//mengambil data username di database
	// 	$b['user'] = $this->m_user->loginok($idUser);
	// 	$b['detailUser'] = $this->m_user->getInfoUser($idUser);
	// 	if (($hashKey == 0) and ($userData == 0)) {
	// 		redirect('login');
	// 	} else {
	// 		$tujuan = $this->m_user->get_email($idUser)->email;
	// 		$pengirim = $this->m_user->get_companyEmail()->email_hosting;
	// 		$subyek = "PIN Baru Akun Anda";
	// 		$CekSecPin = $this->m_user->cek_security($idUser)->sec_pin;
	// 		if (empty($CekSecPin)) {
	// 			$waktuRequest = strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +30 minutes")));
	// 			$i = 0;
	// 			$pin = "";
	// 			while ($i < 4) {
	// 				$pin .= mt_rand(0, 9);
	// 				$i++;
	// 			}
	// 			$message = "
	// 						Yth.Pelanggan , <br><br>

	// 						anda telah melakukan permintaan pin baru, dan ini PIN ANDA:" . $pin . " <br>
	// 						Simpan pin anda dengan baik, dan secara berkala ganti password dan PIN Anda.<br><br>

	// 						Catatan: Jika anda merasa tidak meminta PIN Baru, maka segera login ke dalam akun anda dan rubah password akun anda.<br>
	// 						PIN adalah keamanan verifikasi kedua yang hanya dikirimkan via email.

	// 						<br><br>
	// 						Regards<br>
	// 						Admin- www.adrihost.com<br>
	// 					";

	// 			//kirimkan email pin
	// 			$dataEmail = array(
	// 				'email_pengirim' => $pengirim,
	// 				'email_tujuan' => $tujuan,
	// 				'subyek' => $subyek,
	// 				'email_pesan' => $message,
	// 				'status' => 2
	// 			);
	// 			//simpan data ke tbemail
	// 			$this->m_user->simpan_email($dataEmail);
	// 			//simpan pin ke tbuser
	// 			$newPin = sha1($pin);
	// 			$dataUpdatePin = array(
	// 				"timepin" => $waktuRequest,
	// 				"sec_pin" => $newPin
	// 			);
	// 			$this->m_user->update_pin($dataUpdatePin, $idUser);
	// 			$this->session->set_flashdata('pinPesan', array('pesan' => 'PIN Telah dikirimkan ke email.'));
	// 			redirect('setting');
	// 		} else {
	// 			$waktu = strtotime(date("Y-m-d H:i:s"));
	// 			$cekWaktu = $this->m_user->cek_waktuPin($idUser)->timepin;
	// 			if ($cekWaktu > $waktu) {
	// 				$this->session->set_flashdata('pinSudah', array('pesan' => 'ALERT: Anda sudah pernah meminta PIN Baru, silahkan cek di folder spam email anda. Atau bisa coba beberapa jam lagi!'));
	// 				redirect('setting');
	// 			} else {
	// 				$waktuRequest = strtotime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +30 minutes")));
	// 				$i = 0;
	// 				$pin = "";
	// 				while ($i < 4) {
	// 					$pin .= mt_rand(0, 9);
	// 					$i++;
	// 				}
	// 				$message = "
	// 					Yth.Pelanggan , <br><br>

	// 					anda telah melakukan permintaan pin baru, dan ini PIN ANDA:" . $pin . " <br>
	// 					Simpan pin anda dengan baik, dan secara berkala ganti password dan PIN Anda.<br><br>

	// 					Catatan: Jika anda merasa tidak meminta PIN Baru, maka segera login ke dalam akun anda dan rubah password akun anda.<br>
	// 					PIN adalah keamanan verifikasi kedua yang hanya dikirimkan via email.

	// 					<br><br>
	// 					Regards<br>
	// 					Admin- www.adrihost.com<br>
	// 				";
	// 				//kirimkan email pin
	// 				$dataEmail = array(
	// 					'email_pengirim' => $pengirim,
	// 					'email_tujuan' => $tujuan,
	// 					'subyek' => $subyek,
	// 					'email_pesan' => $message,
	// 					'status' => 2
	// 				);
	// 				//simpan data ke tbemail
	// 				$this->m_user->simpan_email($dataEmail);
	// 				//simpan pin ke tbuser
	// 				$newPin = sha1($pin);
	// 				$dataUpdatePin = array(
	// 					"timepin" => $waktuRequest,
	// 					"sec_pin" => $newPin
	// 				);
	// 				$this->m_user->update_pin($dataUpdatePin, $idUser);
	// 				$this->session->set_flashdata('pinPesan', array('pesan' => 'PIN Telah dikirimkan ke email.'));
	// 				redirect('setting');
	// 			}
	// 		}
	// 	}
	// }

	// function update_password()
	// {
	// 	$hashSes = $this->session->userdata('token');
	// 	$userSes = $this->session->userdata('username');
	// 	$userData = $this->m_user->get_userSession($userSes);
	// 	$hashKey = $this->m_user->get_token($hashSes);
	// 	$idUser = $this->session->userdata('id_user');
	// 	//mengambil data username di database
	// 	$b['user'] = $this->m_user->loginok($idUser);
	// 	$b['detailUser'] = $this->m_user->getInfoUser($idUser);
	// 	if (($hashKey == 0) and ($userData == 0)) {
	// 		redirect('login');
	// 	} else {
	// 		$this->form_validation->set_rules(
	// 			'passwordLama',
	// 			'Password Lama',
	// 			'trim|required|min_length[6]',
	// 			array('required' => 'Anda harus melengkapi kolom %s')
	// 		);
	// 		$this->form_validation->set_rules('passwordBaru', 'Password Baru', 'trim|required|min_length[6]');
	// 		$this->form_validation->set_rules('kpasswordBaru', 'Password Konfirm', 'trim|required|matches[passwordBaru]');
	// 		$this->form_validation->set_rules('pinSecurity', 'PIN', 'trim|required|min_length[4]');
	// 		$this->form_validation->set_message('min_length', '{field} harus minimal berjumlah {param} karakter.');
	// 		$this->form_validation->set_message('matches', '{field} harus sama dengan password Baru.');
	// 		$passLama = $this->m_user->saringan($this->input->post("passwordLama"));
	// 		$passBaru = $this->m_user->saringan($this->input->post("passwordBaru"));
	// 		$kpassBaru = $this->m_user->saringan($this->input->post("kpasswordBaru"));
	// 		$pinSec = $this->m_user->saringan($this->input->post("pinSecurity"));
	// 		$varID = $this->m_user->saringan($this->input->post("varUserID"));
	// 		$pesanError = validation_errors();
	// 		if ($this->form_validation->run() == false) {
	// 			$this->session->set_flashdata('pesanGagal', validation_errors());
	// 			redirect('setting/ganti_password');
	// 		} else {
	// 			if ($idUser == $varID) {
	// 				$passLmEncy = sha1($passLama);
	// 				$pinEncy = sha1($pinSec);
	// 				$cekPassLama = $this->m_user->cek_passLama($idUser, $passLmEncy);
	// 				if ($cekPassLama == 0) {
	// 					$this->session->set_flashdata('pesanGagal', "Password lama anda tidak cocok");
	// 					redirect('setting/ganti_password');
	// 				} else {
	// 					$cekPin = $this->m_user->cek_pin($idUser, $pinEncy);
	// 					if ($cekPin == 0) {
	// 						$this->session->set_flashdata('pesanGagal', "PIN anda tidak cocok, silahkan request pin baru jika anda lupa.");
	// 						redirect('setting/ganti_password');
	// 					} else {
	// 						$passBaruEncy = sha1($passBaru);
	// 						$dataUpdatePass = array(
	// 							'password' => $passBaruEncy
	// 						);
	// 						$this->m_user->updatePass($idUser, $dataUpdatePass);
	// 						$this->session->set_flashdata('pesanSukses', "Password Berhasil dirubah Berhasil dirubah.");
	// 						redirect('setting/ganti_password');
	// 					}
	// 				}
	// 			} else {
	// 				redirect('setting');
	// 			}
	// 		}
	// 	}
	// }
}
