<?php

class Communication extends Controller {

	function Communication()
	{
		parent::Controller();
	}
	
	function index()
	{
		$uuid = Hash::uuid();
		$this->load->library('barcode');
		$barcode = new Barcode();
		$barcode->Barcode39("$communication_type-$employee_id",$uuid);
	}
	
	function doc($id,$type)
	{
		$this->load->model('barcode');
		$this->load->model('uuid');
		$this->load->model('comm');
		$uuid = $this->uuid->generate_uuid();
		$barcode = new Barcode();
		$barcode->Barcode39("$type-$id",$uuid);
		
		
		$data = $this->comm->get_info($id,$type);
		
		$uType = $this->session->userdata('utype');
		if ($data['position'] <= $uType) {
			die('DENIED');
		}
		
		$data['appeal'] = '';
		if ($data['store_state'] == 'MT' && $data['terminated']) { //if state == mt and terminated = true
			$data['appeal'] = "<table style=\"border: solid 1px;\"><tr><td style=\"font-size: 12px;\"><b>If you wish to appeal this discharge, you may present a written statement to your Regional Manager, Human Resources, the Director of Operations, or the President within ten (10) calendar days after you are informed of the discharge.  The written statement shall recite any facts which you believe appropriate.  Your statement will be considered, and you will be advised of a final decision regarding your grievance within thirty (30) calendar days after you initiate the process.</b></td></tr></table><br />";
		}
		
		$data['agreement'] = '';
		if ($data['terminated'] == 0)
			$data['agreement'] = "<p>I have read the above disciplinary warning.  I understand what is expected of me to improve my job performance.  Furthermore, I understand my failure to abide by and Company policies or procedure will result in further disciplinary action up to and including termination of my employment.</p>";
		
		$data['barcode'] = $uuid;
		
		
		$output = $this->parser->parse('communication',$data,true);
		$whandle = fopen("/usr/share/cpm/tmp/$uuid.html","w");
		fwrite($whandle,$output);
		fclose($whandle);
		passthru("/usr/share/cpm/bin/wkhtmltopdf-amd64 --footer-right [page]/[topage] -s Letter https://cpm.carischinc.com/cpm/tmp/$uuid.html /usr/share/cpm/tmp/$uuid.pdf");
		unlink("/usr/share/cpm/tmp/$uuid.html");
		unlink("/usr/share/cpm/tmp/$uuid.jpg");
		$rhandle = fopen("/usr/share/cpm/tmp/$uuid.pdf","rb");
		$data = stream_get_contents($rhandle);
		fclose($rhandle);
		unlink("/usr/share/cpm/tmp/$uuid.pdf");
		
		
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="example.pdf"');
		
		echo $data;
	}
}

?>

