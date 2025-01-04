var dtoken = $('meta[name="csrf-token"]').attr('content');


function getUserModules() {
    $('#loader').show();

    $.ajax({
        type: 'POST',
        url: baseurl + '/dashboard/get-user-modules',
        data: { _token: dtoken },
        dataType: 'json',
        success: function(res) {
            $('#loader').hide();
            let permissionarr = JSON.parse(res.permissions);
            res = res.data;
            console.log("Modules_side"+JSON.stringify(res));
            if (res.status === 200) {                
                let listItem = "";
                const $sidebarMenu = $('#sidebar_menu');
                $sidebarMenu.empty();                
                $.each(res.modules, function(index, item) {
                    
                    console.log("Modules_item"+JSON.stringify(item));
                    const currentPath = window.location.pathname;
					console.log("currentPath "+currentPath);
                    const isActive = currentPath.includes(item.slug) ? ' active' : '';

                    console.log("permissionarr "+permissionarr);
                    console.log(Array.isArray(permissionarr));
                    console.log("permissionitem "+item.id+" URL "+item.slug);
                    if (permissionarr.includes(item.id)) {
                        listItem = `
                            <li class="dash-item${isActive}">
                                <a href="/${item.slug}" class="dash-link">
                                    <span class="dash-micon"><i class="ti ti-home"></i></span>
                                    <span class="dash-mtext">${item.title}</span>
                                </a>
                            </li>
                        `;
                    }else{
                        listItem ="";
                    }
                    $sidebarMenu.append(listItem); 
                });
                
            } else {
                console.log('Unexpected status code:', res.status);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $('#loader').hide();
            console.log('Error fetching data:'+ textStatus+"  Error "+ errorThrown);
        }
    });
}

$('body').on('click', '.getProfilePicture',function(){	
	$.ajax({
        type: "POST",
		url: "{{ url('users/getProfilePicture') }}",
        data: { _token: "{{ csrf_token() }}" },
        dataType: 'json',
        success: function(response) {
            console.log(response.status);
            console.log("PICTURE  "+JSON.stringify(response.data));
			if(response.status == 200){
				$('#userprifilepic').attr('src', response.data);
			}
        },
        error: function(response) {
			console.log(response.status); 
        }
    })
});

