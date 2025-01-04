@extends('app')

@section('content')
<?php $session = Session::all();
?>
<link rel="stylesheet" href="{{ my_asset('/assets/vendor/datatables.net-bs4/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ my_asset('/assets/css/usermanage.css') }}">


<div class="need-gap" style="margin-top: 85px;">
    <div class="container">
        <div class="row">
           <!--  <div class="col">
                <h4>Users</h4>
            </div> -->
        </div>
    </div>
</div>
<!-- Tasks Start -->

<div class="page-wrap">
    <div>
        <div class="d-flex justify-content-between mb-3">
            <span class="h4">Users</span>
            <button class="fc-button btn btn-primary txt-14" onClick="addUser()"><span class="mdi mdi-plus "></span> Add
                New
                User</button>
        </div> 
        <div class="container-fluid btn-group-container w-100 shadow-sm p-3 mb-3">
            <table class="table dataTable equal-space-table-user" data-plugin="dataTable" id="tblusers">
                <thead>
                    <tr>
                        <!-- <th>Sl.No</th> -->
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>UserId</th>
                        <!-- <th>Email</th> -->
                        <!-- <th>Phone</th> -->
                        <!-- <th>Role</th> -->
                        <th>Last Login</th>
                        <th style="text-align:center;">Status</th>
                        <th>Option</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                        $permissions = explode(
                            ",",
                            $session["data"][0]["permissions"]
                        );
                        if ($liststatus == 200) {
                            foreach ($userData as $key => $data) { ?>
                    <tr class="txt-14 thickest">
                        <!-- <td> {{ $data->id }} </td> -->
                        <td>{{ $data->firstname }}</td>
                        <td>{{ $data->lastname }}</td>
                        <td>{{ $data->username }}</td>
                        <!-- <td>{{ $data->email }}</td> -->
                        <!-- <td>{{ $data->phone }}</td> -->
                        <!-- <td>{{ $data->role }}, {{ $data->rolename }}</td> -->
                        <td>{{ $data->last_login }}</td>
                        <td style="text-align:center;">
                            @if ($data->locked_status == 'A')
                            <span class="btn btn-dark-blue updateStatus" data-toggle="modal" data-id="1"
                                type="button" id="{{ $data->id }}" >Active</span>
                                <!-- <span class="thickest"><span class="mdi mdi-check-circle" style="color:#1E7D38;font-size:16px;" aria-hidden="true"></span></span> -->
                            @else
                            <span class="btn btn-brown updateStatus" data-toggle="modal" data-id="1"
                                type="button" id="{{ $data->id }}" onClick="updateStatus(this.id,'A')">Account Locked</span>
                                <!-- <span class="thickest"><span class="txt-14 mdi mdi-window-close" style="color:#FF0000;" aria-hidden="true"></span></span> -->
                            @endif
                        </td>
                        <td class="text-end">
                            <span class="btn btn-dark-blue ajaxEditmodel allige" data-toggle="modal" data-id="1"
                                type="button" id="{{ $data->id }}" onClick="editUser(this.id)"><i
                                    class="fa fa-pencil"></i></span>
                            <input type="hidden" name="email_id" id="email_id_{{ $data->id }}"
                                value="{{ $data->email }}">
                            <span class="btn btn-brown ajaxEditmodel alligd" data-toggle="modal" data-id="1"
                                type="button" id="{{ $data->id }}" onClick="deleteUser(this.id)"><i
                                    class="bi bi-trash"></i></span>
                            @if ($session['data'][0]['role'] == 'ssadmin,')
                                <span class="btn btn-green ajaxEditmodel alligd" data-toggle="modal" data-id="1"
                                    type="button" id="{{ $data->id }}" onClick="mailSendToUser(this.id)"><i
                                        class="bi bi-envelope"></i></span>
                            @endif
                            @if($data->providerid > 0 && $session['data'][0]['role'] == 'ssadmin,')
                                <span class="btn btn-xs btn-info providerConfig" title="Provider Config" data-id="{{ $data->providerid }}"><i class="fa fa-cogs"> </i></span>
                            @endif

                        </td>
                    </tr>
                    <?php }
                    } else {
                        echo "No Data Available";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>


<div class="page-content">
    <!-- Panel Basic -->

    <!-- End Panel Basic -->
</div>

<!-- add user modal start hear -->
<div class="modal fade" id="addNewEvent" aria-hidden="true" aria-labelledby="addNewEvent" role="dialog" tabindex="-1">

    <div class="modal-dialog modal-simple modal-lg">
        <form class="modal-content form-horizontal" action="#" method="post" role="form" id="addUserForm">
            <div class="modal-header">
                <h4 class="modal-title" style="width:100%; text-align:center; font-weight: 500;">ADD USER</h4>
                <button type="button" class="btn btn-icon btn-danger btn-round btn-close" aria-hidden="true" data-bs-dismiss="modal"><i class="icon md-close" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                <div class="row" id="addClass">
                </div>



                <div class="table-responsive" id="user_details"></div>
                <div class="col-md-12">

                    <div class="row ">
                        <input type="hidden" id="addUserId" name="addUserId" value="" />
                        <div class="col-md-6">
                            <div class="form-group">
                                <lable class="">First Name *</lable>
                                <input type="text" class="form-control" id="addFName" name="addFName" placeholder="First Name" required maxlength="100">
                                <span id="firstname" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <lable class="">Last Name *</lable>
                                <input type="text" class="form-control" id="addLName" name="addLName" placeholder="Last Name" required maxlength="100">
                                <span id="lastname" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <lable class="">User Id *</lable>
                                <input type="text" class="form-control inputClass" id="addUID" name="addUID" placeholder="User Id" required maxlength="100">
                                <span id="userid" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <lable class="">Email *</lable>
                                <input type="email" class="form-control" id="addEmail" name="addEmail" placeholder="Email" required onkeyup="isEmailCheck(this.value)" onchange="isEmail(this.value)" maxlength="100">
                                <span id="gmail" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <lable class="">Phone *</lable>
                                <input type="phone" class="form-control" id="addPhone" name="addPhone" placeholder="Phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" required>
                                <span id="mobile" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <lable class="">Status *</lable>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="">Select</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <span id="status_error_msg" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <lable class="">Roles *</lable>
                                <select class="form-control" name="role" id="role" required>
                                    <option value="">Select</option>
                                    @if ($rolesstatus == 200)
                                    @foreach ($roleData as $key => $roled)
                                    <option value="{{ $roled->id }}">{{ $roled->role }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span id="role_error_msg" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:none;" id="role_base">
                <div id="role_based" style="margin-left: 25px;">
                    <h5>Home Page privileges</h5>
                </div>
                @if ($statusprivileges == 200)
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">

                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'Home Screen')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="per_checkbox" class="uncheckpri per_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach

                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div id="role_based" style="margin-left: 25px;">
                    <h5>Tasks Page Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">

                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'Tasks')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="per_checkbox" class="uncheckpri per_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach


                </div>

                <div id="role_based" style="margin-left: 25px;">
                    <h5>Sidebar Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">
                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'Side Bar')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="per_checkbox" class="uncheckpri per_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div id="role_based" style="margin-left: 25px;">
                    <h5>RPM Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">
                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'RPM')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="per_checkbox" class="uncheckpri per_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div id="role_based" style="margin-left: 25px;">
                    <h5>Payment Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">
                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'Payment')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="per_checkbox" class="uncheckpri per_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div id="role_based" style="margin-left: 25px;">
                    <h5>QA Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">
                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'QA')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="per_checkbox" class="uncheckpri per_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>


                @endif

                
            </div>

            <div class="modal-footer">
                <span id="exist_user" class="text-danger font-weight-bold"></span>
                <div class="form-actions">

                    <button type="button" class="btn btn-brown" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-green" type="button" id="addUserDetails">Save</button>
                </div>
            </div>

        </form>

    </div>
