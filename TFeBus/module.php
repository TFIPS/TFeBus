<?php
class TFeBus extends IPSModule{
    
    public function Create(){
        parent::Create();
        //$this->RegisterPropertyString("deviceTopic", "");
        $this->ConnectParent("{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}");
		if (!IPS_VariableProfileExists('TFEB.deviceState')) {
			IPS_CreateVariableProfile('TFEB.deviceState', 1);
			IPS_SetVariableProfileIcon ('TFEB.deviceState', 'Network');
			IPS_SetVariableProfileAssociation('TFEB.deviceState', 0, 'Blockiert', 'Warning', 0xFF0000);
			IPS_SetVariableProfileAssociation('TFEB.deviceState', 1, 'Offline', 'Power', 0xFF8800);
			IPS_SetVariableProfileAssociation('TFEB.deviceState', 2, 'Verbunden', 'Plug', 0xFFFF00);
			IPS_SetVariableProfileAssociation('TFEB.deviceState', 3, 'Wartet', 'Sleep', 0xFFFF00);
			IPS_SetVariableProfileAssociation('TFEB.deviceState', 4, 'Aktiv', 'Repeat', 0x00FF00);
		}
        if (!IPS_VariableProfileExists('TFEB.stateBM')) {
            IPS_CreateVariableProfile('TFEB.stateBM', 1);
			IPS_SetVariableProfileIcon ('TFEB.stateBM', 'Execute');
            IPS_SetVariableProfileAssociation('TFEB.stateBM', 0, 'Service', 'Gear', -1);
			IPS_SetVariableProfileAssociation('TFEB.stateBM', 1, 'Standby-Betrieb', 'Power', -1);
			IPS_SetVariableProfileAssociation('TFEB.stateBM', 2, 'Automatikbetrieb', 'Clock', -1);
			IPS_SetVariableProfileAssociation('TFEB.stateBM', 3, 'Heizbetrieb', 'Radiator', -1);
			IPS_SetVariableProfileAssociation('TFEB.stateBM', 4, 'Absenkbetrieb', 'Moon', -1);
			IPS_SetVariableProfileAssociation('TFEB.stateBM', 5, 'Sommerbetrieb', 'Sun', -1);
			IPS_SetVariableProfileAssociation('TFEB.stateBM', 14, 'Handbetrieb', 'Execute', -1);
			IPS_SetVariableProfileAssociation('TFEB.stateBM', 99, 'Warte auf Status', 'Hourglass', -1);
        }
		if (!IPS_VariableProfileExists('TFEB.timePrg')) {
            IPS_CreateVariableProfile('TFEB.timePrg', 1);
			IPS_SetVariableProfileIcon ('TFEB.timePrg', 'Clock');
            IPS_SetVariableProfileAssociation('TFEB.timePrg', 0, 'Zeitprogramm 1', 'Database', -1);
			IPS_SetVariableProfileAssociation('TFEB.timePrg', 1, 'Zeitprogramm 2', 'Database', -1);
			IPS_SetVariableProfileAssociation('TFEB.timePrg', 2, 'Zeitprogramm 3', 'Database', -1);
			IPS_SetVariableProfileAssociation('TFEB.timePrg', 99, 'Warte auf Status', 'Hourglass', -1);
		}
		if (!IPS_VariableProfileExists('TFEB.heating')) {
            IPS_CreateVariableProfile('TFEB.heating', 1);
            IPS_SetVariableProfileAssociation('TFEB.heating', 0, 'Brenner ausschalten', 'Power', -1);
			IPS_SetVariableProfileAssociation('TFEB.heating', 1, 'Keine Aktion', 'Power', -1);
			IPS_SetVariableProfileAssociation('TFEB.heating', 2, 'Brauchwasserbereitung', 'Shower', -1);
			IPS_SetVariableProfileAssociation('TFEB.heating', 3, 'Heizbetrieb', 'Sun', -1);
			IPS_SetVariableProfileAssociation('TFEB.heating', 4, 'Emissionskontrolle', 'Gear', -1);
			IPS_SetVariableProfileAssociation('TFEB.heating', 5, 'TÜV-Funktion', 'Gear', -1);
			IPS_SetVariableProfileAssociation('TFEB.heating', 6, 'Reglerstop-Funktion', 'Cross', -1);
			IPS_SetVariableProfileAssociation('TFEB.heating', 7, 'Brauchwasserbereitung bei Reglerstop', '', -1);
			IPS_SetVariableProfileAssociation('TFEB.heating', 8, 'Brauchwasserbereitung bei Heizbetrieb', 'Bath', -1);
			IPS_SetVariableProfileAssociation('TFEB.heating', 9, 'Reglerstop-Funktion bei stufigem Betrieb', '', -1);
		}
		
		if (!IPS_VariableProfileExists('TFEB.burner')) {
            IPS_CreateVariableProfile('TFEB.burner', 0);
			IPS_SetVariableProfileIcon ('TFEB.burner', 'Flame');
            IPS_SetVariableProfileAssociation('TFEB.burner', false, 'Aus', 'Power', -1);
			IPS_SetVariableProfileAssociation('TFEB.burner', true, 'An', 'Flame', -1);
		}
		
		if (!IPS_VariableProfileExists('TFEB.uwp')) {
            IPS_CreateVariableProfile('TFEB.uwp', 0);
			IPS_SetVariableProfileIcon ('TFEB.uwp', 'TurnRight');
            IPS_SetVariableProfileAssociation('TFEB.uwp', false, 'Aus', 'Power', -1);
			IPS_SetVariableProfileAssociation('TFEB.uwp', true, 'An', 'TurnRight', -1);
		}			
		
		if (!IPS_VariableProfileExists('TFEB.customer')) {
            IPS_CreateVariableProfile('TFEB.customer', 1);
            IPS_SetVariableProfileAssociation('TFEB.customer', 0, 'Keine Aktion', 'Power', -1);
			IPS_SetVariableProfileAssociation('TFEB.customer', 1, 'Ausschalten Kesselpumpe', '', -1);
			IPS_SetVariableProfileAssociation('TFEB.customer', 2, 'Einschalten Kesselpumpe', '', -1);
			IPS_SetVariableProfileAssociation('TFEB.customer', 3, 'Ausschalten variabler Verbraucher', '', -1);
			IPS_SetVariableProfileAssociation('TFEB.customer', 4, 'Einschalten variabler Verbraucher', '', -1);
		}		
    }
    
