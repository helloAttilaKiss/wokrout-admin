<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $plan['plan_name']; ?> plan details
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
      <li><a href="/admin/plans"><i class="fa fa-list-alt"></i> Plans</a></li>
      <li class="active">plan details</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">plan</h3>
            <div class="box-tools">
              <div class="btn-group">
                <button tpye="button" class="btn btn-info" onClick="editPlan('<?php echo $plan['code']; ?>');">Edit plan</button>
                <button tpye="button" class="btn btn-success" onClick="toggleAddPlanDayModal();">Add new day</button>
                <button tpye="button" class="btn btn-danger" onClick="deletePlan('<?php echo $plan['code']; ?>', 1);">Delete plan</button>
              </div>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table id="plan-details" class="table table-hover">
              <tbody>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Difficulty</th>
                </tr>
              <tr>
                  <td colspan="4">Loading... <i class="fa fa-cog fa-spin fa-fw"></i></td>
              </tr>
            </tbody></table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
    <!-- /.row -->
    <div id="plan-day-container"></div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- add new day modal -->
<div class="modal fade" id="add-day-modal" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Add day</h4>
      </div>
      <div class="modal-body">
        <form id="add-day-form">
            <input type="hidden" name="code" value="<?php echo $plan['code']; ?>">
            <div class="form-group">
              <label class="control-label" for="inputName">Name</label>
              <input type="text" class="form-control" id="inputName" placeholder="Enter name" name="name" required>
              <span class="help-block"></span>
            </div>
            <button id="add-day-button" type="submit" class="btn btn-success">Add day</button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="edit-day-modal" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Edit day</h4>
      </div>
      <div class="modal-body">
        <form id="edit-day-form">
            <input type="hidden" name="code" value="">
            <div class="form-group">
              <label class="control-label" for="inputName">Name</label>
              <input type="text" class="form-control" id="inputName" placeholder="Enter name" name="name" required>
              <span class="help-block"></span>
            </div>
            <button id="add-day-button" type="submit" class="btn btn-success">Edit day</button>
        </form>
        <br>
        <div id="exercise-container"></div>
        <form id="add-exercise-to-day-form">
            <input type="hidden" name="code" value="">
            <div class="form-group">
              <label class="control-label" for="day-exercise-select">Choose exercise</label>
              <select type="text" class="form-control" id="select-exercise-to-day" name="exercise" required>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label class="control-label" for="inputDuration">Duration (sec)</label>
              <input type="number" min="0" step="1" class="form-control" id="inputDuration" placeholder="Enter duration in seconds" name="exerciseDuration" required>
              <span class="help-block"></span>
            </div>
            <button id="add-exercise-to-day-button" type="submit" class="btn btn-success">Add exercise</button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal modal-danger fade" id="delete-day-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Delete day</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete the day?</p>
        </div>
        <div class="modal-footer">
          <button id="no-delete-day" type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
          <button id="yes-delete-day" type="button" class="btn btn-outline">Yes, delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <div class="modal modal-danger fade" id="delete-exercise-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Delete exercise</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete the exercise?</p>
        </div>
        <div class="modal-footer">
          <button id="no-delete-exercise" type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
          <button id="yes-delete-exercise" type="button" class="btn btn-outline">Yes, delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<script>
const plan = "<?php echo $plan['code']; ?>";
  $(document).ready(function(){
    refreshPlanDetails(plan);
    refreshDayList(plan);
  });
</script>