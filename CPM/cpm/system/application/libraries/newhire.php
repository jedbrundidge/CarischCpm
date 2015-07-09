<?php
require ("/var/www/htdocs/cpm/cpm/fpdf/fpdf.php");
class newhire
{
	var $xml_docs;

	var $import_file 	= "/newhire/EMPIMPT";
	var $Single 		= 1;
	var $Married 		= 2;
	var $MarriedSingle  = 3;
	var $org 			= "Carisch Inc., 681 East Lake St., Suite 262, Wayzata, MN 55391";
	/*
	 * Docs to mark as required on completion of a new hire.
	 *
	 * 1=Employment Form (coversheet)
	 * 2=Employee Warning   				(DO NOT SET)
	 * 3=Employee Review					(DO NOT SET)
	 * 4=Termination Info (voluntary)		(DO NOT SET)
	 * 5=Employee Signoffs
	 * 6=Employee Availability
	 * 7=I9
	 * 8=Communication Report
	 * 9=W4
	 * 10=Deposit Authorization
	 */
	var $required_docs	= array (1,5,7,9,10,8850,11);

	var $db;
	var $ci;

	var $bcfontsize 	= 24;

	var $coversheet		= 1;
	var $i9				= 7;
	var $w4				= 9;
	var $payroll		= 10;
	var $signoffs		= 5;
	var $w11		= 40;

	public function __construct() {
		$this->ci =& get_instance();
		$this->db = $this->ci->load->database('default',TRUE);
	}

	public function _init() {

	}

	public function validate() {

	}

