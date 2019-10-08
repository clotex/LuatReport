<?php
   
  function xml_xpath_get($xml_string,$xpath,&$value)
  {
    $xml = new SimpleXMLElement($xml_string);

    /* On cherche <a><b><c> */
    $result = $xml->xpath('/val:Root/SenSet/Entry');

    while(list( , $node) = each($result)) {
      echo '/val:Root/SenSet/Entry',$node,"\n";
    }
  }

  function read_xml($fichier,$item,$champs)
  {
    if($chaine = @implode("",@file($fichier)))
    {
      $tmp = preg_split("/<\/?".$item.">/",$chaine);
      for($i=1;$i<sizeof($tmp)-1;$i+=2)
        foreach($champs as $champ)
        {
          $tmp2 = preg_split("/<\/?".$champ.">/",$tmp[$i]);
          $tmp3[$i-1][] = @$tmp2[1];
        }
        return $tmp3;
    }
  }

  function day_number($date)
  {
    list($annee, $mois, $jour) = explode ("-", $date);
    $timestamp = mktime(0,0,0, date($mois), date($jour), date($annee));
    $njour = date("N",$timestamp);
    return $njour; 
  }

  function compute_cost($energy_device,$kwh_device,$elapsed_time,$start_Hour,&$cost,&$rate)
  {
    switch($energy_device) {
      case "Gaz": {
        $kwh_Gaz=floatval(0.0759); // Prix Gedia septembre 2011
        $cost=(floatval($elapsed_time/3600)*$kwh_Gaz)*$kwh_device;
        $rate="";
      }
      break;
      case "Electricity": {
        $kwh_HC_bleu=floatval(0.0678); // Prix EDF Aout 2011
        $kwh_HP_bleu=floatval(0.0818);
        $kwh_HC_blanc=floatval(0.0983);
        $kwh_HP_blanc=floatval(0.1176);
        $kwh_HC_rouge=floatval(0.1862);
        $kwh_HP_rouge=floatval(0.4948);
        
        if($start_Hour>22 OR $start_Hour<6) {
          $cost=(floatval($elapsed_time/3600)*$kwh_HC_bleu)*$kwh_device;
          $rate="HC";
        }
        else {
          $cost=(floatval($elapsed_time/3600)*$kwh_HP_bleu)*$kwh_device;
          $rate="HP";
        }
      }
      break;
      case "Fioul":
      default:
        $cost=0;
        $rate="?";
      break;
    }
  }
    
  function html_printf()
  {
   $args = func_get_args();
   $format = array_shift($args);
   
   $format=str_replace(" ", "&nbsp;", $format);
   $format=str_replace("°", "&deg;", $format);
   $format=str_replace("\n", "<br>\n", $format);
   
   vprintf($format, array_values($args));
}
  
  //-------------------------------------------------------------------------------------------------------------------------------------------
  // Display d'un évènement éventuel lié à la ligne $line_DateTime
  //-------------------------------------------------------------------------------------------------------------------------------------------
  function display_device($line_DateTime,$an3,$an4,
            $btn_InternalName,$btn_ExternalName,$btn_Status,
            &$btn_LastStatus,&$btn_LastDateStart,&$btn_LastDateTimeStart,&$btn_LastDateTimeEnd,
            &$btn_CostOfTheDay,&$btn_RunningTimeOfTheDay
          )
  {
      global  $btn_RunningTimeOfTheDay,
              $btn_CostOfTheDay;
      global  $btn0ExternalName,
              $btn1ExternalName,
              $btn2ExternalName,
              $btn3ExternalName;


    // Première ligne, fonctionnement en cours
    if($btn_Status=="dn") {
      $btn_LastDateTimeStart[$btn_InternalName]=$line_DateTime;
    }
    // Détection de séquence, calcul du temps elapse
    if(($btn_LastStatus[$btn_InternalName]=="dn")&&($btn_Status=="up")) {
      $start_Time=strftime("%H:%M:%S",strtotime($btn_LastDateTimeStart[$btn_InternalName]));
      
      if($btn_LastDateTimeEnd[$btn_InternalName]==0){
        $elapsed_time=strtotime("now")-strtotime($btn_LastDateTimeStart[$btn_InternalName]);
        echo $start_Time,"&nbsp Running for&nbsp<B>",gmdate("G:i:s",$elapsed_time),"</B>&nbspon &nbsp<B>",$btn_ExternalName,"</B>";
        if($btn_InternalName!="btn2") echo "&nbsp (D&nbsp",number_format(($an3 - 155.135) /3.1027,1),"&nbspR&nbsp",number_format(($an4 - 155.135) /3.1027,1),")";
        echo "<br/>";
      }
      else {
        $elapsed_time=strtotime($btn_LastDateTimeEnd[$btn_InternalName])-strtotime($btn_LastDateTimeStart[$btn_InternalName]);

        $start_Date=strftime("%d/%m/%y",strtotime($btn_LastDateTimeStart[$btn_InternalName]));
        $start_Hour=strftime("%H",strtotime($btn_LastDateTimeStart[$btn_InternalName]));
        
        switch($btn_InternalName) {
          case "btn0":
            compute_cost("Electricity",5,$elapsed_time,$start_Hour,$cost,$rate);
          break;
          case "btn1":
            compute_cost("Gaz",35,$elapsed_time,$start_Hour,$cost,$rate);
          break;
          case "btn2":
            compute_cost("Electricity",1,$elapsed_time,$start_Hour,$cost,$rate);
          break;
          case "btn3":
            compute_cost("Electricity",5,$elapsed_time,$start_Hour,$cost,$rate);
          break;
           default:
            $cost=0;
            $rate="?";
          break;
        }
        // Cumul des couts et dates
        if($start_Date!=$btn_LastDateStart[$btn_InternalName]) {
          if($btn_LastDateStart[$btn_InternalName]!="") {
            echo "<br>\n";
            echo "Running time of the day for device ",$btn0ExternalName,": <B>",gmdate("H:i:s",$btn_RunningTimeOfTheDay["btn0"]),"</B>&nbsp(",number_format($btn_CostOfTheDay["btn0"],2),"&nbsp&#8364)<br>\n";
            echo "Running time of the day for device ",$btn1ExternalName,": <B>",gmdate("H:i:s",$btn_RunningTimeOfTheDay["btn1"]),"</B>&nbsp(",number_format($btn_CostOfTheDay["btn1"],2),"&nbsp&#8364)<br>\n";
            echo "Running time of the day for device ",$btn2ExternalName,": <B>",gmdate("H:i:s",$btn_RunningTimeOfTheDay["btn2"]),"</B>&nbsp(",number_format($btn_CostOfTheDay["btn2"],2),"&nbsp&#8364)<br>\n";
            echo "Running time of the day for device ",$btn3ExternalName,": <B>",gmdate("H:i:s",$btn_RunningTimeOfTheDay["btn3"]),"</B>&nbsp(",number_format($btn_CostOfTheDay["btn3"],2),"&nbsp&#8364)<br>\n";
            echo "</ul>\n",$start_Date,"<ul>\n";

            $btn_LastDateStart["btn0"]="";
            $btn_LastDateStart["btn1"]="";
            $btn_LastDateStart["btn2"]="";
            $btn_LastDateStart["btn3"]="";

            $btn_RunningTimeOfTheDay["btn0"]=0;
            $btn_RunningTimeOfTheDay["btn1"]=0;
            $btn_RunningTimeOfTheDay["btn2"]=0;
            $btn_RunningTimeOfTheDay["btn3"]=0;

            $btn_CostOfTheDay["btn0"]=0;
            $btn_CostOfTheDay["btn1"]=0;
            $btn_CostOfTheDay["btn2"]=0;
            $btn_CostOfTheDay["btn3"]=0;
          }
          $btn_LastDateStart[$btn_InternalName]=$start_Date;
        }

        echo $start_Time,"&nbsp&nbsp<B>",gmdate("G:i:s",$elapsed_time),"</B>&nbsp&nbsp",number_format($cost,2),"&nbsp&#8364";
        if($rate!="") echo "&nbsp(",$rate,")";
        echo "&nbspon &nbsp<B>",$btn_ExternalName,"</B>";
        if($btn_InternalName!="btn2") echo "&nbsp (D&nbsp",number_format(($an3 - 155.135) /3.1027,1),"&nbspR&nbsp",number_format(($an4 - 155.135) /3.1027,1),")";
        echo "<br>\n";


        $btn_CostOfTheDay[$btn_InternalName]=$btn_CostOfTheDay[$btn_InternalName]+$cost;
        $btn_RunningTimeOfTheDay[$btn_InternalName]=$btn_RunningTimeOfTheDay[$btn_InternalName]+$elapsed_time;
      }
    }
    if($btn_Status=="up") {
      $btn_LastDateTimeEnd[$btn_InternalName]=$line_DateTime;
    }
    $btn_LastStatus[$btn_InternalName]=$btn_Status;
  }
?>
