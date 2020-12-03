<?php

$servername = "localhost";

$username = "s190217m_mytechbus";

$password = "x^B,wzY;X1On";

$db = "s190217m_mytechbus";



// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully";

// get data
$route_code="R-601";
$operator_id="6";
$vehicle_type="ORDINARY";

$fareQuery= " SELECT
fare_full,
fare_half,
fare_luggage,
vehicle_type
FROM
vehicle_fare_master
WHERE
operator_id='$operator_id'
AND
vehicle_type='$vehicle_type'; ";

$routeQuery = "SELECT 
row_id,
route_code,
route_start_stage,
route_end_stage,
route_type,
route_stop_count,
route_status,
operator_id
FROM
master_routes
WHERE
route_code='$route_code'
AND
route_status='ACTIVE'
AND
operator_id='$operator_id'";





$route_start_stage="";
$route_end_stage="";
$route_type="";

$fare_full=0;
$fare_half=0;
$fare_luggage=0;



// FARE
//$query = "SELECT * FROM teacher WHERE tremail=? and trpasssword=?";
//mysqli_stmt_bind_param($query, 'ss', $_POST['email'], $_POST['password']);
$result = mysqli_query($conn, $fareQuery) or die(mysqli_error($conn));
$flag = FALSE;
echo ("<table> <tr> <td>Full</td> <td>Half</td> <td>Luggage</td> <td>Vehicle</td> </tr>" );	
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) 
{    
	//echo ($row['stage_code']); 
	 echo ("<tr> <td>".$row['fare_full']."</td> <td>".$row['fare_half']."</td> <td>".$row['fare_luggage']."</td> <td>".$row['vehicle_type']."</td> </tr>");  	
	 $fare_full=$row['fare_full'];
     $fare_half=$row['fare_half'];
     $fare_luggage=$row['fare_luggage'];
}
echo ("</table> <hr />");


//$route_code="R-003";
//$operator_id="1";
//$vehicle_type="ORDINARY";

//Array for Matrix
$route_id = 0;
$startRoutArray = [];
$endRoutArray = [];

//ROUTE
$result = mysqli_query($conn, $routeQuery) or die(mysqli_error($conn));
$flag = FALSE;
echo ("<table> <tr> <td>Route ID</td> <td>Route Code</td> <td>Route Type</td> <td>Start Stage</td> <td>End Stage</td> <td>Operator</td> </tr>" );
$index=0;
$routlist = array();	
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) 
{    
	//echo ($row['stage_code']); 
	 echo ("<tr> <td>".$row['row_id']."</td> <td>".$row['route_code']."</td> <td>".$row['route_type']."</td> <td>".$row['route_start_stage']."</td> <td>".$row['route_end_stage']."</td> <td>".$row['operator_id']."</td> </tr>");

	 $route_id = $row['row_id'];
	 $route_type= $row['route_type'];
	 $route_start_stage = $row['route_start_stage'];
	 $route_end_stage = $row['route_end_stage'];
}
echo ("</table> <hr />");


$routeStageQuery = "SELECT 

route_id,

stage_no,

stage_code,

stage_km,

stage_status

FROM

route_stages

WHERE

route_id='$route_id'

AND

stage_status='ACTIVE'

ORDER BY stage_no;";

//ROUTE STAGE
//$query = "SELECT * FROM teacher WHERE tremail=? and trpasssword=?";
//mysqli_stmt_bind_param($query, 'ss', $_POST['email'], $_POST['password']);
$result = mysqli_query($conn, $routeStageQuery) or die(mysqli_error($conn));
$flag = FALSE;
echo ("<table> <tr> <td>Route Code</td> <td>Stage No</td> <td>Stage Code</td> <td>Stage KM</td> </tr>" );
$index=0;
$routlist = array();	
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) 
{    
	//echo ($row['stage_code']); 
	 echo ("<tr> <td>".$row['route_code']."</td> <td>".$row['stage_no']."</td> <td>".$row['stage_code']."</td> <td>".$row['stage_km']."</td> </tr>");
     //$startRoutArray = [stage_no] [stage_km];	
	 $routarr = array("stage_no"=>$row['stage_no'], "stage_code"=>$row['stage_code'], "stage_km"=>$row['stage_km']);	
	 array_push($routlist,$routarr);
}
echo ("</table> <hr />");


/*$routlist = array
  (
  array("Volvo",22,18),
  array("BMW",15,13),
  array("Saab",5,2),
  array("Land Rover",17,15)
  );
*/


