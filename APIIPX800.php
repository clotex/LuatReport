<?php
    include('/volume1/web/domoreport/ipx800authentication.php');
	
    //***********************************************************************************************************************************************************************************************************
    // Interrogation des IPX et des sondes diverses
	//
	//<response>
	//	<led0>0</led0>
	//	...
	//	<led31>0</led31>
	//	<btn0>up</btn0>
	//	...
	//	<btn31>up</btn31>
	//	<day>28/04/2019 </day>
	//	<time0>13:46:28</time0>
	//	<analog0>187</analog0>
	// 	...
	//	<analog15>0</analog15>
	//	<anselect0>2</anselect0>
	//	...
	//	<anselect15>0</anselect15>
	//	<count0>76</count0>
	//	...
	//	<count7>0</count7>
	//	<tinfo>---</tinfo>
	//	<version>3.05.70</version>
	//</response>
    //***********************************************************************************************************************************************************************************************************
	$xmlStatusChauffage 	= @simplexml_load_file("http://$usernameIPX:$passwordIPX@$hostIPX/status.xml");
	$xmlStatusPiscine 		= @simplexml_load_file("http://$usernameIPX:$passwordIPX@$hostIPXPiscine/status.xml");	
	$xmlStatusPetiteMaison  = @simplexml_load_file("http://$usernameIPX:$passwordIPX@$hostIPXPetiteMaison/status.xml");
	
	function  IPXPropGet( $Property)
	{
		$Value="0";
		
		switch($Property) {
			// IPX Chauffage
			case "TempChauffageRetour":
				$Value=number_format(($xmlStatusChauffage->analog3 - 155.135) /3.1027,1);
			break;
			case "TempGrange":
				$Value=number_format(($xmlStatusChauffage->analog0 - 155.135) /3.1027,1);
			break;
			//IPX Piscine
			case "TempGrangeMur":
			    $Value = number_format(($xmlStatusPiscine->analog0 * 0.323) - 50,1); // (X * 0.323) - 50 pour la TC4012
			break;
			case "TempEauPiscine":
			    $Value = number_format(($xmlStatusPiscine->analog2 * 0.323) - 50,1); // (X * 0.323) - 50 pour la TC4012
			break;
			case "TempPanneauSolaire":
				$Value=number_format(($xmlStatusPiscine->analog1 * 0.323) - 50,1); // anamog1 = temp panneaux solaires (X * 0.323) - 50 pour la TC4012
			break;
			default:
				echo "Unknown&nbspparameter:\"",$Property,"\"<br>";
			break;
		}
		return($Property);
	}
	
	function  IPXPropSet( $Property, $Value) {
  		switch($Property) {
			// IPX Piscine
			case "PiscineHorsGel":
				file("http://$usernameIPX:$passwordIPX@$hostIPXPiscine/preset.htm?set3=$Value"); // set3 => Pompe principale hors gel
			break;
			case "PiscineSolaire":
				file("http://$usernameIPX:$passwordIPX@$hostIPXPiscine/preset.htm?set5=$Value"); // set5 => Pompe solaire
			break;
			// IPX PetiteMaison
			case "PetiteMaisonPoeleCharge":
				file("http://$usernameIPX:$passwordIPX@$hostIPXPetiteMaison/preset.htm?set1=$Value"); // set1 => Charge du poele du RDC
			break;			
			case "PetiteMaisonPoeleVentilation":
				file("http://$usernameIPX:$passwordIPX@$hostIPXPetiteMaison/preset.htm?set2=$Value"); // set2 => Ventilateur du poele du RDC
			break;			
			case "PetiteMaisonChauffageGrandeChambre":
				file("http://$usernameIPX:$passwordIPX@$hostIPXPetiteMaison/preset.htm?set3=$Value"); // set3 => Chauffage grande chambre
			break;
			case "PetiteMaisonChauffagePetiteChambre":
				file("http://$usernameIPX:$passwordIPX@$hostIPXPetiteMaison/preset.htm?set4=$Value"); // set4 => Chauffage petite chambre
			break;
			case "PetiteMaisonChauffageDouche":
				file("http://$usernameIPX:$passwordIPX@$hostIPXPetiteMaison/preset.htm?set6=$Value"); // set6 => Chauffage douche
			break;
			default:
				echo "Unknown&nbspparameter:\"",$Property,"\"<br>";
			break;
		}
	}
 ?>
