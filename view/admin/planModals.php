<!-- ADD PLAN MODAL -->
<div class="modal fade" id="add-plan-modal" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Add plan</h4>
      </div>
      <div class="modal-body">
        <form id="add-plan-form">
            <div class="form-group">
              <label class="control-label" for="inputName">Name</label>
              <input type="text" class="form-control" id="inputName" placeholder="Enter name" name="name" required>
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label class="control-label" for="inputDescription">Description</label>
              <textarea type="text" class="form-control" id="inputDescription" placeholder="Enter description" name="description" required></textarea>
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label class="control-label" for="inputDifficulty">Difficulty</label>
              <select class="form-control" id="inputDifficulty" name="difficulty" required>
                <option value="1">Easy</option>
                <option value="2">Medium</option>
                <option value="3">Hard</option>
              </select>
              <span class="help-block"></span>
            </div>
            <button id="add-plan-button" type="submit" class="btn btn-success">Add plan</button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- EDIT PLAN MODAL -->
<div class="modal fade" id="edit-plan-modal" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Edit plan</h4>
      </div>
      <div class="modal-body">
        <form id="edit-plan-form">
            <input id="plan-edit-code" type="hidden" name="code" value="">
            <div class="form-group">
              <label class="control-label" for="inputName">Name</label>
              <input type="text" class="form-control" id="inputName" placeholder="Enter name" name="name" required>
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label class="control-label" for="inputDescription">Description</label>
              <textarea type="text" class="form-control" id="inputDescription" placeholder="Enter description" name="description" required></textarea>
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label class="control-label" for="inputDifficulty">Difficulty</label>
              <select class="form-control" id="inputDifficulty" name="difficulty" required>
                <option value="1">Easy</option>
                <option value="2">Medium</option>
                <option value="3">Hard</option>
              </select>
              <span class="help-block"></span>
            </div>
            <button id="edit-plan-button" type="submit" class="btn btn-success">Edit plan</button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- DELETE PLAN MODAL -->
<div class="modal modal-danger fade" id="delete-plan-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Delete plan</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the plan?</p>
      </div>
      <div class="modal-footer">
        <button id="no-delete-plan" type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
        <button id="yes-delete-plan" type="button" class="btn btn-outline">Yes, delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>