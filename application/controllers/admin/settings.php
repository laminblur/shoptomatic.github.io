<?php
class Settings extends CI_Controller{
	function __construct()
	{
		parent::__construct();
		if(!isset($_SESSION['user'])){
			redirect('admin/dashboard');
		}else{
			$user=$_SESSION['user'][0];
			if($user->perm==USER){
				redirect('admin/denied');
			}
		}
		$this->load->helper('settings');
		$this->form_validation->set_error_delimiters('<div class="error-line">', '</div>');
	}

	function currency(){
		if(isset($_POST['currency'])){
			$currency=$this->input->post('currency');
			$settings=getSettings();
			$settings['currency']=$currency;
			setSettings($settings);
		}
		$this->load->model('countries_model');
		$data['list']=$this->countries_model->get_currency_code();
		$this->template->write_view('content','backends/settings/currency',$data);
		$this->template->render();
	}

	function mail(){
		if(isset($_POST['host'])){
			$host=$this->input->post('host');
			$user=$this->input->post('user');
			$pwd=$this->input->post('pwd');
			$port=$this->input->post('port');
			$mailpath=$this->input->post('mail_path');
			$from_user=$this->input->post('from_user');
			$from_email=$this->input->post('from_email');
			$settings=getSettings();
			$settings['smtp_host']        = $host;
			$settings['smtp_user']        = $user;
			$settings['smtp_pass']        = $pwd;
			$settings['smtp_port']        = $port;
			$settings['from_email']       = $from_email;
			$settings['from_user'] =        $from_user;
			$settings['mailpath']         = $mailpath;
			setSettings($settings);
		}
		$this->load->model('countries_model');
		$data['obj']=getSettings();
		$this->template->write_view('content','backends/settings/email',$data);
		$this->template->render();
	}

	function reset_mail_settings(){
		resetEmailSettings();
		redirect('admin/settings/mail');
	}

}
?>