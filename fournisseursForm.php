<!DOCTYPE html>
<html>
<?php include('header.php'); require_once('config/config.php'); ?>
<?php
session_start();
$_SESSION['page'] = 305;
require_once('logCheck.php');
?>
<body class="hold-transition skin-<?php echo $SITECOLOR; ?> sidebar-mini fixed">
<div class="wrapper">
    <?php include('bandeausup.php'); ?>
    <?php include('navbar.php'); ?>
    <?php require_once 'config/bdd.php'; ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Fournisseurs
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-home"></i>Accueil</a></li>
                <li><a href="fournisseursForm.php">Fournisseurs</a></li>
                <li class="active">Ajouter/Modifier un fournisseur</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Modification d'un fournisseur</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form role="form" action="fournisseursUpdate.php?id=<?=$_GET['id']?>" method="POST">
                        <?php
                        $query = $db->prepare('SELECT * FROM FOURNISSEURS WHERE idFournisseur=:idFournisseur;');
                        $query->execute(array('idFournisseur' => $_GET['id']));
                        $data = $query->fetch();
                        ?>
                        <!-- text input -->
                        <div class="form-group">
                            <label>Nom:</label>
                            <input type="text" class="form-control" value="<?=$data['nomFournisseur']?>"
                                   name="nomFournisseur" required>
                        </div>
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Adresse</label>
                            <textarea class="form-control" rows="3"
                                      name="adresseFournisseur"><?=$data['adresseFournisseur']?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Téléphone:</label>
                            <input type="tel" class="form-control" value="<?=$data['telephoneFournisseur']?>"
                                   name="telephoneFournisseur">
                        </div>
                        <div class="form-group">
                            <label>eMail:</label>
                            <input type="email" class="form-control" value="<?=$data['mailFournisseur']?>"
                                   name="mailFournisseur">
                        </div>
                        <div class="box-footer">
                            <a href="javascript:history.go(-1)" class="btn btn-default">Retour</a>
                            <button type="submit" class="btn btn-info pull-right">Modifier</button>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->

            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include('footer.php'); ?>


    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<?php include('scripts.php'); ?>
</body>
</html>
