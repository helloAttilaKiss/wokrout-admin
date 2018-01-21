<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      plans
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
      <li class="active">plans</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">plan list</h3>
            <div class="box-tools">
              <div class="input-group input-group-sm">
                <button tpye="button" class="btn btn-success" onClick="toggleAddPlanModal()">Add new plan</button>
              </div>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table id="plan-list" class="table table-hover">
              <tbody>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Difficulty</th>
                  <th>Days</th>
                  <th>Action</th>
                </tr>
              <tr>
                  <td colspan="6">Loading... <i class="fa fa-cog fa-spin fa-fw"></i></td>
              </tr>
            </tbody></table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
  $(document).ready(function(){
    refreshPlanList();
  });
</script>
  
  