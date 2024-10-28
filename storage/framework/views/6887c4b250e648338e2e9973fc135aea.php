<?php $__env->startSection('content'); ?>
    <!-- ========== Start sign-in-page ========== -->
    <section class="cm-signin-page">
        <div class="container-fluid">
            <div class="row">
                <!-- Sign-in Form Section -->
                <div class="col-lg-8">
                    <div class="bg-signin">
                        <div class="cm-logo">
                            <img src="<?php echo e(asset('/assets/images/New-CMLogo.svg')); ?>" alt="logo" class="img-fluid">
                        </div>
                        <div class="cm-signin-form">
                            <div class="user-login-fields">
                                <form method="POST" action="<?php echo e(route('login')); ?>" id="data-form" class="login-form">
                                    <?php echo csrf_field(); ?>
                                    <h2 class="signin-title">Partner Asset Portal</h2>
                                    <p class="sub-text">Login into your account</p>
                                    <p class="error_input text-center text-danger"></p>

                                    <!-- Email Field -->
                                    <div class="email-field mb-3">
                                        <input id="email" type="email" 
                                               class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" 
                                               placeholder="Email" autofocus>
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- Password Field -->
                                    <div class="password-field mb-3">
                                        <input id="password" type="password" 
                                               class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               name="password" required autocomplete="current-password" 
                                               placeholder="Password">
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- Remember Me & Recover Password -->
                                    <div class="recover-details d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                                            <label class="form-check-label" for="remember-me">Remember me</label>
                                        </div>
                                        <div>
                                            <a href="<?php echo e(route('password.request')); ?>">Recover Password</a>
                                        </div>
                                    </div>

                                    <!-- Login Button -->
                                    <div class="login-button">
                                        <button type="submit" class="btn btn-primary w-100">
                                            Log In
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right-Side Image Section -->
                <div class="col-lg-4 d-none d-lg-block cm-signin-image">
                    <div class="circle-gradient"></div>
                    <div class="stock-resources">
                        <div class="top-notch-btn">
                            <a href="#">
                                <img src="<?php echo e(asset('/assets/images/thumbs-up.svg')); ?>" alt="thumbs-up" class="img-fluid"> 
                                Top Notch Stock Resources
                            </a>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
  
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pap\resources\views/auth/login.blade.php ENDPATH**/ ?>