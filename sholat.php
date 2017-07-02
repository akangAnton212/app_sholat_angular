<!DOCTYPE html>
<html>
<title>Waktu Sholat Untuk Daerah Anda</title>

<!-- Kita load terlebih dahulu library bootstrap dan angular-->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src= "http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<!--End of load library-->

<style>
body{
	background:#000;
}
.main{
	
	text-align:center;
	margin-top:100px;
	
}
.main button{
	font-size:28px;
	
}
.tampil{
	font-weight:bold;
	padding:10px;
	margin-top:5px;
//	width:50%;
	text-align:center;
	color:#FF8533;
	font-size:20px;
}

.waktu{
	padding:10px;
	//border:2px solid
	
}
.waktu td{
	color:#FFFFCC;
	font-size:16px;
}
.colorgraph {
  height: 5px;
  border-top: 0;
  background: #c4e17f;
  border-radius: 5px;
  
}
</style>
<body>

<!--attribut ng-app adalah atribut yang di gunakan untuk menunjukan aplikasi
yang akan di load oleh template dan ng-controller di gunakan untuk menunjukan nama dari controller tersebut yang akan di proses pada javascript . NOTE : Javascript case sensitive, jadi saya harapkan setiap penulisan nama app dan controller harus sama persis-->
<div class="main" ng-app="sholatTimeApps" ng-controller="sholatController"> 
	<div class="alert alert-danger" ng-if="error"><!--variable ng-if ini di gunakan untuk kondisional, jika terjadi error maka akan menampilkan eror yang terjadi-->
	  <strong>Perhatian!</strong> {{error}} 
	</div>
	<button class="btn btn-info" ng-click="waktu()"><!--fungsi ng-click="waktu()" ini digunakan untuk memberikan fungsi button yang jika di click akan memanggil fungsi waktu-->
	<span class="glyphicon glyphicon-calendar"></span> Tampilkan Waktu Shalat
	</button>
	<hr class="colorgraph">
	<div class="tampil" ng-if="alamat">{{alamat}}</div><!--untuk menampilkan variabel dalam Angular selalu di tuliskan diantara double kurawal {{}}-->
	<div class="waktu" ng-if="tampil">
		<center>
			<table border="2">
			<tr>
				<td>Tanggal</td>
				<td><?php echo date('d F Y',time());?></td>
			</tr>
			<tr>
				<td>Subuh</td>
				<td>{{subuh}}</td>
			</tr>
			<tr>
				<td>Dhuhur</td>
				<td>{{dhuhur}}</td>
			</tr>
			<tr>
				<td>Asar</td>
				<td>{{asar}}</td>
			</tr>
			<tr>
				<td>Maghrib</td>
				<td>{{maghrib}}</td>
			</tr>
			<tr>
				<td>Isya</td>
				<td>{{isha}}</td>
			</tr>
		</table>	
		</center>
		
	</div>
	<hr class="colorgraph">
<div class="footer"></div>
</body>
</html>

<script>
var app = angular.module('sholatTimeApps', []);//inisiasi nama App dan juga nama module-module yang dipakai
app.controller('sholatController', function($scope, $http) {//di sini akan di deklarasikan controller

	//fungsi waktu. Semua variabel pada angular harus diletakkan pada objek $scope supaya dapat diakses pada template. Isi dari fungsi ini untuk menemukan lokasi. Jika lokasi ditemukan akan memanggil fungsi showPosition
	$scope.waktu=function(){
		if (navigator.geolocation) {
		    navigator.geolocation.getCurrentPosition($scope.showPosition);
		} else { 
			$scope.error="Lokasi tidak ditemukan. Cek koneksi Internet anda";
		    
		}	
	}
	
	$scope.showPosition=function(position){
		lat=position.coords.latitude;
		long=position.coords.longitude;
		var d = new Date();
		var n = Math.floor((new Date).getTime()/1000);
		 $http.get("http://api.aladhan.com/timings/"+n+"?latitude="+lat+"&longitude="+long+"&timezonestring=Asia/Jakarta&method=3")
		  .success(function (response) {
		  	
		  	var latlon = lat + "," + long;
		  	if(response.code == 200){
		  		$scope.tampil =true;
		  		$scope.subuh=response.data.timings.Fajr;
		  		$scope.dhuhur=response.data.timings.Dhuhr;
		  		$scope.asar=response.data.timings.Asr;
		  		$scope.maghrib=response.data.timings.Maghrib;
		  		$scope.isha=response.data.timings.Isha;
		  		
		  	
		  	}
		  	//fungsi dari API Google untuk menampilkan lokasi kita
		  	$http.get("http://maps.googleapis.com/maps/api/geocode/json?latlng="+latlon+"&sensor=true")
		  	.success(function (address){
		  		$scope.alamat='Lokasi Anda: '+address.results[0].formatted_address;
		  			  	})  
		  });
		
	}
 
});
</script>