    public function ApplyChanges(){
        parent::ApplyChanges();
		$deviceState_ID	= $this->RegisterVariableInteger("deviceState", "Status", "TFEB.deviceState", 95);
		$uptime_ID		= $this->RegisterVariableString("uptime", "Uptime", "", 96);
		$fVersion_ID	= $this->RegisterVariableString("fVersion", "Version", "", 97);
		$ipAddress_ID	= $this->RegisterVariableString("ipAddress", "IP-Adresse", "", 98);
		$wlanSignal_ID	= $this->RegisterVariableInteger("wlanSignal", "WLAN-Signal", "~Intensity.100", 99);
		// State default
		SetValueInteger($deviceState_ID, 1);

		// 5022
		$stateBM_ID		= $this->RegisterVariableInteger("stateBM", "Betriebsart", "TFEB.stateBM", 11);
		$tempSW_ID		= $this->RegisterVariableFloat("tempSW", "Sommer- / Winterumschaltung", "~Temperature", 12);
		$tempDay_ID		= $this->RegisterVariableFloat("tempDay", "Tagtemperatur", "~Temperature", 13);
		$tempEco_ID		= $this->RegisterVariableFloat("tempEco", "Spartemperatur", "~Temperature", 14);
		$tempRoom_ID	= $this->RegisterVariableFloat("tempRoom", "Raumtemperatur", "~Temperature", 15);
		$tempKS_ID		= $this->RegisterVariableFloat("tempKS", "Kesseltemperatur Soll", "~Temperature", 16);
		$tempKI_ID		= $this->RegisterVariableFloat("tempKI", "Kesseltemperatur Ist", "~Temperature", 17);
		$tempWW_ID		= $this->RegisterVariableFloat("tempWW", "Eingestellte Warmwassertemperatur", "~Temperature", 18);
		$tempWWS_ID		= $this->RegisterVariableFloat("tempWWS", "Warmwassertemperatur Soll", "~Temperature", 19);
		$tempWWI_ID		= $this->RegisterVariableFloat("tempWWI", "Warmwassertemperatur Ist", "~Temperature", 20);
		$curTimePrg_ID	= $this->RegisterVariableInteger("curTimePrg", "Zeitprogramm", "TFEB.timePrg", 21);
		$burnerH_ID		= $this->RegisterVariableInteger("burnerH", "Brennerstunden", "Time", 27);
		$burnerS_ID		= $this->RegisterVariableInteger("burnerS", "Brennerstarts", "", 28);
		$onH_ID			= $this->RegisterVariableInteger("onH", "Betriebsstunden", "Time", 29);
		$flame_ID		= $this->RegisterVariableBoolean("flame", "Flamme", "TFEB.burner", 30);
		$uwp_ID			= $this->RegisterVariableBoolean("uwp", "Umwälzpumpe", "TFEB.uwp", 31);
		$tempA_ID		= $this->RegisterVariableFloat("tempA", "Außentemperatur", "~Temperature", 32);
		
		// 0700
		$time_ID		= $this->RegisterVariableString("time", "Uhrzeit", "", 33);
		$weekday_ID		= $this->RegisterVariableInteger("weekday", "Wochentag", "Weekday", 34);
		
		// 0507
		$heating_ID		= $this->RegisterVariableInteger("heating", "Heizungsstatus", "TFEB.heating", 35);
		$customer_ID	= $this->RegisterVariableInteger("customer", "Verbraucherstatus", "TFEB.customer", 36);
		
		// Wochenpläne
		if(!$hTimePrg1 = @IPS_GetObjectIDByIdent("hTimePrg1", $this->InstanceID))
		{
			$hTimePrg1	= IPS_CreateEvent(2);
			IPS_SetParent($hTimePrg1, $this->InstanceID);
			IPS_SetIdent($hTimePrg1, "hTimePrg1");
			IPS_SetName($hTimePrg1, "H-Zeitprogramm 1");
			IPS_SetPosition($hTimePrg1, 21);
			IPS_SetEventScheduleGroup($hTimePrg1, 0, 31);
			IPS_SetEventScheduleGroup($hTimePrg1, 1, 96);
			IPS_SetEventScheduleAction($hTimePrg1, 0, "Kalt", 0x0000FF, "return true;");
			IPS_SetEventScheduleAction($hTimePrg1, 1, "Warm", 0xFF0000, "return true;");
		}
		/*
		if(!$hTimePrg2 = @IPS_GetObjectIDByIdent("hTimePrg2", $this->InstanceID))
		{
			$hTimePrg2	= IPS_CreateEvent(2);
			IPS_SetParent($hTimePrg2, $this->InstanceID);
			IPS_SetIdent($hTimePrg2, "hTimePrg2");
			IPS_SetName($hTimePrg2, "H-Zeitprogramm 2");
			IPS_SetPosition($hTimePrg2, 22);
			IPS_SetEventScheduleGroup($hTimePrg2, 0, 31);
			IPS_SetEventScheduleGroup($hTimePrg2, 1, 96);
			IPS_SetEventScheduleAction($hTimePrg2, 0, "Kalt", 0x0000FF, "return true;");
			IPS_SetEventScheduleAction($hTimePrg2, 1, "Warm", 0xFF0000, "return true;");
		}
		*/
		if(!$hTimePrg3 = @IPS_GetObjectIDByIdent("hTimePrg3", $this->InstanceID))
		{
			$hTimePrg3	= IPS_CreateEvent(2);
			IPS_SetParent($hTimePrg3, $this->InstanceID);
			IPS_SetIdent($hTimePrg3, "hTimePrg3");
			IPS_SetName($hTimePrg3, "H-Zeitprogramm 3");
			IPS_SetPosition($hTimePrg3, 23);
			IPS_SetEventScheduleGroup($hTimePrg3, 0, 1);
			IPS_SetEventScheduleGroup($hTimePrg3, 1, 2);
			IPS_SetEventScheduleGroup($hTimePrg3, 2, 4);
			IPS_SetEventScheduleGroup($hTimePrg3, 3, 8);
			IPS_SetEventScheduleGroup($hTimePrg3, 4, 16);
			IPS_SetEventScheduleGroup($hTimePrg3, 5, 32);
			IPS_SetEventScheduleGroup($hTimePrg3, 6, 64);
			IPS_SetEventScheduleAction($hTimePrg3, 0, "Kalt", 0x0000FF, "return true;");
			IPS_SetEventScheduleAction($hTimePrg3, 1, "Warm", 0xFF0000, "return true;");
		}
		if(!$wTimePrg1 = @IPS_GetObjectIDByIdent("wTimePrg1", $this->InstanceID))
		{
			$wTimePrg1	= IPS_CreateEvent(2);
			IPS_SetParent($wTimePrg1, $this->InstanceID);
			IPS_SetIdent($wTimePrg1, "wTimePrg1");
			IPS_SetName($wTimePrg1, "W-Zeitprogramm 1");
			IPS_SetPosition($wTimePrg1, 24);
			IPS_SetEventScheduleGroup($wTimePrg1, 0, 31);
			IPS_SetEventScheduleGroup($wTimePrg1, 1, 96);
			IPS_SetEventScheduleAction($wTimePrg1, 0, "Kalt", 0x0000FF, "return true;");
			IPS_SetEventScheduleAction($wTimePrg1, 1, "Warm", 0xFF0000, "return true;");
		}
		/*
		if(!$wTimePrg2 = @IPS_GetObjectIDByIdent("wTimePrg2", $this->InstanceID))
		{
			$wTimePrg2	= IPS_CreateEvent(2);
			IPS_SetParent($wTimePrg2, $this->InstanceID);
			IPS_SetIdent($wTimePrg2, "wTimePrg2");
			IPS_SetName($wTimePrg2, "W-Zeitprogramm 2");
			IPS_SetPosition($wTimePrg2, 25);
			IPS_SetEventScheduleGroup($wTimePrg2, 0, 31);
			IPS_SetEventScheduleGroup($wTimePrg2, 1, 96);
			IPS_SetEventScheduleAction($wTimePrg2, 0, "Kalt", 0x0000FF, "return true;");
			IPS_SetEventScheduleAction($wTimePrg2, 1, "Warm", 0xFF0000, "return true;");
		}
		*/
		if(!$wTimePrg3 = @IPS_GetObjectIDByIdent("wTimePrg3", $this->InstanceID))
		{
			$wTimePrg3	= IPS_CreateEvent(2);
			IPS_SetParent($wTimePrg3, $this->InstanceID);
			IPS_SetIdent($wTimePrg3, "wTimePrg3");
			IPS_SetName($wTimePrg3, "W-Zeitprogramm 3");
			IPS_SetPosition($wTimePrg3, 26);
			IPS_SetEventScheduleGroup($wTimePrg3, 0, 1);
			IPS_SetEventScheduleGroup($wTimePrg3, 1, 2);
			IPS_SetEventScheduleGroup($wTimePrg3, 2, 4);
			IPS_SetEventScheduleGroup($wTimePrg3, 3, 8);
			IPS_SetEventScheduleGroup($wTimePrg3, 4, 16);
			IPS_SetEventScheduleGroup($wTimePrg3, 5, 32);
			IPS_SetEventScheduleGroup($wTimePrg3, 6, 64);
			IPS_SetEventScheduleAction($wTimePrg3, 0, "Kalt", 0x0000FF, "return true;");
			IPS_SetEventScheduleAction($wTimePrg3, 1, "Warm", 0xFF0000, "return true;");
		}
		
		IPS_SetIcon($burnerS_ID, "Graph");
		IPS_SetIcon($time_ID, "Clock");
		IPS_SetIcon($weekday_ID, "Calendar");
		
		$this->SetValue("stateBM", 2); // Beim (Neu)Start immer auf Automatik stellen !
		
		$this->EnableAction("stateBM");
		//$this->EnableAction("curTimePrg");
		
		$this->RegisterMessage($hTimePrg1, EM_UPDATE);
		//$this->RegisterMessage($hTimePrg2, EM_UPDATE);
		$this->RegisterMessage($hTimePrg3, EM_UPDATE);
		
		$this->RegisterMessage($wTimePrg1, EM_UPDATE);
		//$this->RegisterMessage($wTimePrg2, EM_UPDATE);
		$this->RegisterMessage($wTimePrg3, EM_UPDATE);
    }
	
