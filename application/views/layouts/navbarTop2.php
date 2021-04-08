<header class="main-header">
    <!-- Logo -->
    
    <!-- Header Navbar: style can be found in header.less -->
    
    <nav class="navbar navbar-static-top" style="background: #222d32">
        <!-- <div class="container"> -->
            <div class="navbar-header">
                <a href="<?=base_url()?>" class="logo" style="background: #222d32">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>T</b>MS</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg" ><b>Tooling</b> System</span>
                </a>
            </div>
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse" style="background: #222d32">
                <ul class="nav navbar-nav">
                <?php
                    echo $this->mymenu->get_menu();
                ?>
                </ul>
            </div>
            <div class="navbar-custom-menu" style="background: #222d32">
                <ul class="nav navbar-nav" class="collapse navbar-collapse pull-left">

                    <li class="dropdown messages-menu">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 0 messages today, message unread</li>
                        <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">
                            <li><!-- start message -->
                            <a href="" style="background-color: " onclick="">
                                <div class="pull-left">
                                <img src="<?=BASE_URL_PATH?>assets/dist/img/iconuser.png" class="user-image" alt="User Image">
                                </div>
                                <h4>
                                <small><i class="fa fa-clock-o"></i> <?=date('H:i:s')?></small>
                                </h4>
                            </a>
                            </li>
                            <!-- end message -->
                        </ul>
                        </li>
                        <li class="footer"><a href="">See All Messages</a></li>
                    </ul>
                    </li>

                    <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?=BASE_URL_PATH?>assets/dist/img/iconuser.png" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?=$this->session->vcnama?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                        <img src="<?=BASE_URL_PATH?>assets/dist/img/iconuser.png" class="img-circle" alt="User Image">

                        <p>
                            <?=$this->session->vcnama?>
                            <small></small>
                        </p>
                        </li>
                        <li class="user-footer">
                        <!-- <div class="pull-left">
                            <a href="<?=base_url('profil/view')?>" class="btn btn-default btn-flat">Profile</a>
                        </div> -->
                        <div class="pull-right">
                            <a href="<?=base_url('akses/logout')?>" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                        </li>
                    </ul>
                    </li>
                </ul>
            </div>
        <!-- </div> -->
    </nav>
    </header>

<div id="modalNotes" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content" id="datanotes">
    </div>
  </div>
</div>
<script type="text/javascript">
  function detailNotes(intid, intstatus) {
    var base_url = '<?=base_url('notes')?>';
    $.ajax({
      url: base_url + '/detail/' + intid,
      method: "GET"
    })
    .done(function( data ) {
      $('#datanotes').html(data);
      $('#modalNotes').modal('show');
      $.ajax({
      url: base_url + '/aksi/detailnotes/' + intid + '/' + intstatus,
      method: "GET"
      })
    })
    .fail(function( jqXHR, statusText ) {
      alert( "Request failed: " + jqXHR.status );
    });
  }
</script>