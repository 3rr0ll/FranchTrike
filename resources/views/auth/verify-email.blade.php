<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-10 px-4" style="background-image: url('{{ asset("images/login_bg.jpg") }}'); background-size: cover; background-position: center;">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10 flex flex-col items-center">
            <img src="{{ asset('images/logo.png') }}" alt="FranchTrike Logo" class="h-20 w-20 mb-6 drop-shadow-lg ">
            <h2 class="text-3xl font-extrabold text-indigo-800 mb-3 text-center tracking-tight">Verify Your Email Address</h2>
            <p class="mb-6 text-gray-700 text-center text-base leading-relaxed">
                Thank you for signing up! Please verify your email address by clicking the link we just sent to your inbox.<br>
                If you didn’t receive the email, you can request another below.
            </p>

            @if (session('status') == 'verification-link-sent')
            <div class="mb-6 w-full text-green-800 bg-green-100 border border-green-300 rounded-lg px-5 py-3 text-center font-semibold shadow animate-fade-in">
                <svg class="inline-block w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
                A new verification link has been sent to the email address you provided.
            </div>
            @endif

            <form method="POST" action="{{ route('verification.resend') }}" class="w-full flex flex-col items-center">
                @csrf
                <button id="resendBtn" type="submit"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed"
                    @if(session('error') || $secondsLeft> 0) disabled @endif>
                    Resend Verification Email
                    <span id="countdown" class="ml-2 text-sm text-gray-200"></span>
                </button>
            </form>

            @if(session('error'))
            <div class="mb-6 w-full text-red-800 bg-red-100 border border-red-300 rounded-lg px-5 py-3 text-center shadow animate-fade-in">
                <svg class="inline-block w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-center w-full mt-8 gap-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-red-600 hover:underline font-semibold transition">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.5s ease;
        }

        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }
    </style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let secondsLeft = Math.floor(Number(@json($secondsLeft))); 
    const resendBtn = document.getElementById('resendBtn');
    const countdown = document.getElementById('countdown');
    const resendIcon = document.getElementById('resendIcon');

    function updateDisplay() {
        if (secondsLeft > 0) {
            countdown.textContent = `(${Math.floor(secondsLeft)}s)`;
            resendBtn.disabled = true;
            resendBtn.classList.add('opacity-60', 'cursor-not-allowed');
            if (resendIcon) resendIcon.classList.add('animate-spin');
        } else {
            countdown.textContent = '';
            resendBtn.disabled = false;
            resendBtn.classList.remove('opacity-60', 'cursor-not-allowed');
            if (resendIcon) resendIcon.classList.remove('animate-spin');
        }
    }

    if (secondsLeft > 0) {
        updateDisplay();
        const timer = setInterval(() => {
            secondsLeft--;
            updateDisplay();
            if (secondsLeft <= 0) clearInterval(timer);
        }, 1000);
    }
});
</script>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }
</style>

</x-guest-layout>