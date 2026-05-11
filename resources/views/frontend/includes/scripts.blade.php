<script>
    // Hero Carousel
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-carousel-item');
    if (slides.length > 0) {
        function showSlide(n) {
            slides[currentSlide].classList.remove('active');
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }
        setInterval(() => showSlide(currentSlide + 1), 5000);
    }

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Mobile Drawer Logic
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const closeDrawerBtn = document.getElementById('close-drawer');
    const mobileDrawer = document.getElementById('mobile-drawer');
    const drawerBackdrop = document.getElementById('drawer-backdrop');
    const drawerContent = document.getElementById('drawer-content');

    function openDrawer() {
        mobileDrawer.classList.remove('pointer-events-none');
        drawerBackdrop.classList.add('opacity-100');
        drawerBackdrop.classList.remove('opacity-0');
        drawerContent.classList.remove('translate-x-full');
    }

    function closeDrawer() {
        drawerBackdrop.classList.remove('opacity-100');
        drawerBackdrop.classList.add('opacity-0');
        drawerContent.classList.add('translate-x-full');
        setTimeout(() => {
            mobileDrawer.classList.add('pointer-events-none');
        }, 500);
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', openDrawer);
    }

    if (closeDrawerBtn) {
        closeDrawerBtn.addEventListener('click', closeDrawer);
    }

    if (drawerBackdrop) {
        drawerBackdrop.addEventListener('click', closeDrawer);
    }
</script>
