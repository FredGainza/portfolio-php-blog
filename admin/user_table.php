<?php

// use League\CommonMark\Extension\Table\TableRow;

require '../app/bdd.php';

$columns = array('id','email','avatar', 'role', 'firstname', 'lastname', 'created_at');
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

/********************************************************************************************************************************************/

$select_post = $dbh->prepare('SELECT user_id, COUNT(user_id) as nb_posts FROM blog_posts LEFT JOIN users ON users.id = blog_posts.user_id GROUP BY user_id');
$select_post->execute();
$res_user_nb_posts = $select_post->fetchAll(PDO::FETCH_OBJ);

$select_com = $dbh->prepare('SELECT user_id, COUNT(user_id) as nb_coms FROM blog_comments GROUP BY user_id');
$select_com->execute();
$res_user_nb_coms = $select_com->fetchAll(PDO::FETCH_OBJ);
// dumpPre($res_user_nb_coms);exit;

$select = $dbh->prepare('SELECT * FROM users');
$select->execute();
$result = $select->fetchAll(PDO::FETCH_OBJ);

$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$_SESSION['page-user'] = $page;


if (isset($_GET['nb_items']) && $_GET['nb_items'] > 0) {
    $limit = intval($_GET['nb_items']);
} else {
    if (isset($_SESSION['limit-user'])) {
        $limit = intval($_SESSION['limit-user']);
    } else {
        $limit = 5;
    }
}
$_SESSION['limit-user'] = $limit;

if (!isset($debut)) {
    $debut = 0;
} else {
    $debut = $page * $limit;
}

$nb_total = $select->rowCount();
$limite = $dbh->prepare("SELECT * FROM users limit $debut,$limit");
$limit_str = "LIMIT " . $page * $limit . ",$limit";

if ($resultat = $dbh->prepare('SELECT * FROM users ORDER BY ' .  $column . ' ' . $sort_order . ' ' .$limit_str)) {
	// Some variables we need for the table.
    $up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order); 
    $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
    $add_class = ' class="highlight"';
    $resultat->execute();
    $res = $resultat->fetchAll(PDO::FETCH_OBJ);
}
// dumpPre($res);exit;
$pagin = [];
for ($i = 1; $i <= floor($nb_total / $limit); $i++) {
    $pagin[] = $limit * $i;
}
if ($nb_total % $limit != 0) {
    $pagin[] = $nb_total;
}
?>

