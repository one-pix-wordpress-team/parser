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
                <div class="popup-config"></div>
                <div class="spoiler col-lg-12">
                    <div class="row">
                    <div class="login-info col-lg-6 item-inner"><input class="w-100 parser-input" name="host-name" value="login"></div>
                    <div class="password-info col-lg-3 item-inner"><input class="w-100 parser-input" name="host-name" value="password"></div>
                        <div class="edit col-lg-3 item-inner"></div>
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