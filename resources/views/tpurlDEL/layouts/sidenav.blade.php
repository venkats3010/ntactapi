<?php
$auth = Session::all();
$session = Session::all();
$permissions = [];$name = "";$p_name = "";
if (isset($session)) {	
    $name = $session['username'];    
}
// echo gettype($permissions);
// if(isset($permissions)){
//     echo "342".$permissions;
// };exit;
?>
@php
$base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'];
$BASE_URL = $base_url . $_SERVER["REQUEST_URI"];
$url = explode('/', $BASE_URL);
array_pop($url);
$base_path = implode('/', $url);
$segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$current_page = $segments[0];
@endphp
		
<style>

body {
  padding-bottom: 30px;
  position: relative;
  min-height: 100%;
}

a {
  transition: background 0.2s, color 0.2s;
}
a:hover,
a:focus {
  text-decoration: none;
}

#wrapper {
  padding-left: 0;
  transition: all 0.5s ease;
  position: relative;
}

#sidebar-wrapper {
  z-index: 1000;
  position: fixed;
  left: 250px;
  width: 0;
  height: 100%;
  margin-left: -250px;
  overflow-y: auto;
  overflow-x: hidden;
  background: #222;
  transition: all 0.5s ease;
}

#wrapper.toggled #sidebar-wrapper {
  width: 250px;
}

.sidebar-brand {
  position: absolute;
  top: 0;
  width: 250px;
  text-align: center;
  padding: 20px 0;
}
.sidebar-brand h2 {
  margin: 0;
  font-weight: 600;
  font-size: 24px;
  color: #fff;
}

.sidebar-nav {
  position: absolute;
  top: 75px;
  width: 250px;
  margin: 0;
  padding: 0;
  list-style: none;
}
.sidebar-nav > li {
  text-indent: 10px;
  line-height: 42px;
}
.sidebar-nav > li a {
  display: block;
  text-decoration: none;
  color: #757575;
  font-weight: 600;
  font-size: 18px;
}
.sidebar-nav > li > a:hover,
.sidebar-nav > li.active > a {
  text-decoration: none;
  color: #fff;
  background: #F8BE12;
}
.sidebar-nav > li > a i.fa {
  font-size: 24px;
  width: 60px;
}

#navbar-wrapper {
    width: 100%;
    position: absolute;
    z-index: 2;
}
#wrapper.toggled #navbar-wrapper {
    position: absolute;
    margin-right: -250px;
}
#navbar-wrapper .navbar {
  border-width: 0 0 0 0;
  background-color: #eee;
  font-size: 24px;
  margin-bottom: 0;
  border-radius: 0;
}
#navbar-wrapper .navbar a {
  color: #757575;
}
#navbar-wrapper .navbar a:hover {
  color: #F8BE12;
}

#content-wrapper {
  width: 100%;
  position: absolute;
  padding: 15px;
  top: 100px;
}
#wrapper.toggled #content-wrapper {
  position: absolute;
  margin-right: -250px;
}

@media (min-width: 992px) {
  #wrapper {
    padding-left: 250px;
  }
  
  #wrapper.toggled {
    padding-left: 60px;
  }

  #sidebar-wrapper {
    width: 250px;
  }
  
  #wrapper.toggled #sidebar-wrapper {
    width: 60px;
  }
  
  #wrapper.toggled #navbar-wrapper {
    position: absolute;
    margin-right: -190px;
}
  
  #wrapper.toggled #content-wrapper {
    position: absolute;
    margin-right: -190px;
  }

  #navbar-wrapper {
    position: relative;
  }

  #wrapper.toggled {
    padding-left: 60px;
  }

  #content-wrapper {
    position: relative;
    top: 0;
  }

  #wrapper.toggled #navbar-wrapper,
  #wrapper.toggled #content-wrapper {
    position: relative;
    margin-right: 60px;
  }
}

@media (min-width: 768px) and (max-width: 991px) {
  #wrapper {
    padding-left: 60px;
  }

  #sidebar-wrapper {
    width: 60px;
  }
  
