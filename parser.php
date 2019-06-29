<?php
require_once 'initialize.php';

// include 'runner.php';
?>
<div class="parser-header">
    <div class="container">
        <div class="row header-row">
            <div class="col-lg-4" style="padding-left: 0;">
                <h1>MotoWorld Parser</h1>
            </div>
        </div>
    </div>
</div>
<div class="w-100 message success-message">

</div>

<div class="w-100 message error-message">

</div>

<div class="parser-body">
    <div class="container this-container">
        <div class="row items-row">
            <div class="headers w-100">
                <div class="row">
                    <div class="col-lg-6 head-item">
                        Host
                    </div>
                    <div class="col-lg-3 head-item">
                        Status
                    </div>
                    <div class="col-lg-3 head-item">
                        <div class="inner">
                            Action
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $core = dataCore::instance();
            $connections = $core->get_all('connection');
            foreach ($connections as $connection):
                ?>
                <div class="parser-item w-100">
                    <div class="row">
                        <div class="col-lg-6 host-inner item-inner"><?= $connection->get('host'); ?></div>
                        <div class="col-lg-3 item-inner" style="color:#28a745;">
                            <?= $connection->get('status'); ?>
                        </div>
                        <div class="col-lg-3">
                            <div class="add-files btn"><i class="fa fa-plus" aria-hidden="true"></i></div>
                            <div class="item-info btn"><i class="fa fa-eye" aria-hidden="true"></i></div>
                            <div class="remove-item btn btn-danger">-</div>
                        </div>
                        <div style="display:none" class="popup-config popup-add-files">
                            <div class="spoiler col-lg-12">
                                <div class="row">
                                    <div class="close"><i class="fa fa-times" aria-hidden="true"></i></div>
                                    <div class="row file-row">
                                        <div class="popup-headers row">
                                            <div class="col-lg-12 head-item">
                                                Choose files to add/delete
                                            </div>

                                        </div>
                                        <div class="preloader">
                                        <img style="float:right;" src="/wp-content/plugins/moto-parser/assets/img/moonwalk.gif">
                                    </div>
                                    </div>
                                    <?php
                                    $files = $connection->get('cur_files');
                                    foreach ($files as $file_name => $status): ?>


                                    <?php endforeach; ?>


                                </div>
                            </div>
                        </div>
                        <div style="display:none" class="popup-config status-popup">
                            <div class="spoiler col-lg-12">
                                <div class="row">
                                    <div class="close"><i class="fa fa-times" aria-hidden="true"></i></div>
                                    <div class="popup-headers row">
                                        <div class="col-lg-9 head-item">
                                            File name
                                        </div>
                                        <div class="col-lg-3 head-item">
                                            Status
                                        </div>
                                    </div>
                                    <?php
                                    $files = $connection->get('cur_files');
                                    foreach ($files as $file_name => $status): ?>

                                        <div class="row file-row">

                                            <div class="col-lg-9 host-inner item-inner"><?= $file_name ?></div>
                                            <div class="col-lg-3 item-inner" style="color:#28a745;">
                                                <?= $status ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
        <div style="padding: 15px; margin-top:30px;cursor:pointer;" class="load-docs btn btn-primary col-lg-3">
            Load and combine
        </div>
    </div>
    </div>
    <div style="text-align:center; padding:0;display:table;font-size:52px;" class="add-new-item btn btn-primary">
        <div style="text-align:center;display: table-cell; vertical-align: top;" class="inline">+</div>
    </div>

</div>

</div>
<style>
    #wpcontent{
        padding: 0;
    }
</style>