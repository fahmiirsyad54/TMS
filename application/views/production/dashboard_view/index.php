<div class="row">
    <div class="col-lg-4 col-lg-12">  
      <div class="info-box" style="background: #333;">
        <span class="info-box-icon bg-red" style="background: #b30000;">
            <i class="fa fa-user"></i>
        </span>

        <div class="info-box-content" style="background: #333; color: #ffffff;">
          <a href="<?=base_url('/production/profil/view/')?>" style="color: #ffffff;">
            <span class="info-box-number">Profile</span>
          </a>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-lg-12">  
      <div class="info-box" style="background: #333;">
        <span class="info-box-icon bg-yellow" style="background: #b30000;">
            <i class="fa fa-user-plus"></i>
        </span>

        <div class="info-box-content" style="background: #333; color: #ffffff;">
          <a href="<?=base_url('/production/karyawan/view/')?>" style="color: #ffffff;">
          <span class="info-box-number"><?=$jumkaryawan?></span>
            <span class="info-box-text">Employee</span>
          </a>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-lg-12">  
      <div class="info-box" style="background: #333;">
        <span class="info-box-icon bg-green" style="background: #b30000;">
            <i class="fa fa-gg"></i>
        </span>

        <div class="info-box-content" style="background: #333; color: #ffffff;">
          <a href="<?=base_url('/production/pallet/view/')?>" style="color: #ffffff;">
            <span class="info-box-number"><?=$jumpallet?></span>
            <span class="info-box-text">Pallet </span>
          </a>
        </div>
      </div>
    </div>

    <!-- <div class="col-lg-3 col-lg-12">  
      <div class="info-box" style="background: #333;">
        <span class="info-box-icon bg-blue" style="background: #b30000;">
            <i class="fa fa-cart-arrow-down"></i>
        </span>

        <div class="info-box-content" style="background: #333; color: #ffffff;">
          <a href="<?=base_url('/production/permintaan/view/')?>" style="color: #ffffff;">
            <span class="info-box-number">40</span>
            <span class="info-box-text"> Pallet Request </span>
          </a>
        </div>
      </div>
    </div> -->
</div>
<div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>Borrow</h3>
          <p>Total <?=$jumpinjam?> Pallet</p>
          <span class="info-box-number"><?=$jumpinjamtoday?>  pallet today</span>
        </div>
        <div class="icon">
          <i class="ion ion-ios-upload"></i>
        </div>
        <a href="<?=base_url($controller . '/pinjam/view/')?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>Return</h3>
          <p>Total <?=$jumkembali?> Pallet</p>
          <span class="info-box-number"><?=$jumkembalitoday?>  pallet today</span>
        </div>
        <div class="icon">
          <i class="ion ion-ios-download"></i>
        </div>
        <a href="<?=base_url($controller . '/kembali/view/')?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">

      <!-- small box -->
      <div class="small-box bg-yellow" >
        <div class="inner">
          <h3>Repair</h3>
          <p>Total <?=$jumperbaikan?> Pallet</p>
          <span class="info-box-number"><?=$jumperbaikantoday?>  pallet today</span>
        </div>
        <div class="icon">
          <i class="ion ion-hammer"></i>
        </div>
        <a href="<?=base_url($controller . '/perbaikan/view/')?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>Destroy</h3>
          <p>Total <?=$jumrusak?> Pallet</p>
          <span class="info-box-number"><?=$jumrusaktoday?>  pallet today</span>
        </div>
        <div class="icon">
          <i class="ion ion-ios-trash"></i>
        </div>
        <a href="<?=base_url($controller . '/rusak/view/')?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
</div>