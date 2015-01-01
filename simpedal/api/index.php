<?php 
require 'Slim/Slim.php';
require 'functions.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/tdp/all', function (){
	fetchAll("SELECT * FROM jenis,tdp,sektor WHERE jenis.NOMENKLATUR = tdp.KEGIATAN AND sektor.ID = tdp.ID_SEKTOR");
});

$app->get('/cari/:by/:key', function ($by,$key){
	fetchAll("SELECT * FROM jenis,tdp,sektor WHERE jenis.NOMENKLATUR = tdp.KEGIATAN AND sektor.ID = tdp.ID_SEKTOR AND $by LIKE '%$key%'");
});

$app->get('/tdp/map', function (){
	fetchAll("SELECT NO, KOORDINAT, ID_WARNA FROM tdp,sektor WHERE sektor.ID = tdp.ID_SEKTOR");
});

$app->get('/tdp/mapcari/:by/:key', function ($by, $key){
	fetchAll("SELECT NO, KOORDINAT, ID_WARNA FROM tdp,sektor WHERE sektor.ID = tdp.ID_SEKTOR AND $by LIKE '%$key%'");
});

$app->get('/tdp/jenis', function (){
	fetchAll("SELECT * FROM jenis");
});

$app->get('/tdp/kelurahan', function (){
	fetchAll("SELECT DISTINCT KELURAHAN FROM tdp");
});

$app->get('/tdp/kecamatan', function (){
	fetchAll("SELECT DISTINCT KECAMATAN FROM tdp");
});

$app->get('/tdp/sektor', function (){
	$aa = fetchRet("SELECT DISTINCT * FROM sektor ORDER BY ID");
	echo json_encode($aa, JSON_NUMERIC_CHECK);
});

$app->get('/tdp/:id', function ($id){
	fetchAll("SELECT * FROM jenis,tdp,sektor WHERE jenis.NOMENKLATUR = tdp.KEGIATAN AND sektor.ID = tdp.ID_SEKTOR AND tdp.NO = $id");
});
$app->get('/chartdata/:sektor', function ($sektor){
	$kecamatan = fetchRet("SELECT DISTINCT KECAMATAN FROM tdp");

	$hasil = array();

	foreach ($kecamatan as $key => $value) {
		$aaa = fetchRet("SELECT DISTINCT * FROM tdp WHERE ID_SEKTOR = '$sektor' AND KECAMATAN = '$value->KECAMATAN'");
		$jumlah = sizeof($aaa);
		$hasil[$key] = array($value->KECAMATAN , $jumlah);
		
	}
	echo json_encode($hasil);
});

$app->get('/chartdata2/:kecamatan', function ($kecamatan){
	$sektor = fetchRet("SELECT DISTINCT * FROM sektor");

	$hasil = array();

	foreach ($sektor as $key => $value) {
		$aaa = fetchRet("SELECT DISTINCT * FROM tdp WHERE ID_SEKTOR = '$value->ID' AND KECAMATAN = '$kecamatan'");
		$jumlah = sizeof($aaa);
		$hasil[$key] = array($value->NAMA_SEKTOR , $jumlah);	
	}

	echo json_encode($hasil);
});

$app->get('/laporankegiatan/:nomenklatur', function($nomenklatur){
	fetchAll("SELECT DISTINCT * FROM jenis,tdp,sektor WHERE jenis.NOMENKLATUR = tdp.KEGIATAN AND sektor.ID = tdp.ID_SEKTOR AND jenis.NOMENKLATUR = '$nomenklatur'");
});

$app->get('/laporankelurahan/:kelurahan', function($kelurahan){
	fetchAll("SELECT DISTINCT * FROM jenis,tdp,sektor WHERE jenis.NOMENKLATUR = tdp.KEGIATAN AND sektor.ID = tdp.ID_SEKTOR AND tdp.KELURAHAN = '$kelurahan'");
});
$app->get('/laporankecamatan/:kecamatan', function($kecamatan){
	fetchAll("SELECT DISTINCT * FROM jenis,tdp,sektor WHERE jenis.NOMENKLATUR = tdp.KEGIATAN AND sektor.ID = tdp.ID_SEKTOR AND tdp.KECAMATAN = '$kecamatan'");
});
$app->get('/laporansektor/:sektor', function($sektor){
	fetchAll("SELECT DISTINCT * FROM jenis,tdp,sektor WHERE jenis.NOMENKLATUR = tdp.KEGIATAN AND sektor.ID = tdp.ID_SEKTOR AND sektor.ID = '$sektor'");
});