</div>
<!-- add user modal end hear -->
<!-- edit user modal start hear -->
<div class="modal fade" id="editNewEvent" aria-hidden="true" aria-labelledby="editNewEvent" role="dialog" tabindex="-1">
    <!-- <div class="modal-loader"></div> -->
    <div class="modal-dialog modal-simple modal-lg">
        <form class="modal-content form-horizontal" action="#" method="post" role="form" id="editUserForm">
            <div class="modal-header">
                <h4 class="modal-title" style="width:100%; text-align:center; font-weight: 500;">Edit USER</h4>
                <button type="button" class="btn btn-icon btn-danger btn-round btn-close" aria-hidden="true" data-bs-dismiss="modal"><i class="icon md-close" aria-hidden="true"></i></button>
            </div>
            <div class="modal-body">
                <div class="row" id="addClass">
                </div>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" id="spinner" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="table-responsive" id="user_details"></div>
                <div class="col-md-12">
                    <div class="modal-loader"></div>
                    <div class="row ">
                        <input type="hidden" id="editUserId" name="editUserId" />
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">First Name *</label>
                                <input type="text" class="form-control" id="editFName" name="editFName" placeholder="First Name" required maxlength="100">
                                <span id="efirstname" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Last Name *</label>
                                <input type="text" class="form-control" id="editLName" name="editLName" placeholder="Last Name" required maxlength="100">
                                <span id="elastname" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">User Id *</label>
                                <input type="text" class="form-control inputClass" id="eidtUID" name="eidtUID" placeholder="User Id" required maxlength="100">
                                <span id="euserid" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Email *</label>
                                <input type="text" class="form-control" id="editEmail" name="editEmail" placeholder="Email" required onkeyup="isEmailCheckEdit(this.value)" onchange="isEmailEdit(this.value)" maxlength="100">
                                <span id="egmail" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="">Password *</label>
                                                                                    <input type="password" class="form-control" id="editPassword" name="editPassword" placeholder="Password" required>
                                                                                    <span id="epwd" class="text-danger font-weight-bold"></span>
                                                                                </div>
                                                                            </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Phone *</label>
                                <input type="text" class="form-control" id="editPhone" name="editPhone" placeholder="Phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" required>
                                <span id="emobile" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Status *</label>
                                <select class="form-control" name="editstatus" id="editstatus" required>
                                    <option value="">Select</option>
                                    <option value="A">Active</option>
                                    <option value="I">Inactive</option>
                                </select>
                                <span id="estatus_error_msg" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Roles *</label>
                                <select class="form-control" name="editrole" id="editrole" required>
                                    <option value="">Select</option>
                                    @if ($rolesstatus == 200)
                                    @foreach ($roleData as $key => $roled)
                                    <option value="{{ $roled->id }}">{{ $roled->role }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span id="erole_error_msg" class="text-danger font-weight-bold"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="role_base">
                <div id="role_based" style="margin-left: 25px;">
                    <h5>Home Page privileges</h5>
                </div>
                @if ($statusprivileges == 200)
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">

                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'Home Screen')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="editper_Checkbox" class="uncheckpriedit editper_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach


                </div>
                <div id="role_based" style="margin-left: 25px;">
                    <h5>Tasks Page Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">

                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'Tasks')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="editper_Checkbox" class="uncheckpriedit editper_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach


                </div>

                <div id="role_based" style="margin-left: 25px;">
                    <h5>Sidebar Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">
                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'Side Bar')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="editper_Checkbox" class="uncheckpriedit editper_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div id="role_based" style="margin-left: 25px;">
                    <h5>RPM Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">
                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'RPM')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="editper_Checkbox" class="uncheckpriedit editper_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div id="role_based" style="margin-left: 25px;">
                    <h5>Payment Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">
                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'Payment')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="editper_Checkbox" class="uncheckpriedit editper_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                
                <div id="role_based" style="margin-left: 25px;">
                    <h5>QA Privileges</h5>
                </div>
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">
                    @foreach ($privilageData as $key => $privilages)
                    @if ($privilages->page == 'QA')
                    <div class="col-md-3 provider">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="editper_Checkbox" class="uncheckpriedit editper_Checkbox_{{ $privilages->id }}" autocomplete="off" value="{{ $privilages->id }}">
                            <label for="inputBasicRemember" style="color:black; font-weight:100;">{{ $privilages->privilege }}</label>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                @endif

            </div>
            <div class="modal-footer">
                <span id="editexist_user" class="text-danger font-weight-bold"></span>
                <div class="form-actions">
                    <button type="button" class="btn btn-brown" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-green" type="button" id="editUserDetails">Update</button>
                </div>
            </div>

        </form>

    </div>
