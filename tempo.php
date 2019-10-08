 <?php
    include('/volume1/web/domoreport/API/APITempo.php');
    include('/volume1/web/domoreport/API/APIPushOver.php');
    
    read_Tempo($TempoToday,$TempoTomorrow);
    $EventName = 'EDF Tempo';
    $Level="1";
    $Message=$TempoTomorrow;
    
    if($TempoTomorrow=="BLEU") {
      $Level="0";
      $Message = 'Demain jour bleu';
    }
    else if($TempoTomorrow=="BLANC")   {
      $Level="1";
      $Message = 'Demain jour blanc';
    }
    else if($TempoTomorrow=="ROUGE") {
      $Level="1";
      $Message = 'Demain jour rouge';
    }
        
    SendPushoverAlert($Level,$EventName, $Message);
	mail('ccedard@mega.com', $EventName, $Message,'From: luatcedard@free.fr');
 ?>
