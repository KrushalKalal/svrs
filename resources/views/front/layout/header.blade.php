<!-- Header -->
<header id="header_main" class="header style-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header__body">
                    <div class="header__logo">
                        <a href="{{route('front.index')}}">
                            <img id="site-logo" src="{{ asset('front/assets/images/logo/logo-04.png')}}" alt="Monteno" width="165"
                                height="40" data-retina="{{ asset('front/assets/images/logo/logo-04@x2.png')}}" data-width="165"
                                data-height="40">
                        </a>
                    </div>

                    <div class="header__right">                        
                        <div class="group-button">
                            <a href="{{route('admin.login')}}" class="btn-action style-4">
                                <span>Login</span>
                            </a>
                            <a href="{{route('front.sign.up')}}" class="btn-action style-2">
                                <span>Sign Up</span>
                            </a>
                        </div>
                        <div class="mobile-button"><span></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- end Header -->
