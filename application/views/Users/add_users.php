<?php
if (!empty($users)) {
  $id = $users->uidnr_admin;
  // echo $id;
  $action = base_url() . "Users/edit_admin_users/" . $id;
  $username = $users->admin_username;
  $email = $users->admin_email;
  $user_role = $users->id_admin_role;
  $crm_flag = $users->crm_flag;
  $lab_analyst = $users->lab_analyst;
  $sales_person = $users->sales_person;
  $first_name = $users->admin_fname;
  $last_name = $users->admin_lname;
  $emp_no = $users->employee_no;
  $initial = $users->admin_initial;
  $address = $users->admin_address;
  $mob_no = $users->admin_telephone;
  $t_zone = $users->time_zone_id;
  $password = "";
  $division = $users->user_division_div_id;
  $designation_id = $users->admin_designation;
  $def_branch_id = $users->default_branch_id;
  $def_division = $users->default_division_id;
  $department_id = $users->dept_id;
  $sign_auth = $users->ap_signing_auth;
} else {
  $id = "";
  $action = base_url('Users/add_new_user');
  $emp_no = set_value('emp_no');
  $role = set_value('role');
  $first_name = set_value('first_name');
  $last_name = set_value('last_name');
  $initial = set_value('initial');
  $username = set_value('username');
  $email = set_value('email');
  $mob_no = set_value('mob_no');
  $address = set_value('address');
  $user_role = set_value('role');
  $crm_flag = set_value('crm_user_id');
  $lab_analyst = set_value('lab_analyst');
  $sales_person = set_value('sales_person');
  $division = set_value('division');
  $def_division = set_value('def_div');
  $department_id = set_value('dept');
  $branch = set_value('branch');
  $def_branch_id = set_value('def_branch');
  $lab_id = set_value('labs');
  $designation_id = set_value('designation');
  $t_zone = set_value('time_zone');
  $password = set_value('password');
  $sign_auth = set_value('sign_auth');
}
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Admin User </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('Users'); ?>">Admin Users List</a></li>
            <li class="breadcrumb-item active">Add Admin User</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>


  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><?php if (empty($users)) {
                                        echo "Create New";
                                      } else {
                                        echo "Edit";
                                      } ?> User</h3>
            </div>
            <!-- /.card-header -->
            <form action="<?php echo $action; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="card-body">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Employee Number :</label>
                      <input type="text" name="emp_no" class="form-control" style="width: 100%;" value="<?php echo $emp_no; ?>">
                      <?php echo form_error('emp_no', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label>First Name :</label>
                      <input type="text" name="first_name" class="form-control" style="width: 100%;" value="<?php echo $first_name; ?>">
                      <?php echo form_error('first_name', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Last Name :</label>
                      <input type="text" name="last_name" class="form-control" style="width: 100%;" value="<?php echo $last_name; ?>">
                      <?php echo form_error('last_name', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Initial :</label>
                      <input type="text" name="initial" class="form-control" style="width: 100%;" value="<?php echo $initial; ?>">
                      <?php echo form_error('initial', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label>Username :</label>
                      <input type="text" name="username" class="form-control" style="width: 100%;" value="<?php echo $username; ?>">
                      <?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label>Email :</label>
                      <input type="email" name="email" class="form-control" style="width: 100%;" value="<?php echo $email; ?>">
                      <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Password :</label>
                      <input type="password" name="password" class="form-control" style="width: 100%;" value="<?php echo $password; ?>">
                      <?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label>Telephone :</label>
                      <input type="text" name="mob_no" class="form-control" style="width: 100%;" value="<?php echo $mob_no; ?>">
                      <?php echo form_error('mob_no', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label>Time Zone :</label>
                      <select name="time_zone" class=" form-control select-box" data-placeholder="Select Timezone....." style="width: 100%;">
                        <option value="">Select Timezone.....</option>
                        <?php foreach ($time_zone as $time) { ?>
                          <option value="<?php echo $time->time_zone_id; ?>" <?php if ($t_zone == $time->time_zone_id) {
                                                                              echo "selected";
                                                                            } ?>><?php echo $time->time_zone_label; ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('time_zone', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                     <label for="">Profile Image</label>
                     <input type="file" name="profile_image" class="form-control form-control-sm">
                    </div>
                    <!-- /.form-group -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Role :</label>
                      <select name="role" class=" form-control select-box" data-placeholder="Select a Role" style="width: 100%;">
                        <option value="">Select Role.....</option>
                        <?php foreach ($roles as $role) { ?>
                          <option value="<?php echo $role->id_admin_role; ?>" <?php if ($user_role == $role->id_admin_role) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $role->admin_role_name; ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('role', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <!-- /.form-group -crm_user-->
                    <div class="form-group">
                      <label>CRM Flag :</label>
                      <select name="crm_user_id" class="form-control select-box" style="width: 100%;">
                        <option value="" disabled>Select CRM.....</option>
                        <option value="1" <?php if ($crm_flag == "1") {
                                            echo "selected";
                                          } ?>>Yes</option>
                        <option value="0" <?php if ($crm_flag == "0") {
                                            echo "selected";
                                          } ?>>No</option>
                      </select>
                      <?php echo form_error('crm_user_id', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                      <label>Lab Analyst Flag :</label>
                      <select name="lab_analyst" class="form-control select-box" style="width: 100%;">
                        <option value="" disabled>Lab Analyst</option>
                        <option value="1" <?php if ($lab_analyst == "1") {
                                            echo "selected";
                                          } ?>>Yes</option>
                        <option value="0" <?php if ($lab_analyst == "0") {
                                            echo "selected";
                                          } ?>>No</option>
                      </select>
                      <?php echo form_error('lab_analyst', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label>Sales Person Flag :</label>
                      <select name="sales_person" class="form-control select-box" style="width: 100%;">
                        <option value="" disabled>Sales Person</option>
                        <option value="1" <?php if ($sales_person == "1") {
                                            echo "selected";
                                          } ?>>Yes</option>
                        <option value="0" <?php if ($sales_person == "0") {
                                            echo "selected";
                                          } ?>>No</option>
                      </select>
                      <?php echo form_error('sales_person', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Division :</label>
                      <select name="division[]" class="form-control select-box" multiple style="width: 100%;">
                        <option value="" disabled>Select Division.....</option>
                        <?php if (empty($id)) {
                          foreach ($default_division as $def_div) { ?>
                            <option value="<?php echo $def_div->division_id; ?>" <?php if (!empty($division)) {
                                                                                  if (in_array($def_div->division_id, $division)) {
                                                                                    echo "selected";
                                                                                  }
                                                                                } ?>><?php echo $def_div->division_name; ?></option>
                            <?php }
                        } else {
                          foreach ($default_division as $def_div) {
                            foreach ($user_divisions as $selected_division) { ?>
                              <option value="<?php echo $def_div->division_id; ?>" <?php if ($def_div->division_id == $selected_division['user_division_div_id']) {
                                                                                    echo "selected";
                                                                                  } ?>><?php echo $def_div->division_name; ?></option>
                        <?php }
                          }
                        } ?>
                      </select>
                      <?php echo form_error('division[]', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label>Default Division :</label>
                      <select name="def_div" class="form-control select-box" style="width: 100%;">
                        <option value="" disabled>Select Default Division.....</option>
                        <?php foreach ($default_division as $def_div) { ?>
                          <option value="<?php echo $def_div->division_id; ?>" <?php if ($def_division == $def_div->division_id) {
                                                                                echo "selected";
                                                                              } ?>><?php echo $def_div->division_name; ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('def_div', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label>Department :</label>
                      <select name="dept" class="form-control select-box" style="width: 100%;">
                        <option value="" disabled>Select Department.....</option>
                        <?php foreach ($department as $dept) { ?>
                          <option value="<?php echo $dept->dept_id; ?>" <?php if ($department_id == $dept->dept_id) {
                                                                          echo "selected";
                                                                        } ?>><?php echo $dept->dept_name; ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('dept', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <label>Branch :</label>
                      <select name="branch[]" class="form-control select-box" multiple style="width: 100%;">
                        <option value="" disabled>Select Branch.....</option>
                        <?php if (empty($id)) {
                          foreach ($default_branch as $def_branch) { ?>
                            <option value="<?php echo $def_branch->branch_id; ?>" <?php if (!empty($branch)) {
                                                                                    if (in_array($def_branch->branch_id, $branch)) {
                                                                                      echo "selected";
                                                                                    }
                                                                                  } ?>><?php echo $def_branch->branch_name; ?></option>
                            <?php }
                        } else {
                          foreach ($default_branch as $def_branch) {
                            foreach ($user_branches as $selected_branch) { ?>
                              <option value="<?php echo $def_branch->branch_id; ?>" <?php if ($def_branch->branch_id == $selected_branch['user_branch_branch_id']) {
                                                                                      echo "selected";
                                                                                    } ?>><?php echo $def_branch->branch_name; ?></option>
                        <?php }
                          }
                        } ?>
                      </select>
                      <?php echo form_error('branch[]', '<div class="text-danger">', '</div>'); ?>
                    </div>


                    <div class="form-group">
                      <label>Default Branch :</label>
                      <select name="def_branch" class="form-control select-box" style="width: 100%;">
                        <option value="" disabled>Select Default Branch.....</option>
                        <?php foreach ($default_branch as $def_branch) { ?>
                          <option value="<?php echo $def_branch->branch_id; ?>" <?php if ($def_branch_id == $def_branch->branch_id) {
                                                                                  echo "selected";
                                                                                } ?>><?php echo $def_branch->branch_name; ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('def_branch', '<div class="text-danger">', '</div>'); ?>
                    </div>


                    <div class="form-group">
                      <label>Labs :</label>
                      <select name="labs[]" class="form-control select-box" multiple style="width: 100%;">
                        <option value="" disabled>Select Labs.....</option>
                        <?php if (empty($id)) {
                          foreach ($labs as $lab) { ?>
                            <option value="<?php echo $lab->lab_id; ?>" <?php if (!empty($lab_id)) {
                                                                          if (in_array($lab->lab_id, $lab_id)) {
                                                                            echo "selected";
                                                                          }
                                                                        } ?>><?php echo $lab->lab_name; ?></option>
                            <?php }
                        } else {
                          foreach ($labs as $lab) {
                            foreach ($user_labs as $selected_lab) { ?>
                              <option value="<?php echo $lab->lab_id; ?>" <?php if ($lab->lab_id == $selected_lab['user_labs_lab_id']) {
                                                                            echo "selected";
                                                                          } ?>><?php echo $lab->lab_name; ?></option>
                        <?php }
                          }
                        } ?>
                      </select>
                      <?php echo form_error('labs[]', '<div class="text-danger">', '</div>'); ?>
                    </div>


                    <div class="form-group">
                      <label>Designation :</label>
                      <select name="designation" class="form-control select-box" style="width: 100%;">
                        <option value="" disabled>Select Designation.....</option>
                        <?php foreach ($designation as $design) { ?>
                          <option value="<?php echo $design->designation_id; ?>" <?php if ($designation_id == $design->designation_id) {
                                                                                  echo "selected";
                                                                                } ?>><?php echo $design->designation_name; ?></option>
                        <?php } ?>
                      </select>
                      <?php echo form_error('designation', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                      <input type="checkbox" id="sign_auth" name="sign_auth" value="1" <?php if ($sign_auth == 1) {
                                                                                          echo "checked";
                                                                                        } ?>>
                      <label for="sign_auth">Signing Authority</label>
                    </div>
                  </div>


                  <!-- /.col -->
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Address :</label>
                      <textarea name="address" style="width: 100%;"><?php echo $address; ?></textarea>
                    </div>
                    <?php echo form_error('address', '<div class="text-danger">', '</div>'); ?>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <div style="margin-left: 43%;">
                  <a href="<?php echo base_url('Users/index'); ?>"><button type="button" class="btn btn-primary">Cancel</button></a>
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </div>
          </div>
          </form>
        </div>
      </div>

    </div>
    <!-- /.col (right) -->
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
</section>
</div>