<?php

/*
*this file can access database for appropriate data
*/
class dbhelper{
         
        private $mysqli;
	private $servername = "localhost";
	private $username = "root";
	private $password = "";
	
	public function __construct() {
		$this->mysqli = new mysqli($this->servername,"root","","route");

        }
        function get_all_stopies(){
            $query = "SELECT  node1,node2 FROM route ORDER BY node1  ";
            $result = $this->mysqli->query($query);
            
		 $xml = '<item>';
                 while($row = $result->fetch_assoc()){
                    $xml = $xml.'<route>';
			foreach($row as $key=>$val){
                            $xml=$xml."<$key>$val</$key>";
                        }
                    $xml = $xml.'</route>';
	
                 }
                 $xml = $xml.'</item>';
                 $xml=simplexml_load_string($xml) or die("Error: Cannot create object");
            return $xml;               
       
        }
        
        function get_distinct_stopies(){
        
                $query = "SELECT DISTINCT node1 FROM route ORDER BY node1";
                $result = $this->mysqli->query($query);
                   
                
                $i=0;
		 
                 while($row = $result->fetch_assoc()){
                    
			foreach($row as $key=>$val){
				
                                $node1[$i]=$val;
                                $flag[$val]=0;
                                $i++;
                                
                        }
                    
	
                 }
                  
                   
                  
                 $query = "SELECT DISTINCT node2 FROM route ORDER BY node1 ";
                $result = $this->mysqli->query($query);
                while($row = $result->fetch_assoc()){
                    
			foreach($row as $key=>$val){
				if(!isset($flag[$val])){
                                    $node1[$i]=$val;
                                    $flag[$val]=0;
                                    $i++;
                                }
                                
                        }
                    
	
                 }
                 return $node1;
            
        
          }
              

	/*
	 *dikjtra() function will calculate the path using bfs  and relaxing algirithm 
	*at last , we also show the result making table
	*/  
        