</div>
<!-- edit user modal end hear -->
<!-- custome delete user modal start hear -->
<div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                You want to delete it?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark-blue" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-brown" id="oktodelete">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- custome delete user modal start hear -->


<!-- custom update status user modal start hear -->
<div class="modal fade" id="ModalCenterForUpdateStatus" tabindex="-1" role="dialog" aria-labelledby="ModalCenterForUpdateStatusTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    You want to unlock the account ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark-blue" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-brown" id="oktoupdatestatus">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- custome update status user modal start hear -->


<!-- custom mail sent to user modal start hear -->
<div class="modal fade" id="emailModalCenter" tabindex="-1" role="dialog" aria-labelledby="emailModalCenterTitle" aria-hidden="true">
    <div class="modal-loader"></div>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="d-flex justify-content-center">
                <div class="spinner-border" id="" role="status" style="display:none !important;">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div class="modal-body">
                You want to send Password Reset Link?
            </div>
            <div class="modal-footer">
                <div class="text-danger font-weight-bold" id="responseMsgId" role="alert"></div>

                <button type="button" class="btn btn-dark-blue" data-bs-dismiss="modal">No</button>
                <div id="mailsend">
                    <button type="button" class="btn btn-brown" id="okToSendMail">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- custome delete user modal start hear -->
