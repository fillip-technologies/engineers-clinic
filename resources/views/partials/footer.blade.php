<footer class="relative bg-gradient-to-br from-[#022c22] via-[#064e3b] to-[#022c22] text-white {{ request()->is('login') ? 'mt-0' : 'mt-16' }}">

    <!-- 🌿 GREEN GLOW -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-green-500/10 blur-[140px] rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-emerald-400/10 blur-[140px] rounded-full"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 py-16">

        <!-- GRID -->
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">

            <!-- BRAND -->
            <div>
                <h2 class="text-lg font-semibold tracking-tight">
                    Engineers <span class="text-green-400">Clinic</span>
                </h2>

                <p class="mt-4 text-sm text-green-200/80 leading-relaxed max-w-xs">
                    Build real skills through internships, AI tools, and structured learning designed for engineers.
                </p>
            </div>

            <!-- PLATFORM -->
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-green-300">
                    Platform
                </h3>

                <ul class="mt-4 space-y-2 text-sm text-green-200/80">
                    <li><a href="#" class="hover:text-white transition">Internships</a></li>
                    <li><a href="#" class="hover:text-white transition">AI Tools</a></li>
                    <li><a href="#" class="hover:text-white transition">Tracks</a></li>
                    <li><a href="#" class="hover:text-white transition">Dashboard</a></li>
                </ul>
            </div>

            <!-- COMPANY -->
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-green-300">
                    Company
                </h3>

                <ul class="mt-4 space-y-2 text-sm text-green-200/80">
                    <li><a href="#" class="hover:text-white transition">About</a></li>
                    <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition">Terms</a></li>
                </ul>
            </div>

            <!-- CONTACT -->
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-green-300">
                    Get in Touch
                </h3>

                <ul class="mt-4 space-y-2 text-sm text-green-200/80">
                    <li>support@engineersclinic.com</li>
                    <li>+91 98765 43210</li>
                    <li>India</li>
                </ul>

                <!-- CTA -->
                <a href="#"
                   class="inline-block mt-5 px-5 py-2.5 rounded-lg bg-gradient-to-r from-green-500 to-emerald-400 text-white text-sm font-medium shadow hover:opacity-90 transition">
                    Contact Us
                </a>
            </div>

        </div>

        <!-- BOTTOM -->
        <div class="mt-12 border-t border-green-400/10 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-green-300/70">

            <p>
                © {{ date('Y') }} Engineers Clinic. All rights reserved.
            </p>

            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-white transition">Privacy</a>
                <a href="#" class="hover:text-white transition">Terms</a>
            </div>

        </div>

    </div>

</footer>