<style>
    #application_name-error,
    #application_desc-error {
        color: red;
    }
</style>
<script src="<?php echo base_url(); ?>assets/js/webinar.js"></script>
<main class="main" style="min-height: 80vh;">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> MEETING LIST</h2>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <?php if (exist_val('Webinar/add', $this->session->userdata('permission'))) { ?>
                    <button type="button" class="btn btn-sm btn-primary add_application" data-bs-toggle="modal" data-bs-target="#add_application"><span> <i class="fa fa-plus"> </i></span></button>
                <?php } ?>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" class="search form-control form-control-sm" placeholder="SEARCH....." name="" id="">
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-sm btn-primary search_listing">SEARCH</button>
                        <button class="btn btn-sm btn-danger clear_listing">CLEAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class=" mt-3 table-responsive small">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">SL NO</th>
                    <th>TITLE</th>
                    <th>HOST NAME</th>
                    <th>PROFILE</th>
                    <th>DATE</th>
                    <th>START TIME</th>
                    <th>END TIME</th>
                    <th>DESCRIPTION</th>
                    <th>STATUS</th>
                    <th>CREATED BY</th>
                    <th>CREATED ON</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="application_list"></tbody>
        </table>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" id="application_pagination"></div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</main>

<div class="modal fade" id="add_application" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">ADD WEBINAR</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center role_errors"></div>
            </div>
            <form method="post" name="submit_application" action="javscript:void(0);" class="add_submit"><input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12"><label for="">Title Name</label><input type="text" name="title" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"><label for="">Host Name</label><input type="text" name="host_name" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                        <div class="col-sm-6"><label for="">Profile Name</label><input type="text" name="profile" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><label for="">DATE</label><input type="date" name="date" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                        <div class="col-sm-4"><label for="">START TIME</label><input type="time" name="start_time" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                        <div class="col-sm-4"><label for="">END Name</label><input type="time" name="end_time" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><label for="">LINK</label><input type="text" name="link" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><label for="">DESCRIPTION</label><textarea name="desc" id="" class="form-control form-control-sm" placeholder="Type here......" ></textarea></div>
                    </div>

                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary">ADD</button></div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="edit_application" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">EDIT ROLE</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="row">
                <div class="col-sm-12 role_edit_errors"></div>
            </div>
            <form method="post" class="edit_submit_application" action="javscript:void(0);">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" class="edit_application_id" value="">
                <div class="modal-body">
                <div class="row">
                        <div class="col-sm-12"><label for="">Title Name</label><input type="text" name="title" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"><label for="">Host Name</label><input type="text" name="host_name" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                        <div class="col-sm-6"><label for="">Profile Name</label><input type="text" name="profile" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><label for="">DATE</label><input type="date" name="date" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                        <div class="col-sm-4"><label for="">START TIME</label><input type="time" name="start_time" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                        <div class="col-sm-4"><label for="">END Name</label><input type="time" name="end_time" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><label for="">LINK</label><input type="text" name="link" id="" class="form-control form-control-sm" placeholder="Type here......"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><label for="">DESCRIPTION</label><textarea name="desc" id="" class="form-control form-control-sm" placeholder="Type here......" ></textarea></div>
                    </div>

                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary edit_application_submit">UPDATE</button></div>
            </form>
        </div>
    </div>
</div>