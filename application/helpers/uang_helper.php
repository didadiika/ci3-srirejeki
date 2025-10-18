<?php

function uangRp($angka)
{
	$hasil = "Rp ".number_format($angka,0,',','.');
	
	return $hasil;
}
function uang($angka)
{
	$angka = (int) $angka;
	$hasil = number_format($angka,0,',','.');
	if($angka < 0)
	{
		$hasil = "-".number_format(abs($angka),0,',','.');
	}
	
	return $hasil;
}

function uangDecimal($angka)
{
	$hasil = number_format($angka,2,',','.');
	if($angka < 0)
	{
		$hasil = "(".number_format(abs($angka),0,',','.').")";
	}
	
	return $hasil;
}

function uangPecah($uang){

$hasil = str_replace(".","",$uang);

return $hasil;	
}

function uangPecahDecimal($uang){

	$uang = str_replace(".","",$uang);
	$hasil = str_replace(",",".",$uang);
	
	return $hasil;	
	}

function uangRpBiasa($uangRp)
{
	list($matauang,$uang) = explode(" ",$uangRp);
	
	$hasil = str_replace(".","",$uang);
	
	return $hasil;
}

function terbilang($x) {
	$x = (int) $x;
	$angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
  
	if ($x < 12)
	  return " " . $angka[$x];
	elseif ($x < 20)
	  return terbilang($x - 10) . " belas";
	elseif ($x < 100)
	  return terbilang($x / 10) . " puluh" . terbilang($x % 10);
	elseif ($x < 200)
	  return "seratus" . terbilang($x - 100);
	elseif ($x < 1000)
	  return terbilang($x / 100) . " ratus" . terbilang($x % 100);
	elseif ($x < 2000)
	  return "seribu" . terbilang($x - 1000);
	elseif ($x < 1000000)
	  return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
	elseif ($x < 1000000000)
	  return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
  }
?>