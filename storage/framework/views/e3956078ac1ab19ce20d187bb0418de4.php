<!-- Footer -->
<footer class="footer style-4">
    <div class="footer__top">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="content-left">
                        <img src="<?php echo e(asset('front/assets/images/logo/logo03.png')); ?>" alt="ICOLand">                      
                        <ul class="list-info">
                            <li> <span class="icon-message"></span>Info.yourcompany@gmail.com</li>
                            <li><span class="icon-Calling"></span>+345 54689435</li>
                        </ul>
                        <ul class="list-social">
                            <li><span class="icon-twitter"></span></li>
                            <li><span class="icon-facebook"></span></li>
                            <li><span class="icon-place"></span></li>
                            <li><span class="icon-youtobe"></span></li>
                            <li><span class="icon-tiktok"></span></li>
                            <li><span class="icon-reddit"></span></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="footer-link s1">
                        <h5 class="title">Company</h5>
                        <ul class="list-link">
                            <li><a href="<?php echo e(route('front.privacy.policy')); ?>">Privacy Policy</a></li>
                            <li><a href="<?php echo e(route('front.terms.conditions')); ?>">Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-right">
                        <form>
                            <div class="form row">
                                <div class="form-group col-md-6">
                                    <label>Your Name</label>
                                    <input type="text" class="form-control" placeholder="Type your name here">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Your Email</label>
                                    <input type="email" class="form-control" id="exampleInputEmail"
                                        placeholder="Type your email here">
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="mb-6">Your Message</label>
                                    <textarea placeholder="Leave your question or comment here"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn-action style-4"><span>SUBMIT YOUR MESSAGE</span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>© 2022. All rights reserved by <a
                            href="https://themeforest.net/user/themesflat/portfolio">Themesflat</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH D:\xampp\htdocs\SVRS\resources\views/front/layout/footer.blade.php ENDPATH**/ ?>