
<?php 
session_start();
require_once 'ayar.php';
?>

<?php require_once 'layout/navbar.php'; ?>
    <!-- Header-->
    <div class="card">
        <img src="assets/img/kirtasiye.jpg" class="card-img" alt="...">
        <div class="card-img-overlay text-center mt-5 m-5">
            <br><br><br><br>
            <h1 class="card-text col-12"   style="background-color:maroon; color: #000;">Kırtasiye Alışverişi ve Ofis İhtiyaçlarınızda Güvenilir Adres Kırtasiye Dünyası</h1>
            
            <br><br>
            <div class="col-6 mx-auto">
            <h3 class="card-title" style="background-color: pink; color: #000;">Boyalar ve Boya Ürünleri</h3>
            <h3 class="card-title" style="background-color: purple; color: #000;">Çanta, Kalem Kutu ve Mataralar</h3>
            <h3 class="card-title" style="background-color: navy; color: #000;">Dosyalama, Arşivleme</h3>
            <h3 class="card-title" style="background-color: blue; color: #000;">Kalemler ve Yazı Gereçleri</h3>
            <h3 class="card-title" style="background-color: teal; color: #000;">Masaüstü Gereçleri</h3>
            <h3 class="card-title" style="background-color: green; color: #000;">Yazı Tahtası, Panolar ve Levhalar</h3>
            <h3 class="card-title" style="background-color: yellow; color: #000;">Ajanda ve Takvim</h3>
            <h3 class="card-title" style="background-color: orange; color: #000;">Kartuş ve Tonerler</h3>
            <h3 class="card-title" style="background-color: red; color: #000;">Kutu Oyunları & Puzzle</h3>
            </div>
        </div>
    </div>

    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php 
                /* Ürünü sepete ekle */
                if(isset($_GET['ekle'])){
                    $id= $_GET['ekle'];
                    // Veritabanından ürün adını çek
                    $urun_sorgu = $baglan->prepare("SELECT name FROM products WHERE id = ?");
                    $urun_sorgu->execute(array($id));
                    $urun_veri = $urun_sorgu->fetch(PDO::FETCH_OBJ);

                    setcookie('urun['.$id.']', $id, time()+8000);
                    echo "<script>
                    alert('".$urun_veri->name." adlı ürünü sepetinize eklediniz.');
                    window.location.href='".$_SERVER['HTTP_REFERER']."';
                    </script>";
                    $urun_sorgu->closeCursor(); unset($urun_sorgu);
                }
                /** Eğer sepetim sayfasına gitmek isterse */
                if ( isset($_GET['sepetim']) ){
                    header('location:card.php');
                }
                /* Ürünü sepetten çıkar */
                if(isset($_GET['cıkar'])){
                    setcookie('urun['.$_GET['cıkar'].']', $_GET['cıkar'], time() - 8000);
                    header('location:'.$_SERVER['HTTP_REFERER'].'');
                }

                /* Ürünü db den sil */
                if(isset($_GET['sil'])){
                    $id = $_GET['sil'];
                    // SQL DELETE komutunu hazırla ve çalıştır
                    $sorgu = $baglan->prepare('DELETE FROM products WHERE id = ?');
                    $sorgu->execute(array($id));
                    // İşlem sonucunu kontrol et
                    if ($sorgu->rowCount() > 0) {
                        echo "<script>
                        alert('Ürün silindi');
                        window.location.href='".$_SERVER['HTTP_REFERER']."';
                        </script>";
                    } else {
                        echo "Ürün silme işlemi başarısız oldu.";
                    }
                }
                /** Veritabanından ürünlerin bilgilerini çekin */
                $sorgu = $baglan->prepare("select * from products");
                $sorgu->execute(array());

                /** Ürünlerin verilerini döngüye alın */
                foreach ($sorgu as $satir) {
                    echo '
                    <div class="col mb-5">
                        <div class="card h-100">
                        <!-- Sale badge-->
                        <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">İNDİRİM</div>
                            <!-- Product image-->
                            <div style="height: 200px; overflow: hidden;">
                                <img class="card-img-top" src="'.$satir->image.'" alt="..." style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder" name="name">'.$satir->name.'</h5>
                                    <!-- Product reviews-->
                                    <div class="d-flex justify-content-center small text-warning mb-2">
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                    </div>
                                    <!-- Product price-->
                                    $'.$satir->fiyat.'
                                    <br> sadece '.$satir->stok.' adet
                                </div>
                            </div>
                            <!-- Product actions-->
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    '.(isset($_COOKIE['urun'][$satir->id]) ? 
                                    '<div class="text-center">
                                    <a class="btn btn-outline-dark mt-auto m-1" href="?cıkar='.$satir->id.'">Sepeten Sil</a>
                                    <a class="btn btn-outline-dark mt-auto m-1" href="?sil='.$satir->id.'">Ürün Sil</a></div>' : 
                                    '<div class="text-center">
                                    <a class="btn btn-outline-dark mt-auto m-1" href="?ekle='.$satir->id.'">Sepete Ekle</a>
                                    <a class="btn btn-outline-dark mt-auto m-1" href="?sil='.$satir->id.'">Ürün Sil</a>
                                    </div>')
                                    .'
                                </div>
                        </div>
                    </div>';
                    }
                $sorgu->closeCursor(); unset($sorgu);
                ?> 
            </div>
        </div>
    </section>
<?php require_once 'layout/footer.php'; ?>