<section class="bg-white shadow-md rounded-xl p-6 mb-6">

    <header>
        <h2 class="text-2xl font-semibold text-gray-800 mb-1">
            {{ __('Đổi mật khẩu') }}
        </h2>

        <p class="text-sm text-gray-500 mb-4">
            {{ __('Hãy đảm bảo mật khẩu mới của bạn đủ mạnh và bảo mật.') }}
        </p>
    </header>

    <div id="password-view">
        <button id="btn-show-change-password" class="btn btn-warning mb-3">
            Đổi mật khẩu
        </button>
    </div>

    <div id="password-form" style="display:none;">
        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            <div>
                <x-input-label for="update_password_current_password" :value="__('Mật khẩu hiện tại')" />
                <br>
                <x-text-input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('Mật khẩu mới')" />
                <br>
                <x-text-input
                    id="update_password_password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Xác nhận mật khẩu')" />
                <br>
                <x-text-input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button class="!bg-green-600 hover:!bg-green-700 !text-white !px-6 !py-2.5 !text-base">
                    {{ __('Lưu thay đổi') }}
                </x-primary-button>

                <button type="button" id="btn-cancel-change-password" class="btn btn-secondary">
                    Hủy
                </button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600"
                    >
                        {{ __('Đã lưu.') }}
                    </p>
                @endif
            </div>
        </form>
    </div>
</section>

<script>
    document.getElementById('btn-show-change-password').addEventListener('click', function () {
        document.getElementById('password-view').style.display = 'none';
        document.getElementById('password-form').style.display = 'block';
    });

    document.getElementById('btn-cancel-change-password').addEventListener('click', function () {
        document.getElementById('password-form').style.display = 'none';
        document.getElementById('password-view').style.display = 'block';
    });
</script>

<!-- Toast popup -->
<div id="toast-success" style="
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #4caf50;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgb(0 0 0 / 0.2);
    font-weight: 600;
    z-index: 9999;
">
    Đổi mật khẩu thành công!
</div>

<!-- Toast success -->
<div id="toast-success" style="
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #4caf50;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgb(0 0 0 / 0.2);
    font-weight: 600;
    z-index: 9999;
">
    Đổi mật khẩu thành công!
</div>

<!-- Toast error -->
<div id="toast-error" style="
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #e53e3e; /* đỏ đậm báo lỗi */
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgb(0 0 0 / 0.2);
    font-weight: 600;
    z-index: 9999;
">
    Có lỗi xảy ra, vui lòng kiểm tra lại!
</div>

<script>
    // Hiện popup thành công nếu có session 'status' đúng
    @if (session('status') === 'password-updated')
        const toastSuccess = document.getElementById('toast-success');
        toastSuccess.style.display = 'block';
        setTimeout(() => {
            toastSuccess.style.display = 'none';
        }, 5000);
    @endif

    // Hiện popup lỗi nếu có lỗi validate (dựa vào số lượng lỗi)
    @if ($errors->updatePassword->any())
        const toastError = document.getElementById('toast-error');
        toastError.style.display = 'block';
        setTimeout(() => {
            toastError.style.display = 'none';
        }, 5000);
    @endif
</script>

