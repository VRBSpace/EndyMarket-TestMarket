
<div class="content-wrapper css-nomargin m-0">
    <!-- Content Header (Page header) -->
      <section class="content-header p-1">
        <div class="pageTitle float-left">
          <h1><i class="fa fa-address-book"></i> Log Viewer</h1>
        </div>

        <div class="actionBtns col-sm-5 float-right">
            <div class="float-right"></div>
        </div>
    </section>

    <br />

    <div class="content mt-5">
            <div class="row">
                <div class="col-sm-2" style="overflow: auto;max-height: 550px;">
                    <?php if (empty($files)): ?>
                        <a class="list-group-item liv-active">No Log Files Found</a>
                    <?php else: ?>
                        <?php foreach ($files as $file): ?>
                            <a href="?f=<?= base64_encode($file); ?>"
                               class="list-group-item <?= ($currentFile == $file) ? "llv-active" : "" ?>">
                                   <?= $file; ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="col">
                    <?php if (is_null($logs)): ?>
                        <div>
                            <br><br>
                            <strong>Log file > 50MB, please download it.</strong>
                            <br><br>
                        </div>
                    <?php else: ?>
                        <table id="table-log" class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">Ниво</th>
                                    <th>Дата</th>
                                    <th>Съдържание на грешката</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($logs as $key => $log): ?>
                                    <tr class="notAutoNum" data-display="stack<?= $key; ?>">

                                        <td class="text-<?= $log['class']; ?>">
                                            <span class="<?= $log['icon']; ?>" aria-hidden="true"></span>
                                            &nbsp;<?= $log['level']; ?>
                                        </td>
                                        <td class="date"><?= $log['date']; ?></td>
                                        <td class="text">
                                            <?php if (array_key_exists("extra", $log)): ?>
                                                <a class="pull-right expand btn btn-default btn-xs"
                                                   data-display="stack<?= $key; ?>">
                                                    <span class="fa fa-search fa-stack"></span>
                                                </a>
                                            <?php endif; ?>
                                            <?= $log['content']; ?>
                                            <?php if (array_key_exists("extra", $log)): ?>
                                                <?= '<div class="stack" id="stack' . $key . '" style="display: none; white-space: pre-wrap;">' . $log['extra'] . '</div>' ?>

                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <div>
                        <?php if ($currentFile): ?>
                            <a href="?dl=<?= base64_encode($currentFile); ?>">
                                <span class="glyphicon glyphicon-download-alt"></span>
                                Свали файла
                            </a>
                            -
                            <a id="delete-log" href="?del=<?= base64_encode($currentFile); ?>"><span
                                    class="fa fa-trash"></span> Изтрий файла</a>
                                <?php if (count($files) > 1): ?>
                                -
                                <a id="delete-all-log" href="?del=<?= base64_encode("all"); ?>"><span class="fa fa-trash"></span> Изтрий всички файлове</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
  
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!--<script src="/assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>-->
<script src="/assets/vendor/popper.js/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>


<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/datatables.min.js"></script>
<script>
    $(document).ready(function () {

        $('.table tr').on('click', function () {
            $('#' + $(this).data('display')).toggle();
        });

        $('.table').DataTable({
            "order": [ ],
            "stateSave": true,
            "stateSaveCallback": function (settings, data) {
                window.localStorage.setItem("datatable", JSON.stringify(data));
            },
            "stateLoadCallback": function (settings) {
                var data = JSON.parse(window.localStorage.getItem("datatable"));

                if (data)
                    data.start = 0;
                return data;
            }
        });
        $('#delete-log, #delete-all-log').click(function () {
            return confirm('Are you sure?');
        });
    });
</script>

