// file: public/js/script.js

// ===================================================
// JALANKAN SEMUA FUNGSI SAAT HALAMAN SIAP
// ===================================================
document.addEventListener('DOMContentLoaded', () => {
    initSideMenu();
    initImagePreview();
    
    // Untuk kalender, kita akan panggil VanillaCalendar langsung
    // karena kita tidak lagi menggunakan 'import' dari Vite
    if (typeof VanillaCalendar !== 'undefined') {
        initBookingCalendar();
    }
});


// ===================================================
// FUNGSI-FUNGSI UTAMA
// ===================================================

/**
 * Inisialisasi logika untuk menu samping (hamburger).
 */
function initSideMenu() {
    const menuBtn = document.getElementById('menu-btn');
    const sideMenu = document.getElementById('side-menu');
    const closeBtn = document.getElementById('close-btn');

    if (menuBtn && sideMenu && closeBtn) {
        menuBtn.addEventListener('click', () => {
            sideMenu.classList.toggle('open');
            menuBtn.classList.toggle('open');
        });
        closeBtn.addEventListener('click', () => {
            sideMenu.classList.remove('open');
            menuBtn.classList.remove('open');
        });
    }
}

/**
 * Inisialisasi logika untuk preview gambar pada form.
 */
function initImagePreview() {
    const gambarInput = document.getElementById('gambar-input');
    const imagePreview = document.getElementById('image-preview');

    if (gambarInput && imagePreview) {
        gambarInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                imagePreview.style.display = 'block';
                imagePreview.src = URL.createObjectURL(file);
            }
        });
    }
}

/**
 * Inisialisasi kalender booking.
 */
function initBookingCalendar() {
    const calendarEl = document.getElementById('my-calendar');
    if (calendarEl) {
        // Logika lengkap untuk fetch data dan inisialisasi kalender
        const fetchAndRenderBookings = (month, year, calendar) => {
            fetch(`/api/monthly-bookings?month=${month}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    const datesToMark = [];
                    const datesToDisable = [];
                    for (const date in data) {
                        const count = data[date];
                        datesToMark.push({ date: date, note: `${count} kucing` });
                        if (count >= 10) datesToDisable.push(date);
                    }
                    calendar.settings.dates = datesToMark;
                    calendar.settings.range.disabled = datesToDisable;
                    calendar.update();
                })
                .catch(error => console.error('Error fetching booking data:', error));
        };

        const today = new Date();
        const calendar = new VanillaCalendar(calendarEl, {
            settings: {
                lang: 'id',
                iso8601: true,
                range: { disablePast: true },
                selection: { day: 'single' },
                visibility: { daysOutside: false },
            },
            actions: {
                clickDay(event, dates) {
                    if (dates[0]) window.location.href = `/booking/create?date=${dates[0]}`;
                },
                clickMonth(event, month, year) {
                   fetchAndRenderBookings(month + 1, year, calendar);
                },
                clickYear(event, year) {
                    fetchAndRenderBookings(today.getMonth() + 1, year, calendar);
                },
            },
        });

        calendar.init();
        fetchAndRenderBookings(today.getMonth() + 1, today.getFullYear(), calendar);
    }
}