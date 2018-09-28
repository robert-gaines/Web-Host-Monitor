<?php

 error_reporting(0);

 set_time_limit(0); /* ALlows script to run indefinitely */

 /*
    I -> Class C Network
    P -> Populates the entirety of a Class C Subnet
    O -> An associative array representing the entire subnet
 */

 function populateHosts($net_segment)
 {
    $net_hosts = array();

    $n = $net_segment;

    for($i = 1; $i < 255; $i++)
    {
        $h = (string)$i;

        $host = $n.$h;

        array_push($net_hosts, $host);
    }

    return $net_hosts;

 }

 /*
    I -> Receives an array of host IPV4 addresses
    P -> Attempts to communicate with each host via the specified port
    O -> Returns alive or unresponsive status
 */

 function pingHosts($host_array,$port)
 {
    $tally = 0;

    while(1)
    {
      foreach($host_array as $h)
      {
        $s = fsockopen($h,$port,$errno,$errstr,0);
        //
        stream_set_blocking(FALSE);
        //
        if($s)
        {
            echo "[*] Host ".$h." alive on port->  ".$port."\n\n";
            //
            echo "\n";
            // 
            $tally += 1;
            //
        }
        else
        {
            echo "[-] Host ".$h." inactive on port-> ".$port."\n\n";
            //
            echo "\n";
            //
        }
        //
        fclose($s);
     }

    echo "\n";
    //       
    echo "****************************";
    //
    echo "[+] Total Hosts-> ".$tally."\n";
    //
    echo "****************************";
    //    
    echo "\n";
    //
    $tally = 0;    
    }
    sleep(60);
 }

 $test = populateHosts("10.8.0.");

 $port = 80;

  pingHosts($test,$port);





    ?>


