<?php
    include '../config/koneksi.php'; 

    // Check connection
    if ($con -> connect_error) {
      die("Connection failed: " . $con->connect_error);
    }

    $bulan_ini = "%20".date("y-m")."%";
	$hari_ini = "%20".date("y-m-d")."%";
	
	//Membuat Array Kosong
	$master_data = array();
	$daily_check = array();
	$location_check = array();
	
	
	$location = 0;
	$security = 0;
	
	$aman = 0;
	$temuan = 0;
	$insiden = 0;
	$tamu = 0;
	$checking = 0;
	$tag_location = "";
	

	//Membuat SQL Query
	$sql = "SELECT * FROM subarea_list WHERE status = '1'";
	//Mendapatkan Hasil
	$r = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($r)){
		//Memasukkan Nama dan ID kedalam Array Kosong yang telah dibuat
		
		$location++;
		
		//Membuat SQL Query
		$sql2 = "SELECT * FROM tour_report WHERE (id_subarea = '".$row['id']."' AND create_date LIKE '%$hari_ini%')";
		//Mendapatkan Hasil
		$r2 = mysqli_query($con,$sql2); $checking=0;
		while($row2 = mysqli_fetch_array($r2)){
			$checking++;
		}
		
		array_push($location_check,array(
			"location"=>$row['subarea'],
			"checking"=>$checking,
			"tag_location"=>$tag_location,
		));
	}
	
	//Membuat SQL Query
	$sql = "SELECT * FROM tour_report WHERE create_date LIKE '%$hari_ini%'";
	//Mendapatkan Hasil
	$r = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($r)){
		//Memasukkan Nama dan ID kedalam Array Kosong yang telah dibuat
		
		if($row['id_kondisi']=="1"){
			$aman++;
		}if($row['id_kondisi']=="2"){
			$temuan++;
		}else{
			$insiden++;
		}
	}
	
	//Membuat SQL Query
	$sql = "SELECT * FROM user_list WHERE id_level = '4'";
	//Mendapatkan Hasil
	$r = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($r)){
		//Memasukkan Nama dan ID kedalam Array Kosong yang telah dibuat
		$security++;
	}
	
	//=======================================================================================================================================
	array_push($master_data,array(
		"location"=>$location,
		"security"=>$security,
	));
	
	array_push($daily_check,array(
		"aman"=>$aman,
		"temuan"=>$temuan,
		"insiden"=>$insiden,
		"tamu"=>$tamu,
	));
	array_push($location_check,array(
		"location"=>"jancok",
		"checking"=>0,
		"tag_location"=>"jancok",
	));
	array_push($location_check,array(
		"location"=>"jancok",
		"checking"=>1,
		"tag_location"=>"jancok",
	));
	array_push($location_check,array(
		"location"=>"jancok",
		"checking"=>1,
		"tag_location"=>"jancok",
	));
	// array_push($location_check,array(
	// 	"location"=>"jancok",
	// 	"checking"=>1,
	// 	"tag_location"=>"jancok",
	// ));
	// array_push($location_check,array(
	// 	"location"=>"jancok",
	// 	"checking"=>1,
	// 	"tag_location"=>"jancok",
	// ));
	// array_push($location_check,array(
	// 	"location"=>"jancok",
	// 	"checking"=>0,
	// 	"tag_location"=>"jancok",
	// ));
	// array_push($location_check,array(
	// 	"location"=>"jancok",
	// 	"checking"=>0,
	// 	"tag_location"=>"jancok",
	// ));
	// array_push($location_check,array(
	// 	"location"=>"jancok",
	// 	"checking"=>0,
	// 	"tag_location"=>"jancok",
	// ));
	
	
	$data = json_encode(array('master_data'=>$master_data,'daily_check'=>$daily_check,'location_check'=>$location_check));
    // rubah data json ke array
    $data_array = json_decode($data, true);
    //print_r($data_array['location_check']);
	//echo "</br>". count($data_array['location_check']);

	mysqli_close($con);