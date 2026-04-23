<style>
    .marquee-wrapper {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .marquee {
        display: flex;
        width: max-content;
    }

    /* TRACK */
    .marquee-track {
        display: flex;
        align-items: center;
        gap: 80px;
        flex-shrink: 0;
    }

    /* INFINITE SCROLL */
    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .scroll {
        animation: scroll 28s linear infinite;
    }

    /* PAUSE ON HOVER */
    .scroll:hover {
        animation-play-state: paused;
    }

    /* LOGO STYLE */
    .logo {
        height: 28px;
        transition: all 0.3s ease;
    }

    .logo:hover {
        filter: grayscale(100%);
        opacity: 0.5;
        transform: scale(0.95);
    }
</style>

<section class="relative bg-bgWhite py-16 overflow-hidden">

    <!-- 🔥 BACKGROUND BLOBS -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-[300px] h-[300px] bg-glowGreen blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-glowOrange blur-[120px] rounded-full"></div>
    </div>

    <!-- HEADING -->
    <div class="relative text-center mb-10 px-6">
        <h2 class="text-2xl md:text-4xl font-bold text-textPrimary">
            Get offers from
            <span class="text-brand">800+</span> top companies
        </h2>
    </div>

    <!-- MARQUEE -->
    <div class="marquee-wrapper">

        <!-- LEFT FADE -->
        <div class="absolute left-0 top-0 h-full w-24 bg-gradient-to-r from-bgWhite to-transparent z-10"></div>

        <!-- RIGHT FADE -->
        <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-bgWhite to-transparent z-10"></div>

        <div class="marquee scroll">

            <!-- SET 1 -->
            <div class="marquee-track">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Uber_logo_2018.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/55/Paytm_logo.png" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg" class="logo">
            </div>

            <!-- DUPLICATE (IMPORTANT) -->
            <div class="marquee-track">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Uber_logo_2018.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/55/Paytm_logo.png" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg" class="logo">
            </div>

        </div>

    </div>

</section>