$app->get('/laporansektor2/:sektor/:kecamatan', function($sektor , $kecamatan){
	fetchAll("SELECT DISTINCT * FROM jenis,tdp,sektor WHERE jenis.NOMENKLATUR = tdp.KEGIATAN AND sektor.ID = tdp.ID_SEKTOR AND sektor.ID = '$sektor' AND tdp.KECAMATAN = '$kecamatan'");
});

$app->post('/add', function () use ($app){

	echo "process";

	$NO_SK = $_POST['NO_SK'];
	$TGL_SK = $_POST['TGL_SK'];
	$TGL_BERLAKU = $_POST['TGL_BERLAKU'];
	$JENIS_PENDAFTARAN = $_POST['JENIS_PENDAFTARAN'];
	$NAMA_USAHA = $_POST['NAMA_USAHA'];
	$ALAMAT_USAHA = $_POST['ALAMAT_USAHA'];
	$KELURAHAN = $_POST['KELURAHAN'];
	$KECAMATAN = $_POST['KECAMATAN'];
	$TELP = $_POST['TELP'];
	$PEMILIK = $_POST['PEMILIK'];
	$MODAL = $_POST['MODAL'];
	$KEGIATAN = $_POST['KEGIATAN'];
	$TENAGA_KERJA = $_POST['TENAGA_KERJA'];
	$FASILITAS = $_POST['FASILITAS'];
	$KOORDINAT = $_POST['KOORDINAT'];
	$ID_SEKTOR = $_POST['ID_SEKTOR'];
	$KETERANGAN = $_POST['KETERANGAN'];

	mkdir('../asset/'.$NO_SK);

	$FOTO_1 = $NO_SK.'/'.$_FILES['FOTO_1']['name'];
	$FOTO_2 = $NO_SK.'/'.$_FILES['FOTO_2']['name'];
	$FOTO_3 = $NO_SK.'/'.$_FILES['FOTO_3']['name'];
	$FOTO_4 = $NO_SK.'/'.$_FILES['FOTO_4']['name'];

	move_uploaded_file($_FILES["FOTO_1"]["tmp_name"] , "../asset/".$FOTO_1);
	move_uploaded_file($_FILES["FOTO_2"]["tmp_name"] , "../asset/".$FOTO_2);
	move_uploaded_file($_FILES["FOTO_3"]["tmp_name"] , "../asset/".$FOTO_3);
	move_uploaded_file($_FILES["FOTO_4"]["tmp_name"] , "../asset/".$FOTO_4);


	executeSql(
		"INSERT INTO tdp (
			NO_SK, TGL_SK, TGL_BERLAKU, JENIS_PENDAFTARAN, NAMA_USAHA, ALAMAT_USAHA, KELURAHAN, KECAMATAN, TELP, PEMILIK, MODAL, KEGIATAN, TENAGA_KERJA, FASILITAS, KOORDINAT, FOTO_1, FOTO_2, FOTO_3, FOTO_4, ID_SEKTOR, KETERANGAN) 
		VALUES 
			('$NO_SK', '$TGL_SK', '$TGL_BERLAKU', '$JENIS_PENDAFTARAN', '$NAMA_USAHA', '$ALAMAT_USAHA', '$KELURAHAN', '$KECAMATAN', '$TELP', '$PEMILIK', '$MODAL', '$KEGIATAN', '$TENAGA_KERJA', '$FASILITAS', '$KOORDINAT', '$FOTO_1', '$FOTO_2', '$FOTO_3', '$FOTO_4', '$ID_SEKTOR', '$KETERANGAN')"
	);
	$app->redirect('../#/tdp');
});