	public function MessageSink($time, $sender, $message, $data) 
	{
		//IPS_LogMessage("MessageSink", "JETZT!");
		$hTimePrg1ID = IPS_GetObjectIDByIdent("hTimePrg1", $this->InstanceID);
		//$hTimePrg2ID = IPS_GetObjectIDByIdent("hTimePrg2", $this->InstanceID);
		$hTimePrg3ID = IPS_GetObjectIDByIdent("hTimePrg3", $this->InstanceID);
		
		$wTimePrg1ID = IPS_GetObjectIDByIdent("wTimePrg1", $this->InstanceID);
		//$wTimePrg2ID = IPS_GetObjectIDByIdent("wTimePrg2", $this->InstanceID);
		$wTimePrg3ID = IPS_GetObjectIDByIdent("wTimePrg3", $this->InstanceID);
		
		//IPS_LogMessage("MessageSink", (int) $this->GetBuffer("lastUpdate_hTimePrg1") );
		/*
		if($message == EM_UPDATE && $this->GetBuffer("lockUpdateEvent") != 1)
		{
			switch($sender)
			{
				case $hTimePrg1ID : 
					if(time() - (int) $this->GetBuffer("lastUpdate_hTimePrg1") > 5)
					{
						$this->SetBuffer("lastUpdate_hTimePrg1", time());
						$this->writeTimePrg(10);
					}
				break;
				case $hTimePrg2ID : 
					if(time() - (int) $this->GetBuffer("lastUpdate_hTimePrg2") > 5)
					{
						$this->SetBuffer("lastUpdate_hTimePrg2", time());
						$this->writeTimePrg(11);
					}
				break;
				case $hTimePrg3ID : 
					if(time() - (int) $this->GetBuffer("lastUpdate_hTimePrg3") > 5)
					{
						$this->SetBuffer("lastUpdate_hTimePrg3", time());
						$this->writeTimePrg(12);
					}
				break;
				case $wTimePrg1ID : 
					if(time() - (int) $this->GetBuffer("lastUpdate_wTimePrg1") > 5)
					{
						$this->SetBuffer("lastUpdate_wTimePrg1", time());
						$this->writeTimePrg(20);
					}
				break;
				case $wTimePrg2ID : 
					if(time() - (int) $this->GetBuffer("lastUpdate_wTimePrg2") > 5)
					{
						$this->SetBuffer("lastUpdate_wTimePrg2", time());
						$this->writeTimePrg(21);
					}
				break;
				case $wTimePrg3ID : 
					if(time() - (int) $this->GetBuffer("lastUpdate_wTimePrg3") > 5)
					{
						$this->SetBuffer("lastUpdate_wTimePrg3", time());
						$this->writeTimePrg(20);
					}
				break;
			}
		}
		*/
	}
	
	
	public function Test(){
		//$this->SendCMD("setState", "standby");
	}

	public function LoadP(string $program){
		switch($program)
		{
			case 'H1': // Heizprogramm 1
				$this->SendCMD("getP_1_1_1", 0);
				$this->SendCMD("getP_1_1_2", 0);
				$this->SendCMD("getP_1_1_3", 0);
				$this->SendCMD("getP_1_2_1", 0);
				$this->SendCMD("getP_1_2_2", 0);
				$this->SendCMD("getP_1_2_3", 0);
			break;
			case 'H2': // Heizprogramm 2
				$this->SendCMD("getP_2_1_1", 0);
				$this->SendCMD("getP_2_1_2", 0);
				$this->SendCMD("getP_2_1_3", 0);
				$this->SendCMD("getP_2_2_1", 0);
				$this->SendCMD("getP_2_2_2", 0);
				$this->SendCMD("getP_2_2_3", 0);
			break;
			case 'H3': // Heizprogramm 3
				$this->SendCMD("getP_3_1_1", 0);
				$this->SendCMD("getP_3_1_2", 0);
				$this->SendCMD("getP_3_1_3", 0);
				$this->SendCMD("getP_3_2_1", 0);
				$this->SendCMD("getP_3_2_2", 0);
				$this->SendCMD("getP_3_2_3", 0);
				$this->SendCMD("getP_3_3_1", 0);
				$this->SendCMD("getP_3_3_2", 0);
				$this->SendCMD("getP_3_3_3", 0);
				$this->SendCMD("getP_3_4_1", 0);
				$this->SendCMD("getP_3_4_2", 0);
				$this->SendCMD("getP_3_4_3", 0);
				$this->SendCMD("getP_3_5_1", 0);
				$this->SendCMD("getP_3_5_2", 0);
				$this->SendCMD("getP_3_5_3", 0);
				$this->SendCMD("getP_3_6_1", 0);
				$this->SendCMD("getP_3_6_2", 0);
				$this->SendCMD("getP_3_6_3", 0);
				$this->SendCMD("getP_3_7_1", 0);
				$this->SendCMD("getP_3_7_2", 0);
				$this->SendCMD("getP_3_7_3", 0);
			break;
			case 'W1': // Wasserprogramm 1
				$this->SendCMD("getP_4_1_1", 0);
				$this->SendCMD("getP_4_1_2", 0);
				$this->SendCMD("getP_4_1_3", 0);
				$this->SendCMD("getP_4_2_1", 0);
				$this->SendCMD("getP_4_2_2", 0);
				$this->SendCMD("getP_4_2_3", 0);
			break;
			case 'W2': // Wasserprogramm 2
				$this->SendCMD("getP_5_1_1", 0);
				$this->SendCMD("getP_5_1_2", 0);
				$this->SendCMD("getP_5_1_3", 0);
				$this->SendCMD("getP_5_2_1", 0);
				$this->SendCMD("getP_5_2_2", 0);
				$this->SendCMD("getP_5_2_3", 0);
			break;
			case 'W3': // Wasserprogramm 3
				$this->SendCMD("getP_6_1_1", 0);
				$this->SendCMD("getP_6_1_2", 0);
				$this->SendCMD("getP_6_1_3", 0);
				$this->SendCMD("getP_6_2_1", 0);
				$this->SendCMD("getP_6_2_2", 0);
				$this->SendCMD("getP_6_2_3", 0);
				$this->SendCMD("getP_6_3_1", 0);
				$this->SendCMD("getP_6_3_2", 0);
				$this->SendCMD("getP_6_3_3", 0);
				$this->SendCMD("getP_6_4_1", 0);
				$this->SendCMD("getP_6_4_2", 0);
				$this->SendCMD("getP_6_4_3", 0);
				$this->SendCMD("getP_6_5_1", 0);
				$this->SendCMD("getP_6_5_2", 0);
				$this->SendCMD("getP_6_5_3", 0);
				$this->SendCMD("getP_6_6_1", 0);
				$this->SendCMD("getP_6_6_2", 0);
				$this->SendCMD("getP_6_6_3", 0);
				$this->SendCMD("getP_6_7_1", 0);
				$this->SendCMD("getP_6_7_2", 0);
				$this->SendCMD("getP_6_7_3", 0);
			break;
		}
	}
		
