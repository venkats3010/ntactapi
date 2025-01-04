<!-- START: PATIENT GRID ROWS -->
@extends('layouts.app')
@section('content')
    <?php
    $session = Session::all();
	//print_r($session['username']);//exit;
	$role = $session['auth']['data']['role'];
    $today = date('Y-m-d', strtotime(now()));
    ?>




<div id="dash-container">
      <div class="row">
        <div class="col-lg-12">
         <!-- <h2 class="content-title">Dashboard</h2>-->
          
          
<div class="container">
    <div class="need-gap" style="margin-top: 10px;">
    </div>
	
	<div class="row dashboard my-2">
    </div>
          
         </div> 
        </div>
      </div>
</div>




@endsection('content')

@section('jscontent')
<script type="text/javascript">
    $(document).ready(function() {

    });

    let getDashboardData = () => {
        $(".dashboard").html('');
        $.ajax({
            type: "POST",
            url: "{{ url('dashboard/getDashboardData') }}",
            data: {
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $(".dashboard").html('<span style="color:red;font-size:25px;"><i class="fa fa-spinner" ></i> Please wait...</span>');
            },
            success: function(res) {
                $('#loader').hide();
                $(".dashboard").html(res);
            }
        })
    }
    getDashboardData();
</script>
@endsection('jscontent')
