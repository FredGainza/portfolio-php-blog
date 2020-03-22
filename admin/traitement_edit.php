<?php session_start();
require '../toolbox.php';
require '../app/bdd.php';

$lim=0;
isset($_SESSION['limit-art']) ? $lim = $_SESSION['limit-art'] : $lim = 5;
$page=0;
isset($_SESSION['page-art']) ? $page = $_SESSION['page-art'] : $page = 0;

if (!empty($_POST)) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $date = $_POST['date'];
    $label = $_POST['label'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $spotify = $_POST['spotify_URI'];
    $slug = slugify($title);
    
    $edit = $dbh->prepare('UPDATE blog_posts SET category = :category, title = :title, date = :date, label = :label, description = :description, content = :content, spotify_URI = :spotify_URI, slug = :slug WHERE id = :id');
    $edit->bindValue(':id', $id, PDO::PARAM_INT);
    $edit->bindValue(':category', $category, PDO::PARAM_STR);
    $edit->bindValue(':title', $title, PDO::PARAM_STR);
    $edit->bindValue(':date', $date, PDO::PARAM_STR);
    $edit->bindValue(':label', $label, PDO::PARAM_STR);
    $edit->bindValue(':description', $description, PDO::PARAM_STR);
    $edit->bindValue(':content', $content, PDO::PARAM_STR);
    $edit->bindValue(':slug', $slug, PDO::PARAM_STR);
    $edit->bindValue(':spotify_URI', $spotify, PDO::PARAM_STR);

    $edit->execute();

    if (empty($_FILES)) {
        $_SESSION['success_admin'] = "Release correctement éditée";
        header('Location: admin.php?nb_items='.$lim.'&page='.$page.'&article=table');;
        exit;
    }

    if (!empty($_FILES)) {
        $maxSize = 1024 * 1024; // 1 048 576

        if ($_FILES['image']['error'] === 0) {


            if ($_FILES['image']['size'] <= $maxSize) {

                $fileInfo = pathinfo($_FILES['image']['name']);
                // dumpPre($fileInfo);

                $extension = strtolower($fileInfo['extension']);
                $extension_autorise = ['jpg', 'jpeg', 'png', 'gif'];
                // dumpPre($extension);

                if (in_array($extension, $extension_autorise)) {

                    $image_name = md5(uniqid(rand(), true));
                    // dumpPre($image_name);

                    $config_miniature_width = 300;

                    if ($extension === 'jpg' || $extension === 'jpeg') {
                        $new_image = imagecreatefromjpeg($_FILES['image']['tmp_name']);
                    } elseif ($extension === 'png') {
                        $new_image = imagecreatefrompng($_FILES['image']['tmp_name']);
                    } elseif ($extension === 'gif') {
                        $new_image = imagecreatefromgif($_FILES['image']['tmp_name']);
                    }

                    $original_width = imagesx($new_image);
                    $original_heigth = imagesy($new_image);
                    $miniature_heigth = ($original_heigth * $config_miniature_width) / $original_width;
                    $miniature = imagecreatetruecolor($config_miniature_width, $miniature_heigth);
                    imagecopyresampled($miniature, $new_image, 0, 0, 0, 0, $config_miniature_width, $miniature_heigth, $original_width, $original_heigth);

                    $folder = '../images/miniatures/';

                    if ($extension === 'jpg' || $extension === 'jpeg') {
                        imagejpeg($miniature, $folder . $image_name . '.' . $extension);
                    } elseif ($extension === 'png') {
                        imagepng($miniature, $folder . $image_name . '.' . $extension);
                    } elseif ($extension === 'gif') {
                        imagegif($miniature, $folder . $image_name . '.' . $extension);
                    }
                    move_uploaded_file($_FILES['image']['tmp_name'], '../images/' .
                        $image_name . '.' . $extension);
                    $msg = 'image transférée';

                    $edition_img = $dbh->prepare("UPDATE blog_posts SET image = :image WHERE id = :id");

                    $edition_img->bindValue(":image", $image_name . '.' . $extension, PDO::PARAM_STR);
                    $edition_img->bindValue(":id", $id, PDO::PARAM_INT);
                    $edition_img->execute();

                    $_SESSION['success_admin'] = "Release correctement éditée";
                    header('Location: admin.php?nb_items='.$lim.'&page='.$page.'&article=table');
                    exit;
                } else {
                    $_SESSION['errors_admin'] = 'Extension non autorisée; ';
                    header('Location: admin.php?nb_items='.$lim.'&page='.$page.'&article=table');
                    exit;
                }
            } else {
                $_SESSION['errors_admin'] = 'Fichier trop lourd';
                header('Location: admin.php?nb_items='.$lim.'&page='.$page.'&article=table');
                exit;
            }
        } elseif ($_FILES['image']['error'] === 4) {
            $_SESSION['success_admin'] = "Release correctement éditée";
            header('Location: admin.php?nb_items='.$lim.'&page='.$page.'&article=table');
            exit;
        } else {
            $_SESSION['errors_admin'] = 'Une erreur est survenue lors du transfert de la photo';
            header('Location: admin.php?nb_items='.$lim.'&page='.$page.'&article=table');
            exit;
        }
    } else {
        $_SESSION['success_admin'] = "Release correctement éditée";
        header('Location: admin.php?nb_items='.$lim.'&page='.$page.'&article=table');
        exit;
    }
}