    public function ReceiveData($JSONString)
    {
		$data = json_decode($JSONString, true);
		if($data['DataID'] == '{7F7632D9-FA40-4F38-8DEA-C83CD4325A32}')
		{
			$deviceTopic	= "tfebus"; //$this->ReadPropertyString("deviceTopic");
			$topic			= explode('/', $data['Topic']);
			
			if($topic[0] == $deviceTopic)
			{
				$this->SendDebug($topic[1], $data["Payload"], 0);
				$valueData = json_decode($data["Payload"], true);

				switch($topic[1])
				{
					// STATE
					case "state" 		:
						if(isset($valueData["cloudState"]))
						{
							switch($valueData["cloudState"])
							{
								case 'offline' 		: $cloudState = 0; break;
								case 'online' 		: $cloudState = 1; break;
								case 'maintenance' 	: $cloudState = 2; break;
							}
						}
						if(isset($valueData["deviceState"]))
						{
							switch($valueData["deviceState"])
							{
								case 'ban' 			: $deviceState = 0; break;
								case 'offline' 		: $deviceState = 1; break;
								case 'connected'	: $deviceState = 2; break;
								case 'waiting'		: $deviceState = 3; break;
								case 'active'		: $deviceState = 4; break;
							}
							$deviceState != $this->GetValue("deviceState") ? $this->SetValue("deviceState", $deviceState) : 1;
						}
						if(isset($valueData["fVersion"]))
						{
							$valueData["fVersion"] != $this->GetValue("fVersion") ? $this->SetValue("fVersion", $valueData["fVersion"]) : 1;
						}
						if(isset($valueData["ipAddress"]))
						{
							$valueData["ipAddress"] != $this->GetValue("ipAddress") ? $this->SetValue("ipAddress", $valueData["ipAddress"]) : 1;
						}
						if(isset($valueData["wlanSignal"]))
						{
							$valueData["wlanSignal"] != $this->GetValue("wlanSignal") ? $this->SetValue("wlanSignal", $valueData["wlanSignal"]) : 1;
						}
						if(isset($valueData["uptime"]))
						{
							$valueData["uptime"] != $this->GetValue("uptime") ? $this->SetValue("uptime", $valueData["uptime"]) : 1;
						}
					break;
					// TFeBus Data
					case "tfEbusData" 		:
						// 5022
						if(isset($valueData["getStateBM"]))
						{
							switch($valueData["getStateBM"])
							{
								case '00' : $valueBM = 0;break;
								case '01' : $valueBM = 1;break;
								case '02' : $valueBM = 2;break;
								case '03' : $valueBM = 3;break;
								case '04' : $valueBM = 4;break;
								case '05' : $valueBM = 5;break;
								case '0e' :	$valueBM = 14;break;
							}
							$this->GetValue("stateBM") != $valueBM ? $this->SetValue("stateBM", $valueBM) : 1;
						}

						if(isset($valueData["getTempSW"]))
						{
							$valueData["getTempSW"] 	!= $this->GetValue("tempSW") 	? $this->SetValue("tempSW", $valueData["getTempSW"]) 	: 1;
						}
						
						if(isset($valueData["getTempDay"]))
						{
							$valueData["getTempDay"] 	!= $this->GetValue("tempDay")	? $this->SetValue("tempDay", $valueData["getTempDay"]) 	: 1;
						}

						if(isset($valueData["getTempEco"]))
						{
							$valueData["getTempEco"] 	!= $this->GetValue("tempEco")	? $this->SetValue("tempEco", $valueData["getTempEco"]) 	: 1;
						}

						if(isset($valueData["getTempRoom"]))
						{
							$valueData["getTempRoom"]	!= $this->GetValue("tempRoom")	? $this->SetValue("tempRoom", $valueData["getTempRoom"]): 1;
						}

						if(isset($valueData["getTempKS"]))
						{
							$valueData["getTempKS"] 	!= $this->GetValue("tempKS") 	? $this->SetValue("tempKS", $valueData["getTempKS"]) 	: 1;
						}
						
						if(isset($valueData["getTempKI"]))
						{
							$valueData["getTempKI"] 	!= $this->GetValue("tempKI") 	? $this->SetValue("tempKI", $valueData["getTempKI"]) 	: 1;
						}

						if(isset($valueData["getCurTimePrg"]))
						{
							$this->setCurTimePrg($valueData["getCurTimePrg"]);
						}

						if(isset($valueData["timePrg"]))
						{
							$this->SetBuffer("lockUpdateEvent", 1);
							$this->convTimePrgW2S($valueData["timePrg"]); 
							$this->SetBuffer("lockUpdateEvent", 0);
						}

						if(isset($valueData["getBurnerH"]))
						{
							$valueData["getBurnerH"]	!= $this->GetValue("burnerH")	? $this->SetValue("burnerH", $valueData["getBurnerH"]) 	: 1;
						}

						if(isset($valueData["getBurnerS"]))
						{
							$valueData["getBurnerS"]  	!= $this->GetValue("burnerS")	? $this->SetValue("burnerS", $valueData["getBurnerS"]) 	: 1;
						}

						if(isset($valueData["getOnH"]))
						{
							$valueData["getOnH"]		!= $this->GetValue("onH")		? $this->SetValue("onH", $valueData["getOnH"]) 			: 1;
						}

						if(isset($valueData["getTempA"]))
						{
							$valueData["getTempA"]		!= $this->GetValue("tempA") ? $this->SetValue("tempA", $valueData["getTempA"]) 			: 1;
						}

						if(isset($valueData["getTempWW"]))
						{
							$valueData["getTempWW"] 	!= $this->GetValue("tempWW") ? $this->SetValue("tempWW", $valueData["getTempWW"]) 		: 1;
						}
						
						if(isset($valueData["getTempWWS"]))
						{
							$valueData["getTempWWS"] 	!= $this->GetValue("tempWWS") ? $this->SetValue("tempWWS", $valueData["getTempWWS"]) 	: 1;
						}
						
						if(isset($valueData["tempWWI"]))
						{
							$valueData["tempWWI"] 		!= $this->GetValue("tempWWI") ? $this->SetValue("tempWWI", $valueData["tempWWI"]) 		: 1;
						}

						if(isset($valueData["valuesF"]))
						{
							$valuesF	= str_split($valueData["valuesF"]);
							$uwp 		= boolval($valuesF[1]);
							$flame 		= boolval($valuesF[4]);
							$this->GetValue("uwp") 		!= $uwp 	? $this->SetValue("uwp", $uwp) 		: 1;
							$this->GetValue("flame")	!= $flame 	? $this->SetValue("flame", $flame) 	: 1;
						}

						if(isset($valueData["valuesBM"]))
						{
							$valuesBM	= str_split($valueData["valuesBM"], 2);
							$time 		= $valuesBM[0].':'.$valuesBM[1];
							$weekday 	= $valuesBM[2];

							$this->GetValue("time") != $time ? $this->SetValue("time", $time) : 1;
							$this->GetValue("weekday") != $weekday ? $this->SetValue("weekday", $weekday) : 1;
						}

						if(isset($valueData["valuesH"]))
						{
							$valuesH	= str_split($valueData["valuesH"], 2);						
							switch($valuesH[0])
							{
								case "00" : $this->GetValue("heating") != 0 ? $this->SetValue("heating", 0) : 1; break; // Brenner ausschalten
								case "01" : $this->GetValue("heating") != 1 ? $this->SetValue("heating", 1) : 1; break; // Keine Aktion
								case "55" : $this->GetValue("heating") != 2 ? $this->SetValue("heating", 2) : 1; break; // Brauchwasserbereitung
								case "AA" : $this->GetValue("heating") != 3 ? $this->SetValue("heating", 3) : 1; break; // Heizbetrieb
								case "CC" : $this->GetValue("heating") != 4 ? $this->SetValue("heating", 4) : 1; break; // Emissionskontrolle
								case "DD" : $this->GetValue("heating") != 5 ? $this->SetValue("heating", 5) : 1; break; // TÜV-Funktion
								case "EE" : $this->GetValue("heating") != 6 ? $this->SetValue("heating", 6) : 1; break; // Reglerstop-Funktion
								case "66" : $this->GetValue("heating") != 7 ? $this->SetValue("heating", 7) : 1; break; // Brauchwasserbereitung bei Reglerstop
								case "BB" : $this->GetValue("heating") != 8 ? $this->SetValue("heating", 8) : 1; break; // Brauchwasserbereitung bei Heizbetrieb
								case "44" : $this->GetValue("heating") != 9 ? $this->SetValue("heating", 9) : 1; break; // Reglerstop-Funktion bei stufigem Betrieb
							}
							switch($valuesH[1])
							{
								case "00" : $this->GetValue("customer") != 0 ? $this->SetValue("customer", 0) : 1; break; // Keine Aktion
								case "01" : $this->GetValue("customer") != 1 ? $this->SetValue("customer", 1) : 1; break; // Ausschalten Kesselpumpe
								case "02" : $this->GetValue("customer") != 2 ? $this->SetValue("customer", 2) : 1; break; // Einschalten Kesselpumpe
								case "03" : $this->GetValue("customer") != 3 ? $this->SetValue("customer", 3) : 1; break; // Ausschalten variabler Verbraucher
								case "04" : $this->GetValue("customer") != 4 ? $this->SetValue("customer", 4) : 1; break; // Einschalten variabler Verbraucher
							}
						}
						/*
						case "will" :
							$valueData["will"] == "offline" && $this->GetValue("state") ? $this->SetValue("state", false) : 1;
						break;
						*/
					break;
				}

				$matches = [];
				// Zeitprogramme
				if(preg_match('/^getP_([0-9])_([0-9])_([0-9])$/', $topic[1], $matches))
				{
					$time 	= str_split($data["Payload"], 2);
					$start 	= hexdec($time[0]);
					$end 	= hexdec($time[1]);
					$this->getP2S($matches[1],$matches[2], $matches[3], $start, $end);
				}
			}
		}        
    }
	
