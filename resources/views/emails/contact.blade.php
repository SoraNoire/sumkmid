<br>Ini adalah pesan yang dikirim melalui kontak form website sahabatumkm.id ( {{ url('/kontak')}} )
<br>Nama : {{ $name }}
<br>E-mail : {{ $email_from }}
<br>No Telp : {{ $contact }}
<br>Ditujukan kepada : info@mdirect.id
<br>Judul : {{ $subject }}
<br>Pesan:
<br>
<br><?php echo $pesan; ?>