$app->post('/tdp/edit', function () use ($app){
	
	$NO = $_POST['NO'];
	$NO_SK = $_POST['NO_SK'];
	$TGL_SK = $_POST['TGL_SK'];
	$TGL_BERLAKU = $_POST['TGL_BERLAKU'];
	$JENIS_PENDAFTARAN = $_POST['JENIS_PENDAFTARAN'];
	$NAMA_USAHA = $_POST['NAMA_USAHA'];
	$ALAMAT_USAHA = $_POST['ALAMAT_USAHA'];
	$KELURAHAN = $_POST['KELURAHAN'];
	$KECAMATAN = $_POST['KECAMATAN'];
	$TELP = $_POST['TELP'];
	$PEMILIK = $_POST['PEMILIK'];
	$MODAL = $_POST['MODAL'];
	$KEGIATAN = $_POST['KEGIATAN'];
	$TENAGA_KERJA = $_POST['TENAGA_KERJA'];
	$FASILITAS = $_POST['FASILITAS'];
	$KOORDINAT = $_POST['KOORDINAT'];
	$ID_SEKTOR = $_POST['ID_SEKTOR'];
	$KETERANGAN = $_POST['KETERANGAN'];


	$aa = fetchRet("SELECT * FROM jenis,tdp,sektor WHERE jenis.NOMENKLATUR = tdp.KEGIATAN AND sektor.ID = tdp.ID_SEKTOR");
	$aa= $aa[0];

	if ($KEGIATAN == '-') {
		$KEGIATAN = $aa->NOMENKLATUR;
	}

	if ($ID_SEKTOR == 0){
		$ID_SEKTOR = $aa->ID_SEKTOR;
	}

	$aa = fetchRet("SELECT * FROM tdp,jenis WHERE NO = $NO and jenis.NOMENKLATUR = tdp.KEGIATAN");
	$aa= $aa[0];

	if ($KEGIATAN == '-') {
		$KEGIATAN = $aa->NOMENKLATUR;
	}

	if ($_FILES['FOTO_1']['name'] != null) {
		$FOTO_1 = $NO_SK.'/'.$_FILES['FOTO_1']['name'];
		move_uploaded_file($_FILES["FOTO_1"]["tmp_name"] , "../asset/".$FOTO_1);
	}
	else{
		$FOTO_1 = $aa->FOTO_1;
	}

	if ($_FILES['FOTO_2']['name'] != null) {
		$FOTO_2 = $NO_SK.'/'.$_FILES['FOTO_2']['name'];
		move_uploaded_file($_FILES["FOTO_2"]["tmp_name"] , "../asset/".$FOTO_2);
	}
	else{
		$FOTO_2 = $aa->FOTO_2;
	}

	if ($_FILES['FOTO_3']['name'] != null) {
		$FOTO_3 = $NO_SK.'/'.$_FILES['FOTO_3']['name'];
		move_uploaded_file($_FILES["FOTO_3"]["tmp_name"] , "../asset/".$FOTO_3);
	}
	else{
		$FOTO_3 = $aa->FOTO_3;
	}

	if ($_FILES['FOTO_4']['name'] != null) {
		$FOTO_4 = $NO_SK.'/'.$_FILES['FOTO_4']['name'];
		move_uploaded_file($_FILES["FOTO_4"]["tmp_name"] , "../asset/".$FOTO_4);
	}
	else{
		$FOTO_4 = $aa->FOTO_4;
	}

	executeSql(
		"UPDATE tdp SET
			NO_SK = '$NO_SK', TGL_SK = '$TGL_SK', TGL_BERLAKU = '$TGL_BERLAKU', JENIS_PENDAFTARAN = '$JENIS_PENDAFTARAN', NAMA_USAHA = '$NAMA_USAHA', ALAMAT_USAHA = '$ALAMAT_USAHA', KELURAHAN = '$KELURAHAN', KECAMATAN = '$KECAMATAN', TELP = '$TELP', PEMILIK = '$PEMILIK', MODAL = '$MODAL', KEGIATAN = '$KEGIATAN', TENAGA_KERJA = '$TENAGA_KERJA', FASILITAS = '$FASILITAS', KOORDINAT = '$KOORDINAT', FOTO_1 = '$FOTO_1', FOTO_2 = '$FOTO_2', FOTO_3 = '$FOTO_3', FOTO_4 = '$FOTO_4', ID_SEKTOR = '$ID_SEKTOR', KETERANGAN = '$KETERANGAN' WHERE NO = '$NO'"
	);
	$app->redirect('../../#/tdp');
});

$app->post('/tdp/delete', function () use ($app){

	echo "process";

	$NO = $_POST['NO'];
	executeSql("DELETE FROM tdp WHERE NO = '$NO'");
	$app->redirect('../../#/tdp');
});

$app->post('/addSektor' , function () use ($app){
	
	$ID = $_POST['ID'];
	$NAMA_SEKTOR = $_POST['NAMA_SEKTOR'];
	$ID_WARNA = $_POST['ID_WARNA'];

	executeSql("INSERT INTO sektor (ID, NAMA_SEKTOR, ID_WARNA) VALUES ('$ID', '$NAMA_SEKTOR', '$ID_WARNA')");
	$app->redirect('../#/editsektor');
});

$app->post('/editSektor' , function () use ($app){

	$ID = $_POST['ID'];
	$NAMA_SEKTOR = $_POST['NAMA_SEKTOR'];
	$ID_WARNA = $_POST['ID_WARNA'];

	executeSql("UPDATE sektor SET NAMA_SEKTOR = '$NAMA_SEKTOR', ID_WARNA = '$ID_WARNA' WHERE ID = '$ID'");
	$app->redirect('../#/editsektor');

});
$app->post('/hapusSektor' , function () use ($app){
	$ID = $_POST['ID'];
	executeSql("DELETE FROM sektor WHERE ID = '$ID'");
	$app->redirect('../#/editsektor');
});

$app->run();

?>