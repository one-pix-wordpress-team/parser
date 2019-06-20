<?php
require_once 'header/header.php';
?>
<div class="parser-body">
<div class="container">
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
        <div class="parser-item w-100">
            <div class="row">
                <div class="col-lg-6 host-inner item-inner">ftp://test.motoworld.ru</div>
                <div class="col-lg-3 item-inner" style="color:#28a745;">
                  Working
                </div>
                <div class="col-lg-3">
                    <div class="item-info btn"><i class="fa fa-eye" aria-hidden="true"></i></div><div class="remove-item btn btn-danger">-</div>
                </div>
                <div style="display:none" class="popup-config"><div class="spoiler col-lg-12">
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
                            <div class="row file-row">
                                <div class="col-lg-9 host-inner item-inner">File</div>
                                <div class="col-lg-3 item-inner" style="color:#28a745;">
                                    Working
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="add-new-item btn btn-primary">
        +
    </div>
</div>

</div>