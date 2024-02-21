<?php require "include/database.php";
    if (@$_SESSION["oturum"]) {
        header("location:index.php");
    }else{
        echo("Giriş yapınız");
    }
?>

<form action="" method="post">
    <input type="text" name="kullaniciadim" placeholder="Kullanıcı adı"/>
    <input type="password" name="sifremparolam" placeholder="Şifre" />
    <input type="submit" valu="Giriş yap" />
</form>

<?php

if (!isset($_SESSION['giris_denemesi'])) {
    $_SESSION['giris_denemesi'] = 0;
    $_SESSION['yasakli'] = 0;
    echo("İlkgiriş");
}

if ($_SESSION["yasakli"]) {
    echo("YASAKLANDIN 5DK BEKLE işte burada form gönderme yasağı veya bu sayfaya girememe yasağı falan başka sayfaya atabiliriz sonra");
}

if($_POST) {
    $kontrol = $db->prepare("SELECT * FROM ayarlar WHERE site_kad=:anlikkad AND site_pass=:anlikpass");
    $kontrol->execute(["anlikkad" => $_POST["kullaniciadim"], "anlikpass" => $_POST["sifremparolam"]]);
    if ($kontrol->rowCount()) {
        // $row = $kontrol->fetch(PDO::FETCH_OBJ);
        $_SESSION["oturum"] = true;
        echo "Başarıyla giriş yaptınız. Yönlendiriliyorsunuz!";
        header("Refresh:2; url=index.php");
    }else{
        $_SESSION['giris_denemesi']++;
        echo("Başarısız giriş! Kontrol edin!");
        if ($_SESSION['giris_denemesi'] >= 3) {
            $_SESSION['yasakli'] = true;
            echo("yasaklandın ağa");
            header("Refresh:2; url=loginplus.php");
        }
    }
}


?>