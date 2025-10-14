<div class="blur-bg-overlay"></div>
<div class="form-popup" style="z-index: 1100">
    <span class="close-btn material-symbols-rounded">close</span>
    <div class="form-box login">
        <div class="form-details">
            <h2>Welcome Back</h2>
            <p>Please log in using your personal information to stay connected with us.</p>
        </div>
        <div class="form-content">
            <h2>LOGIN</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="input-field">
                    <input type="text" name="id" required>
                    <label>Email or username</label>
                </div>
                <div class="input-field">
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <a href="#" class="forgot-pass-link">Forgot password?</a>
                <button type="submit">Log In</button>
            </form>
            <div class="bottom-link">
                Don't have an account?
                <a href="#" id="signup-link">Signup</a>
            </div>
        </div>
    </div>
    <div class="form-box signup">
        <div class="form-details">
            <h2>Create Account</h2>
            <p>To become a part of our community, please sign up using your personal information.</p>
        </div>
        <div class="form-content">
            <h2>SIGNUP</h2>
            <form action="{{ route('signup') }}" method="POST">
                @csrf
                <input id="userType" type="hidden" name="type" value="">
                <div class="input-field">
                    <input type="text" name="name" required>
                    <label>Full Name</label>
                </div>
                <div class="input-field">
                    <input type="email" name="email" required>
                    <label>Enter your email</label>
                </div>
                <div class="input-field">
                    <input type="password" name="password" required>
                    <label>Create password</label>
                </div>
                <div class="input-field">
                    <input type="password" name="password_confirmation" required>
                    <label>Confirm password</label>
                </div>
                <div class="input-field">
                    <input type="text" name="phone" required>
                    <label>Phone</label>
                </div>
                <div class="policy-text">
                    <input type="checkbox" id="policy" name="policy" required>
                    <label for="policy">
                        I agree the
                        <a href="{{ url('/html_pages/term&condition') }}" class="option">Terms & Conditions</a>
                    </label>
                </div>
                <button type="submit">Sign Up</button>
            </form>
            <div class="bottom-link">
                Already have an account?
                <a href="#" id="login-link">Login</a>
            </div>
        </div>
    </div>
</div>