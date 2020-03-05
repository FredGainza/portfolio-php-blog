<?php
session_start();
require '../app/can_connect_admin.php';
require '../toolbox.php';

$success = false;

$_SESSION['success_admin'] = "";
$_SESSION['errors_admin'] = "";

if (isset($_POST) && !empty($_POST)) {
  foreach ($_POST as $k => $v) {
    if (!empty($v)) {
      $succes;
    } else {
      $_SESSION['errors_admin'] .= $k . ' /' . ' ';
    }
  }
}

if ($_SESSION['errors_admin'] === "" || $_SESSION['errors_admin'] === "spotify_URI / ") {
  $user_id = $_SESSION['log_id'];
  $title = $_POST['title'];
  $date = $_POST['date'];
  $label = $_POST['label'];
  $category = $_POST['category'];
  $description = $_POST['description'];
  $content = $_POST['content'];
  $spotify = $_POST['spotify_URI'];
  // $image = $_POST['image'];
  $slug = slugify($title);

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
                            $msg = '../image transférée';

                            require '../app/bdd.php';

                            $select = $dbh->prepare('SELECT * FROM blog_categories');
                            $select->execute();
                            $res_cat = $select->fetchAll(PDO::FETCH_OBJ);

                            // dumpPre($res_cat);
                            // exit;

                            foreach ($res_cat as $v) {
                                if ($v->name == $category) {
                                    $category_id = $v->id;
                                }
                            }

                            // echo $category_id;
                            // exit;


                            $insert = $dbh->prepare('INSERT INTO blog_posts (user_id, image, category, category_id, title, date, label, description, content, slug, spotify_URI) VALUES (:user_id, :image, :category, :category_id, :title, :date, :label, :description, :content, :slug, :spotify_URI)');
                            $insert->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                            $insert->bindValue(':image', $image_name . '.' . $extension, PDO::PARAM_STR);
                            $insert->bindValue(':category', $category, PDO::PARAM_STR);
                            $insert->bindValue(':category_id', $category_id, PDO::PARAM_INT);
                            $insert->bindValue(':title', $title, PDO::PARAM_STR);
                            $insert->bindValue(':date', $date, PDO::PARAM_STR);
                            $insert->bindValue(':label', $label, PDO::PARAM_STR);
                            $insert->bindValue(':description', $description, PDO::PARAM_STR);
                            $insert->bindValue(':content', $content, PDO::PARAM_STR);
                            $insert->bindValue(':slug', $slug, PDO::PARAM_STR);
                            $insert->bindValue(':spotify_URI', $spotify, PDO::PARAM_STR);

                            $insert->execute();



                            $res_insert = $insert->fetchAll(PDO::FETCH_OBJ);

                            $article_id = $dbh->lastInsertId();

                            $insert = $dbh->prepare('INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)');
                            $insert->bindValue(':article_id', $article_id, PDO::PARAM_INT);
                            $insert->bindValue(':category_id', $category_id, PDO::PARAM_INT);
                            $insert->execute();

                            $_SESSION['success_admin'] = "Release correctement ajoutée";
                            header('Location: admin.php?article=table');
                        

                } else {
                    $_SESSION['errors_admin'] = 'Extension non autorisée';
                    header('Location: admin.php?article=affichage');
                }
            } else {
            $_SESSION['errors_admin'] = 'Fichier trop lourd';
            header('Location: admin.php?article=table');
            }
        } else {
            $_SESSION['errors_admin'] = 'Erreur lors du transfert de fichier';
            header('Location: admin.php?article=table');
        }
    } else {
    $_SESSION['errors_admin'] = 'Un problème est survenu';
    header('Location: admin.php?article=table');
    }
}