	public function setCurTimePrg(string $value)
	{
		$curTimePrg  = (int) $value;
		$this->SetValue("curTimePrg", $curTimePrg);
		
		$hTimePrg1ID 		= IPS_GetObjectIDByIdent("hTimePrg1", $this->InstanceID);
		$hTimePrg2ID 		= IPS_GetObjectIDByIdent("hTimePrg2", $this->InstanceID);
		$hTimePrg3ID 		= IPS_GetObjectIDByIdent("hTimePrg3", $this->InstanceID);
		$wTimePrg1ID 		= IPS_GetObjectIDByIdent("wTimePrg1", $this->InstanceID);
		$wTimePrg2ID 		= IPS_GetObjectIDByIdent("wTimePrg2", $this->InstanceID);
		$wTimePrg3ID 		= IPS_GetObjectIDByIdent("wTimePrg3", $this->InstanceID);

		$hTimePrg1Active	= IPS_GetEvent($hTimePrg1ID)["EventActive"];
		$hTimePrg2Active	= IPS_GetEvent($hTimePrg2ID)["EventActive"];
		$hTimePrg3Active	= IPS_GetEvent($hTimePrg3ID)["EventActive"];
		$wTimePrg1Active	= IPS_GetEvent($wTimePrg1ID)["EventActive"];
		$wTimePrg2Active	= IPS_GetEvent($wTimePrg2ID)["EventActive"];
		$wTimePrg3Active	= IPS_GetEvent($wTimePrg3ID)["EventActive"];
		
		switch($curTimePrg)
		{
			case 0 :
				$hTimePrg1Active ? 1 : IPS_SetEventActive($hTimePrg1ID, true);
				$hTimePrg2Active ? IPS_SetEventActive($hTimePrg2ID, false) : 1;
				$hTimePrg3Active ? IPS_SetEventActive($hTimePrg3ID, false) : 1;
				$wTimePrg1Active ? 1 : IPS_SetEventActive($wTimePrg1ID, true);
				$wTimePrg2Active ? IPS_SetEventActive($wTimePrg2ID, false) : 1;
				$wTimePrg3Active ? IPS_SetEventActive($wTimePrg3ID, false) : 1;
			break;
			case 1 :
				$hTimePrg1Active ? IPS_SetEventActive($hTimePrg1ID, false) : 1;
				$hTimePrg2Active ? 1 : IPS_SetEventActive($hTimePrg2ID, true);
				$hTimePrg3Active ? IPS_SetEventActive($hTimePrg3ID, false) : 1;
				$wTimePrg1Active ? IPS_SetEventActive($wTimePrg1ID, false) : 1;
				$wTimePrg2Active ? 1 : IPS_SetEventActive($wTimePrg2ID, true);
				$wTimePrg3Active ? IPS_SetEventActive($wTimePrg3ID, false) : 1;
			break;
			case 2 :
				$hTimePrg1Active ? IPS_SetEventActive($hTimePrg1ID, false) : 1;
				$hTimePrg2Active ? IPS_SetEventActive($hTimePrg2ID, false) : 1;
				$hTimePrg3Active ? 1 : IPS_SetEventActive($hTimePrg3ID, true);
				$wTimePrg1Active ? IPS_SetEventActive($wTimePrg1ID, false) : 1;
				$wTimePrg2Active ? IPS_SetEventActive($wTimePrg2ID, false) : 1;
				$wTimePrg3Active ? 1 : IPS_SetEventActive($wTimePrg3ID, true);
			break;
		}
	}
	
