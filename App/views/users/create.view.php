<?= loadPartial('head');?>
    <!-- Nav -->
<?= loadPartial('navbar');?>



    <!-- Registration Form Box -->
<div class="flex justify-center items-center mt-20">
    <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-500 mx-6">
    <h2 class="text-4xl text-center font-bold mb-4">Register</h2>
    <?=loadPartial('errors',[
        'errors' => $errors ?? []
    ]) ?>
    <form method="post" avtion="/auth/register">
        <div class="mb-4">
        <input
            type="text"
            name="name"
            value="<?= $user['name'] ?? '' ?>"
            placeholder="Full Name"
            class="w-full px-4 py-2 border rounded focus:outline-none"
        />
        </div>
        <div class="mb-4">
        <input
            type="email"
            name="email"
            value="<?= $user['email'] ?? '' ?>"
            placeholder="Email Address"
            class="w-full px-4 py-2 border rounded focus:outline-none"
        />
        </div>
        <div class="mb-4">
        <input
            type="text"
            name="city"
            value="<?= $user['city'] ?? '' ?>"
            placeholder="City"
            class="w-full px-4 py-2 border rounded focus:outline-none"
        />
        </div>
        <div class="mb-4">
        <input
            type="text"
            name="state"
            value="<?= $user['state'] ?? '' ?>"
            placeholder="State"
            class="w-full px-4 py-2 border rounded focus:outline-none"
        />
        </div>
        <div class="mb-4">
        <input
            type="password"
            name="password"
            value="<?= $user['password'] ?? '' ?>"
            placeholder="Password"
            class="w-full px-4 py-2 border rounded focus:outline-none"
        />
        </div>
        <div class="mb-4">
        <input
            type="password"
            name="password_confirmation"
            placeholder="Confirm Password"
            class="w-full px-4 py-2 border rounded focus:outline-none"
        />
        </div>
        <button
        type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded focus:outline-none"
        >
        Register
        </button>

        <p class="mt-4 text-gray-500">
        Already have an account?
        <a class="text-blue-900" href="/auth/login">Login</a>
        </p>
    </form>
    </div>
</div>

<!-- Footer -->
<?= loadPartial('footer'); ?>