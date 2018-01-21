<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Users
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
      <li class="active">Users</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">User list</h3>
            <div class="box-tools">
              <div class="input-group input-group-sm">
                <button tpye="button" class="btn btn-success" onClick="toggleAddUserModal()">Add new user</button>
              </div>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table id="user-list" class="table table-hover">
              <tbody>
                <tr>
                  <th>ID</th>
                  <th>First name</th>
                  <th>Last name</th>
                  <th>Email address</th>
                  <th>Plans</th>
                  <th></th>
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
<!-- ADD USER MODAL -->
<div class="modal fade" id="add-user-modal" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Add user</h4>
      </div>
      <div class="modal-body">
        <form id="add-user-form">
            <div class="form-group">
              <label class="control-label" for="inputFirstName">First name</label>
              <input type="text" class="form-control" id="inputFirstName" placeholder="Enter first name" name="firstName" required>
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label class="control-label" for="inputLastName">Last name</label>
              <input type="text" class="form-control" id="inputLastName" placeholder="Enter last name" name="lastName" required>
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label class="control-label" for="inputEmail">Email address</label>
              <input type="email" class="form-control" id="inputEmail" placeholder="Enter email address" name="email" required>
              <span class="help-block"></span>
            </div>
            <button id="add-user-button" type="submit" class="btn btn-success">Add user</button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- EDIT USER MODAL -->
<div class="modal fade" id="edit-user-modal" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Edit user</h4>
      </div>
      <div class="modal-body">
        <form id="edit-user-form">
            <input id="user-edit-code" type="hidden" name="code" value="">
            <div class="form-group">
              <label class="control-label" for="inputFirstName">First name</label>
              <input type="text" class="form-control" id="inputFirstName" placeholder="Enter first name" name="firstName" required>
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label class="control-label" for="inputLastName">Last name</label>
              <input type="text" class="form-control" id="inputLastName" placeholder="Enter last name" name="lastName" required>
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label class="control-label" for="inputEmail">Email address</label>
              <input type="email" class="form-control" id="inputEmail" placeholder="Enter email address" name="email" required>
              <span class="help-block"></span>
            </div>
            <button id="edit-user-button" type="submit" class="btn btn-success">Edit user</button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- DELETE USER MODAL -->
<div class="modal modal-danger fade" id="delete-user-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Delete user</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the user?</p>
      </div>
      <div class="modal-footer">
        <button id="no-delete-user" type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
        <button id="yes-delete-user" type="button" class="btn btn-outline">Yes, delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- ADD PLAN TO USER MODAL -->
<div class="modal fade" id="add-plan-to-user-modal" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Add plan to user</h4>
      </div>
      <div class="modal-body">
         <!-- Plan list -->
         <div id="plan-container"></div>
         <!-- add plan to form -->
        <form id="add-plan-to-user-form">
            <input type="hidden" name="user" value="">
            <div class="form-group">
              <label class="control-label" for="select-plan-to-user">Choose plan</label>
              <select class="form-control" id="select-plan-to-user" name="plan" required> 
                <option value="0">Choose plan</option>
              </select>
              <span class="help-block"></span>
            </div>
            <button id="add-plan-to-user-button" type="submit" class="btn btn-success">Add plan</button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script>
  $(document).ready(function(){
    refreshUserList();
  });
</script>