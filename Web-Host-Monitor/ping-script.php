
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Host Status</title>
    
    <link rel="stylesheet" href="Style/Bootstrap-4/css/bootstrap.css">
    <script src="../Style/Bootstrap-4/js/bootstrap.js"></script>
    
    <style>
        
    table
        {
            border-radius: 5px;
        }
        
    .card
        {
            margin: 0 auto;
            margin-top: 20px;
        }
        
    .fixed-table
        {
            table-layout: fixed;
            /* word-wrap: break-word; */
        }
        
        
    </style>

</head>

<body>

<div class="card" style="width: 500px;">
 <br>
 <br>
 <img style="margin: 0 auto;" src="Style/Image/QuantaDyn_logo_blue_low-res.png" alt="logo">
 <br>
 <br>
 <h6 class="mx-auto"><u>Host Status Monitor</u></h6>
 <br>
 <form class="mx-auto" action="" method='POST'>
  <div class="form-group">
  <label for="net_select">Network</label>
   <select class="custom-select" name="net_select">
     <option value="#" name="#" selected>--Select Network--</option>
     <option value="192.168.115.">DAT</option>
     <option value="192.168.120.">DIS</option>
   </select>
  </div>
   <br>
   <br>
    <div class="form-group">
     <label for="net_select_custom">Custom Network</label>
     <input type="text" class="form-control" name="net_select_custom">
    </div>
    <div class="form-group">
     <label for="port">Port</label>
     <input type="text" class="form-control" name="port">
    </div>
    <br>
   <div class="form-group">
    <input class="btn btn-warning col-sm-3" type="submit" name="halt" value="Stop">
    <input class="btn btn-danger col-sm-3" type="submit" name="reset_fields" value="Reset"> 
    <input class="btn btn-success col-sm-3" type="submit" name="Submit" value="Scan"> 
   </div>
 </form>
 <hr>
 <div class="table-responsive-md" style="height: 150px;">
   <table class="table mx-auto">
    <thead class="thead-light">
     <tr>
      <th>Host</th>
      <th>Port</th>
      <th>Status</th>
     </tr>
    </thead>
    <tr>
    
    <?php

        error_reporting(0);

        set_time_limit(0);

        /*
            I -> Class C Network Address
            P -> Truncates the address to the network portion
            O -> String with network portion only
        */

        function removeOctet($network)
        {
            $truncated_segment = "";
            $i = 0;
            $j = 0;
            while($j <= 2)
            {
                if($network[$i] == '.')
                {
                    $j++;
                }
                $i++;
            }
            $truncated_segment = substr($network,0,$i);
            //
            return $truncated_segment;
        }

        if(isset($_POST['net_select_custom']))
        {
            $net_select_custom = $_POST['net_select_custom'];
            $net_segment = removeOctet($net_select_custom);
        }
        else
        {
            $net_segment = $_POST['net_select'];
        }

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

        $host_array = populateHosts($net_segment);

        $port = $_POST['port'];

        $halt = $_POST['halt'];

         /*
            I -> Receives an array of host IPV4 addresses
            P -> Attempts to communicate with each host via the specified port
            O -> Returns alive or unresponsive status
         */

        function pingHosts($host_array,$port)
        {
            $tally = 0;

            while(!isset($halt))
            {
              foreach($host_array as $h)
              {
                $s = fsockopen($h,$port,$errno,$errstr,0);
                //
                stream_set_blocking(FALSE);
                //
                if($s)
                {
                    //echo "[*] Host ".$h." alive on port->  ".$port."\n";
                    //
                    //echo "\n";
                    //
                    echo "<tr>";
                    echo "<td class='bg-success'>";
                    echo "$h";
                    echo "</td>";
                    echo "<td class='bg-success'>";
                    echo "$port";
                    echo "</td>";
                    echo "<td class='bg-success'>";
                    echo "Online";
                    echo "</td>";
                    echo "</tr>";
                    //
                    //$tally += 1;
                    //
                }
                else
                {
                    //echo "[-] Host ".$h." inactive on port-> ".$port."\n";
                    //
                    //echo "\n";
                    //
                    echo "<tr>";
                    echo "<td class='bg-danger'>";
                    echo "$h";
                    echo "</td>";
                    echo "<td class='bg-danger'>";
                    echo "$port";
                    echo "</td>";
                    echo "<td class='bg-danger'>";
                    echo "Offline";
                    echo "</td>";
                    echo "</tr>";

                }
                //
                fclose($s);
              }
              //    
              //echo "\n";
              //       
              //echo "****************************";
              //
              //echo "[+] Total Hosts-> ".$tally."\n";
              //
              //echo "****************************";
              //    
              //echo "\n";
              //
              //$tally = 0;    
            }

            sleep(60);

        }

        if(isset($_POST))
        {
           pingHosts($host_array,$port);
        }
        else if(isset($_POST['halt']))
        {
            exit(1);
        }
        else
        {
            exit(1);
        }


   ?>
          
    </tr>
   </table>
  </div>
 </div>

</body>

</html>    