<!--  Modal start-->
<div class="modal fade" id="user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="margin-top: 80px;">
        <div class="modal-header">
            <h3 class="modal-title usermodeltitle" id="exampleModalLabel" style="text-align:center;"> User</h3>
            <button type="button" class="btn-close-red" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <div class="card-body">
        <div class="usrcontainer"></div>

        </div>
        </div>
        
    </div>
  </div>
</div>

<!--  Modal End-->
@endsection

@section('jscontent')
<script src="{{ my_asset('/assets/vendor/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ my_asset('/assets/vendor/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#tblusers').DataTable({
            iDisplayLength: 50,
            aaSorting: [5, "asc"],
            language: {
                search: '',
            },
            initComplete: function(settings) {
                var searchInput = $(this).closest('.dataTables_wrapper').find(
                    'div.dataTables_filter input');
                searchInput.attr('placeholder', 'Search...');
                searchInput.wrap('<div class="search-wrapper"></div>');
            }

        });
    });

    function addUser() {
        $('.invalid-feedback').hide();
        $('.invalid-p-pid').hide();
        $('.modal-loader').hide();
        $('#addClass').addClass('form-group');
        $('#addNewEvent').modal('show');
    }


    function editUser(userid) {
        $("#editFName").val('');
        $("#editLName").val('');
        $("#eidtUID").val('');
        $("#editEmail").val('');
        $("#editPassword").val('');
        $("#editPhone").val('');
        $("#editstatus").val('');
        $("#editrole").val('');
        $(".editper_Checkbox").val('');

        $('.spinner-border').show();
        $.ajax({
            type: "post",
            url: "{{ url('users/get_userbyid') }}",
            data: {
                _token: "{{ csrf_token() }}",
                "uid": userid
            },

            success: function(response) {
                var data = jQuery.parseJSON(response);
                console.log(data);
                $("#editUserId").val(data.id);
                $("#editFName").val(data.firstname);
                $("#editLName").val(data.lastname);
                $("#eidtUID").val(data.username);
                $("#editEmail").val(data.email);
                // $("#editPassword").val(data.pwd);
                $("#editPhone").val(data.phone);
                $('#editstatus').val(data.status);
                $('#editrole').val(data.grole);

                $.each(data.permissions1, function(key, value) {

                    $('.editper_Checkbox_' + value).prop('checked', true);
                })

                $('.spinner-border').hide();
            },
            error: function(response) {
                toastr.error('Unable to delete user.');

            }
        })
        $('#editNewEvent').modal('show');
        $('#editrole_base').show();
    }

    function deleteUser(userid) {

        $('#ModalCenter').modal('show');
        $("#oktodelete").on("click", function() {
            $.ajax({
                type: "post",
                url: "{{ url('users/delete_user') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    "uid": userid
                },
                success: function(response) {

                    $('#ModalCenter').modal('hide');
                    toastr.success('User Deleted successfully.');
                    $(document).ajaxStop(function() {
                        window.location.reload();
                    });

                },
                error: function(response) {
                    toastr.error('Unable to delete user.');

                }
            })
        })
    }


    $('#addUserDetails').click(function() {

        let u_fname = $('#addFName').val();
        let u_lname = $('#addLName').val();
        let u_uid = $('#addUID').val();
        let u_email = $('#addEmail').val();
        // let u_pwd = $('#addPassword').val();
        let u_phone = $('#addPhone').val();
        let u_status_id = $('#status :checked').val();
        let u_role_id = $('#role :checked').val();
        i = 0;
        var permis = [];
        $('#per_checkbox:checked').each(function() {
            permis[i++] = $(this).val();
        });
        // var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
        var filter = /^(0|91)?[1-9][0-9]{9}$/;

        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        
        if (u_fname == "" || u_fname.trim() === '') {
            document.getElementById("firstname").innerHTML = " Please fill the firstname field ";
            return false;
        } else {
            document.getElementById("firstname").innerHTML = "";
        }

        if (u_lname == "" || u_lname.trim() === '') {
            document.getElementById("lastname").innerHTML = " Please fill the lastname field ";
            return false;
        } else {
            document.getElementById("lastname").innerHTML = "";
        }

        if (u_uid == "" || u_uid.trim() === '') {
            document.getElementById("userid").innerHTML = " Please fill the userid field ";
            return false;
        } else {
            document.getElementById("userid").innerHTML = "";
        }

        if (!regex.test(u_email)) {
            $("#gmail").html("Email address is Invalid");
            return false;
        } else {
            document.getElementById("gmail").innerHTML = "";
        }

        if (u_phone == "" || u_phone.trim() === '') {
            document.getElementById("mobile").innerHTML = " Please fill the phone number field ";
            return false;
        }
        if (!(filter.test(u_phone))) {
            document.getElementById("mobile").innerHTML = " Please Enter Valid number";
            return false;
        } else {
            document.getElementById("mobile").innerHTML = "";
        }

        if (u_phone.length > 10 || u_phone.length < 10) {
            document.getElementById("mobile").innerHTML = " Please enter 10 digits";
            return false;
        }

        if (u_status_id == "") {
            document.getElementById("status_error_msg").innerHTML = " Please choose status ";
            return false;
        } else {
            document.getElementById("status_error_msg").innerHTML = "";
        }

        if (u_role_id == "") {
            document.getElementById("role_error_msg").innerHTML = " Please choose role ";
            return false;
        } else {
            document.getElementById("role_error_msg").innerHTML = "";
        }
        //console.log(u_fname,u_lname,u_uid,u_email,u_pwd,u_phone,u_status_id,u_role_id,permis);return false;

        $('.modal-loader').show();
        $('.spinner-border').show();
        $.ajax({
            type: "post",
            url: "{{ url('users/add_user') }}",
            data: {
                _token: "{{ csrf_token() }}",
                "fname": u_fname,
                "lname": u_lname,
                "uid": u_uid,
                "email": u_email,
                "phone": u_phone,
                "statusid": u_status_id,
                "roleid": u_role_id,
                "permissions": permis
            },

            success: function(response) {
                var data = jQuery.parseJSON(response);
                if (data.status == 201) {
                    $('.spinner-border').hide();
                    $("#exist_user").html(
                        "Your user ID or email address exists. Please select a unique user ID and Email Address."
                    );
                    return false;
                }
                $('.spinner-border').show();
                $('.modal-loader').hide();
                toastr.success('User created successfully.');

                $('#addNewEvent').modal('hide');
                $('#addUserForm')[0].reset();
                $("#role_base").hide();
                window.location.reload();
                // $(document).ajaxStop(function() {
                //     window.location.reload();
                // });
            },
            error: function(response) {
                $('.modal-loader').hide();
                $('.spinner-border').hide();
                toastr.error('Unable to create user.');

            }
        })
    });


    $('#editUserDetails').click(function() {

        let u_id = $('#editUserId').val();
        let u_fname = $('#editFName').val();
        let u_lname = $('#editLName').val();
        let u_uid = $('#eidtUID').val();
        let u_email = $('#editEmail').val();
        // let u_pwd = $('#editPassword').val();
        let u_phone = $('#editPhone').val();
        let u_status_id = $('#editstatus :checked').val();
        let u_role_id = $('#editrole :checked').val();
        i = 0;
        var permis = [];
        $('#editper_Checkbox:checked').each(function() {
            permis[i++] = $(this).val();
        });
        // var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
        var filter = /^(0|91)?[1-9][0-9]{9}$/;
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        //console.log(u_fname,u_lname,u_uid,u_email,u_pwd,u_phone,u_status_id,u_role_id,permis);return false;
        document.getElementById("efirstname").innerHTML = " ";

        if (u_fname == "" || u_fname.trim() === '') {
            document.getElementById("efirstname").innerHTML = " Please fill the firstname field ";
            return false;
        }

        document.getElementById("elastname").innerHTML = " ";

        if (u_lname == "" || u_lname.trim() === '') {
            document.getElementById("elastname").innerHTML = " Please fill the lastname field ";
            return false;
        }

        document.getElementById("euserid").innerHTML = "";

        if (u_uid == "" || u_uid.trim() === '') {
            document.getElementById("euserid").innerHTML = " Please fill the userid field ";
            return false;
        }

        // if (u_email == "") {
        //     document.getElementById("egmail").innerHTML = " Please fill the email field ";
        //     return false;
        // }

        if (!regex.test(u_email)) {
            $("#egmail").html("Email address is Invalid");
            $('#editEmail').focus();
            return false;
        } else {
            document.getElementById("egmail").innerHTML = "";
        }


        // if (u_pwd == "") {
        //     document.getElementById("epwd").innerHTML = " Please fill the password field ";
        //     return false;
        // } else {
        //     document.getElementById("epwd").innerHTML = "";
        // }

        document.getElementById("emobile").innerHTML = "";

        if (u_phone == "" || u_phone.trim() === '') {
            document.getElementById("emobile").innerHTML = " Please fill the phone number field ";
            return false;
        }
        document.getElementById("emobile").innerHTML = "";

        if (u_phone.length > 10 || u_phone.length < 10) {
            document.getElementById("emobile").innerHTML = " Please enter 10 digits";
            return false;
        }
        document.getElementById("emobile").innerHTML = "";
        if (!(filter.test(u_phone))) {
            document.getElementById("emobile").innerHTML = " Please Enter Valid number";
            return false;
        }

        document.getElementById("estatus_error_msg").innerHTML = "";
        if (u_status_id == "") {
            document.getElementById("estatus_error_msg").innerHTML = " Please choose status ";
            return false;
        }
        document.getElementById("erole_error_msg").innerHTML = "";
        if (u_role_id == "") {
            document.getElementById("erole_error_msg").innerHTML = " Please choose role ";
            return false;
        }

        // console.log(u_fname,u_lname,u_uid,u_email,u_pwd,u_phone,u_status_id,u_role_id,permis);return false;

        $('#spinner').show();
        $('.modal-loader').show();
        $.ajax({
            type: "post",
            url: "{{ url('users/edit_user') }}",
            data: {
                _token: "{{ csrf_token() }}",
                "fname": u_fname,
                "lname": u_lname,
                "uid": u_uid,
                "email": u_email,
                "phone": u_phone,
                "statusid": u_status_id,
                "roleid": u_role_id,
                "permissions": permis,
                "u_id": u_id
            },
            success: function(response) {
                var data = jQuery.parseJSON(response);
                $('#spinner').hide();
                if (data.status == 201) {
                    $("#editexist_user").html(
                        "Your user ID or email address exists. Please select a unique user ID and Email Address."
                    );
                    return false;
                }

                $('.modal-loader').hide();

                toastr.success('User Updated successfully.');

                $('#editNewEvent').modal('hide');

                $(document).ajaxStop(function() {
                    window.location.reload();
                });
            },
            error: function(response) {
                $('#spinner').hide();
                $('.modal-loader').hide();
                toastr.error('Unable to Update user.');

            }
        })
    });


    $(document).ready(function() {
        $('#role').change(function() {
            if (this.value) {
                var roless = this.value;

                $('.spinner-border').show();
                $.ajax({
                    type: "post",
                    url: "{{ url('users/get_privileges') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        "roleid": roless
                    },

                    success: function(response) {
                        var data = jQuery.parseJSON(response);
                        //console.log(response);return false;
                        $('.uncheckpri').prop('checked', false);

                        $.each(data, function(key, value) {

                            $('.per_Checkbox_' + value).prop('checked', true);
                        })
                        $('.spinner-border').hide();
                    },
                    error: function(response) {
                        toastr.error('Unable to create user.');

                    }
                })

                //console.log("hi");return false;
                $("#role_base").show();
            } else {
                $("#role_base").hide();
            }
        });
    });


    $(document).ready(function() {
        $('#editrole').change(function() {
            if (this.value) {
                var roless = this.value;

                $('.spinner-border').show();
                $.ajax({
                    type: "post",
                    url: "{{ url('users/get_privileges') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        "roleid": roless
                    },

                    success: function(response) {
                        var data = jQuery.parseJSON(response);
                        //console.log(response);return false;
                        $('.uncheckpriedit').prop('checked', false);

                        $.each(data, function(key, value) {

                            $('.editper_Checkbox_' + value).prop('checked', true);
                        })
                        $('.spinner-border').hide();
                    },
                    error: function(response) {
                        toastr.error('Unable to create user.');

                    }
                })

            } else {
                $("#role_base").hide();
            }
        });
    });

    function mailSendToUser(id) {

        let emailid = $("#email_id_" + id).val();
        $("#responseMsgId").html("");
        $("#mailsend").show();
        if (emailid == "") {
            $("#responseMsgId").html("Email Id is Empty");
            $("#mailsend").hide();

        }
        $('#emailModalCenter').modal('show');
        $("#okToSendMail").on("click", function() {
            $('.spinner-border').show();
            $.ajax({
                type: "post",
                url: "{{ url('forget-password') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    "email": emailid,
                    "row_id": id
                },

                success: function(response) {
                    $('#emailModalCenter').modal('hide');
                    toastr.success('Email sent successfully');

                    $('.spinner-border').hide();

                    $(document).ajaxStop(function() {
                        window.location.reload();
                    });

                },
                error: function(response) {
                    toastr.error('Unable to sent Email');

                }
            })
        })
    }

    function isEmail(email) {
        $("#gmail").html("");
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            $("#gmail").html("Email address is Invalid");
            return false;
        } else {
            $("#gmail").html("");
        }
    }

    function isEmailCheck(email) {
        $("#gmail").html("Please Enter Valid Email Address");
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            $("#gmail").html("Email address is Invalid");
        } else {
            $("#gmail").html("");
        }
    }

    function isEmailEdit(email) {
        $("#egmail").html("");
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            $("#egmail").html("Email address is Invalid");
            return false;
        } else {
            $("#egmail").html("");
        }
    }

    function isEmailCheckEdit(email) {
        $("#egmail").html("Please Enter Valid Email Address");
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            $("#gmail").html("Email address is Invalid");
        } else {
            $("#gmail").html("");
        }
    }

    $('.inputClass').keypress(function( e ) {
    if(!/[0-9a-zA-Z-]/.test(String.fromCharCode(e.which)))
        return false;
    });

	$('body').on('click blur change keypress', '#addUID', function(event) {
 		var number = $(this).val();
		number = number.replace(/\s/g, '');
        $(this).val(number);
 	});

     
     $('body').on('click blur change keypress', '#eidtUID', function(event) {
 		var number = $(this).val();
		number = number.replace(/\s/g, '');
        $(this).val(number);
 	});


    $('.providerConfig').click(function(){ 
        $('.usermodeltitle').html("Update Provider Config"); 
        var providerId = $(this).attr('data-id');
        $.ajax({
            type: "post",
            url: "{{ url('users/pconfig') }}",
            data: {
                _token: "{{ csrf_token() }}",
                "providerId": providerId
            },
        success: function(response){ 
            $('.usrcontainer').html(response);

            $('#user_modal').modal('show'); 
        }
        });
    });


    // $('.updateStatus').click(function(){ 
    //     $('.usermodeltitle').html("Update Provider Config"); 
    //     var providerId = $(this).attr('data-id');
    //     $.ajax({
    //         type: "post",
    //         url: "{{ url('users/statusupdate') }}",
    //         data: {
    //             _token: "{{ csrf_token() }}",
    //             "providerId": providerId
    //         },
    //     success: function(response){ 
    //         $('.usrcontainer').html(response);

    //         $('#user_modal').modal('show'); 
    //     }
    //     });
    // });

    

function updateStatus(userid,status) {

$('#ModalCenterForUpdateStatus').modal('show');
$("#oktoupdatestatus").on("click", function() {
    $.ajax({
        type: "post",
        url: "{{ url('users/statusupdate') }}",
        data: {
            _token: "{{ csrf_token() }}",
            "uid": userid,
            "status":status
        },
        success: function(response) {

            $('#ModalCenter').modal('hide');
            toastr.success('Status updated successfully.');
            $(document).ajaxStop(function() {
                window.location.reload();
            });

        },
        error: function(response) {
            toastr.error('Unable to delete user.');

        }
    })
})
}
    
</script>
@endsection