#wrapper.toggled #navbar-wrapper {
    position: absolute;
    margin-right: -250px;
}
  
  #wrapper.toggled #content-wrapper {
    position: absolute;
    margin-right: -250px;
  }

  #navbar-wrapper {
    position: relative;
  }

  #wrapper.toggled {
    padding-left: 250px;
  }

  #content-wrapper {
    position: relative;
    top: 0;
  }

  #wrapper.toggled #navbar-wrapper,
  #wrapper.toggled #content-wrapper {
    position: relative;
    margin-right: 250px;
  }
}

@media (max-width: 767px) {
  #wrapper {
    padding-left: 0;
  }

  #sidebar-wrapper {
    width: 0;
  }

  #wrapper.toggled #sidebar-wrapper {
    width: 250px;
  }
  #wrapper.toggled #navbar-wrapper {
    position: absolute;
    margin-right: -250px;
  }

  #wrapper.toggled #content-wrapper {
    position: absolute;
    margin-right: -250px;
  }

  #navbar-wrapper {
    position: relative;
  }

  #wrapper.toggled {
    padding-left: 250px;
  }

  #content-wrapper {
    position: relative;
    top: 0;
  }

  #wrapper.toggled #navbar-wrapper,
  #wrapper.toggled #content-wrapper {
    position: relative;
    margin-right: 250px;
  }
}

.navbar-nav {
    flex-direction: row;
}
.navbar-nav .nav-item {
    margin-left: 1rem; /* Add spacing between items */
}
.dropdown-menu {
    min-width: 200px; /* Optional: Ensure the dropdown has sufficient width */
}

</style>



 <!-- <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <h2>Encloud</h2>
    </div>
    <ul class="sidebar-nav">
      <li class="active">
        <a href="#"><i class="fa fa-home"></i>Home</a>
      </li>
      <li>
        <a href="#"><i class="fa fa-plug"></i>Plugins</a>
      </li>
      <li>
        <a href="#"><i class="fa fa-user"></i>Users</a>
      </li>
    </ul>
  </aside>-->


  <nav class="dash-sidebar light-sidebar">
  <div id="navbar-wrapper">
  <div class="m-header main-logo">
            <a href="<?php echo url('/dashboard') ?>" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                
                <img src="{{ my_asset('/assets/images/logo.png') }}" alt="{{ config('app.name', 'TicketGo SaaS') }}" class="logo logo-lg">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar" id="sidebar_menu">
                <!--<li class="dash-item {{ request()->is('*dashboard*') ? ' active' : '' }}">
                    <a href="<?php echo url('/dashboard') ?>" class="dash-link "><span class="dash-micon"><i class="ti ti-home"></i></span><span class="dash-mtext">{{ __('Dashboard') }}</span></a>
                </li>-->
            </ul>
      </div>
      <!--<nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
          <a href="#" class="navbar-brand" id="sidebar-toggle"><i class="fa fa-bars"></i></a>

          <div class="d-flex ms-auto"> 
            <a class="nav-link ms-3" href="<?php echo url('/dashboard') ?>">
                <span class="mdi mdi-home-outline"></span>
                Home
            </a>

            <a class="nav-link ms-3" href="<?php echo url('/pages') ?>">
                <span class="mdi mdi-console"></span>
                Console
            </a>

            <div class="dropdown ms-3"> 
              <a class="nav-link dropdown-toggle getProfilePicture" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="width:200px; text-overflow: ellipsis; overflow: hidden;">
                <span class="mdi mdi-account-circle-outline" style="cursor:pointer;"> </span> Testing
              </a>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li>
                  <div class="d-flex user-header" style="margin:0; justify-content: space-between; gap: 20px; background: #fafafa;">
                    <img src="{{ my_asset('/assets/images/user2-160x160.jpg') }}" id="userprifilepic" class="img-fluid rounded-circle" alt="User Image">
                    <div>
                      <a href="{{ url('/users/profile') }}" class="btn btn-sm btn-outline-secondary m-1">Profile</a>
                      <a href="{{ url('/logout') }}" class="btn btn-sm btn-outline-secondary m-1">Logout</a>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>-->
    </div>
</nav>
