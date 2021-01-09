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
					<h1>Paket Shared Hosting</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= base_url('staff/Admin') ?>">Home</a></li>
						<li class="breadcrumb-item active">Paket Shared Hosting</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<?php
					echo $this->session->flashdata('pesan');
					echo $this->session->flashdata('pesan2'); ?>
				</div>
			</div>
			<!-- Small boxes (Stat box) -->
			<div class="row">
				<!-- Khusus Personal Hosting -->
				<div class="col-md-12">
					<div class="card card-dark">
						<div class="card-header">
							<h3 class="card-title">Daftar Paket Shared Hosting</h3>
							<a href="<?= base_url('staff/Admin/tambah_shared'); ?>"><button class="btn btn-sm btn-primary float-right"><i class="fas fa-plus-square"></i> Tambah</button></a>
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<table id="tabelUser" class="table table-bordered table-hover">
								<thead>
								<tr>
									<th class="text-center">No</th>
									<th class="text-center">Nama Produk</th>
									<th class="text-center">Tipe Produk</th>
									<th class="text-center">Harga</th>
									<th class="text-center">Kapasitas</th>
									<th class="text-center">Aksi</th>
								</tr>
								</thead>
								<tbody>
								<?php
								$no =1;
								foreach ($dataPaket->result_array() as $row) :?>
									<tr>
										<td class="text-center"><?= cetak($no++); ?></td>
										<td class="text-center"><?= cetak($row['nama_product']) ?></td>
										<td class="text-center"><?= cetak($row['type_product']) ?></td>
										<td class="text-center"><?= cetak($row['harga']) ?></td>
										<td class="text-center"><?= cetak($row['kapasitas']) ?></td>
										<td class="text-center">
											<a class="btn btn-primary btn-sm" href='<?= base_url("staff/admin/edit_paket/".encrypt_url($row['id_product'])); ?>'>Edit</a>
											<a href="#myAlert" data-toggle="modal" class="btn btn-danger btn-sm">Hapus</a>
										</td>
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