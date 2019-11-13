<?php
require_once 'initialize.php';

$core = dataCore::instance();
$partsUnlimited = $core->get_data('partsUnlimited');
$partsUnlimited = is_array($partsUnlimited) ? array_shift($partsUnlimited) : [];

// include 'runner.php';
?>
<style>
    body{
        overflow-x: hidden;
    }
    #wpfooter {
        position: fixed;
        bottom: 0;
    }
</style>

<div class="parser-header">
    <div class="container">
        <div class="row header-row">
            <div class="col-lg-4" style="padding-left: 0;">
                <h1>MotoWorld Parser</h1>
            </div>
        </div>
    </div>
</div>
<div class="container">
<div class="row main-parser-wrapper">
    <input type="radio" name="list" id="partsUn"/>
    <label for="partsUn">Parts Unlimited</label>
    <input type="radio" name="list" checked="checked" id="ftp"/>
    <label for="ftp">FTP</label>
    <div class="parts-unlimited top-tab w-100">
            <form style="margin-top:30px;" class="parts-unlimited-form row">
                <h4 style="border-bottom: 4px dashed #ededed; margin-bottom:30px; padding-bottom:15px;" class="w-100 col-lg-12" style="margin-bottom: 30px;">Parts Unlimited credentials</h4>
                <div class="col-lg-3">
                   <input class="parser-input w-100" placeholder="Distributor id" name="dist_id" type="text" value="<?= $partsUnlimited['dist_id'] ?? '' ?>">
                </div>
                <div class="col-lg-3">
                    <input class="parser-input w-100" placeholder="Distributor user" name="dist_user" type="text" value="<?= $partsUnlimited['dist_user'] ?? '' ?>">
                </div>
                <div class="col-lg-6">
                    <input class="parser-input w-100" placeholder="Distributor password" name="dist_password" type="text" value="<?= isset($partsUnlimited['dist_password'])?'******':'' ?>">
                </div>
                <div style="margin-top:15px" class="col-lg-3">
                    <button type="submit" class="w-100 parser-input btn btn-primary">Save</button>
                </div>
            </form>
        </div>

    <div class="ftp-docs top-tab">
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
                                    <div class="remove-item btn btn-danger"><i class="fa fa-minus" aria-hidden="true"></i>
                                    </div>
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
                                                <div class="container">
                                                    <form class="accept-files">
                                                        <div class="row add-files-inner">

                                                            <div class="preloader">

                                                            </div>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

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

                                                    <div class="col-lg-9 item-inner"><?= $file_name ?></div>
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
                        Combine
                    </div>
                    <div style="padding: 15px; margin-left: 15px; margin-top:30px;cursor:pointer;" class="update-docs btn btn-primary col-lg-3">
                        Updatenut' record
                    </div>
                </div>
            </div>
            <div style="text-align:center; padding:0;font-size:33px;padding-top: 10px;" class="add-new-item btn btn-primary">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </div>

        </div>

    </div>
</div>
<div class="w-100 message success-message">

</div>

<div class="w-100 message error-message">

</div>

<div class="new-item-append" style="">

</div>

</div>
<style>
    #wpcontent{
        padding: 0;
    }
</style>
