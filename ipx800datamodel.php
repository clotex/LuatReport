<?php

 $ipx800RawTable='ipx800V3'; //Table
 
 $kwh_HC_bleu=floatval(0.0678);
 $kwh_HP_bleu=floatval(0.0818);
 $kwh_HC_blanc=floatval(0.0983);
 $kwh_HP_blanc=floatval(0.1176);
 $kwh_HC_rouge=floatval(0.1862);
 $kwh_HP_rouge=floatval(0.4948);
 
 $kwh_PHRT=5;
 
 $led0ExternalName="Undefined";
 $led1ExternalName="Undefined";
 $led2ExternalName="Undefined";
 $led3ExternalName="Undefined";
 $led4ExternalName="Undefined";
 $led5ExternalName="Undefined";
 $led6ExternalName="Undefined";
 $led7ExternalName="Undefined";
   
 $btn0ExternalName="Undefined";
 $btn1ExternalName="Undefined";
 $btn2ExternalName="Undefined";
 $btn3ExternalName="Undefined";
 $btn4ExternalName="Undefined";
 $btn5ExternalName="Undefined";
 $btn6ExternalName="Undefined";
 $btn7ExternalName="Undefined";

 $an1ExternalName="Undefined";
 $an2ExternalName="Undefined";
 
 function ipx800MetaDataRetrieve($idCustomer)
 {
   global $led0ExternalName,$led1ExternalName,$led2ExternalName,$led3ExternalName,$led4ExternalName,$led5ExternalName,$led6ExternalName,$led7ExternalName,
          $btn0ExternalName,$btn1ExternalName,$btn2ExternalName,$btn3ExternalName,$btn4ExternalName,$btn5ExternalName,$btn6ExternalName,$btn7ExternalName,
          $an1ExternalName,$an2ExternalName;
   
   $requestMetaData="SELECT * FROM ipx800V3MetaData WHERE idCustomer=$idCustomer";
   $queryMetaData=mysql_query($requestMetaData) or die ('Erreur SQL ! '.$requestMetaData.'<br/>'.mysql_error());
   $lineMetaData=mysql_fetch_array($queryMetaData);
   
   $led0ExternalName=$lineMetaData['led0'];
   $led1ExternalName=$lineMetaData['led1'];
   $led2ExternalName=$lineMetaData['led2'];
   $led3ExternalName=$lineMetaData['led3'];
   $led4ExternalName=$lineMetaData['led4'];
   $led5ExternalName=$lineMetaData['led5'];
   $led6ExternalName=$lineMetaData['led6'];
   $led7ExternalName=$lineMetaData['led7'];
   
   $btn0ExternalName=$lineMetaData['btn0'];
   $btn1ExternalName=$lineMetaData['btn1'];
   $btn2ExternalName=$lineMetaData['btn2'];
   $btn3ExternalName=$lineMetaData['btn3'];
   $btn4ExternalName=$lineMetaData['btn4'];
   $btn5ExternalName=$lineMetaData['btn5'];
   $btn6ExternalName=$lineMetaData['btn6'];
   $btn7ExternalName=$lineMetaData['btn7'];

   $an1ExternalName=$lineMetaData['an1'];
   $an2ExternalName=$lineMetaData['an2'];
 }
 
?>