	public function SendCMD(string $command, string $value)
	{
		//$deviceTopic				= $this->ReadPropertyString("deviceTopic");
		$data['DataID'] 			= '{043EA491-0325-4ADD-8FC2-A30C8EEB4D3F}';
        $data['PacketType'] 		= 3;
        $data['QualityOfService'] 	= 0;
        $data['Retain'] 			= false;
		$data['Topic'] 				= "tfebus/cmnd/".$command;
        $data['Payload'] 			= strval($value);
        $dataJSON 					= json_encode($data,JSON_UNESCAPED_SLASHES);
        $this->SendDataToParent($dataJSON);
	}
	
	public function RequestAction($ident, $value) {
		//IPS_LogMessage("RequestAction", print_r($ident));
		switch($ident)
		{
			case "stateBM" :
				$command 	= "setStateBM";
				$getCommand = "getStateBM";
				switch($value)
				{
					case 0 : 	$value = '00';break;
					case 1 : 	$value = '01';break;
					case 2 : 	$value = '02';break;
					case 3 : 	$value = '03';break;
					case 4 : 	$value = '04';break;
					case 5 : 	$value = '05';break;
					case 14 :	$value = '0e';break;
				}
				$this->SetValue("stateBM", 99);
			break;
			case "curTimePrg" :
				$command 	= "setCurTimePrg";
				$getCommand = "getCurTimePrg";
				$this->SetValue("curTimePrg", 99);
			break;
		}
		$this->SendCMD($command, $value);
		$this->SendCMD($getCommand, "");
	}
	
	public function getP2S(int $program, int $group, int $point, int $start, int $end) // Wolf-Programm to Symcon
	{
		// Get Event
		switch($program)
		{
			case 1 : $ident = "hTimePrg1"; break;
			case 2 : $ident = "hTimePrg2"; break;
			case 3 : $ident = "hTimePrg3"; break;
			case 4 : $ident = "wTimePrg1"; break;
			case 5 : $ident = "wTimePrg2"; break;
			case 6 : $ident = "wTimePrg3"; break;
		}

		$eventID 	= IPS_GetObjectIDByIdent($ident, $this->InstanceID);
		$event		= IPS_GetEvent($eventID)["ScheduleGroups"];

		$groupID 	= $group - 1;
		$pointID	= $point - 1;
		
		$this->SendDebug("Empfange Wert", "GroupID: ".$groupID.' - PointID: '.$pointID, 0);
		if($pointID == 0)
		{
			if($start > 0)
			{
				if(isset($event[$groupID]["Points"][$pointID]))
				{
					$eventPoint = $event[$groupID]["Points"][$pointID];
					if($eventPoint["Start"]["Hour"] != 0 || $eventPoint["Start"]["Minute"] != 0 || $eventPoint["Start"]["Second"] != 0 || $eventPoint["ActionID"] != 0)
					{
						// Start oder Aktion stimmt nicht: Lösche Alles ab hier!
						for($i=$pointID; $i<count($event[$groupID]["Points"]); $i++)
						{
							$this->SendDebug("Point 0", 'Punkt stimmt nicht -> LÖSCHE =  EventID: '.$eventID.' - GruppenID: '.$groupID.' - Schaltpunkt:'. $event[$groupID]["Points"][$i]["ID"], 0);
							IPS_SetEventScheduleGroupPoint($eventID, $groupID, $event[$groupID]["Points"][$i]["ID"], -1, -1, -1, 0);
						}
						// Erstelle Start bei 0 Uhr (mit ID = 0)
						IPS_SetEventScheduleGroupPoint($eventID, $groupID, 0, 0, 0, 0, 0);
					}
				}
				else
				{
					// Erstelle Start bei 0 Uhr (mit ID = 0)
					$this->SendDebug("Point 0", "Punkt existiert nicht -> Erstelle", 0);
					IPS_SetEventScheduleGroupPoint($eventID, $groupID, 0, 0, 0, 0, 0);
				}
				$this->SendDebug("Point 0", 'Gruppe:'.$groupID.' - Schaltpunkt:'.$pointID.' - Start: 0:0:0', 0);
				$pointID++;
			}
		}
		else
		{
			$pointID += $pointID;
			if(isset($event[$groupID]["Points"][0]))
			{
				$eventPoint = $event[$groupID]["Points"][0];
				if($eventPoint["Start"]["Hour"] == 0 && $eventPoint["Start"]["Minute"] == 0 && $eventPoint["Start"]["Second"] == 0 && $eventPoint["ActionID"] == 0)
				{
					$pointID++;
					$this->SendDebug("Erhöhe", "0 vorhanden, erhöhe um 1 - Point: ".$pointID, 0);
				}
			}
			if(isset($event[$groupID]["Points"][$pointID-1]))
			{
				$eventPoint = $event[$groupID]["Points"][$pointID - 1];
				$time		= (mktime($eventPoint["Start"]["Hour"], $eventPoint["Start"]["Minute"],$eventPoint["Start"]["Second"]) - mktime (0,0,0));
				$lastEnd 	= $time / 900;
			}
		}
		
		if($start != 128 && $end != 128)
		{
			$sek 			= $start * 900;
			$time			= strtotime('0:0') + $sek;
			if(isset($lastEnd) && $lastEnd == $start)
			{
				$time = $time+1;
			}
			$sHour 			= intval(strftime("%H", $time));
			$sMinute 		= intval(strftime("%M", $time));
			$sSecond 		= intval(strftime("%S", $time));
				
			// END
			$sek 			= $end * 900;
			$time			= strtotime('0:0') + $sek;
			$eHour 			= intval(strftime("%H", $time));
			$eMinute 		= intval(strftime("%M", $time));
			$eSecond 		= intval(strftime("%S", $time));
			if($end == 96)
			{
				$eHour 		= 23;
				$eMinute 	= 59;
				$eSecond 	= 59;
			}

			$this->SendDebug("START", 'Gruppe:'.$groupID.' - Schaltpunkt:'.$pointID.' - Start:'.$sHour.':'.$sMinute.':'.$sSecond, 0);
			$this->SendDebug("ENDE",  'Gruppe:'.$groupID.' - Schaltpunkt:'.($pointID+1).' - Ende:'.$eHour.':'.$eMinute.':'.$eSecond, 0);

				
			// Start setzen
			if(isset($event[$groupID]["Points"][$pointID]))
			{
				$eventPoint = $event[$groupID]["Points"][$pointID];
				if($eventPoint["Start"]["Hour"] != $sHour || $eventPoint["Start"]["Minute"] != $sMinute || $eventPoint["Start"]["Second"] != $sSecond || $eventPoint["ActionID"] != 1)
				{
					// Start oder Aktion stimmt nicht: Lösche Alles ab hier!
					for($i=$pointID; $i<count($event[$groupID]["Points"]); $i++)
					{
						IPS_SetEventScheduleGroupPoint($eventID, $groupID, $event[$groupID]["Points"][$i]["ID"], -1, -1, -1, 0);
					}
					// Erstelle Start
					IPS_SetEventScheduleGroupPoint($eventID, $groupID, $pointID, $sHour, $sMinute, $sSecond, 1);
				}
			}
			else
			{
				IPS_SetEventScheduleGroupPoint($eventID, $groupID, $pointID, $sHour, $sMinute, $sSecond, 1);
			}
			$event = IPS_GetEvent($eventID)["ScheduleGroups"];

			$pointID++;
				
			// Punkt vorhanden
			if(isset($event[$groupID]["Points"][$pointID]))
			{
				$eventPoint = $event[$groupID]["Points"][$pointID];
				if($eventPoint["Start"]["Hour"] != $eHour || $eventPoint["Start"]["Minute"] != $eMinute || $eventPoint["Start"]["Second"] != $eSecond || $eventPoint["ActionID"] != 0)
				{
					// Start oder Aktion stimmt nicht: Lösche Alles ab hier!
					for($i=$pointID; $i<count($event[$groupID]["Points"]); $i++)
					{
						IPS_SetEventScheduleGroupPoint($eventID, $groupID, $event[$groupID]["Points"][$i]["ID"], -1, -1, -1, 0);
					}
					// Erstelle Start
					IPS_SetEventScheduleGroupPoint($eventID, $groupID, $pointID, $eHour, $eMinute, $eSecond, 0);
				}
			}
			else
			{
				// Erstelle Start bei 0 Uhr (mit ID=1)
				IPS_SetEventScheduleGroupPoint($eventID, $groupID, $pointID, $eHour, $eMinute, $eSecond, 0);
			}
			$event = IPS_GetEvent($eventID)["ScheduleGroups"];
		}		

		/*




		//IPS_LogMessage("TFeBus - Event", print_r($event));



		if($start != 128 && $end != 128)
		{
			$sek 			= $start * 900;
			$time			= strtotime('0:0') + $sek;

			$sHour 			= intval(strftime("%H", $time));
			$sMinute 		= intval(strftime("%M", $time));
			$sSecond 		= intval(strftime("%S", $time));
				
			// END
			$sek 			= $end * 900;
			$time			= strtotime('0:0') + $sek;
			$eHour 			= intval(strftime("%H", $time));
			$eMinute 		= intval(strftime("%M", $time));
			$eSecond 		= intval(strftime("%S", $time));
			if($end == 96)
			{
				$eHour 		= 23;
				$eMinute 	= 59;
				$eSecond 	= 59;
			}
		}
		$this->SendDebug("START", 'Start: '.$sHour.':'.$sMinute.':'.$sSecond, 0);
		$this->SendDebug("ENDE",  'Ende: '.$eHour.':'.$eMinute.':'.$eSecond, 0);
		IPS_LogMessage("SCHALTPUNKT", 'Ident: '.$ident.' - Gruppe:'.$groupID.' - Schaltpunkt:'.$pointID);
		





		if($program == 1 || $program == 2 || $program == 4 || $program == 5) // Nur Programme mit MO-FR und SA-SO
		{
			/*
			switch($days)
			{
				case 1 : // MO
			}
		
		}
		*/
	}

