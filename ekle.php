<?php
require_once 'ayar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $stok = $_POST["stok"];
    $fiyat = $_POST["fiyat"];

    // Dosya yükleme işlemi
    $targetDir = "assets/";
    $fileName = $_FILES["image"]["name"];
    $targetFilePath = $targetDir . basename($fileName);
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Dosya yükleme sınırlamaları
    $allowedTypes = array("jpg", "jpeg", "png");
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    // Dosya türü ve boyut kontrolü
    if (in_array($fileType, $allowedTypes) && $_FILES["image"]["size"] <= $maxFileSize) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Veritabanına ekleme işlemi
            $sorgu = $baglan->prepare("INSERT INTO products (name, stok, fiyat, image) VALUES (?, ?, ?, ?)");
            $sorgu->execute([$name, $stok, $fiyat, $targetFilePath]);

            if ($sorgu->rowCount() > 0) {
                $kayitno = $baglan->lastInsertId();
                echo "<script>
                    alert('[ID: $kayitno] Kayıt Başarılı...');
                    window.location.href='ekle.php';
                </script>";
            } else {
                echo "Kayıt eklenirken bir hata oluştu.";
            }
        } else {
            echo "Dosya yükleme işlemi başarısız oldu.";
        }
    } else {
        echo "Sadece JPG, JPEG ve PNG dosyaları yükleyebilirsiniz. Dosya boyutu 2MB'tan küçük olmalıdır.";
    }
}
?>

<?php require_once 'layout/navbar.php'; ?>
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx gx-lg row-cols-2 row-cols-md-3 row-cols-xl justify-content-center">
                <div class="col mb-5">
                    <div class="card h-100 p-5">
                        <div style="text-align:center">
                        <div class="input-group mb-3">
                            <form action="ekle.php" method="post" enctype="multipart/form-data">
                                <div class="form-outline">
                                    <label class="form-label" for="typeText">Ürün Adı</label>
                                    <input type="text" id="typeText" class="form-control m-1" name="name" />
                                </div>
                                <div class="form-outline">
                                    <label class="form-label" for="typeNumber">Stok Sayısı</label>
                                    <input type="number" id="typeNumber" class="form-control m-1"  name="stok"/>
                                </div>
                                <div class="form-outline">
                                    <label class="form-label" for="typeText2">Ürün Fiyatı</label>
                                    <input type="text" id="typeText2" class="form-control m-1" name="fiyat" />
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Ürün Fotoğrafı</label>
                                    <input class="form-control" type="file" id="formFile" name="image">
                                </div>                                    
                                <button class="btn btn-primary m-2" type="submit">Kaydet</button>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </section>


<?php require_once 'layout/footer.php'; ?>