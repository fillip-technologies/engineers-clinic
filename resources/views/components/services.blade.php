<style>
    .services-tech-grid {
        background-image:
            linear-gradient(rgba(124, 92, 252, 0.16) 1px, transparent 1px),
            linear-gradient(90deg, rgba(124, 92, 252, 0.16) 1px, transparent 1px),
            linear-gradient(115deg, transparent 0%, transparent 42%, rgba(61, 32, 144, 0.18) 50%, transparent 58%, transparent 100%);
        background-size: 46px 46px, 46px 46px, 220% 220%;
        animation: servicesScanShift 7s linear infinite;
        mask-image: linear-gradient(to bottom, transparent, black 18%, black 82%, transparent);
    }

    .services-circuit-line {
        background: linear-gradient(90deg, transparent, rgba(124, 92, 252, 0.38), rgba(245, 200, 66, 0.42), transparent);
        animation: servicesCircuitPulse 4.8s ease-in-out infinite;
    }

    @keyframes servicesScanShift {
        0% {
            background-position: 0 0, 0 0, 0% 50%;
        }

        100% {
            background-position: 46px 46px, 46px 46px, 100% 50%;
        }
    }

    @keyframes servicesCircuitPulse {
        0%, 100% {
            opacity: 0.24;
            transform: translateX(-4%);
        }

        50% {
            opacity: 0.72;
            transform: translateX(4%);
        }
    }
</style>

<section class="relative isolate overflow-hidden bg-bgWhite py-20">

    <div class="pointer-events-none absolute inset-0">
        <div class="services-tech-grid absolute inset-0 opacity-100"></div>
        <div class="services-circuit-line absolute bottom-16 left-0 h-px w-full [animation-delay:1.7s]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6">

        <!-- HEADING -->
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-bold text-textPrimary">
                Choose Your
                <span class="bg-gradient-to-r from-brand via-brandLight to-secondary bg-clip-text text-transparent">
                    AI Toolkit Plan
                </span>
            </h2>

            <p class="mt-4 text-textSecondary max-w-2xl mx-auto">
                Get access to powerful AI tools designed for engineers. Start free or unlock premium features.
            </p>
        </div>

        <!-- PRICING CARDS -->
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">

            <!-- BASIC PLAN -->
            <div class="relative bg-bgWhite border border-borderLight rounded-2xl p-8 shadow-sm hover:shadow-xl transition">

                <h3 class="text-xl font-semibold text-textPrimary mb-2">Basic Plan</h3>

                <p class="text-sm text-textSecondary mb-6">
                    Perfect for beginners getting started with AI tools
                </p>

                <div class="mb-6">
                    <span class="text-4xl font-bold text-textPrimary">₹0</span>
                    <span class="text-sm text-textSecondary">/month</span>
                </div>

                <ul class="space-y-3 text-sm text-textSecondary mb-8">
                    <li>✔ Limited AI Resume Builder</li>
                    <li>✔ Basic Project Ideas</li>
                    <li>✔ AI Code Suggestions (limited)</li>
                    <li>✔ Community Access</li>
                </ul>

                <button class="w-full py-3 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition">
                    Get Started
                </button>

            </div>

            <!-- PREMIUM PLAN -->
            <div class="relative bg-bgWhite border-2 border-brand rounded-2xl p-8 shadow-lg hover:shadow-2xl transition overflow-hidden">

                <!-- TAG -->
                <span class="absolute top-4 right-4 text-xs bg-brand text-white px-3 py-1 rounded-full">
                    MOST POPULAR
                </span>

                <!-- GLOW -->
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-brandSoft blur-2xl rounded-full"></div>
                </div>

                <div class="relative">
                    <h3 class="text-xl font-semibold text-textPrimary mb-2">Premium Plan</h3>

                    <p class="text-sm text-textSecondary mb-6">
                        Unlock full AI power for serious learners
                    </p>

                    <div class="mb-6">
                        <span class="text-4xl font-bold text-textPrimary">₹499</span>
                        <span class="text-sm text-textSecondary">/month</span>
                    </div>

                    <ul class="space-y-3 text-sm text-textSecondary mb-8">
                        <li>✔ Full AI Resume Builder</li>
                        <li>✔ Advanced Project Generator</li>
                        <li>✔ Unlimited Code Assistance</li>
                        <li>✔ Real-world Project Ideas</li>
                        <li>✔ Priority Support</li>
                    </ul>

                    <button class="w-full py-3 bg-brand text-white rounded-lg font-semibold hover:bg-brandDark transition shadow">
                        Upgrade Now 🚀
                    </button>
                </div>

            </div>

        </div>

    </div>

</section>
