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
        $password = "tracker101"."2pCRukHlm47CKXCmUWpDuLIKv";

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

    public function getUserData()
    {
        $row_user = DB::connection('makeadiff_madapp')->select('SELECT id, name, email, city_id FROM User WHERE id = ?',array($_SESSION['user_id']));


        if (!empty($row_user[0])) {
            $user = $row_user[0];
            $row_city = DB::connection('makeadiff_madapp')->select('SELECT id, name FROM City WHERE id = ?', array($user->city_id));
            $row_vertical = DB::connection('makeadiff_madapp')->select('SELECT `Group`.type, Vertical.name as vertical_name FROM Vertical
                                                                            INNER JOIN `Group`
                                                                            ON `Group`.vertical_id = Vertical.id
                                                                            INNER JOIN UserGroup
                                                                            ON UserGroup.group_id = `Group`.id
                                                                            INNER JOIN `User`
                                                                            ON `User`.id = UserGroup.user_id
                                                                            WHERE `User`.id = ? AND Group.type <> ?',array($user->id,'volunteer'));

            if(empty($row_vertical[0])) {
                return Redirect::to('error')->with('message','You have not been assigned a vertical. Please contact your HR to ensure that your vertical in MADApp has been marked correctly.');
            }

            foreach ($row_vertical as $vertical) {
                if ($vertical->type == 'national') {
                    $user->vertical = "National";
                    break;
                }

                if ($vertical->type == 'fellow') {
                    $user->vertical = $vertical->vertical_name;

                }
            }
            $user->city_name = $row_city[0]->name;

            return $user;
        } else {
            return false;
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
        $query = "SELECT Id,Name,Requester_ID__c,Type__c,Status__c,Telephone_Internet_Amount__c,Travel_Total_Amount__c FROM Reimbursement__c";
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

        $client = $this->getClient();

        $sObject = new stdClass();
        $sObject->fields = array(
            'Type__c' => 'Telephone/Internet',
            'Telephone_Internet_Month__c' => Input::get('monthSelect'),
            'Telephone_Internet_Amount__c' => $amount,
            'Telephone_Internet_Comment__c' => Input::get('comments'),
            'Requester_ID__c' => $user->id,
            'Requester_Name__c' => $user->name,
            'Requester_Email__c' => $user->email,
            'Requester_City__c' => $user->city_name,
            'Requester_Vertical__c' => $user->vertical,


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
            return Redirect::to('error')->with('message','Something went wrong. Please try again after sometime.');
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
