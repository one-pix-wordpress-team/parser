<?php
/**
 * @package moto-parser
 */

require_once 'initialize.php';

include 'runner.php';

exit;

require_once 'header/header.php';
?>
<div class="parser-body">
<div class="container">
    <div class="row">
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
                <div class="col-lg-6 item-inner">
                   ftp://test.motoworld.ru
                </div>
                <div class="col-lg-3 item-inner" style="color:#28a745;">
                  Working
                </div>
                <div class="col-lg-3">
                   <div class="remove-item btn btn-danger">-</div>
                </div>
            </div>
        </div>
 <div class="new-parser-item parser-item w-100">
     <div class="row">
 <div class="col-lg-6">
 <input class="w-100 parser-input" name="host-name" placeholder="Input host name">
 </div>
     <div class="col-lg-3">
         <input class="w-100 parser-input" name="username" placeholder="Input username">
     </div>
     <div class="col-lg-3">
         <input class="w-100 parser-input" name="password" placeholder="Input password">
     </div>
         <div class="separator w-100"></div>
         <div class="col-lg-3 ml-auto">
             <button class="test-connection w-100 btn btn-primary">Test connection</button>
         </div>
 </div>
 </div>
    </div>
    <div class="add-new-item btn btn-primary">
        +
    </div>
</div>

</div>
