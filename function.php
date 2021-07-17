<?php
session_start();

//membuat koneksi ke data base
$conn = mysqli_connect("localhost","root","","stockbarang");



//menambah barang barung
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi , stock) values ('$namabarang','$deskripsi','$stock')");
    if($addtotable){
         header('locatioan:index.php');
    }else{
        echo 'Gagal';
        header('location:index.php');
    } 
};

//menambah barang masuk
 if(isset($_POST['barangmasuk'])){
     $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang ='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    
    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;


    $addtomasuk = mysqli_query($conn, "insert into masuk (idbarang, keterangan, qty) values ('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock ='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");

    if($addtomasuk&&$updatestockmasuk){
        header('locatioan:masuk.php');
   }else{
       echo 'Gagal';
       header('location:masuk.php');
   } 
    
 };

 //menambah barang keluar
 if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
   $penerima = $_POST['penerima'];
   $qty = $_POST['qty'];

   $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang ='$barangnya'");
   $ambildatanya = mysqli_fetch_array($cekstocksekarang);
   
   $stocksekarang = $ambildatanya['stock'];
   $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;


   $addtokeluar = mysqli_query($conn, "insert into keluar(idbarang, penerima, qty) values ('$barangnya','$penerima','$qty')");
   $updatestockmasuk = mysqli_query($conn, "update stock set stock ='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");

   if($addtokeluar&&$updatestockmasuk){
       header('locatioan:masuk.php');
  }else{
      echo 'Gagal';
      header('location:masuk.php');
  } 
   
};



//update info barang

if(isset($_POST['updatebarang'])){

    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];


    $update = mysqli_query($conn ,"update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang='$idb'");
   if($update){
    header('locatioan:index.php');
    }else{
        echo 'Gagal';
        header('location:index.php');
    } 
   
};

//Hapus Barang dari stock
if(isset($_POST['hapusbarang'])){

    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if($hapus){
        header('locatioan:index.php');
    }else{
        echo 'Gagal';
        header('location:index.php');
    } 

};


//mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){

    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty =$_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];


    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){

        $selisih = $qty - $qtyskrg;
        $kurangin =$stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty ='$qty',keterangan='$deskripsi' where idmasuk='$idm'");


        if($kurangistocknya&&$updatenya){
            header('locatioan:masuk.php');
        }else{
            echo 'Gagal';
            header('location:masuk.php');
        } 


      
    }else{
        $selisih = $qtyskrg - $qty;
        $kurangin =$stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty ='$qty', keterangan='$deskripsi' where idmasuk='$idm'");


        if($kurangistocknya&&$updatenya){
            header('locatioan:masuk.php');
        }else{
            echo 'Gagal';
            header('location:masuk.php');
        } 


    }






}


//menghapus barang masuk

if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "select * from stock  where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];
    $selisih = $stock - $qty;


    $update = mysqli_query($conn, "update stock set stock = '$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from masuk where idmasuk='$idm'");

    if($update&&$hapusdata){
        header('locatioan:masuk.php');


    }else{
        header('locatioan:masuk.php');

    }
}


//mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){

    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty =$_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];


    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){

        $selisih = $qty - $qtyskrg;
        $kurangin =$stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty ='$qty', penerima='$penerima' where idkeluar='$idk'");


        if($kurangistocknya&&$updatenya){
            header('locatioan:keluar.php');
        }else{
            echo 'Gagal';
            header('location:keluar.php');
        } 


      
    }else{
        $selisih = $qtyskrg - $qty;
        $kurangin =$stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty ='$qty', penerima='$penerima' where idkeluar='$idk'");


        if($kurangistocknya&&$updatenya){
            header('locatioan:keluar.php');
        }else{
            echo 'Gagal';
            header('location:keluar.php');
        } 


    }






}


//menghapus barang keluar

if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "select * from stock  where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];
    $selisih = $stock + $qty;


    $update = mysqli_query($conn, "update stock set stock = '$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from keluar where idkeluar='$idk'");

    if($update&&$hapusdata){
        header('locatioan:keluar.php');


    }else{
        header('locatioan:keluar.php');

    }
}



function tgl_indonesia($date){
   /* ARRAY u/ hari dan bulan */
   $Hari = array ("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu",);
   $Bulan = array ("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

/* Memisahkan format tanggal bulan dan tahun menggunakan substring */
$tahun 	 = substr($date, 0, 4);
$bulan 	 = substr($date, 5, 2);
$tgl	 = substr($date, 8, 2);
$waktu	 = substr($date,11, 5);
$hari	 = date("w", strtotime($date));

$result = $Hari[$hari].", ".$tgl." ".$Bulan[(int)$bulan-1]." ".$tahun." ".$waktu." WIB";
return $result;
}

?>
