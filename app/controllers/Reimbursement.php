<?php

class Reimbursement extends BaseController
{

    function getClient()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        require_once(app_path().'/includes/soapclient/SforcePartnerClient.php');
        require_once(app_path().'/includes/soapclient/SforceHeaderOptions.php');

        // Salesforce Login information
        $wsdl = app_path().'/includes/soapclient/partner.wsdl.xml';
        $userName = "binnyva@makeadiff.in";
        $password = "Live-pride-13" . "FQOcfhg0hm3fRIMz5USX7qcPQ";

        // Process of logging on and getting a salesforce.com session
        $client = new SforcePartnerClient();
        $client->createConnection($wsdl);
        $loginResult = $client->login($userName, $password);

        return $client;
    }


    public function showSuccess()
    {
        return View::make('success');
    }

    public function showError()
    {
        return View::make('error');
    }

	public function showHome()
	{
        $data = $this->getUserData();

        if ($data->error == true) {
            return View::make('home')->with('message',$data->message);
        }

        return View::make('home');
	}

    public function showTelephoneInternet()
    {
       return View::make('telephone-internet');
    }

    public function showTravel()
    {
        return View::make('travel');
    }

    public function showStatus()
    {
        $rows = $this->getStatus();

        $data = array(
            'rows' => $rows,
        );

        return View::make('status',$data);
    }

    public function getBankDetails()
    {
        $row_user = DB::connection('makeadiff_madapp')->select('SELECT id, name, email, city_id FROM User WHERE id = ?',array($_SESSION['user_id']));

        if (empty($row_user[0])) {

            return Redirect::to('error')->with('message','Something went wrong. Please try again after sometime. ("Technical" Error : Something is messing with the session data)');

        } else {

            $bank_name = DB::connection('makeadiff_madapp')->select('SELECT data FROM UserData WHERE user_id = ? AND name = ?',array($_SESSION['user_id'],'bank_name'));
            $bank_address = DB::connection('makeadiff_madapp')->select('SELECT data FROM UserData WHERE user_id = ? AND name = ?',array($_SESSION['user_id'],'bank_address'));
            $bank_account_number = DB::connection('makeadiff_madapp')->select('SELECT data FROM UserData WHERE user_id = ? AND name = ?',array($_SESSION['user_id'],'bank_account_number'));
            $bank_ifsc_code = DB::connection('makeadiff_madapp')->select('SELECT data FROM UserData WHERE user_id = ? AND name = ?',array($_SESSION['user_id'],'bank_ifsc_code'));
            $bank_account_type = DB::connection('makeadiff_madapp')->select('SELECT data FROM UserData WHERE user_id = ? AND name = ?',array($_SESSION['user_id'],'bank_account_type'));

            $data['error'] = false;

            if (empty($bank_name[0]->data) || empty($bank_address[0]->data) || empty($bank_account_number[0]->data) || empty($bank_ifsc_code[0]->data) || empty($bank_account_type[0]->data)) {

                $data['message'] = 'Your bank details have not been filled. Click <a href="http://makeadiff.in/madapp/index.php/user/edit_bank_details" target="_blank">here</a> to enter the details';
                $data['error'] = true;
                return $data;
            } else{

                $data = compact("bank_name", "bank_address", "bank_account_number", "bank_ifsc_code", "bank_account_type");
                $data['error'] = false;
                return $data;

            }
        }


    }

    public function getUserData()
    {
        $row_user = DB::connection('makeadiff_madapp')->select('SELECT id, name, email, city_id FROM User WHERE id = ?',array($_SESSION['user_id']));

        $data['error'] = false;

        if (!empty($row_user[0])) {
            $user = $row_user[0];
            $row_city = DB::connection('makeadiff_madapp')->select('SELECT id, name FROM City WHERE id = ?', array($user->city_id));
            $row_vertical = DB::connection('makeadiff_madapp')->select('SELECT `Group`.type, Vertical.name as vertical_name FROM Vertical
                                                                            INNER JOIN `Group` ON `Group`.vertical_id = Vertical.id
                                                                            INNER JOIN UserGroup ON UserGroup.group_id = `Group`.id
                                                                            INNER JOIN `User` ON `User`.id = UserGroup.user_id
                                                                            WHERE `User`.id = ?',array($user->id));
            if(empty($row_vertical[0])) {
                $data = new stdClass();
                $data->message = 'You have not been assigned a vertical. Please contact your HR to ensure that your vertical in MADApp has been marked correctly.';
                $data->error = true;
                return $data;
            }

            $user->vertical = "";
            foreach ($row_vertical as $vertical) {
                if ($vertical->type == 'national') {
                    $user->vertical = "National";
                    break;
                }

                if ($vertical->type == 'fellow' || $vertical->type == 'strat') {
                    $user->vertical = $vertical->vertical_name;
                }
            }
            $user->city_name = $row_city[0]->name;

            $bank_details = $this->getBankDetails();

            if($bank_details['error'] == true) {
                $data = new stdClass();
                $data->error = true;
                $data->message = $bank_details['message'];
                return $data;
            }

            $user->bank_name = $bank_details['bank_name'][0]->data;
            $user->bank_address = $bank_details['bank_address'][0]->data;
            $user->bank_account_number = $bank_details['bank_account_number'][0]->data;
            $user->bank_ifsc_code = $bank_details['bank_ifsc_code'][0]->data;
            $user->bank_account_type = $bank_details['bank_account_type'][0]->data;
            $user->error = false;

            return $user;

        } else {
            $data = new stdClass();
            $data->message = 'Something went wrong. Please try again after sometime. (Technical Error : Something is messing with the session data. Try logging out and logging in again.)';
            $data->error = true;
            return $data;
        }


    }

    function sortRequests($a,$b)
    {
        if($a->Id < $b->Id) return -1;
        if($a->Id > $b->Id) return 1;
        return 0;
    }

    function getStatus()
    {
        $client = $this->getClient();
        $query = "SELECT Id,Name,Requester_ID__c,Type__c,Status__c,Telephone_Internet_Amount__c,Travel_Total_Amount__c,Telephone_Internet_Year__c,Telephone_Internet_Month__c FROM Reimbursement__c";
        $result = $client->query($query);

        $i=0;

        $row = array();

        foreach($result as $record) {
            if($record->fields->Requester_ID__c == $_SESSION['user_id']) {
                $row[$i] = new stdClass();
                $row[$i]->Id = $record->fields->Name;
                $row[$i]->Type = $record->fields->Type__c;
                $row[$i]->Status = $record->fields->Status__c;
                $row[$i]->Telephone_Internet_Amount = $record->fields->Telephone_Internet_Amount__c;
                $row[$i]->Telephone_Internet_Month = $record->fields->Telephone_Internet_Month__c;
                $row[$i]->Telephone_Internet_Year = $record->fields->Telephone_Internet_Year__c;
                $row[$i]->Travel_Amount = $record->fields->Travel_Total_Amount__c;
                $i++;
            }
        }

        uasort($row,array($this,'sortRequests'));

        return $row;

    }



    function submitTelephoneInternet()
    {

        $rules = array(
            'amount' => 'min:1|required|numeric',
        );

        $validator = Validator::make(Input::all(),$rules);

        if ($validator->fails()) {
            return Redirect::to('telephone-internet')->withErrors($validator);
        }

        $amount = Input::get('amount');

        if(empty($amount)) {
            $amount = 0;
        }

        $user = $this->getUserData();

        if ($user->error == true) {
            return Redirect::to('error')->with('message',$user->message);
        }

        $client = $this->getClient();

        $query = "SELECT Id,Name,Requester_ID__c,Type__c,Status__c,Telephone_Internet_Month__c,Telephone_Internet_Year__c FROM Reimbursement__c";
        $result = $client->query($query);

        foreach ($result as $record) {
            if($record->fields->Requester_ID__c == $user->id && ($record->fields->Status__c == 'Created' || $record->fields->Status__c == 'Approved') &&
                $record->fields->Type__c == 'Telephone/Internet' && $record->fields->Telephone_Internet_Month__c == Input::get('monthSelect') && 
                $record->fields->Telephone_Internet_Year__c == Input::get('yearSelect')) {

                return Redirect::to('error')->with('message','You have already submitted a telephone/internet reimbursement request for ' . Input::get('monthSelect') . "," . Input::get('yearSelect'));
            }
        }

        $sObject = new stdClass();
        // if($user->vertical=="Shelter Operations") $user->vertical = "Center Head";
        // if($user->vertical=="Human Capital") $user->vertical = "Human Resources";
        // if($user->vertical=="Transition Readiness") $user->vertical = "Propel";
        $sObject->fields = array(
            'Type__c' => 'Telephone/Internet',
            'Telephone_Internet_Month__c' => Input::get('monthSelect'),
            'Telephone_Internet_Year__c' => Input::get('yearSelect'),
            'Telephone_Internet_Amount__c' => $amount,
            'Telephone_Internet_Comment__c' => htmlspecialchars(Input::get('comments')),
            'Requester_ID__c' => $user->id,
            'Requester_Name__c' => $user->name,
            'Requester_Email__c' => $user->email,
            'Requester_City__c' => $user->city_name,
            'Requester_Vertical__c' => $user->vertical,
            'Bank_Name__c' => htmlspecialchars($user->bank_name),
            'Bank_Address__c' => htmlspecialchars($user->bank_address),
            'Bene_Account_Number__c' => $user->bank_account_number,
            'Receiver_IFSC__c' => $user->bank_ifsc_code,
            //'Receiver_A_c_type__c' => $user->bank_account_type,
        );
        $sObject->type = 'Reimbursement__c';


        $response_r = $client->create(array($sObject),'Reimbursement');

        if($response_r[0]->success == true){

            if (Input::hasFile('bills')) {
                $bill = Input::file('bills');
                $extension = $bill->getClientOriginalExtension();
                $path = $bill->getRealPath();

                $base64_bill = chunk_split(base64_encode(file_get_contents($path)));

                $sObjectA = new stdClass();
                $sObjectA->fields = array(
                    'Name' => 'Bill.' . $extension,
                    'ParentId' => $response_r[0]->id,
                    'Body' => $base64_bill
                );
                $sObjectA->type = 'Attachment';
                $response_a = $client->create(array($sObjectA),'Attachment');

            }

            $reimbursement_id[0] = $response_r[0]->id;

            $result = $client->retrieve('Id,Name','Reimbursement__c',$reimbursement_id);

            return Redirect::to('success')->with('id',$result[0]->Name)->with('email',$user->email);

        } else {
            return Redirect::to('error')->with('message',"Error log : <br><pre>" . print_r($sObject->fields,true) . print_r($response_r,true) . "</pre>");
        }
    }

    function submitTravel()
    {
        $rules = array(
            'reason' => 'required',
            'daily_bhatta' => 'required|numeric|min:1',
        );

        $validator = Validator::make(Input::all(),$rules);

        if ($validator->fails()) {
            return Redirect::to('travel')->withErrors($validator);
        }


        $user = $this->getUserData();

        if ($user->error == true) {
            return Redirect::to('error')->with('message',$user->message);
        }

        $client = $this->getClient();

        $sObject = new stdClass();

        //To check if amount field is present but blank. If so return 0
        $letters = range('a','e');

        $sObject->fields = array();

        foreach($letters as $letter){
            $amount_form = 'amount_' . $letter;
            $amount[$letter] = Input::get($amount_form);
            $amount[$letter] = (!empty($amount[$letter])) ? $amount[$letter] : 0;

            $date_form = 'pickdate_' . $letter;
            $date_sf = 'Travel_' . strtoupper($letter) . '_Date__c';
            $date[$letter] = Input::get($date_form);
            if (!empty($date[$letter])) {
                $sObject->fields[$date_sf] = date_format(date_create(Input::get($date_form)),'Y-m-d');
            }

        }

        $sObject->fields['Requester_ID__c'] = $user->id;
        $sObject->fields['Requester_Name__c'] = $user->name;
        $sObject->fields['Requester_Email__c'] = $user->email;
        $sObject->fields['Requester_City__c'] = $user->city_name;
        $sObject->fields['Requester_Vertical__c'] = $user->vertical;


        $sObject->fields['Type__c'] = 'Travel';
        $sObject->fields['Reason_for_Travel__c'] = Input::get('reason');
        $sObject->fields['Total_Daily_Bha__c'] = Input::get('daily_bhatta');
        $sObject->fields['Travel_Comment__c'] = Input::get('comments');
        $sObject->fields['Travel_A_From__c'] = Input::get('travel_from_a');
        $sObject->fields['Travel_A_To__c'] = Input::get('travel_to_a');
        $sObject->fields['Travel_A_Mode__c'] = Input::get('modeSelect_a');
        $sObject->fields['Travel_A_Amount__c'] = $amount['a'];
        $sObject->fields['Travel_B_From__c'] = Input::get('travel_from_b');
        $sObject->fields['Travel_B_To__c'] = Input::get('travel_to_b');
        $sObject->fields['Travel_B_Mode__c'] = Input::get('modeSelect_b');
        $sObject->fields['Travel_B_Amount__c'] = $amount['b'];
        $sObject->fields['Travel_C_From__c'] = Input::get('travel_from_c');
        $sObject->fields['Travel_C_To__c'] = Input::get('travel_to_c');
        $sObject->fields['Travel_C_Mode__c'] = Input::get('modeSelect_c');
        $sObject->fields['Travel_C_Amount__c'] = $amount['c'];
        $sObject->fields['Travel_D_From__c'] = Input::get('travel_from_d');
        $sObject->fields['Travel_D_To__c'] = Input::get('travel_to_d');
        $sObject->fields['Travel_D_Mode__c'] = Input::get('modeSelect_d');
        $sObject->fields['Travel_D_Amount__c'] = $amount['d'];
        $sObject->fields['Travel_E_From__c'] = Input::get('travel_from_e');
        $sObject->fields['Travel_E_To__c'] = Input::get('travel_to_e');
        $sObject->fields['Travel_E_Mode__c'] = Input::get('modeSelect_e');
        $sObject->fields['Travel_E_Amount__c'] = $amount['e'];


        $sObject->type = 'Reimbursement__c';

        $response_r = $client->create(array($sObject),'Reimbursement');

        if($response_r[0]->success == true){

            $letters = range('a','e');
            $file = 'bills_';

            foreach ($letters as $letter) {
                $bill = $file . $letter;
                if (Input::hasFile($bill)) {
                    $bill = Input::file($bill);
                    $extension = $bill->getClientOriginalExtension();
                    $path = $bill->getRealPath();

                    $base64_bill = chunk_split(base64_encode(file_get_contents($path)));

                    $sObjectA = new stdClass();
                    $sObjectA->fields = array(
                        'Name' => 'Bill for Travel ' . $letter . '.' . $extension,
                        'ParentId' => $response_r[0]->id,
                        'Body' => $base64_bill
                    );
                    $sObjectA->type = 'Attachment';
                    $response_a = $client->create(array($sObjectA),'Attachment');

                }
            }

            $reimbursement_id[0] = $response_r[0]->id;

            $result = $client->retrieve('Id,Name','Reimbursement__c',$reimbursement_id);

            return Redirect::to('success')->with('id',$result[0]->Name)->with('email',$user->email);

        } else {
            return Redirect::to('error')-with('message','Something went wrong. Please try again after sometime.');
        }
    }

}