	/*
	public function getTimePrg(int $timePrg) // wolf send timePrgs
	{
		switch($timePrg)
		{
			case 10 : $this->SendCMD("getTimePrg", 10); break;
			case 11 : $this->SendCMD("getTimePrg", 11); break;
			case 12 : $this->SendCMD("getTimePrg", 12); break;
			case 20 : $this->SendCMD("getTimePrg", 20); break;
			case 21 : $this->SendCMD("getTimePrg", 21); break;
			case 22 : $this->SendCMD("getTimePrg", 22); break;
		}
	}
	*/
	

	/*
	private function convTimePrgW2S(string $json) // convert wolf to symcon-event FERTIG !
	{
		$value 		= json_decode($json, true);
		$timePrgNr	= $value["timePrgNr"];
		$timeID 	= $value["timeID"];
		$start 		= $value["start"];
		$end 		= $value["end"];
		
		switch($timePrgNr)
		{
			case 10: $ident = "hTimePrg1"; $startID = 8; break;
			case 11: $ident = "hTimePrg2"; $startID = 8; break;
			case 12: $ident = "hTimePrg3"; $startID = 1; break;
			case 20: $ident = "wTimePrg1"; $startID = 8; break;
			case 21: $ident = "wTimePrg2"; $startID = 8; break;
			case 22: $ident = "wTimePrg3"; $startID = 1; break;
		}
		$eventID 	= IPS_GetObjectIDByIdent($ident, $this->InstanceID);
		$event		= IPS_GetEvent($eventID)["ScheduleGroups"];
		$groupID 	= (int) ($timeID / 16 - $startID);
		$pointID 	= ($timeID - (($groupID + $startID) * 16)) * 2;
		
		//IPS_LogMessage("SCHALTPUNKT", 'Ident: '.$ident.' - Gruppe:'.$groupID.' - Schaltpunkt:'.$pointID.' - EventID:'.$eventID);
		
		# hTimePrg1 & hTimePrg2
		// Gruppe:0 - Schaltpunkt:0
		// Gruppe:0 - Schaltpunkt:1
		// Gruppe:0 - Schaltpunkt:2
		
		// Gruppe:1 - Schaltpunkt:0
		// Gruppe:1 - Schaltpunkt:1
		// Gruppe:1 - Schaltpunkt:2
		
		# hTimePrg3
		// Gruppe:0 - Schaltpunkt:0
		// Gruppe:0 - Schaltpunkt:1
		// Gruppe:0 - Schaltpunkt:2
		
		// Gruppe:1 - Schaltpunkt:0
		// Gruppe:1 - Schaltpunkt:1
		// Gruppe:1 - Schaltpunkt:2
		
		// Gruppe:2 - Schaltpunkt:0
		// Gruppe:2 - Schaltpunkt:1
		// Gruppe:2 - Schaltpunkt:2
		
		// Gruppe:3 - Schaltpunkt:0
		// Gruppe:3 - Schaltpunkt:1
		// Gruppe:3 - Schaltpunkt:2
		
		// Gruppe:4 - Schaltpunkt:0
		// Gruppe:4 - Schaltpunkt:1
		// Gruppe:4 - Schaltpunkt:2
		
		// Gruppe:5 - Schaltpunkt:0
		// Gruppe:5 - Schaltpunkt:1
		// Gruppe:5 - Schaltpunkt:2
		
		// Gruppe:6 - Schaltpunkt:0
		// Gruppe:6 - Schaltpunkt:1
		// Gruppe:6 - Schaltpunkt:2
		if($pointID == 0)
		{
			if($start > 0)
			{
				// $this->SendDebug("0", 'Gruppe:'.$groupID.' - Schaltpunkt:'.$pointID.' - Start:0:0:0',0);
				if(isset($event[$groupID]["Points"][$pointID]))
				{
					$eventPoint = $event[$groupID]["Points"][$pointID];
					if($eventPoint["Start"]["Hour"] != 0 || $eventPoint["Start"]["Minute"] != 0 || $eventPoint["Start"]["Second"] != 0 || $eventPoint["ActionID"] != 0)
					{
						// Start oder Aktion stimmt nicht: Lösche Alles ab hier!
						for($i=$pointID; $i<count($event[$groupID]["Points"]); $i++)
						{
							//$this->SendDebug("0", '0 stimmt nicht -> LÖSCHE =  EventID: '.$eventID.' - GruppenID: '.$groupID.' - Schaltpunkt:'. $event[$groupID]["Points"][$i]["ID"] ,0);
							IPS_SetEventScheduleGroupPoint($eventID, $groupID, $event[$groupID]["Points"][$i]["ID"], -1, -1, -1, 0);
						}
						// Erstelle Start bei 0 Uhr (mit ID = 0)
						IPS_SetEventScheduleGroupPoint($eventID, $groupID, 0, 0, 0, 0, 0);
					}
				}
				else
				{
					// Erstelle Start bei 0 Uhr (mit ID = 0)
					IPS_SetEventScheduleGroupPoint($eventID, $groupID, 0, 0, 0, 0, 0);
				}
				$event = IPS_GetEvent($eventID)["ScheduleGroups"];
			}
		}
		
		// Ist der Start > 0 -> Erhöhe $pointID um 1
		$eventPoint = $event[$groupID]["Points"][0];
		if($eventPoint["Start"]["Hour"] == 0 && $eventPoint["Start"]["Minute"] == 0 && $eventPoint["Start"]["Second"] == 0 && $eventPoint["ActionID"] == 0)
		{
			$pointID = $pointID + 1;
		}
		
		//  Ist die PointID größer 0, dann Zeit umrechnen !
		if($pointID > 0 && isset($event[$groupID]["Points"][$pointID - 1]))
		{
			$eventPoint = $event[$groupID]["Points"][$pointID - 1];
			$time		= (mktime($eventPoint["Start"]["Hour"], $eventPoint["Start"]["Minute"],$eventPoint["Start"]["Second"]) - mktime (0,0,0));
			$lastEnd 	= $time / 900;
		}
		
		// START
		if($start != 128 && $end != 128)
		{
			$sek 			= $start * 900;
			$time			= strtotime('0:0') + $sek;
			if(isset($lastEnd) && $lastEnd == $start)
			{
				$time = $time+1;
			}
			$sHour 			= intval(strftime("%H", $time));
			$sMinute 		= intval(strftime("%M", $time));
			$sSecond 		= intval(strftime("%S", $time));
				
			// END
			$sek 			= $end * 900;
			$time			= strtotime('0:0') + $sek;
			$eHour 			= intval(strftime("%H", $time));
			$eMinute 		= intval(strftime("%M", $time));
			$eSecond 		= intval(strftime("%S", $time));
			if($end == 96)
			{
				$eHour 		= 23;
				$eMinute 	= 59;
				$eSecond 	= 59;
			}
			
			$this->SendDebug("START", 'Gruppe:'.$groupID.' - Schaltpunkt:'.$pointID.' - Start:'.$sHour.':'.$sMinute.':'.$sSecond, 0);
			$this->SendDebug("ENDE",  'Gruppe:'.$groupID.' - Schaltpunkt:'.($pointID+1).' - Ende:'.$eHour.':'.$eMinute.':'.$eSecond, 0);
			
			// Punkt vorhanden
			if(isset($event[$groupID]["Points"][$pointID]))
			{
				$eventPoint = $event[$groupID]["Points"][$pointID];
				if($eventPoint["Start"]["Hour"] != $sHour || $eventPoint["Start"]["Minute"] != $sMinute || $eventPoint["Start"]["Second"] != $sSecond || $eventPoint["ActionID"] != 1)
				{
					// Start oder Aktion stimmt nicht: Lösche Alles ab hier!
					for($i=$pointID; $i<count($event[$groupID]["Points"]); $i++)
					{
						IPS_SetEventScheduleGroupPoint($eventID, $groupID, $event[$groupID]["Points"][$i]["ID"], -1, -1, -1, 0);
					}
					// Erstelle Start
					IPS_SetEventScheduleGroupPoint($eventID, $groupID, $pointID, $sHour, $sMinute, $sSecond, 1);
				}
			}
			else
			{
				IPS_SetEventScheduleGroupPoint($eventID, $groupID, $pointID, $sHour, $sMinute, $sSecond, 1);
			}
			$event = IPS_GetEvent($eventID)["ScheduleGroups"];
			
			$pointID++;
			
			// Punkt vorhanden
			if(isset($event[$groupID]["Points"][$pointID]))
			{
				$eventPoint = $event[$groupID]["Points"][$pointID];
				if($eventPoint["Start"]["Hour"] != $eHour || $eventPoint["Start"]["Minute"] != $eMinute || $eventPoint["Start"]["Second"] != $eSecond || $eventPoint["ActionID"] != 0)
				{
					// Start oder Aktion stimmt nicht: Lösche Alles ab hier!
					for($i=$pointID; $i<count($event[$groupID]["Points"]); $i++)
					{
						IPS_SetEventScheduleGroupPoint($eventID, $groupID, $event[$groupID]["Points"][$i]["ID"], -1, -1, -1, 0);
					}
					// Erstelle Start
					IPS_SetEventScheduleGroupPoint($eventID, $groupID, $pointID, $eHour, $eMinute, $eSecond, 0);
				}
			}
			else
			{
				// Erstelle Start bei 0 Uhr (mit ID=1)
				IPS_SetEventScheduleGroupPoint($eventID, $groupID, $pointID, $eHour, $eMinute, $eSecond, 0);
			}
			$event = IPS_GetEvent($eventID)["ScheduleGroups"];			
		}
	}
	*/
	
