<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Service</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('member') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Service</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <!-- Khusus Personal Hosting -->
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Layanan Hosting Anda</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tabelku" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">Domain</th>
                                        <th class="text-center">Pricing</th>
                                        <th class="text-center">Next Due Date</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($dataService->result_array() as $row) :
                                        $idHosting = $row['id_hosting'];
                                        $namaService = $row['nama_hosting'];
                                        $domain = $row['domain'];
                                        $harga = number_format($row['harga'], 0, ",", ".");
                                        $dateRegister = konversiTanggal($row['start_hosting']);
                                        $status = htmlentities($row['status_hosting'], ENT_QUOTES, 'UTF-8');
                                        if ($status == 1) {
                                            $statusHosting = '<small class=\"badge badge-warning\"> AKTIF </small>';
                                        } else if ($status == 2) {
                                            $statusHosting = "<small class='badge badge-warning'> PENDING </small>";
                                        } else if ($status == 3) {
                                            $statusHosting = 'SUSPEND';
                                        } else {
                                            $statusHosting = 'TERMINATED';
                                        }
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= htmlentities($no++, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-center"><?= htmlentities($namaService, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-center"><?= htmlentities($domain, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-center">Rp. <?= htmlentities($harga, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-center"><?= htmlentities($dateRegister, ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-center"><?= $statusHosting ?></td>
                                            <td class="text-center"><a class="btn btn-primary" href="<?= base_url('service/detailhosting/' . htmlentities($idHosting, ENT_QUOTES, 'UTF-8')) ?>">Detail</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->