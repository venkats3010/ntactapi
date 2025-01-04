

<header class="dash-header">
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            
            <a href="<?php echo url('/tpurl') ?>" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ my_asset('/assets/images/logo.png') }}" alt="{{ config('app.name', 'TicketGo SaaS') }}" class="logo logo-lg mt-3 h-50">
            </a>
            

        </div>

        <div class="ms-auto">
            <ul class="list-unstyled">
                   <!-- <li class="dash-h-item">
                        <a class="dash-head-link me-0" href="<?php echo url('/dashboard') ?>">
                            <i class="ti ti-home"></i>
                            
                        </a>
                    </li>
                    <li class="dash-h-item">
                        <a class="dash-head-link me-0" href="{{ url('/modules') }}">
                            <i class="mdi mdi-console"></i> 
                            <span class=""> {{ __('Console') }} </span>
                        </a>
                    </li>-->

                    <li class="dash-h-item">
                        <a class="dash-head-link me-0" href="">
                            <i class="ti ti-message-circle"></i>
                            <span class="bg-danger px-1 mb-1 dash-h-badge message-counter custom_messanger_counter">80<span class="sr-only"></span>
                        </a>
                    </li>

                    <li class="dropdown dash-h-item drp-company">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="theme-avtar">
                                    <?php 
                                    $session= Session::all();                                    
										if($session['profilepicture']){
											?>											
                                            <img src="{{ my_asset('assets/images/profilePictures/' . $session['profilepicture']) }}" class="header-avtar" width="50">
											<?php
										}else{
											?>
											<img src="{{ my_asset('assets/images/avatar.png') }}" class="header-avtar" width="50">
											<?php
										}
									?>                                
                            </span>
                            <span class="hide-mob ms-2">{{__('Hi')}}, <?php echo $session['auth']['data']['firstname']; ?></span>
                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown">
                            <?php  $tpurl = "tpurl"; ?>
                            <a href="{{ route('profile.edit', ['tpurl' => $tpurl]) }}" class="dropdown-item">
                                <i class="ti ti-user"></i>
                                <span>{{ __('Profile') }}</span>
                            </a>
                            <a href="{{ url('/logout') }}" class="dropdown-item">
                                <i class="ti ti-power"></i>
                                <span>{{ __('Logout') }}</span>
                            </a>
                        </div>
                    </li>


            </ul>
        </div>
    </div>
</header>




