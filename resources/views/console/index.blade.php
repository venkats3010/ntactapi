@extends('layouts.app')
<style>
@media (max-width: 768px) {
    #myoffcanvasRight {
        width: 100%;
    }
}

@media (min-width: 769px) {
    #myoffcanvasRight {
        width: 75%;
    }
}
</style>
<style>
.custom-btn {
    border: 2px solid red; 
    background-color: transparent;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    position: relative;
    transition: background-color 0.3s ease; 
}

.custom-btn::before, .custom-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 70%;
    height: 2px;
    background-color: red; 
}

.custom-btn::before {
    transform: translate(-50%, -50%) rotate(45deg);
}

.custom-btn::after {
    transform: translate(-50%, -50%) rotate(-45deg);
}

.custom-btn:hover {
    background-color: red; 
}

.custom-btn:hover::before, 
.custom-btn:hover::after {
    background-color: white;
}

.table-wrapper {
    max-height: 320px;
    overflow-x: hidden;
    overflow-y: scroll; 
}
/* Webkit browsers (Chrome, Safari) */
.table-wrapper::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.table-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
</style>

@section('content')
    <div class="col-lg-12">
        <div class="row">
            <div class="col-xl-6">
                <div class="card" style="height: 415px !important;">
                    <div class="card-body table-border-style">
                        <label class="form-label" style="font-weight: 900;font-size: 20px;">{{ __('Priorities') }}</label>
                        <span class="btn btn-sm btn-primary viewConsole" id="priority" data-bs-toggle="modal" data-bs-target="#myModal">Add</span>
                        <div class="priorities table-wrapper">
    
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="card" style="height: 415px !important;">
                    <div class="card-body table-border-style">
                        <label class="form-label" style="font-weight: 900;font-size: 20px;">{{ __('Categories') }}</label>
                        <span class="btn btn-sm btn-primary viewConsole" id="category" data-bs-toggle="modal" data-bs-target="#myModal">Add</span>
                        <div class="categories table-wrapper">
    
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="card" style="height: 415px !important;">
                    <div class="card-body table-border-style">
                        <label class="form-label" style="font-weight: 900;font-size: 20px;">{{ __('Status Types') }}</label>
                        <span class="btn btn-sm btn-primary viewConsole" id="statuses" data-bs-toggle="modal" data-bs-target="#myModal">Add</span>
                        <div class="statustypes table-wrapper">
    
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>


<!-- Model -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mtitle">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="viewdata">
        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
      </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>-->
    </div>
  </div>
</div>

<!-- Model -->
    
<!-- Off Canvas Right -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="myoffcanvasRight" aria-labelledby="offcanvasRightLabel" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="offcanvas-header" style="background-color:#c1c1c1;color:#000;">
    <h5 id="myoffcanvasRightLabel"></h5>
    <!--<button type="button" class="btn-close text-reset btn-danger" data-bs-dismiss="offcanvas" id="UserClose" aria-label="Close"></button>-->
    <button type="button" class="btn-close custom-btn" data-bs-dismiss="offcanvas" id="UserClose" aria-label="Close" style="border: 2px solid red;border-radius: 50%;"></button>
  </div>
  <div class="offcanvas-body" id="offcanvas_viewdata">
    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
  </div>
</div>
<!-- Off Canvas Right -->

@endsection

@section('jscontent')

