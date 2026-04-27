<style>
    .partners-marquee-wrapper {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .partners-marquee {
        display: flex;
        width: max-content;
    }

    .partners-marquee-track {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        gap: clamp(32px, 8vw, 80px);
        padding-right: clamp(32px, 8vw, 80px);
    }

    @keyframes partners-scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .partners-scroll {
        animation: partners-scroll 28s linear infinite;
    }

    .partners-scroll:hover {
        animation-play-state: paused;
    }

    .partner-logo {
        display: block;
        height: clamp(22px, 5vw, 28px);
        max-width: clamp(88px, 28vw, 140px);
        object-fit: contain;
        transition: all 0.3s ease;
    }

    .partner-logo:hover {
        filter: grayscale(100%);
        opacity: 0.5;
        transform: scale(0.95);
    }

    @media (max-width: 640px) {
        .partners-scroll {
            animation-duration: 22s;
        }
    }
</style>

<section class="relative overflow-hidden bg-bgWhite py-12 sm:py-16">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-24 top-0 h-56 w-56 rounded-full bg-glowGreen blur-[100px] sm:h-[300px] sm:w-[300px] sm:blur-[120px]"></div>
        <div class="absolute -right-24 bottom-0 h-56 w-56 rounded-full bg-glowOrange blur-[100px] sm:h-[300px] sm:w-[300px] sm:blur-[120px]"></div>
    </div>

    <div class="relative mb-8 px-5 text-center sm:mb-10 sm:px-6">
        <h2 class="mx-auto max-w-2xl text-2xl font-bold leading-tight text-textPrimary sm:text-3xl md:text-4xl">
            Get offers from
            <span class="text-brand">800+</span> top companies
        </h2>
    </div>

    <div class="partners-marquee-wrapper">
        <div class="absolute left-0 top-0 z-10 h-full w-10 bg-gradient-to-r from-bgWhite to-transparent sm:w-24"></div>
        <div class="absolute right-0 top-0 z-10 h-full w-10 bg-gradient-to-l from-bgWhite to-transparent sm:w-24"></div>

        <div class="partners-marquee partners-scroll">
            <div class="partners-marquee-track">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Google" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" alt="Amazon" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg" alt="Netflix" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Uber_logo_2018.svg" alt="Uber" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/55/Paytm_logo.png" alt="Paytm" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg" alt="IBM" class="partner-logo">
            </div>

            <div class="partners-marquee-track" aria-hidden="true">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" alt="" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg" alt="" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Uber_logo_2018.svg" alt="" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/55/Paytm_logo.png" alt="" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="" class="partner-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg" alt="" class="partner-logo">
            </div>
        </div>
    </div>
</section>