        function find_route($source,$destination,$min_dist,$min_time,$min_cost,$no_jam,$hour){
            
                 
                $query = "SELECT DISTINCT node1 FROM route ";
                $result = $this->mysqli->query($query);
                   
                
                $i=0;
		 
                 while($row = $result->fetch_assoc()){
                    
			foreach($row as $key=>$val){
				
                                $node1[$i]=$val;
                                $flag[$val]=0;
                                $i++;
                                
                        }
                    
	
                 }
                  
                   
                  
                 $query = "SELECT DISTINCT node2 FROM route ";
                $result = $this->mysqli->query($query);
                while($row = $result->fetch_assoc()){
                    
			foreach($row as $key=>$val){
				if(!isset($flag[$val])){
                                    $node1[$i]=$val;
                                    $flag[$val]=0;
                                    $i++;
                                }
                                
                        }
                    
	
                 }
                 
                
                 for($i=0;$i<count($node1);$i++){
                     for($j=0;$j<count($node1);$j++){
                         $array[$i][$j] = 0;
                     }
                 }
                $query = "SELECT node1 FROM route ";
                $result = $this->mysqli->query($query);
                $i=0;
                while($row = $result->fetch_assoc()){
			foreach($row as $key=>$val){	
                                    $from[$i]=$val;
                                       $i++;                               
                        }
                    
	
                 }
                $query = "SELECT node2 FROM route ";
                $result = $this->mysqli->query($query);
                $i=0;
                while($row = $result->fetch_assoc()){
			foreach($row as $key=>$val){	
                                    $to[$i]=$val;
                                       $i++;                               
                        }
                    
	
                 }
                 
                $query = "SELECT fair FROM route ";
                $result = $this->mysqli->query($query);
                $i=0;
                while($row = $result->fetch_assoc()){
			foreach($row as $key=>$val){	
                                    $fair[$i]=$val;
                                       $i++;                               
                        }
                    
	
                 }     
                
               $query = "SELECT time FROM route ";
                $result = $this->mysqli->query($query);
                $i=0;
                while($row = $result->fetch_assoc()){
			foreach($row as $key=>$val){	
                                    $time[$i]=$val;
                                       $i++;                               
                        }
                    
	
                 }
                 
                
                  $query = "SELECT distance FROM route ";
                $result = $this->mysqli->query($query);
                $i=0;
                while($row = $result->fetch_assoc()){
			foreach($row as $key=>$val){	
                                    $distance[$i]=$val;
                                       $i++;                               
                        }
                    
	
                 }
                 
                 
                 
                 
                 $query = "SELECT jamcreator FROM route ";
                $result = $this->mysqli->query($query);
                $i=0;
                while($row = $result->fetch_assoc()){
			foreach($row as $key=>$val){	
                                    $jam[$i]=$val;
                                       $i++;                               
                        }
                    
	
                 }
                
                $query = "SELECT bus_name FROM route ";
                $result = $this->mysqli->query($query);
                $i=0;
                while($row = $result->fetch_assoc()){
			foreach($row as $key=>$val){	
                                    $bus_name[$i]=$val;
                                       $i++;                               
                        }
                    
	
                 }
                for($i=0;$i<count($node1);$i++){                    
                    $d[$node1[$i]] = 9999;
                }
                $d[$source] = 0;
                $way[$source] = "";
                for($i=0;$i<count($node1);$i++){
                  if($node1[$i]==$source){
                      $node1[$i]=$node1[0];
                      $node1[0] = $source;                      
                  }
                }
               
                $i=0;
                $k=0;
                $queuee[$i]=$source;               
               
                while (isset($queuee[$k])){
                    $u=$queuee[$k];
                    for($j=0;$j<count($from);$j++){
                        if($from[$j]==$u){
                            $v = $to[$j];
                            $value = 0;$l=0;
                            
                            if($min_dist!='null'){
                                $value = $value + $distance[$j];
                                $l++;
                            }
                            if($time!='null'){
                                 
                                $value = $value + $time[$j];
                                if($jam[$j]!="null" && ($hour>=3 && $hour<=5) ){
                                    $value = $value+30;
                                   
                                }
                                $l++;
                            }
                            if( $no_jam != 'null'){
                                 if($jam[$j]=="school" && ($hour>=3 && $hour<=5) ){
                                    $value = $value+30;
                                    
                                }
                                if($jam[$j] == 'jam'){
                                    $value = $value+30;
                                }
                                $l++;
                            }
                            
                            if($min_cost != 'null'){
                                $value = $value+$fair[$j];
                                $l++;
                            }
                            if($l==0){
                                $value=$distance[$j];
                            }
                            $alt = $d[$u]+$value;
                            if($alt<$d[$v]){
                                $d[$v] = $alt;
                                $prev[$v] = $u;
                                $way[$v] = $bus_name[$j];
                                $stack_dist[$v] = $distance[$j];;
                                $stack_time[$v] = $time[$j];
                                $stack_cost[$v] = $fair[$j];
                                $i++;
                                $queuee[$i]=$v;
                                
                            }
                        }
                        elseif($to[$j]==$u){
                            $v = $from[$j];
                           $value = 0;$l=0;
                            if($min_dist!='null'){
                                $value = $value + $distance[$j];
                                $l++;
                            }
                            if($time!='null'){
                                $value = $value + $time[$j];
                                $l++;
                            }
                            if($min_cost != 'null'){
                                $value = $value+$fair[$j];
                                $l++;
                            }
                            if($l==0){
                                $value=$value+$distance[$j]+$time[$j]+$fair[$j];
                            }
                            $alt = $d[$u]+$value;                            
                            
                            
                            if($alt<$d[$v]){
                                $d[$v] = $alt;
                                $prev[$v] = $u;
                                $way[$v] = $bus_name[$j];
                                 $stack_dist[$v] = $distance[$j];;
                                $stack_time[$v] = $time[$j];
                                $stack_cost[$v] = $fair[$j];                               
                                $i++;
                                $queuee[$i]=$v;
                            }
                        }
                    }
                    $k++;
                }
               // print_r($queuee);
               //print_r($prev);
               // print_r($way);
                $v=$destination;
                $j=0;
                $stack_stopies[$j]=$v;
                
                $stack_bus[$j]=$way[$v];
                $j++;
               
                while($v!=$source){                  
                    $v = $prev[$v];
                    $stack_stopies[$j]=$v;
                    
                    $stack_bus[$j]=$way[$v];
                    $j++;
                }
                
                $total_distance = 0;
                $total_cost = 0;
                $total_time = 0;
                
                for($j= count($stack_stopies)-1;$j>0;$j--){
                    //echo '<font color=blue>'.$stack_stopies[$j]."->". $stack_stopies[$j-1].'</font>'."<font color=orange size=25px> by </font>".'<font color=blue>'.$stack_bus[$j-1].'</font>'.' distance: '.$stack_dist[$stack_stopies[$j-1]].'km  time: '.$stack_time[$stack_stopies[$j-1]].'min  cost: '.$stack_cost[$stack_stopies[$j-1]].'tk<br>';
                    $total_distance = $total_distance+$stack_dist[$stack_stopies[$j-1]];
                    $total_cost =$total_cost + $stack_cost[$stack_stopies[$j-1]];
                    $total_time = $total_time + $stack_time[$stack_stopies[$j-1]];
                    
                }
                
                //echo 'total distance: '.$total_distance.'km   total time: '.$total_time.'minuite   total cost: '.$total_cost.'taka ';
                
                ?>
<html> 
    <head>
        <style>
            
        table#t01 tr:nth-child(even) {
            background-color: #eee;
        }
        table#t01 tr:nth-child(odd) {
           background-color:#fff;
        }
        table#t01 th	{
            background-color: black;
            color: white;
        }

        </style>
    </head>
    <body>
          <table id="t01" style="width:50%">
                <tr>
                  <th>From</th>
                  <th>To</th>		
                  <th>By</th>
                  <th>Distance</th>
                  <th>Cost</th>
                  <th>Time</th>

                </tr>
                <?php  for($j= count($stack_stopies)-1;$j>0;$j--){ ?>
                    
                    <tr>
                        <td><?php echo $stack_stopies[$j];?></td>
                        <td><?php echo $stack_stopies[$j-1];?></td>		
                        <td><?php echo $stack_bus[$j-1];?> </td>
                        <td><?php echo $stack_dist[$stack_stopies[$j-1]].' km';?></td>
                        <td><?php echo $stack_cost[$stack_stopies[$j-1]].' taka';?></td>
                        <td><?php echo $stack_time[$stack_stopies[$j-1]].' minuite';?></td>
                        
                 </tr>
                <?php } ?>
                 <tr>
                     <td><?php echo '<font color=blue size=4px>'.$source.'</font>' ;?></td>
                     <td><?php echo '<font color=blue size=4px>'.$destination.'</font>' ;?></td>
                     <td></td>
                     <td><?php echo '<font color=blue >'.'total= '.$total_distance.' km'.'</font>';?></td>
                     <td><?php echo '<font color=blue >'.'total= '.$total_cost.' taka'.'</font>';?></td>
                     <td><?php echo '<font color=blue >'.'total= '.$total_time.' minuite'.'</font';?></td>
                 </tr>
                
            </table>
    </body>

</html>
                
                
                <?php
                
                       
                        
                
            
        }
         
}