<script>
$(document).ready(function() {
    //
});

    let getCategories = () => {
        var str = '<table class="table"><tr><th>#</th><th>Category</th><th>Status</th><th>Action</th></tr>';
        let param = "ALL";
        $.ajax({
            type: "post",
            url: "{{ url('category/get/') }}",
            data: {
                _token: "{{ csrf_token() }}",param:param
            },
            success: function(res) {
                console.log(JSON.stringify(res));
                if(res.result == "true"){
                    $.each(res.data, function (k, v) {
                        console.log(v.name);
                        str += '<tr><td>'+(k+1)+'</td><td>'+v.name+'</td><td>' + ((v.status=="A") ? "Active" : "Inactive") + '</td>'+
                        `<td><span class="btn btn-sm btn-warning editConsole" data-id="category" data-rowid=${v.id} data-name='${v.name}'   data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-pencil" aria-hidden="true"></i></span>`+
                        '<span class="btn btn-sm btn-danger deleteButton m-1"  data-type="category" data-rowid='+v.id+'><i class="fa fa-trash" aria-hidden="true"></i></span></td></tr>';
                    });
                }else{
                    str += "<tr><td rowspan=3>No data available</td></tr>";
                }
                $('.categories').html(str);
            }
        })
        $('.categories').html(str);
    }

    let getPriorities = () => {
        var str = '<table class="table"><tr><th>#</th><th>Category</th><th>Status</th><th>Action</th></tr>';
        let param = "ALL";
        $.ajax({
            type: "post",
            url: "{{ url('priority/get/') }}",
            data: {
                _token: "{{ csrf_token() }}",param:param
            },
            success: function(res) {
                console.log(JSON.stringify(res));
                if(res.result == "true"){
                    $.each(res.data, function (k, v) {
                        console.log(v.priority);
                        str += '<tr><td>'+(k+1)+'</td><td>'+v.priority+'</td><td>' + ((v.status=="A") ? "Active" : "Inactive") + '</td>'+
                        `<td><span class="btn btn-sm btn-warning editConsole" data-id="priority" data-rowid=${v.id} data-name='${v.priority}'   data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-pencil" aria-hidden="true"></i></span>`+
                        '<span class="btn btn-sm btn-danger deleteButton m-1"  data-type="priority" data-rowid='+v.id+'><i class="fa fa-trash" aria-hidden="true"></i></span></td></tr>';
                    });
                }else{
                    str += "<tr><td rowspan=3>No data available</td></tr>";
                }
                $('.priorities').html(str);
            }
        })
        $('.priorities').html(str);
    }
    
    let getStatusTypes = () => {
        var str = '<table class="table"><tr><th>#</th><th>Category</th><th>Status</th><th>Action</th></tr>';
        let param = "ALL";
        $.ajax({
            type: "post",
            url: "{{ url('statuses/get/') }}",
            data: {
                _token: "{{ csrf_token() }}",param:param
            },
            success: function(res) {
                console.log(JSON.stringify(res));
                if(res.result == "true"){
                    $.each(res.data, function (k, v) {
                        console.log(v.name);
                        str += '<tr><td>'+(k+1)+'</td><td>'+v.name+'</td><td>' + ((v.status=="A") ? "Active" : "Inactive") + '</td>'+
                        `<td><span class="btn btn-sm btn-warning editConsole" data-id="statuses" data-rowid=${v.id} data-name='${v.name}'   data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-pencil" aria-hidden="true"></i></span>`+
                        '<span class="btn btn-sm btn-danger deleteButton m-1"  data-type="statuses" data-rowid='+v.id+'><i class="fa fa-trash" aria-hidden="true"></i></span></td></tr>';
                    });
                }else{
                    str += "<tr><td rowspan=3>No data available</td></tr>";
                }
                $('.statustypes').html(str);
            }
        })
        $('.statustypes').html(str);
    }
    
getCategories();
getPriorities();
getStatusTypes();
</script>
<script>

$('body').on('click', '.viewConsole', function(){
	$('#viewdata').html('');
	$('#mtitle').html('').html('Add  ');
    let addtype = $(this).attr('id');    
	      str = '<input type="text" name="newname" id="newname" class="form-control">';
	      str += '<button type="button" class="btn btn-primary m-3" id="saveButton" data-id='+addtype+'  style="float: right;"><i class="mdi mdi-content-save"></i> Save</button>';
		  $('#viewdata').html(str);


});


