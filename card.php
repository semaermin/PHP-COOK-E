
<?php 
require_once 'ayar.php';
?>
<?php require_once 'layout/navbar.php'; ?>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <?php 
                        $sorgu = $baglan->prepare("select * from products");
                        $sorgu->execute(array());
                        /* Ürünü sepete ekle */
                        if(isset($_GET['ekle'])){
                            setcookie('urun['.$_GET['ekle'].']', $_GET['ekle'], time() + 8000);
                            header('location:'.$_SERVER['HTTP_REFERER'].'');
                        }
                        /* Ürünü sepetten çıkar */
                        if(isset($_GET['cıkar'])){
                            setcookie('urun['.$_GET['cıkar'].']', $_GET['cıkar'], time() - 8000);
                            header('location:'.$_SERVER['HTTP_REFERER'].'');
                        }
                        /** sorguyu mutlaka kapatıyoruz */
                        $sorgu->closeCursor(); unset($sorgu);

                        /**Eğer sepette ürün varsa */
                        if(isset($_COOKIE['urun'])){ 
                            /* Sepetteki ürün listesi */
                            foreach ($_COOKIE['urun'] as $urun => $val) {
                                /** Cookiedeki ürün id'sini veritabanında bul */
                                $urun_sorgu = $baglan->prepare("SELECT * FROM products WHERE id = ?");
                                $urun_sorgu->execute(array($urun));
                                $urun_veri = $urun_sorgu->fetchAll(PDO::FETCH_OBJ);// fetchAll() kullanarak tüm sonuçları alın.
                                /** Ürünlerin verilerini döngüye alın */
                                foreach ($urun_veri as $urun) {
                                echo '
                                <div class="col mb-5">
                                    <div class="card h-100">
                                        <!-- Product image-->
                                        <div style="height: 200px; overflow: hidden;">
                                            <img class="card-img-top" src="'.$urun->image.'" alt="..." style="object-fit: cover; width: 100%; height: 100%;">
                                        </div>
                                        <!-- Product details-->
                                        <div class="card-body p-4">
                                            <div class="text-center">
                                                <!-- Product name-->
                                                <h5 class="fw-bolder" name="name">'.$urun->name.'</h5>
                                                <!-- Product reviews-->
                                                <div class="d-flex justify-content-center small text-warning mb-2">
                                                    <div class="bi-star-fill"></div>
                                                    <div class="bi-star-fill"></div>
                                                    <div class="bi-star-fill"></div>
                                                    <div class="bi-star-fill"></div>
                                                    <div class="bi-star-fill"></div>
                                                </div>
                                                <!-- Product price-->
                                                $'.$urun->fiyat.'
                                                <br> sadece '.$urun->stok.' adet
                                            </div>
                                        </div>      
                                        <!-- Product actions-->
                                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                            <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="?cıkar='.$urun->id.'">Sepetten Çıkar</a></div>
                                        </div>
                                    </div>
                                </div>';
                            }
                            /** Sorguyu mutlaka kapatıyoruz. */
                            $urun_sorgu->closeCursor(); unset($urun_sorgu);
                            }
                        } 
                        /**eğer sepette ürün yoksa */
                        else {          
                            echo '<div style="margin-right:1000px;">Suan sepetinizde hiç urun bulunmuyor!!!!</div>';
                        }
                    ?>
                </div>
            </div>
        </section>
        

<?php require_once 'layout/footer.php'; ?>