<?php if (isset($res) || isset($_GET['users']) && $_GET['users'] == "table") : ?>
    <div class="row align-items-start width-resp justify-content-center mt-3 mb-1 mx-auto">


        <div class="col-auto ml-0 mr-auto mb-2">

            <form action="admin.php" method="GET">
                <div class="form-group form-select row w-100 align-items-start flex-nowrap">

                    <label class="col-auto col-form-label col-form-label-lg" for="nb_items">Eléments par page</label>
                    <div class="col-auto pad-l-0 ">
                        <select name="nb_items" id="nb_items" class="form-control form-control-lg pad-r-0 custom-select">
                            <option value="3" <?= $limit == 3 ? ' selected="selected"' : ''; ?>>3</option>
                            <option value="5" <?= $limit == 5 ? ' selected="selected"' : ''; ?>>5</option>
                            <option value="10" <?= $limit == 10 ? ' selected="selected"' : ''; ?>>10</option>
                            <option value="20" <?= $limit == 20 ? ' selected="selected"' : ''; ?>>20</option>
                            <option value="25" <?= $limit == 25 ? ' selected="selected"' : ''; ?>>25</option>
                            <option value="50" <?= $limit == 50 ? ' selected="selected"' : ''; ?>>50</option>
                            <option value="100" <?= $limit == 100 ? ' selected="selected"' : ''; ?>>100</option>
                        </select>
                    </div>
                    <input type="hidden" name="user" value="table">

                    <div class="form-group form-select w-25 pl-3 valid">
                        <button type="submit" class="btn btn-success btn-valid px-3 py-1 mb-2">Valider</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-auto ml-auto mr-2 mb-2 ajout">
            <button type="button" class="btn btn-ajout btn-dark px-2 ml-auto"><a href="?user=ajout" class="ajout" title="Ajout">Ajouter un User</a></button>
        </div>
    </div>


    <div class="col-12 d-resp-xl">
        <table class="my-3 w-100 table-striped">
            <thead>
                <tr>
                    <th<?= $column == 'id' ? $add_class : ''; ?>><a href="admin.php?nb_items=<?= $limit; ?>&user=table&page=<?= $page; ?>&column=id&order=<?= $asc_or_desc; ?>">Id<i class="fas fa-sort<?= $column == 'id' ? '-' . $up_or_down . ' color-darky ml-2' : ' text-warning ml-2'; ?>"></i></a></th>
                    <th<?= $column == 'email' ? $add_class : ''; ?>><a href="admin.php?nb_items=<?= $limit; ?>&user=table&page=<?= $page; ?>&column=email&order=<?= $asc_or_desc; ?>">Email<i class="fas fa-sort<?= $column == 'email' ? '-' . $up_or_down . ' color-darky ml-2' : ' text-warning ml-2'; ?>"></i></a></th>
                    <!-- <th>Email</th> -->
                    <th class="w-70p">Avatar</th>
                    <th>Role</th>
                    <th>Prenom</th>
                    <th<?= $column == 'lastname' ? $add_class : ''; ?>><a href="admin.php?nb_items=<?= $limit; ?>&user=table&page=<?= $page; ?>&column=lastname&order=<?= $asc_or_desc; ?>">Nom<i class="fas fa-sort<?= $column == 'lastname' ? '-' . $up_or_down . ' color-darky ml-2' : ' text-warning ml-2'; ?>"></i></a></th>
                    <th>Nb de POST</th>
                    <th>Nb de COM</th>
                    <th<?= $column == 'created_at' ? $add_class : ''; ?>><a href="admin.php?nb_items=<?= $limit; ?>&user=table&page=<?= $page; ?>&column=created_at&order=<?= $asc_or_desc; ?>">Date membre<i class="fas fa-sort<?= $column == 'created_at' ? '-' . $up_or_down . ' color-darky ml-2' : ' text-warning ml-2'; ?>"></i></a></th>
                    <th class="text-center w-150p">Actions</th>
                </tr>
            </thead>
            
            <tbody>
                <?php $z = $page * $limit; ?>
                <?php foreach ($res as $v) : ?>
                    <?php
                    $created = date("d-m-Y", strtotime($v->created_at));
                    $z++;
                    ?>
                    <tr>
                        <td class="pl-3"><?= $v->id; ?></td>
                        <td><?= $v->email; ?></td>

                        <td><img src="<?= $v->avatar; ?>"></td>
                        <td <?= $v->role == 'admin' ? ' class="text-danger"' : ''; ?>>
                            <?= $v->role; ?>
                        </td>
                        <td><?= $v->firstname; ?></td>
                        <td><?= $v->lastname; ?></td>
                        <td class="text-center">
                            <?php
                            $actifPost=false;
                            foreach ($res_user_nb_posts as $v1) {
                                $id = $v1->user_id;
                                $nb_posts = $v1->nb_posts;
                                if ($v->id == $id) {
                                    $actifPost=true;
                                    echo $nb_posts;
                                }
                            }if(!$actifPost){
                                echo '-';
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $actifCom=false;
                            foreach ($res_user_nb_coms as $v2) {
                                $id = $v2->user_id;
                                $nb_coms = $v2->nb_coms;
                                if ($v->id == $id) {
                                    $actifCom=true;
                                    echo $nb_coms;
                                }
                            }if(!$actifCom){
                                echo '-';
                            }
                            ?>
                        </td>
                        <!-- date("d-m-Y", strtotime($value->created_at) -->
                        <td><?= $created; ?></td>
                        <td class="text-center">
                            <a href="../user_profil.php?&id=<?= $v->id ?>" class="view pr-1" title="view"><i class="material-icons color-green-item">visibility</i></a>
                            <a href="?user=edit&id=<?= $v->id ?>" class="edit pr-1" title="Edit"><i class="material-icons color-blue-item"></i></a>
                            <a href="?user=delete&id=<?= $v->id ?>" class="delete pr-1" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red-item"></i></a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="col-12 d-resp-xs d-resp-norm mx-auto text-center">
        <table class="w90pct border-dark">
            <?php $z = $page * $limit; ?>
            <?php foreach ($res as $v) : ?>
                <?php
                $created = date("d-m-Y", strtotime($v->created_at));
                $z++;
                ?>

                <tr>
                    <th>Id</th>
                    <td class="text-center b-top"><?= $v->id; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td class="text-center"><?= $v->email; ?></td>
                </tr>
                <tr>
                    <th>Avatar</th>
                    <td class="text-center"><img src="<?= $v->avatar; ?>" class="w-70p"></td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td class="text-center<?= $v->role == 'admin' ? ' text-danger' : ''; ?>">
                        <?= $v->role; ?>
                    </td>
                </tr>
                <tr>
                    <th>Prenom</th>
                    <td class="text-center"><?= $v->firstname; ?></td>
                </tr>
                <tr>
                    <th>Nom</th>
                    <td class="text-center"><?= $v->lastname; ?></td>
                </tr>
                <tr>
                    <th class="text-nowrap">Nb de POST</th>
                    <td class="text-center">
                        <?php
                        $actifPost=false;
                        foreach ($res_user_nb_posts as $v1) {
                            $id = $v1->user_id;
                            $nb_posts = $v1->nb_posts;
                            if ($v->id == $id) {
                                $actifPost=true;
                                echo $nb_posts;
                            }
                        }if(!$actifPost){
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th class="text-nowrap">Nb de COM</th>
                    <td class="text-center">
                        <?php
                        $actifCom=false;
                        foreach ($res_user_nb_coms as $v2) {
                            $id = $v2->user_id;
                            $nb_coms = $v2->nb_coms;
                            if ($v->id == $id) {
                                $actifCom=true;
                                echo $nb_coms;
                            }
                        }if(!$actifCom){
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th class="text-nowrap">Date membre</th>
                    <td class="text-center"><?= $created; ?></td>
                </tr>
                <tr>
                    <th class="b-bot">Actions</th>
                    <td class="text-center text-nowrap">
                        <a href="../user_profil.php?&id=<?= $v->id ?>" class="view px-3" title="view"><i class="material-icons color-green-item va-middle">visibility</i></a>
                        <a href="?user=edit&id=<?= $v->id ?>" class="edit px-3" title="Edit"><i class="material-icons color-blue-item va-middle"></i></a>
                        <a href="?user=delete&id=<?= $v->id ?>" class="delete px-3" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red-item va-middle"></i></a>
                    </td>
                </tr>
                <?php for ($i = 0; $i < count($pagin); $i++) : ?>
                    <?php if ($z != $pagin[$i]) : ?>
                        <tr>
                            <td class="border-0"></td>
                        </tr>
                        <tr>
                            <td class="border-0"></td>
                        </tr>
                        <?php break; ?>
                    <?php endif; ?>
                <?php endfor; ?>
            <?php endforeach ?>
        </table>
    </div>

    <nav aria-label="Partie Pagination" class="mb-4">
        <ul class="pagination align-resp">
            <?php
            if ($page > 0) {
                $precedent = $page - 1;
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?nb_items=".$limit."&user=table&page=".$precedent."&column=".$column."&order=".strtolower($sort_order)."\"\ aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">Previous</span></a></li>";
            } else {
                $page = 0;
            }
            $i = 0;
            $j = 1;
            if ($nb_total > $limit) {
                while ($i < ($nb_total / $limit)) {
                    if ($i != $page) {
                        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?nb_items=".$limit."&user=table&page=".$i."&column=".$column."&order=".strtolower($sort_order)."\">".$j."</a></li>";
                    } else {
                        echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">".$j."<span class=\"sr-only\">(current)</span></a></li>";
                    }
                    $i++;
                    $j++;
                }
            }
            if ($debut + $limit < $nb_total) {
                $suivant = $page + 1;
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?nb_items=".$limit."&user=table&page=".$suivant."&column=".$column."&order=".strtolower($sort_order)."\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span><span class=\"sr-only\">Next</span></a></li>";
            }
            echo "</ul></nav>";
            ?>


<?php endif; ?>
<?php require 'footer_admin.php'; ?>