///////////////////  GENERATE MATRIX /////////////////////////
if($route_type=="TWOWAY")
{

	$journey_type="ONWARD";
	//$routlistrev = $routlist;
	$arrlength = count($routlist);
	$rcount = 0;
	$insertsql = "INSERT INTO `route_fare_matrix`(`route_code`, `route_start_stage`, `route_end_stage`, `start_stage_code`, `end_stage_code`, `journey_type`, `fare_km`, `fare_full`, `fare_half`, `fare_luggage`, `vehicle_type`, `operator_id`) VALUES ";

	for($x = 0; $x < $arrlength; $x++) {
		$fromstage = $routlist[$x]['stage_code'];
		echo ("<table> <tr> <td>".$fromstage."</td> </tr>" ); 
		echo ("<tr>");
		for($y = $x+1; $y < $arrlength; $y++) {
			$rcount ++;
			$tostage = $routlist[$y]['stage_code'];
			//echo ("<td>".$tostage." <br />" );
			$distance = $routlist[$y]['stage_km']-$routlist[$x]['stage_km'];
			$full_fare_val = $fare_full*$distance;
			$half_fare_val = $fare_half*$distance;
			$luggage_fare_val = $fare_luggage*$distance;
			
			// convet to positive value
			$distance = abs($distance);
			$full_fare_val = abs($full_fare_val);
			$half_fare_val = abs($half_fare_val);
			$luggage_fare_val = abs($luggage_fare_val);
			
			//echo (" ".$distance." <br />" );
			//echo (" fare_full= ".full_fare_val." <br />" );
			//echo (" fare_half= ".$half_fare_val." <br />" );
			//echo (" fare_luggage= ".$luggage_fare_val." <br />" );
			//echo (" </td>" );
			if($rcount % 200 == 0)
			{
				$insertsql .= "('$route_code','". $route_start_stage ."','". $route_end_stage ."','" .$fromstage. "','" .$tostage. "','". $journey_type ."','" .$distance. "','" .$full_fare_val. "','" .$half_fare_val. "','" .$luggage_fare_val. "','" .$vehicle_type. "','" .$operator_id. "');  <br /><br />" ;
				$insertsql .= "INSERT INTO `route_fare_matrix`(`route_code`, `route_start_stage`, `route_end_stage`, `start_stage_code`, `end_stage_code`, `journey_type`, `fare_km`, `fare_full`, `fare_half`, `fare_luggage`, `vehicle_type`, `operator_id`) VALUES ";
			}else
			{
				$insertsql .= "<br /> ('$route_code','". $route_start_stage ."','". $route_end_stage ."','" .$fromstage. "','" .$tostage. "','". $journey_type ."','" .$distance. "','" .$full_fare_val. "','" .$half_fare_val. "','" .$luggage_fare_val. "','" .$vehicle_type. "','" .$operator_id. "'),  " ;
			}
		
		}
		echo ("<tr> </table>");
		
		echo ("<hr />");
	}

	echo($insertsql);

	
	$revroutlist = array_reverse($routlist);
	$journey_type="RETURN";
	$insertsql = "INSERT INTO `route_fare_matrix`(`route_code`, `route_start_stage`, `route_end_stage`, `start_stage_code`, `end_stage_code`, `journey_type`, `fare_km`, `fare_full`, `fare_half`, `fare_luggage`, `vehicle_type`, `operator_id`) VALUES ";

	for($x = 0; $x < $arrlength; $x++) {
		$fromstage = $revroutlist[$x]['stage_code'];
		echo ("<table> <tr> <td>".$fromstage."</td> </tr>" ); 
		echo ("<tr>");
		for($y = $x+1; $y < $arrlength; $y++) {
			$rcount ++;
			$tostage = $revroutlist[$y]['stage_code'];
			//echo ("<td>".$tostage." <br />" );
			$distance = $revroutlist[$y]['stage_km']-$revroutlist[$x]['stage_km'];
			$full_fare_val = $fare_full*$distance;
			$half_fare_val = $fare_half*$distance;
			$luggage_fare_val = $fare_luggage*$distance;
			
			// convet to positive value
			$distance = abs($distance);
			$full_fare_val = abs($full_fare_val);
			$half_fare_val = abs($half_fare_val);
			$luggage_fare_val = abs($luggage_fare_val);
			
			//echo (" ".$distance." <br />" );
			//echo (" fare_full= ".full_fare_val." <br />" );
			//echo (" fare_half= ".$half_fare_val." <br />" );
			//echo (" fare_luggage= ".$luggage_fare_val." <br />" );
			//echo (" </td>" );
			if($rcount % 200 == 0)
			{
				$insertsql .= "('$route_code','". $route_start_stage ."','". $route_end_stage ."','" .$fromstage. "','" .$tostage. "','". $journey_type ."','" .$distance. "','" .$full_fare_val. "','" .$half_fare_val. "','" .$luggage_fare_val. "','" .$vehicle_type. "','" .$operator_id. "');  <br /><br />" ;
				$insertsql .= "INSERT INTO `route_fare_matrix`(`route_code`, `route_start_stage`, `route_end_stage`, `start_stage_code`, `end_stage_code`, `journey_type`, `fare_km`, `fare_full`, `fare_half`, `fare_luggage`, `vehicle_type`, `operator_id`) VALUES ";
			}else
			{
				$insertsql .= "<br /> ('$route_code','". $route_start_stage ."','". $route_end_stage ."','" .$fromstage. "','" .$tostage. "','". $journey_type ."','" .$distance. "','" .$full_fare_val. "','" .$half_fare_val. "','" .$luggage_fare_val. "','" .$vehicle_type. "','" .$operator_id. "'),  " ;
			}
		
		}
		echo ("<tr> </table>");
		
		echo ("<hr />");
	}

	echo($insertsql);
	
}else
{
	$journey_type="CIRCULAR";
	//$routlistrev = $routlist;
	$arrlength = count($routlist);
	$rcount = 0;
	$insertsql = "INSERT INTO `route_fare_matrix`(`route_code`, `route_start_stage`, `route_end_stage`, `start_stage_code`, `end_stage_code`, `journey_type`, `fare_km`, `fare_full`, `fare_half`, `fare_luggage`, `vehicle_type`, `operator_id`) VALUES ";

	for($x = 0; $x < $arrlength; $x++) {
		$fromstage = $routlist[$x]['stage_code'];
		echo ("<table> <tr> <td>".$fromstage."</td> </tr>" ); 
		echo ("<tr>");
		for($y = $x+1; $y < $arrlength; $y++) {
			$rcount ++;
			$tostage = $routlist[$y]['stage_code'];
			//echo ("<td>".$tostage." <br />" );
			$distance = $routlist[$y]['stage_km']-$routlist[$x]['stage_km'];
			$full_fare_val = $fare_full*$distance;
			$half_fare_val = $fare_half*$distance;
			$luggage_fare_val = $fare_luggage*$distance;
			//echo (" ".$distance." <br />" );
			//echo (" fare_full= ".full_fare_val." <br />" );
			//echo (" fare_half= ".$half_fare_val." <br />" );
			//echo (" fare_luggage= ".$luggage_fare_val." <br />" );
			//echo (" </td>" );
			if($rcount % 200 == 0)
			{
				$insertsql .= "('$route_code','". $route_start_stage ."','". $route_end_stage ."','" .$fromstage. "','" .$tostage. "','". $journey_type ."','" .$distance. "','" .$full_fare_val. "','" .$half_fare_val. "','" .$luggage_fare_val. "','" .$vehicle_type. "','" .$operator_id. "');  <br /><br />" ;
				$insertsql .= "INSERT INTO `route_fare_matrix`(`route_code`, `route_start_stage`, `route_end_stage`, `start_stage_code`, `end_stage_code`, `journey_type`, `fare_km`, `fare_full`, `fare_half`, `fare_luggage`, `vehicle_type`, `operator_id`) VALUES ";
			}else
			{
				$insertsql .= "<br /> ('$route_code','". $route_start_stage ."','". $route_end_stage ."','" .$fromstage. "','" .$tostage. "','". $journey_type ."','" .$distance. "','" .$full_fare_val. "','" .$half_fare_val. "','" .$luggage_fare_val. "','" .$vehicle_type. "','" .$operator_id. "'),  " ;
			}
		
		}
		echo ("<tr> </table>");
		
		echo ("<hr />");
	}

	echo($insertsql);
}


echo ("<hr /> <h3> Row Count ".$rcount."</h3>");

//echo( $routlist[0]['stage_code'] );


//$matrix = Matrix::fromFlatArray($flatArray);
//echo (" Matrix <hr />");

//echo( $matrix->getRows() ); // rows count
//echo( $matrix->getColumns() ); // columns count
//echo( $matrix->getColumnValues($column=4) ); // get values from given column

$conn->close();