	public function emp_coversheet($pdf,$eid) {
		$info = $this->db->query("
			SELECT  
				`e_id`,
				`first_name`,
				`last_name`,
				`middle_initial`,
				`ssn_hash` AS `ssn`,
				`position`,
				`uniq_id`,
				`store`,
				`street_addr`,
				`city`,
				`zip`,
				`employee_info`.`state`,
				`phone`,
				DATE_FORMAT(`dob`,'%m-%d-%Y') AS `dob`,
				DATE_FORMAT(`doh`,'%m-%d-%Y') AS `doh`,
				DATE_FORMAT(`start_date`,'%m-%d-%Y') AS `start_date`,
				`exemption`,
				`marital_status`,
				`allowances`,
				`additional_withholdings`,
				`gender`,
				`ethnicity`,
				`rate`,
				`pay_type`,
				`carisch_dimes`,
				`store_name`,
				`franchise_num`,
				`pms_num`,
				`family`,
				`submitter_id`,
				`stores`.`state` AS `store_state`,
				`stores`.`store_name` AS `store_name`,
				`state_num`,
				`comp_code`
			FROM
				`employees`
			JOIN
				`employee_info`
				ON
					`employees`.`uniq_id` = `employee_info`.`unique_id`
			JOIN
				`employee_wage`
				ON
					`employees`.`uniq_id` = `employee_wage`.`unique_id`
			JOIN
				`stores`
				ON
					`employees`.`store` = `stores`.`store_id`
			WHERE
				`uniq_id` = $eid
		");
		$info = $info->last_row();

		switch ($info->ethnicity) {
			case 1:
				$info->ethnicity = 'Caucasian';
				break;
			case 2:
				$info->ethnicity = 'Black';
				break;
			case 3:
				$info->ethnicity = 'Hispanic';
				break;
			case 4:
				$info->ethnicity = 'Asian';
				break;
			case 5:
				$info->ethnicity = 'American Indian or Alaskan Native';
				break;
			case 6:
				$info->ethnicity = 'Native Hawaiian or Pacific Islander';
				break;
			case 7:
				$info->ethnicity = 'Two or More';
				break;

		}
		
		switch ($info->marital_status) {
			case 0:
				$info->marital_status = "Single";
				break;
			case 1:
				$info->marital_status = "Married";
				break;
			case 2:
				$info->marital_status = "Married @ Single";
				break;
		}
		
		switch ($info->gender) {
			case "m":
				$info->gender = "Male";
				break;
			case "f":
				$info->gender = "Female";
				break;
		}

		switch ($info->pay_type) {
			case 0:
				$info->pay_type = "Hourly";
				break;
			case 1:
				$info->pay_type = "Salary";
				break;
		}

		switch ($info->carisch_dimes) {
			case 0:
				$info->carisch_dimes = "No";
				break;
			case 1:
				$info->carisch_dimes = "Yes";
				break;
		}

		switch ($info->family) {
			case 0:
				$info->family = "No";
				break;
			case 1:
				$info->family = "Yes";
				break;
		}

		$pdf->addFont("BarCode39fHR","","bar39fh.php");
		$pdf->AddPage();
		$pdf->setFont("BarCode39fHR","",$this->bcfontsize);
		$pdf->Text(10,20,"*$this->coversheet-$eid*");
		$pdf->setFont("Arial","B",13);
		$pdf->Text(20,32,"EMPLOYMENT FORM");
		$pdf->setFont("Arial","",12);
		$pdf->Text(170,10,"Carisch ID: $info->e_id");
		$pdf->Text(20,40,"Store #:   $info->store");
		$pdf->Text(60,40,"Franchise #:   $info->franchise_num");
		$pdf->Text(110,40,"Store Name:   $info->store_name");
		$pdf->Text(20,50,"Employee #:   $info->e_id");
		$format_ssn = substr($info->ssn,0,3) . '-';
		$format_ssn .= substr($info->ssn,3,2) . '-';
		$format_ssn .= substr($info->ssn,5,4);
		$pdf->Text(20,60,"SSN:   $format_ssn");
		$pdf->Text(20,70,"Last Name:   $info->last_name");
		$pdf->Text(90,70,"First Name:   $info->first_name");
		$pdf->Text(150,70,"Middle Initial:   $info->middle_initial");
		$pdf->Text(20,80,"Street Address:   $info->street_addr");
		$pdf->Text(140,80,"Apt #:");
		$pdf->Text(154,80,$this->ci->session->userdata('apt_number'));
		$pdf->Text(20,90,"City:   $info->city");
		$pdf->Text(85,90,"State:   $info->state");
		$pdf->Text(130,90,"Zip:   $info->zip");
		$format_phone = substr($info->phone,0,3) . '-';
		$format_phone .= substr($info->phone,3,3) . '-';
		$format_phone .= substr($info->phone,6,4);
		$pdf->Text(20,100,"Phone Number:   $format_phone");
		$pdf->Text(20,110,"Gender:   $info->gender");
		$pdf->Text(130,110,"Ethnic Code:   $info->ethnicity");
		$pdf->Text(20,120,"Marital Status:   $info->marital_status");
		$pdf->Text(20,130,"Date of Birth:   $info->dob");
		$pdf->Text(20,140,"Start Date:   $info->start_date");
		$pdf->Text(20,150,"Work Comp Code:   $info->store_state=$info->comp_code");
		$pdf->Text(80,150,"Pay Type:   $info->pay_type");
		$pdf->Text(20,160,"Pay Rate:   $info->rate");
		$pdf->Text(20,170,"Carisch Dimes Deduction (non-management $.10/pay period):   $info->carisch_dimes");
		$pdf->Text(20,180,"State Where Employed:   $info->store_state-$info->state_num");
		$pdf->Text(20,190,"Have you or a family member worked for Carisch Inc. before?   $info->family");
		//$pdf->Text(20,200,"If so, which location? $info->family_location");
		$pdf->setFont("Arial","B",10);
		return $pdf;
	}
	
	public function emp_w11($pdf,$eid) {
		$pdf->addFont("BarCode39fHR","","bar39fh.php");
		$pdf->AddPage();
		$pdf->Image('w11.png',11,15,190);
		$pdf->setFont("BarCode39fHR","",$this->bcfontsize);
		$pdf->Text(10,20,"*$this->w11-$eid*");
		$pdf->setFont("Arial","B",11);
		return $pdf;
	}

	public function emp_i9($pdf,$eid,$arch) {
		$info = $this->db->query("
			SELECT  
				`e_id`,
				`first_name`,
				`last_name`,
				`middle_initial`,
				`ssn_hash` AS `ssn`,
				`position`,
				`uniq_id`,
				`store`,
				`street_addr`,
				`city`,
				`zip`,
				`employee_info`.`state`,
				`phone`,
				`dob`,
				`doh`,
				`start_date`,
				date_format(start_date,'%c/%d/%Y') AS `ss_start`,
				`exemption`,
				`marital_status`,
				`allowances`,
				`additional_withholdings`,
				`gender`,
				`ethnicity`,
				`rate`,
				`pay_type`,
				`carisch_dimes`,
				`store_name`,
				`franchise_num`,
				`pms_num`,
				`family`,
				`submitter_id`,
				`stores`.`state` AS `store_state`,
				`stores`.`store_name` AS `store_name`
			FROM
				`employees`
			JOIN
				`employee_info`
				ON
					`employees`.`uniq_id` = `employee_info`.`unique_id`
			JOIN
				`employee_wage`
				ON
					`employees`.`uniq_id` = `employee_wage`.`unique_id`
			JOIN
				`stores`
				ON
					`employees`.`store` = `stores`.`store_id`
			WHERE
				`uniq_id` = $eid
		");
		$info = $info->last_row();


		$dob = explode("-",$info->dob);
		$dob = "$dob[1]/$dob[2]/$dob[0]";

		$pdf->addFont("BarCode39fHR","","bar39fh.php");
		$pdf->AddPage();
		$pdf->Image('formi9.png',11,15,190);
		$pdf->setFont("BarCode39fHR","",$this->bcfontsize);
		$pdf->Text(10,20,"*$this->i9-$eid*");
		$pdf->setFont("Arial","B",11);
		$pdf->Text(170,10,"Carisch ID: $info->e_id");
		$pdf->Text(25,74,$info->last_name);
		$pdf->Text(78,74,$info->first_name);
		$pdf->Text(128,74,$info->middle_initial);
		$pdf->Text(139,74,$this->ci->session->userdata('other_name'));
		//$pdf->Text(150,65,$info->other_name); //add form field for other names
		$pdf->Text(25,84,$info->street_addr);
		$pdf->Text(89,84,$this->ci->session->userdata('apt_number'));
		$pdf->Text(91,94,$this->ci->session->userdata('email'));
		$pdf->Text(157,94,$info->phone);
		//$pdf->Text(127,77,$_SESSION["APT"]);  //add form field for apt number
		//$pdf->Text(127,77,$_SESSION["APT"]);  //add form field for email
		//$pdf->Text(127,77,$_SESSION["APT"]);  //add form field for phone number

		$pdf->Text(25,94,$dob);
		$pdf->Text(107,84,$info->city);
		$pdf->Text(151,84,$info->state);
		$pdf->Text(165,84,$info->zip);
              
		// Print SSN
              $pdf->setFont("Arial","B",14);
		$format_ssn = substr($info->ssn,0,3) . '  ';
		$format_ssn .= substr($info->ssn,3,2) . '  ';
		$format_ssn .= substr($info->ssn,5,4);
		$pdf->Text(56,94,"$format_ssn");
		$pdf->setFont("Arial","B",11);

	/*
		//Position Tests I9 (Page 1)

			// Other Names Position Test
			$pdf->Text(139,74,"Other Name");

			// Apt Number Position Test
			$pdf->Text(89,84,"1234");

			// Email Address Position Test
			$pdf->Text(91,94,"employee@carischinc.com");

			// Telephone Number Position Test
			$pdf->Text(157,94,"555-867-5309");

			// Alien Admission Number PermRes Number Position Test
			$pdf->Text(119,123.5,"Admission Number PermRes");

			// Alien Auth Until Position Test
			$pdf->Text(115,130,"12/12/1212");

			// Alien Registration Number Position Test
			$pdf->Text(86,144,"Registration Number");

			// Alien I94 Number Position Test
			$pdf->Text(70,152.5,"I94 Number");

			// Citizenship Checkbox Position Tests
			$pdf->setFont("Arial","B",9);
			$pdf->Text(23,113.5,"X");
			$pdf->Text(23,118.7,"X");
			$pdf->Text(23,124,"X");
			$pdf->Text(23,130,"X");
	*/

		// Citizenship Checkbox 
		$pdf->setFont("Arial","B",9);
		switch ($this->ci->session->userdata('citizenship')) {
			case 0:
				$pdf->Text(23,113.5,"X");
				break;
			case 1:
				$pdf->Text(23,118.7,"X");
				break;
			case 2:
				$pdf->Text(23,124,"X");
				$pdf->Text(119,123.5,$this->ci->session->userdata('alien_admission_number_permres'));
				break;
			case 3:
				$pdf->Text(23,130,"X");
				$pdf->Text(115,130,$this->ci->session->userdata('alien_auth_until'));
				$pdf->Text(86,144,$this->ci->session->userdata('alien_admission_number'));
				$pdf->Text(70,152.5,$this->ci->session->userdata('alien_i94_number'));
				break;
		}
		//$pdf->Text(165,103,$this->ci->session->userdata('alien_admission_number'));

		// X placed for new empoyee signature
		$pdf->setFont("Arial","B",20); //set arial bold size 20
		$pdf->SetTextColor(255,0,0); //set color red
		$pdf->Text(16,190,"X"); //place red X to left of signature area
		$pdf->SetTextColor(0,0,0); //set color back to black

		// Start I9 second page
		$pdf->AddPage();
		$pdf->Image('formi9b.png',11,15,190);

		$pdf->setFont("Arial","B",11);
		$pdf->Text(170,10,"Carisch ID: $info->e_id");

		switch ($this->ci->session->userdata('a_doc_title')) {
			case "passport":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(25,69,"US Passport OR US Passport Card");
				break;
			case "perm":
				$pdf->setFont("Arial","B",4);
				$pdf->Text(25,69,"Permanent Resident Card / Alien Registration Receipt Card (I-551)");
				break;
			case "alienreg":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(25,69,"Employment Authorization Document (I-766)");
				break;
			case "unexfpwst":
				$pdf->setFont("Arial","B",5);
				$pdf->Text(25,69,"Unexpired foreign passport w/ I-551 Stamp");
				break;
			case "unexfpweadr":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(25,69,"Foreign Passport w/ Form I-94 or I-94A");
				break;
			case "micro":
				$pdf->setFont("Arial","B",5);
				$pdf->Text(25,69,"Passport from FSM/RMI w/ Form I-94 or I-94A");
				break;
		}

		$pdf->setFont("Arial","B",7);
		$pdf->Text(25,75,$this->ci->session->userdata('a_issuing_auth'));
		$pdf->Text(25,82,$this->ci->session->userdata('a_docnum'));
		$pdf->Text(25,90,$this->ci->session->userdata('a_expiredate'));
		//$pdf->Text(42,177,$this->ci->session->userdata('a_docnum2'));
		//$pdf->Text(60,182,$this->ci->session->userdata('a_expiredate2'));

		switch ($this->ci->session->userdata('b_doc_title')) {
			case "drvl":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"Driver's License");
				break;
			case "stateid":
				$pdf->setFont("Arial","B",5);
				$pdf->Text(80,69,"State Issued ID Card w/ photo or personal info");
				break;
			case "idcard":
				$pdf->setFont("Arial","B",4);
				$pdf->Text(80,69,"ID issued by fed/state/local gov't w/ photo or personal info");
				break;
			case "schid":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"School ID w/ Photo");
				break;
			case "voterid":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"Voter's registration card");
				break;
			case "milit":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"US Military Card or Draft Record");
				break;
			case "midepc":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"Military Dependent's ID Card");
				break;
			case "coastg":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"US Coast Guard Merchant Mariner Card");
				break;
			case "natamtr":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"Native American tribal document");
				break;
			case "candr":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"Canadian Driver's license");
				break;
			case "u18sch":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"U18-School record/report card");
				break;
			case "u18clin":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"U18-Clinic, doctor or hospital record");
				break;
			case "u18day":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(80,69,"U18-Day-care/nursery school record");
				break;
		}

		$pdf->setFont("Arial","B",7);
		$pdf->Text(80,75,$this->ci->session->userdata('b_issuing_auth'));
		$pdf->Text(80,82,$this->ci->session->userdata('b_docnum'));
		$pdf->Text(80,90,$this->ci->session->userdata('b_expiredate'));

		switch ($this->ci->session->userdata('c_doc_title')) {
			case "ssc":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(137,69,"US Social Security Card");
				break;
			case "coba":
				$pdf->setFont("Arial","B",4);
				$pdf->Text(137,69,"Certification of Birth Abroad issued by Department of State (FS-545)");
				break;
			case "cobc":
				$pdf->setFont("Arial","B",4);
				$pdf->Text(137,69,"Original/Certified copy of state issued birth certificate");
				break;
			case "cobc2":
				$pdf->setFont("Arial","B",4);
				$pdf->Text(137,69,"Certification of Birth issued by Department of State (DS-1350)");
				break;
			case "natd":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(137,69,"Native American Tribal Document");
				break;
			case "uscid":
				$pdf->setFont("Arial","B",7);
				$pdf->Text(137,69,"US Citizen ID Card (Form I-197)");
				break;
			case "idcard":
				$pdf->setFont("Arial","B",6);
				$pdf->Text(137,69,"ID Card for use of Resident Citizen in US (Form I-179)");
				break;
			case "dhs":
				$pdf->setFont("Arial","B",6);
				$pdf->Text(137,69,"DHS issued unexpired employement authorization document");
				break;
		}


		$pdf->setFont("Arial","B",7);
		$pdf->Text(137,75,$this->ci->session->userdata('c_issuing_auth'));
		$pdf->Text(137,82,$this->ci->session->userdata('c_docnum'));
		$pdf->Text(137,90,$this->ci->session->userdata('c_expiredate'));


		if (!$this->ci->session->userdata('smonth')) {
			$ss_start_date = $info->ss_start;
		} else {
			$ss_start_date = $this->ci->session->userdata('smonth')."/".$this->ci->session->userdata('sday')."/".$this->ci->session->userdata('syear');
		}
		$pdf->Text(100,167,"$ss_start_date");
		$pdf->setFont("Arial","B",11);
		if ($info->submitter_id) {
			$suid = $this->db->query("SELECT `first_name`,`last_name`,`user_type` FROM `users` WHERE `user_id` = $info->submitter_id");
			$suid = $suid->last_row();
			//$ssname = "$suid->first_name $suid->last_name";
			switch ($suid->user_type) {
				case 3:
					$sub_pos = "Regional Manager";
					break;
				case 4:
					$sub_pos = "General Manager";
					break;
				case 5:
					$sub_pos = "Assistant Manager";
					break;
				case 6:
					$sub_pos = "Shift Manager";
					break;
				default:
					$sub_pos = "";
					break;
			}
			$pdf->Text(130,178,"$sub_pos");
			$pdf->Text(25,187,"$suid->last_name");
			$pdf->Text(78,187,"$suid->first_name");

		} else {
			//$ssname = "";
			//$sub_pos = "";
		}


		//$pdf->Text(93,205,"$ssname");
		//$pdf->Text(130,178,"$sub_pos");

	/*
		//Position Tests I9 (Page 2)

			// Submitter Name & Position & Date
			$pdf->setFont("Arial","B",11);
			$pdf->Text(100,167,"12/12/1212");  // First Day of Employment Date (Start Date)
			$pdf->Text(102,178,"12/12/1212");  // Submission Date
			$pdf->Text(130,178,"Sub-Position");
			$pdf->Text(25,187,"Sub-Last");
			$pdf->Text(78,187,"Sub-First");
		
			// A Doc Info
			$pdf->setFont("Arial","B",8);
			$pdf->Text(25,69,"A-Doc-Title");
			$pdf->Text(25,75,"A-Issue_Auth");
			$pdf->Text(25,82,"A-Doc-Num");
			$pdf->Text(25,90,"A-Expire-Date");

			// B Doc Info
			$pdf->setFont("Arial","B",8);
			$pdf->Text(80,69,"B-Doc-Title");
			$pdf->Text(80,75,"B-Issue_Auth");
			$pdf->Text(80,82,"B-Doc-Num");
			$pdf->Text(80,90,"B-Expire-Date");

			// C Doc Info
			$pdf->setFont("Arial","B",8);
			$pdf->Text(137,69,"C-Doc-Title");
			$pdf->Text(137,75,"C-Issue_Auth");
			$pdf->Text(137,82,"C-Doc-Num");
			$pdf->Text(137,90,"C-Expire-Date");
	*/		

		//Print Org Name and Address
		$pdf->setFont("Arial","B",11);
		$pdf->Text(121,187,"Carisch, Inc");
		$pdf->Text(25,195,"681 Lake St E, STE 262");
		$pdf->Text(110,195,"Wayzata");
		$pdf->Text(153,195,"MN");
		$pdf->Text(170,195,"55391");


		// Old way of printing org name and address
		//$pdf->Text(25,190,"$this->org");


	/*
		if ($arch) {
			$arch_pdf = new FPDF();
			$arch_pdf->addFont("BarCode39fHR","","bar39fh.php");
			$arch_pdf->AddPage();
			$arch_pdf->Image('formi9.png',11,15,190);
			$arch_pdf->setFont("BarCode39fHR","",$this->bcfontsize);
			$arch_pdf->Text(10,20,"*$this->i9-$eid*");
			$arch_pdf->setFont("Arial","B",11);

			$arch_pdf->Text(36,65,$info->last_name);
			$arch_pdf->Text(85,65,$info->first_name);
			$arch_pdf->Text(130,65,$info->middle_initial);
			//$arch_pdf->Text(150,65,$info->maiden_name); //add maiden name to db
			$arch_pdf->Text(36,74,$info->street_addr);
			//$arch_pdf->Text(127,77,$_SESSION["APT"]);  //add form field for apt number
			$arch_pdf->Text(150,74,$dob);
			$arch_pdf->Text(36,82,$info->city);
			$arch_pdf->Text(81,82,$info->state);
			$arch_pdf->Text(126,82,$info->zip);
			$arch_pdf->Text(150,82,$info->ssn);

			$arch_pdf->setFont("Arial","B",9);
			switch ($this->ci->session->userdata('citizenship')) {
				case 0:
					$arch_pdf->Text(106,90,"X");
					break;
				case 1:
					$arch_pdf->Text(106,95,"X");
					break;
				case 2:
					$arch_pdf->Text(105,99,"X");
					break;
				case 3:
					$arch_pdf->Text(105,104,"X");
					$arch_pdf->Text(164,107,$this->ci->session->userdata('alien_auth_until'));
					break;
			}
			$arch_pdf->Text(165,103,$this->ci->session->userdata('alien_admission_number'));
			$arch_pdf->setFont("Arial","B",4);
			switch ($this->ci->session->userdata('a_doc_title')) {
				case "passport":
					$arch_pdf->Text(43,158,"US Passport (unexpired/expired)");
					break;
				case "perm":
					$arch_pdf->Text(43,158,"Permanent Resident Card");
					break;
				case "alienreg":
					$arch_pdf->Text(43,158,"Alien Registration Receipt Card (FI551)");
					break;
				case "unexfpwst":
					$arch_pdf->Text(43,158,"Unexpired foreign passport w/temporary I-551 Stamp");
					break;
				case "unexfpweadr":
					$arch_pdf->Text(37,158,"Unexp foreign passport w/unexp. Arrival Departure Record");
					break;
				case "micro":
					$arch_pdf->Text(43,158,"Passport from FSM/RMI w/ I-94 Nonimmigrant admission");
					break;
			}

			$arch_pdf->setFont("Arial","B",7);
			$arch_pdf->Text(47,163,$this->ci->session->userdata('a_issuing_auth'));
			$arch_pdf->Text(42,168,$this->ci->session->userdata('a_docnum'));
			$arch_pdf->Text(60,173,$this->ci->session->userdata('a_expiredate'));
			$arch_pdf->Text(42,177,$this->ci->session->userdata('a_docnum2'));
			$arch_pdf->Text(60,182,$this->ci->session->userdata('a_expiredate2'));

			switch ($this->ci->session->userdata('b_doc_title')) {
				case "drvl":
					$arch_pdf->Text(90,158,"Driver's License");
					break;
				case "stateid":
					$arch_pdf->setFont("Arial","B",4);
					$arch_pdf->Text(83,158,"State Issued ID Card containing photo or name,DOB,gender,height,eye color,address");
					break;
				case "idcard":
					$arch_pdf->setFont("Arial","B",4);
					$arch_pdf->Text(82,158,"ID issued by fed, state or local gov't w/photo or name,DOB,gender,height,eye color,address");
					break;
				case "schid":
					$arch_pdf->Text(90,158,"School ID w/ Photo");
					break;
				case "voterid":
					$arch_pdf->Text(90,158,"Voter's registration card");
					break;
				case "milit":
					$arch_pdf->Text(90,158,"US Military Card or Draft Record");
					break;
				case "midepc":
					$arch_pdf->Text(90,158,"Military Dependent's ID Card");
					break;
				case "coastg":
					$arch_pdf->Text(90,158,"US Coast Guard Merchant Mariner Card");
					break;
				case "natamtr":
					$arch_pdf->Text(90,158,"Native American tribal document");
					break;
				case "candr":
					$arch_pdf->Text(90,158,"Canadian Driver's license");
					break;
				case "u18sch":
					$arch_pdf->Text(90,158,"U18-School record/report card");
					break;
				case "u18clin":
					$arch_pdf->Text(90,158,"U18-Clinic, doctor or hospital record");
					break;
				case "u18day":
					$arch_pdf->Text(90,158,"U18-Day-care/nursery school record");
					break;
			}

			$arch_pdf->setFont("Arial","B",7);
			$arch_pdf->Text(90,163,$this->ci->session->userdata('b_issuing_auth'));
			$arch_pdf->Text(90,168,$this->ci->session->userdata('b_docnum'));
			$arch_pdf->Text(90,173,$this->ci->session->userdata('b_expiredate'));

			switch ($this->ci->session->userdata('c_doc_title')) {
				case "ssc":
					$arch_pdf->Text(150,158,"US Social Security Card");
					break;
				case "coba":
					$arch_pdf->setFont("Arial","B",4);
					$arch_pdf->Text(145,158,"Certification of Birth Abroad issued by the Department of State (FS-545)");
					break;
				case "cobc":
					$arch_pdf->setFont("Arial","B",5);
					$arch_pdf->Text(145,158,"Original/Certified copy of state issued birth certificate");
					break;
				case "cobc2":
					$arch_pdf->setFont("Arial","B",5);
					$arch_pdf->Text(145,158,"Certification of Birth Abroad issued by the Department of State (DS-1350)");
					break;
				case "natd":
					$arch_pdf->Text(145,158,"Native American Tribal Document");
					break;
				case "uscid":
					$arch_pdf->Text(145,158,"US Citizen ID Card (Form I-197)");
					break;
				case "idcard":
					$arch_pdf->setFont("Arial","B",6);
					$arch_pdf->Text(145,158,"ID Card for use of Resident Citizen in US");
					break;
				case "dhs":
					$arch_pdf->setFont("Arial","B",4);
					$arch_pdf->Text(145,158,"DHS issued unexpired employement authorization document");
					break;
			}


			$arch_pdf->setFont("Arial","B",7);
			$arch_pdf->Text(150,163,$this->ci->session->userdata('c_issuing_auth'));
			$arch_pdf->Text(150,168,$this->ci->session->userdata('c_docnum'));
			$arch_pdf->Text(150,173,$this->ci->session->userdata('c_expiredate'));

			$arch_pdf->Text(48,193,$this->ci->session->userdata('smonth')."/".$this->ci->session->userdata('sday')."/".$this->ci->session->userdata('syear'));
			$arch_pdf->setFont("Arial","B",9);
			$suid = $this->db->query("SELECT `first_name`,`last_name`,`user_type` FROM `users` WHERE `user_id` = $info->submitter_id");
			$suid = $suid->last_row();
			$arch_pdf->Text(93,205,"$suid->first_name $suid->last_name");
			switch ($suid->user_type) {
				case 3:
					$sub_pos = "Regional Manager";
					break;
				case 4:
					$sub_pos = "General Manager";
					break;
				case 5:
					$sub_pos = "Assisstant Manager";
					break;
				case 6:
					$sub_pos = "Shift Manager";
					break;
				default:
					$sub_pos = "manager";
					break;
			}

			$arch_pdf->Text(150,205,"$sub_pos");
			$arch_pdf->Text(30,213,"$this->org");



			//X for signatures
			$arch_pdf->setFont("Arial","B",20); //set arial bold size 20
			$arch_pdf->SetTextColor(255,0,0); //set color red
			$arch_pdf->Text(16,113,"X"); //place red X to left of signature area
			$arch_pdf->SetTextColor(0,0,0); //set color back to black
			$ts = date('mdYHis');
			$arch_pdf->Output("/var/pdf/$eid-$ts.pdf","F");
			$this->ci->db->query("UPDATE `employee_info` SET `last_i9` = '$eid-$ts.pdf' WHERE `unique_id` = $eid");
		}

	*/

		return $pdf;
	}

	public function emp_8850($pdf,$eid) {
		$info = $this->db->query("
			SELECT  
				`e_id`,
				`first_name`,
				`last_name`,
				`middle_initial`,
				`ssn_hash` AS `ssn`,
				`position`,
				`uniq_id`,
				`store`,
				`street_addr`,
				`city`,
				`zip`,
				`employee_info`.`state`,
				`phone`,
				`dob`,
				`doh`,
				`start_date`,
				`exemption`,
				`county`,
				`marital_status`,
				`allowances`,
				`additional_withholdings`,
				`gender`,
				`ethnicity`,
				`rate`,
				`pay_type`,
				`carisch_dimes`,
				`store_name`,
				`franchise_num`,
				`pms_num`,
				`family`,
				`submitter_id`,
				`stores`.`state` AS `store_state`,
				`stores`.`store_name` AS `store_name`
			FROM
				`employees`
			JOIN
				`employee_info`
				ON
					`employees`.`uniq_id` = `employee_info`.`unique_id`
			JOIN
				`employee_wage`
				ON
					`employees`.`uniq_id` = `employee_wage`.`unique_id`
			JOIN
				`stores`
				ON
					`employees`.`store` = `stores`.`store_id`
			WHERE
				`uniq_id` = $eid
		");
		$info = $info->last_row();
		$pdf->AddPage();
		$pdf->Image('8850.png',9,12,190);
		$pdf->setFont("Arial","B",16);
		$pdf->Text(10,10,$eid);
		$pdf->setFont("Arial","B",11);
		$pdf->Text(40,49,"$info->first_name $info->last_name");
		$pdf->Text(158,49,substr($info->ssn,0,3)."  ".substr($info->ssn,3,2)."  ".substr($info->ssn,5,4));
		$pdf->Text(63,56,$info->street_addr);
			// Trying to add Apt # to address on form, line below prints out the variable name "$this->ci->session->userdata('apt_number')" on the form
			// $pdf->Text(63,56,"$info->street_addr, Apt #: $this->ci->session->userdata('apt_number')");
		$pdf->Text(71,64,"$info->city, $info->state $info->zip");
		$pdf->Text(38,71,"$info->county");
		$pdf->Text(147,71,substr($info->phone,0,3)."    ".substr($info->phone,3,3)."     ".substr($info->phone,6,4));

		$check_age = $this->db->query("SELECT DATEDIFF(CURRENT_DATE(),`dob`)/365 AS age FROM `employee_info` WHERE `unique_id` = $eid");
		$check_age = $check_age->last_row();
		if ($check_age->age < 40) {
			$dob_array = explode("-",$info->dob);
			$pdf->Text(110,79,"$dob_array[1]  $dob_array[2]  $dob_array[0]");
		}

		$pdf->setFont("Arial","B",10);
		//if ($this->ci->session->userdata('katrina')) $pdf->Text(29.5,86.5,"X");
		//$pdf->Text(35,94,$this->ci->session->userdata('katrina_address'));
		if ($this->ci->session->userdata('swa')) $pdf->Text(29.5,86,"X");
		if ($this->ci->session->userdata('tanf')) $pdf->Text(29.5,97.5,"X");
		if ($this->ci->session->userdata('vet')) $pdf->Text(29.5,153,"X");
		if ($this->ci->session->userdata('vet2')) $pdf->Text(29.5,164,"X");
		if ($this->ci->session->userdata('vet3')) $pdf->Text(29.5,175,"X");
		if ($this->ci->session->userdata('memfam')) $pdf->Text(29.5,186.5,"X");

/*
		// Checkbox Position Test
			$pdf->setFont("Arial","B",10);
			$pdf->Text(29.5,86,"X1");	//SWA
			$pdf->Text(29.5,97.5,"X2");  //TANF
			$pdf->Text(29.5,153,"X3");   //VET
			$pdf->Text(29.5,164,"X4");   //VET2
			$pdf->Text(29.5,175,"X5");   //VET3
			$pdf->Text(29.5,186.5,"X6");   //MEMFAM
*/


		$pdf->setFont("Arial","",9);
		$pdf->text(26,255,"PMS #");
		$pdf->text(62,255,"Franchise #");

		$pdf->setFont("Arial","B",14);
		$pdf->Text(37,255,$info->pms_num);
		$pdf->Text(80,255,$info->franchise_num);


		/*
		 * CREATE A PDF AND SAVE IT TO THE SERVER, WHEN SOMEONE WANTS TO CHANGE ANY INFO PRINT
		 * OUT THIS DOCUMENT AND HAVE THE COMPLETE THE LAST SECTION
		 */

		return $pdf;
	}
	public function emp_w4($pdf,$eid) {
		$info = $this->db->query("
			SELECT  
				`e_id`,
				`first_name`,
				`last_name`,
				`middle_initial`,
				`ssn_hash` AS `ssn`,
				`position`,
				`uniq_id`,
				`store`,
				`street_addr`,
				`city`,
				`zip`,
				`employee_info`.`state`,
				`phone`,
				`dob`,
				`doh`,
				`start_date`,
				`exemption`,
				`marital_status`,
				`allowances`,
				`additional_withholdings`,
				`gender`,
				`ethnicity`,
				`rate`,
				`pay_type`,
				`carisch_dimes`,
				`store_name`,
				`franchise_num`,
				`pms_num`,
				`family`,
				`submitter_id`,
				`stores`.`state` AS `store_state`,
				`stores`.`store_name` AS `store_name`
			FROM
				`employees`
			JOIN
				`employee_info`
				ON
					`employees`.`uniq_id` = `employee_info`.`unique_id`
			JOIN
				`employee_wage`
				ON
					`employees`.`uniq_id` = `employee_wage`.`unique_id`
			JOIN
				`stores`
				ON
					`employees`.`store` = `stores`.`store_id`
			WHERE
				`uniq_id` = $eid
		");
		$info = $info->last_row();
		$pdf->addFont("BarCode39fHR","","bar39fh.php");
		$pdf->AddPage();
		$pdf->Image('formw4.png',11,15,190);
		$pdf->setFont("BarCode39fHR","",$this->bcfontsize);
		$pdf->Text(10,20,"*$this->w4-$eid*");
		$pdf->setFont("Arial","B",11);
		$pdf->Text(170,10,"Carisch ID: $info->e_id");
		
/*
		//Writes "Exempt" on form if option was selected.  Removed by request from Payroll Dept  (2014/07/11)

		if ($info->exemption == 1) {
			$pdf->Text(155,231,"Exempt");
		}

		//Writes Allowances & Additional Withholdings data on form.  Removed by request from Payroll Dept  (2014/07/11)

		$pdf->Text(177,212,$info->allowances);
		$pdf->Text(177,216,$info->additional_withholdings);
*/

		$pdf->Text(30,193,"$info->first_name $info->middle_initial");
		$pdf->Text(85,193,"$info->last_name");
		$pdf->Text(153,193,substr($info->ssn,0,3)."  ".substr($info->ssn,3,2)."  ".substr($info->ssn,5,4));
		$pdf->Text(30,201,"$info->street_addr");
		$pdf->Text(30,208,"$info->city, $info->state, $info->zip");
		switch ($info->marital_status) {
			case 0:
				$pdf->Text(111,197,"X");
				break;
			case 1:
				$pdf->Text(125,197,"X");
				break;
			case 2:
				$pdf->Text(138,197,"X");
				break;
		}

		return $pdf;
	}

	public function emp_deposit($pdf,$eid) {
		$c_id = $this->db->query("SELECT `e_id` FROM `employees` WHERE `uniq_id` = $eid");
		$c_id = $c_id->last_row();
		$pdf->addFont("BarCode39fHR","","bar39fh.php");
		$pdf->AddPage();
		$pdf->Image('payroll.png',-10,0,240);
		$pdf->setFont("BarCode39fHR","",$this->bcfontsize);
		$pdf->Text(10,20,"*$this->payroll-$eid*");
		$pdf->setFont("Arial","B",16);
		$pdf->Text(95,106,"$c_id->e_id");
		$pdf->Text(95,256,"$c_id->e_id");
		return $pdf;
	}

	public function emp_signoffs($pdf,$eid) {
		$pdf->addFont("BarCode39fHR","","bar39fh.php");
		$pdf->AddPage();
		$pdf->setFont("BarCode39fHR","",$this->bcfontsize);
		$pdf->Text(10,20,"*$this->signoffs-$eid*");
		return $pdf;
	}

	public function generate_pdf($pdf) {
		header("Pragma: public");
		header("Expires: 0");
		$pdf->Output("newhire.pdf","D");
	}

	private function current_ids($store) {
		$query = $this->db->query("
		    SELECT 
		        `e_id` 
		    FROM 
		        `employees` 
		    WHERE 
		        `store` = $store
		");
		foreach ($query->result() as $result) {
			$employee_ids[$result->e_id] = true;
		}
		if (isset($employee_ids)) {
			return $employee_ids;
		} else {
			return false;
		}
	}

	public function create_import($uniq_id) {
		//@TODO SQL QUERIES
		$result = $this->db->query("
		    			SELECT  
				`e_id`,
				`first_name`,
				`last_name`,
				`middle_initial`,
				`ssn_hash` AS `ssn`,
				`position`,
				`uniq_id`,
				`store`,
				`street_addr`,
				`city`,
				`zip`,
				`employee_info`.`state`,
				`phone`,
				`dob`,
				`doh`,
				`start_date`,
				date_format(start_date,'%c%d%y') AS `ss_start`,
				`exemption`,
				`marital_status`,
				`allowances`,
				`additional_withholdings`,
				`gender`,
				`ethnicity`,
				`rate`,
				`pay_type`,
				`carisch_dimes`,
				`store_name`,
				`franchise_num`,
				`pms_num`,
				`family`,
				`submitter_id`,
				`stores`.`state` AS `store_state`,
				`stores`.`store_name` AS `store_name`,
				`comp_code`,
				`state_num`
			FROM
				`employees`
			JOIN
				`employee_info`
				ON
					`employees`.`uniq_id` = `employee_info`.`unique_id`
			JOIN
				`employee_wage`
				ON
					`employees`.`uniq_id` = `employee_wage`.`unique_id`
			JOIN
				`stores`
				ON
					`employees`.`store` = `stores`.`store_id`
			WHERE
				`uniq_id` = $uniq_id
		");
		$result = $result->last_row();

		$ssnd 			= substr($result->ssn,-9,3).substr($result->ssn,-6,2).substr($result->ssn,-4,4);
		$last_name 		= trim(strtoupper($result->last_name));
		$first_name 	= trim(strtoupper($result->first_name));
		$middle_initial = trim(strtoupper($result->middle_initial));
		$street_addr 	= trim(strtoupper($result->street_addr));
		$city 			= trim(strtoupper($result->city));
		$phone 			= substr($result->phone,-10,3).'-'.substr($result->phone,-7,3).'-'.substr($result->phone,-4,4);
		$carisch_id 	= $result->e_id;
		$state 			= trim(strtoupper($result->state));
		$dob 			= substr($result->dob,-5,2).substr($result->dob,-2,2).substr($result->dob,-8,2);
		$zip 			= trim($result->zip);
		$gender 		= strtoupper($result->gender);
		$paytype 		= $result->pay_type;
		$payrate 		= $result->rate;
		$marital_status = $result->marital_status;
		$allowances 	= $result->allowances;
		//$store_id		= $result->store;
		$comp_code		= $result->comp_code;
		$state_emp_code = $result->state_num;
		$store_id		= $result->store;
		$ethnicity 		= $result->ethnicity;
		switch ($ethnicity) {
			case 1:
				$ethnicity = 'C';
				break;
			case 2:
				$ethnicity = 'B';
				break;
			case 3:
				$ethnicity = 'H';
				break;
			case 4:
				$ethnicity = 'A';
				break;
			case 5:
				$ethnicity = 'I';
				break;
			case 6:
				$ethnicity = 'P';
				break;
			case 7:
				$ethnicity = 'T';
				break;

		}
		$start_date		= $result->ss_start;
		$tax_code 		= 1;
		if ($result->exemption) {
			$tax_code = 0;
		}
		if ($result->additional_withholdings > 0) {
			$tax_code = 3;
		}
		//@TODO Finish variable assignments.

		$block  = "1,$carisch_id,1,\"\",$ssnd,\"$last_name\",\"$first_name $middle_initial\",\"$street_addr\",\"$city\",\"$state\",US,$zip,$phone,$gender,$dob,$paytype,$payrate,B,$store_id,$marital_status,$allowances,$state_emp_code,0,$state_emp_code,,,$comp_code,0,N,$ethnicity,1,$store_id".chr(13).chr(10);
		$block .= "1,$carisch_id,2,0,,,,,$start_date".chr(13).chr(10);
		$block .= "1,$carisch_id,3,0,0,2,0,0".chr(13).chr(10);
		$block .= "1,$carisch_id,100,$tax_code".chr(13).chr(10);
		$block .= "1,$carisch_id,200$state_emp_code,$tax_code".chr(13).chr(10);
		//$block .= "1,$carisch_id,2,0,,,,,$start_date".chr(13).chr(10);

		//return $block;
		$file = fopen($this->import_file,"a");
		fwrite($file,$block);
		fclose($file);
	}

	private function is_id_used($id) {
		$check = $this->db->query("SELECT `uniq_id` FROM `employees` WHERE `e_id` = '$id'");
		if ($check->num_rows() === 0) {
			return false;

		} else {
			return true;
		}
	}

	public function get_carisch_id($ssn,$store,$position,$override = 0) {
		$store = str_pad((int) $store,2,"0",STR_PAD_LEFT);
		$trail = substr($ssn,-3,3);
		if ($trail >= 980) {
			$trail = "979";
		}
		switch ($position) {
			case 4:
				$trail = "999";
				break;
			case 5:
				$trail = "989";
				break;
		}
		$ssn_query = $this->db->query("
		    SELECT 
		        `e_id`,
		        `position` 
		    FROM 
		        `employees` 
		    WHERE 
		        `ssn_hash` = '$ssn'
		");
		$used_ids = $this->current_ids($store);
		if ($ssn_query->num_rows() === 0) {
			$inverse = false;
			for (; ; ) {
				if ($trail < 0) {
					$trail *= -1;
					$inverse = true;
				}
				$trail = str_pad($trail,3,"0",STR_PAD_LEFT);
				$id = (string) $store . (string) $trail;
				if (!isset($used_ids[$id]) && $this->is_id_used($id) !== true) {
					break;
				} else {
					if ($inverse) {
						$trail *= -1;
					}
					$trail--;
				}
			}
		} elseif ($ssn_query->num_rows() === 1) {
			$result = $ssn_query->last_row();
			if ($override == 1) {
				$inverse = false;
				for (; ; ) {
					if ($trail < 0) {
						$trail *= -1;
						$inverse = true;
					}
					$trail = str_pad($trail,3,"0",STR_PAD_LEFT);
					$id = (string) $store . (string) $trail;
					if (!isset($used_ids[$id]) && $this->is_id_used($id) !== true) {
						break;
					} else {
						if ($inverse) {
							$trail *= -1;
						}
						$trail--;
					}
				}
					
			} else {
				$id = (string) "$result->e_id";
			}
		}
		return $id;
	}


	public function commit() {


		//$xml->addChild($);

		//@TODO Add missing fields to db and commit them, use a short lived xml for i9 storage (deleted nightly)

		$first_name 		= mysql_real_escape_string($this->ci->session->userdata('first_name'));
		if (strlen($first_name) < 2) {
			$failure[] = 'First Name';
		}
		$last_name 			= mysql_real_escape_string($this->ci->session->userdata('last_name'));
		if (strlen($last_name) < 2) {
			$failure[] = 'Last Name';
		}
		$middle_initial		= $this->ci->session->userdata('middle_initial');
		$ssn_hash 			= mysql_real_escape_string($this->ci->session->userdata('ssn'));
		if (strlen($ssn_hash) != 9) {
			$failure[] = 'SSN';
		}
		$passwd 			= md5(substr($ssn_hash,-4,4));
		$position 			= $this->ci->session->userdata('position');
		$store 				= $this->ci->session->userdata('csid');

		//@TODO


		$street_addr 		= mysql_real_escape_string($this->ci->session->userdata('street_address'));
		if (strlen($street_addr) < 5) {
			$failure[] = "Street Address";
		}
		$city		 		= mysql_real_escape_string($this->ci->session->userdata('city'));
		if (strlen($city) < 3) {
			$failure[] = "City";
		}
		$zip				= mysql_real_escape_string($this->ci->session->userdata('zip'));
		if (strlen($zip) != 5) {
			$failure[] = "Zip";
		}
		$carisch_dimes		= $this->ci->session->userdata('carisch_dimes');
		$state		 		= $this->ci->session->userdata('state');
		$phone		 		= mysql_real_escape_string($this->ci->session->userdata('phone'));
		$dob		 		= $this->ci->session->userdata('year').'-'.$this->ci->session->userdata('month').'-'.$this->ci->session->userdata('day');
		//$doh				= 'curdate?';
		//$start_date		= $this->ci->session->userdata('sdate');
		$start_date			= $this->ci->session->userdata('syear').'-'.$this->ci->session->userdata('smonth').'-'.$this->ci->session->userdata('sday');
		$gender				= $this->ci->session->userdata('gender');
		$ethnicity			= $this->ci->session->userdata('ethnic_code');
		$exemption			= $this->ci->session->userdata('exempt');
		$marital_status 	= $this->ci->session->userdata('marital_status');
		switch ($marital_status) {
			case "single":
				$marital_status = $this->Single;
				break;
			case "married":
				$marital_status = $this->Married;
				break;
			case "married_single":
				$marital_status = $this->MarriedSingle;
				break;
		}
		$allowances			= $this->ci->session->userdata('allowances');
		if (strlen($allowances) < 1) {
			$allowances 	= 0;
		}
		$withheld			= $this->ci->session->userdata('withheld');
		if (strlen($withheld) < 1) {
			$withheld 		= 0;
		}
		$rate				= $this->ci->session->userdata('payrate');
		$county				= mysql_real_escape_string($this->ci->session->userdata('county'));
		$pay_type = 1;
		if ($position == 6 || $position == 8) {
			$pay_type = 0;
		}

		$s_uid = $this->ci->session->userdata('uid');

		if (!isset($position)) {
			$position = 8;
		}
		if (!empty($failure)) {
			$fails[] = $failure;
			$this->ci->session->set_userdata('failure_array',$fails);
			return false;
		} else {
			$this->db->trans_start();
			$this->db->query("
			INSERT INTO `employees` (
			    `first_name`,
			    `last_name`,
			    `middle_initial`,
			    `ssn_hash`,
			    `passwd`,
			    `position`,
			    `store`,
			    `active`
			) VALUES (
			    '$first_name',
			    '$last_name',
			    '$middle_initial',
			    '$ssn_hash',
			    '$passwd',
			    '$position',
			    $store,
			    1
			)
		");
			    $uniq_id = $this->db->insert_id();
				$this->db->query("UPDATE `employees` SET `e_id` = '$uniq_id' WHERE `uniq_id` = $uniq_id");
			    $this->db->query("
		    INSERT INTO `employee_info`
		        (
		        `unique_id`,
		        `street_addr`,
		        `city`,
		        `zip`,
		        `state`,
		        `phone`,
		        `dob`,
		        `doh`,
		        `start_date`,
		        `exemption`,
		        `marital_status`,
		        `allowances`,
		        `additional_withholdings`,
		        `gender`,
		        `ethnicity`,
		        `submitter_id`,
		        `county`
		        )
		    VALUES
		        (
		        $uniq_id,
		        '$street_addr',
		        '$city',
		        '$zip',
		        '$state',
		        '$phone',
		        '$dob',
		        CURRENT_DATE(),
	            '$start_date',
	            '$exemption',
	            $marital_status,
	            $allowances,
	            $withheld,
	            '$gender',
	            $ethnicity,
	            $s_uid,
	            '$county'
		        )
		");

	            $this->db->query("
			INSERT INTO 
			    `employee_wage`
			    (
			    `unique_id`,
			    `rate`,
			    `dor`,
			    `pay_type`,
			    `approved_by`,
			    `carisch_dimes`
			    )
			VALUES
			    (
			    $uniq_id,
		        '$rate',
		        CURRENT_DATE(),
		        $pay_type,
		        $s_uid,
		        $carisch_dimes
		        )
		");

		        foreach ($this->required_docs as $doc) {
		        	$this->db->query("
				INSERT INTO 
				`employee_docs` (
					`uniq_id`,
					`document`,
					`scanned`,
					`status`,
					`date_set`
					) 
				VALUES (
				$uniq_id,
				$doc,
					0,
					0,
					CURRENT_DATE()
					)
			");
		        }
		        $this->db->trans_complete();
		        return $uniq_id;
		}

	}
}
?>
