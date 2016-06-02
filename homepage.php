<?php
/*
*kamrul hasan , cse , dhaka university
*/
/*
*this project is for showing rout  
*if you want to journey , it will be help full
*this project only included some data on dhaka city
*/
include 'data_access.php';//this file page has some function that will access the database
$rt=new dbhelper();
$node = $rt->get_distinct_stopies();

        
   
?>

<html>
    <head>
     
    <style>
    input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius:12px;
    box-sizing: border-box;
}

 input[type=date], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
input[type=password], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    
}
input [type=radio]{
    
}

input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}    
    div {
        margin-left: 10cm;
        margin-right: 10cm;
        margin-top: 2cm;
        border-radius: 5px;
        background-color: whitesmoke;
        padding: 40px;
    }
    </style>
     </head>
    <body background="images/map.png">
        
                <div>
	            <h1><font color="blue">Select Your Choice of Journey</font></h1>
        
        	        <form  action="map.php" method="post"><!-- this page will show the result by indicating the map -->
                	    from<select name="node1"  >
                               <?php 
                               for($i=0;$i<count($node);$i++){ ?>
                                  <option value="<?php echo $node[$i]; ?>"> <?php echo $node[$i]; ?> </option>

                            <  <?php } ?>
                              </select>

                            to<select name="node2">
                               <?php 
                               for($i=0;$i<count($node);$i++){     
                                  ?>
                                 <option value="<?php echo $node[$i]; ?>"> <?php echo $node[$i]; ?> </option>
                                  <?php

                                  }

                                  ?>
                              </select><br>
                            time of journay:<br>
                            <input type="time" name="schedule" required >   <br>  <br>                

                            save:<br>
                            <input type ="checkbox" name="distance" value="distance">Minimam Distance<br>
                            <input type ="checkbox" name="cost" value="cost">Minimam Cost<br>
                            <input type ="checkbox" name="time" value="time">Shortest time<br>
                            <input type ="checkbox" name="jam" value="jam">Avoid jam<br>
                            <input type ="checkbox" name="jam" value="jam">Avoid School breaking time<br>
                            <br>
                          
                            
                           <input type="submit" value="submit" name="submit">

                          <br>

                   </form>
           </div>
    </body>
    
</html>