$('body').on('click', '#saveButton', function(e) {
    e.preventDefault();
    let addtype = $(this).attr('data-id');
    var name = $("#newname").val();
    if(name == ""){
        msg = "Please enter "+addtype;
        toastr.error(msg);
        return false;
    }
    
    $.ajax({
        type: "POST",
        url: baseurl + "/"+addtype+"/create",
        dataType: "json",
        data: { _token: "{{ csrf_token() }}", name:name},
        cache: false,
        success: function (res) {
            console.log("data: " + JSON.stringify(res));
            if (res.status == 200) {
                if(addtype == "priority"){
                    getPriorities();
                }    
                if(addtype == "category"){
                    getCategories();
                }
                if(addtype == "statuses"){
                    getStatusTypes();
                }
                $('#myModal .btn-close').click();
                toastr.success(res.message);
            }else{
                toastr.error(res.message);
            }
        },
        error: function(err) {
            console.error("Error fetching priority: ", err);
        }
    });

});

$('body').on('click', '.editConsole', function(){
    let edittype = $(this).attr('data-id');
    let rowid = $(this).attr('data-rowid');
    let name = $(this).attr('data-name');
    
	$('#viewdata').html('');
	$('#mtitle').html('').html('Edit  ');
   
      str = `<input type="text" name="editname" id="editname" value='${name}' class="form-control">`;
      str += '<button type="button" class="btn btn-primary m-3" id="updateButton" data-id='+edittype+' data-rowid='+rowid+'  style="float: right;"><i class="mdi mdi-content-save"></i> Update</button>';
	  $('#viewdata').html(str);

});

$('body').on('click', '#updateButton', function(e) {
    e.preventDefault();
    let edittype = $(this).attr('data-id');
    let rowid = $(this).attr('data-rowid');
    var name = $("#editname").val();
    if(name == ""){
        msg = "Please enter "+edittype;
        toastr.error(msg);
        return false;
    }
    
    $.ajax({
        type: "POST",
        url: baseurl + "/"+edittype+"/update",
        dataType: "json",
        data: { _token: "{{ csrf_token() }}", rowid:rowid, name:name},
        cache: false,
        success: function (res) {
            console.log("data: " + JSON.stringify(res));
            if (res.status == 200) {
                if(edittype == "priority"){
                    getPriorities();
                }    
                if(edittype == "category"){
                    getCategories();
                }
                if(edittype == "statuses"){
                    getStatusTypes();
                }
                $('#myModal .btn-close').click();
                toastr.success(res.message);
            }else{
                toastr.error(res.message);
            }
        },
        error: function(err) {
            console.error("Error fetching priority: ", err);
        }
    });

});

$('body').on('click', '.deleteButton', function(e) {
    e.preventDefault();
    let name = $(this).attr('data-type');
    let rowid = $(this).attr('data-rowid');
    console.log(name+"  DelBtm  "+rowid);
    swal({
        title: "Are you sure!",
        text: " you want to delete it ?",
        buttons: [
          'NO',
          'YES'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) {
            
                
                $.ajax({
                    type: "POST",
                    url: baseurl + "/"+name+"/delete",
                    dataType: "json",
                    data: { _token: "{{ csrf_token() }}", rowid:rowid, name:name},
                    cache: false,
                    success: function (res) {
                        console.log("data: " + JSON.stringify(res));
                        if (res.status == 200) {
                            if(name == "priority"){
                                getPriorities();
                            }    
                            if(name == "category"){
                                getCategories();
                            }
                            if(name == "statuses"){
                                getStatusTypes();
                            }
                            $('#myModal .btn-close').click();
                            toastr.success(res.message);
                        }else{
                            toastr.error(res.message);
                        }
                    },
                    error: function(err) {
                        console.error("Error fetching priority: ", err);
                    }
                });
    
            
            } else {
             return false;   
            }
      });

});
</script>
@endsection