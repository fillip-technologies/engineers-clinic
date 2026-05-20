<div
    x-data="{
        open: false,
        copied: false,
        referralCode: 'EC-SHRUTI10',
        referralLink: '{{ url('/?ref=EC-SHRUTI10') }}',
        copyReferral() {
            navigator.clipboard?.writeText(this.referralLink);
            this.copied = true;
            setTimeout(() => this.copied = false, 1800);
        }
    }"
    x-init="$watch('open', value => {
        document.documentElement.classList.toggle('overflow-hidden', value);
        document.body.classList.toggle('overflow-hidden', value);
    })"
    @open-referral-popup.window="open = true"
    @keydown.escape.window="open = false"
>
    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[170] overflow-y-auto bg-[#070316]/80 px-4 py-6 backdrop-blur-2xl sm:px-6"
        role="dialog"
        aria-modal="true"
        @click="open = false"
    >
        <div class="flex min-h-full items-center justify-center">
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-6 scale-[0.96] opacity-0"
                x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                x-transition:leave-end="translate-y-5 scale-[0.98] opacity-0"
                class="referral-modal relative w-full max-w-5xl overflow-hidden rounded-[2rem] border border-white/12 bg-[#110728]/90 text-white shadow-[0_40px_140px_rgba(0,0,0,0.52)]"
                @click.stop
            >
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -left-24 top-10 h-56 w-56 rounded-full bg-brand/35 blur-3xl"></div>
                    <div class="absolute -right-20 top-28 h-64 w-64 rounded-full bg-secondary/25 blur-3xl"></div>
                    <div class="absolute bottom-0 left-1/3 h-60 w-60 rounded-full bg-auroraMid/20 blur-3xl"></div>
                    <div class="referral-grid absolute inset-0 opacity-45"></div>
                </div>

                <button
                    type="button"
                    class="absolute right-5 top-5 z-20 grid h-11 w-11 place-items-center rounded-full border border-white/10 bg-white/10 text-white/70 backdrop-blur-xl transition hover:bg-white/18 hover:text-white"
                    @click="open = false"
                    aria-label="Close referral popup"
                >
                    <i class="fi fi-rr-cross-small text-xl"></i>
                </button>

                <div class="relative grid gap-8 p-5 sm:p-7 lg:grid-cols-[0.95fr_1.05fr] lg:p-8">
                    <section class="flex flex-col justify-between rounded-[1.5rem] border border-white/10 bg-white/[0.06] p-6 backdrop-blur-2xl sm:p-8">
                        <div>
                            <p class="inline-flex items-center gap-2 rounded-full border border-secondary/25 bg-secondary/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-secondary">
                                <i class="fi fi-rr-gift"></i>
                                Referral Rewards
                            </p>

                            <h2 class="mt-6 text-4xl font-black leading-none tracking-tight text-white sm:text-5xl">
                                Give 10% <span class="text-secondary">•</span> Get 10%
                            </h2>
                            <p class="mt-4 max-w-md text-sm leading-7 text-white/68 sm:text-base">
                                Invite friends to Engineers Clinic and unlock rewards together.
                            </p>
                        </div>

                        <div class="relative mt-8 min-h-[300px] overflow-hidden rounded-[1.35rem] border border-white/10 bg-[#090318]/70 p-5">
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_30%,rgba(124,92,252,0.34),transparent_34%),radial-gradient(circle_at_74%_70%,rgba(245,200,66,0.20),transparent_28%)]"></div>
                            <div class="referral-orbit absolute left-1/2 top-1/2 h-56 w-56 -translate-x-1/2 -translate-y-1/2 rounded-full border border-white/10"></div>
                            <div class="absolute left-1/2 top-1/2 grid h-24 w-24 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-3xl border border-white/15 bg-white/10 shadow-[0_0_60px_rgba(124,92,252,0.42)] backdrop-blur-xl">
                                <i class="fi fi-rr-users-alt text-4xl text-secondary"></i>
                            </div>

                            @foreach ([
                                ['class' => 'left-8 top-10', 'icon' => 'fi fi-rr-graduation-cap'],
                                ['class' => 'right-8 top-16', 'icon' => 'fi fi-rr-briefcase'],
                                ['class' => 'bottom-10 left-14', 'icon' => 'fi fi-rr-chart-network'],
                                ['class' => 'bottom-8 right-14', 'icon' => 'fi fi-rr-star'],
                            ] as $node)
                                <div class="referral-node absolute {{ $node['class'] }} grid h-14 w-14 place-items-center rounded-2xl border border-white/12 bg-white/10 text-white shadow-[0_16px_44px_rgba(0,0,0,0.26)] backdrop-blur-xl">
                                    <i class="{{ $node['icon'] }}"></i>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="space-y-4">
                        <div class="referral-sweep rounded-[1.5rem] border border-white/12 bg-white/[0.07] p-5 backdrop-blur-2xl sm:p-6">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-white/45">Your referral code</p>
                                    <p class="mt-2 font-mono text-2xl font-black tracking-[0.08em] text-white" x-text="referralCode"></p>
                                </div>
                                <button
                                    type="button"
                                    class="inline-flex h-12 items-center gap-2 rounded-full border border-secondary/30 bg-secondary/15 px-4 text-sm font-black text-secondary transition hover:-translate-y-0.5 hover:bg-secondary hover:text-textPrimary"
                                    @click="copyReferral()"
                                >
                                    <i class="fi fi-rr-copy"></i>
                                    <span x-text="copied ? 'Copied' : 'Copy'"></span>
                                </button>
                            </div>

                            <div class="mt-5 rounded-2xl border border-white/10 bg-black/25 px-4 py-3">
                                <p class="text-xs font-semibold text-white/45">Referral link preview</p>
                                <p class="mt-1 truncate font-mono text-sm text-auroraMid" x-text="referralLink"></p>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach ([
                                ['icon' => 'fi fi-rr-users-alt', 'label' => 'Successful Referrals', 'value' => '12'],
                                ['icon' => 'fi fi-rr-coins', 'label' => 'Rewards Earned', 'value' => '₹2.4k'],
                                ['icon' => 'fi fi-rr-time-forward', 'label' => 'Pending Rewards', 'value' => '3'],
                            ] as $stat)
                                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-4 backdrop-blur-xl transition hover:-translate-y-1 hover:border-secondary/35 hover:bg-white/[0.09]">
                                    <i class="{{ $stat['icon'] }} text-lg text-secondary"></i>
                                    <p class="mt-4 text-2xl font-black text-white">{{ $stat['value'] }}</p>
                                    <p class="mt-1 text-xs font-semibold leading-5 text-white/50">{{ $stat['label'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="rounded-[1.5rem] border border-white/12 bg-white/[0.06] p-5 backdrop-blur-2xl">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-white/45">How it works</p>
                            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                @foreach ([
                                    ['step' => '01', 'label' => 'Share Link'],
                                    ['step' => '02', 'label' => 'Friend Enrolls'],
                                    ['step' => '03', 'label' => 'Both Get Rewards'],
                                ] as $item)
                                    <div class="relative rounded-2xl border border-white/10 bg-black/20 p-4">
                                        <span class="text-xs font-black text-secondary">{{ $item['step'] }}</span>
                                        <p class="mt-2 text-sm font-bold text-white">{{ $item['label'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <a
                                :href="'https://wa.me/?text=' + encodeURIComponent('Join Engineers Clinic with my referral link: ' + referralLink)"
                                target="_blank"
                                rel="noreferrer"
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-secondary px-5 text-sm font-black text-textPrimary shadow-[0_18px_44px_rgba(245,200,66,0.28)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_60px_rgba(245,200,66,0.38)]"
                            >
                                <i class="fi fi-rr-paper-plane"></i>
                                Invite Friends
                            </a>
                            <button
                                type="button"
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-white/14 bg-white/10 px-5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:border-brandLight/50 hover:bg-white/16"
                                @click="copyReferral()"
                            >
                                <i class="fi fi-rr-copy"></i>
                                <span x-text="copied ? 'Copied Link' : 'Copy Link'"></span>
                            </button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .referral-grid {
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.055) 1px, transparent 1px);
        background-size: 44px 44px;
        mask-image: radial-gradient(circle at center, black, transparent 72%);
    }

    .referral-modal::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(115deg, transparent 0%, rgba(245, 200, 66, 0.65) 28%, rgba(124, 92, 252, 0.75) 52%, transparent 78%);
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        animation: referral-border 5s linear infinite;
        pointer-events: none;
    }

    .referral-sweep {
        position: relative;
        overflow: hidden;
    }

    .referral-sweep::after {
        content: "";
        position: absolute;
        inset-block: -40%;
        left: -30%;
        width: 30%;
        transform: rotate(18deg);
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.20), transparent);
        animation: referral-sweep 4s ease-in-out infinite;
    }

    .referral-orbit {
        animation: referral-spin 14s linear infinite;
    }

    .referral-node {
        animation: referral-float 4s ease-in-out infinite;
    }

    .referral-node:nth-of-type(2n) {
        animation-delay: -1.6s;
    }

    @keyframes referral-border {
        0% {
            filter: hue-rotate(0deg);
            opacity: 0.68;
        }

        50% {
            opacity: 1;
        }

        100% {
            filter: hue-rotate(360deg);
            opacity: 0.68;
        }
    }

    @keyframes referral-sweep {
        0%,
        45% {
            transform: translateX(0) rotate(18deg);
            opacity: 0;
        }

        55% {
            opacity: 1;
        }

        100% {
            transform: translateX(520%) rotate(18deg);
            opacity: 0;
        }
    }

    @keyframes referral-spin {
        to {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    @keyframes referral-float {
        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }
    }
</style>