	public function writeTimePrg(int $timePrg) // write symcon event to wolf
	{
		//IPS_LogMessage("Wolf", "Schreibe Daten");
		switch($timePrg)
		{
			case 10: $ident = "hTimePrg1"; $startID = 128;	break; // EINHEITLICH !!
			case 11: $ident = "hTimePrg2"; $startID = 128;	break;
			case 12: $ident = "hTimePrg3"; $startID = 16; 	break;
			case 20: $ident = "wTimePrg1"; $startID = 128;	break;
			case 21: $ident = "wTimePrg2"; $startID = 128;	break;
			case 22: $ident = "wTimePrg3"; $startID = 16;	break;
		}
		
		$send["timePrgNr"] 	= $timePrg;
		$events 			= IPS_GetEvent(IPS_GetObjectIDByIdent($ident, $this->InstanceID))["ScheduleGroups"];
		$start 				= false;

		foreach($events as $event)
		{
			$id 	= $startID;
			$num	= 0;
			$points	= 0;
			foreach($event["Points"] as $point)
			{
				if($start == true)
				{
					$start 	= false;	
					if($num == 0)
					{
						$send["end"]	= 96;
						$ende1 			= 96;
					}
					else
					{
						if($point["ActionID"] == 0)
						{
							$time	= (mktime($point["Start"]["Hour"], $point["Start"]["Minute"],$point["Start"]["Second"]) - mktime (0,0,0));
							if($point["Start"]["Second"] == 59)
							{
								$time++;
							}
							$dezTime = $time / 900;
							$send["end"]	= $dezTime;
						}
					}
					$data = json_encode($send);
					$this->SendCMD("setTimePrg", $data);
				}
				
				if($point["ActionID"] == 1)
				{
					$start 	= true;
					$time 	= (mktime($point["Start"]["Hour"], $point["Start"]["Minute"],$point["Start"]["Second"]) - mktime (0,0,0));
					if($point["Start"]["Second"] == 59)
					{
						$time--;
					}
					$dezTime = $time / 900;
					$send["timeID"] = $id;
					$send["start"]	= $dezTime;
					$id++;
					$points++;
				}
				$num++;
			}
			while($points < 3)
			{
				$send["timeID"] = $id;
				$send["start"] 	= 128;
				$send["end"] 	= 128;
				$data 			= json_encode($send);
				$this->SendCMD("setTimePrg", $data);
				$points++;
				$id++;
			}
			$startID = $startID + 16;
		}
	}
}
?>