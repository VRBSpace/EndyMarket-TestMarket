<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pageTitle col-sm-10 float-sm-left">
            <h1 class="m-0 text-dark">Добавяне на Patch</h1>
        </div>
        <!--            <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Stores</li>
                    </ol> -->
        <div class="actionBtns col-sm-2 float-sm-right">
            <button class="btn btn-primary" type="submit" form="form_patch" data-toggle="tooltip" title="" > Качване и актуалиране</button>

            <form method="POST"  action=''>
                 <input type="hidden" name="logout" value="1" />
                <button class="btn btn-primary" type="submit"> logout</button>
            </form>  

        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <br /> <br />

            <div class="box-body">

                <p>В тази секция може да се качи пакетен файл с разширение .diff или .patch за актуализиране на проектните файлове</p><br/>

                <form id="form_patch" method="POST" enctype='multipart/form-data' action=''>
                    <label>път:  </label> <?php echo WRITEPATH ?>uploads/uploadsPatch

                    <br/> 

                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                    <label>Файл : </label>
                    <input type="file" name="file" accept=".diff, .patch"/>

                </form>

                <div id="infoMessage"><?php echo $message; ?></div>

            </div>
        </div>
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->

<script>
    $("#mainSystemNav").addClass('active menu-open');
    $("#managePatch").addClass('active');
</script>
