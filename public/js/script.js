// file: public/js/script.js

// ===================================================
// JALANKAN SEMUA FUNGSI SAAT HALAMAN SIAP
// ===================================================
document.addEventListener('DOMContentLoaded', () => {
    initSideMenu();
    initImagePreview();
    initBookingCalendar();
    
   
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
    const timeInfo = document.getElementById('calendar-time-info');
    const btnKonfirmasi = document.getElementById('btn-konfirmasi-booking');
    let selectedDate = null;

    if (calendarEl && timeInfo && btnKonfirmasi) {
        const calendar = new Calendar('#my-calendar', {
            lang: 'en',
            iso8601: true,
            selectionDatesMode: 'single',
            selected: { dates: [] },
            controls: true,
            months: {
                long: [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ],
                short: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                ],
            },
            weekdays: {
                long: [
                    'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
                ],
                short: [
                    'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'
                ],
            },
            onClickDate(self) {
                if (self.context.selectedDates.length > 0) {
                    selectedDate = self.context.selectedDates[0];
                    timeInfo.textContent = `Tanggal dipilih: ${selectedDate}`;
                    btnKonfirmasi.disabled = false;
                } else {
                    selectedDate = null;
                    timeInfo.textContent = '';
                    btnKonfirmasi.disabled = true;
                }
            },
            onClickDayDisabled(self) {
                alert('Tanggal penuh, silakan pilih tanggal lain.');
            },
        });

        // Fetch data, set disable, TANPA init ulang!
        const fetchAndRenderBookings = (month, year, calendar) => {
            fetch(`/api/monthly-bookings?month=${month}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    const datesToDisable = [];
                    for (const date in data) {
                        const count = data[date];
                        if (count >= 10) datesToDisable.push(date);
                    }
                    // GUNAKAN disableDates di root, BUKAN settings.range.disabled!
                    calendar.set({
                        disableDates: datesToDisable
                    });
                });
        };

        const today = new Date();
        fetchAndRenderBookings(today.getMonth() + 1, today.getFullYear(), calendar);

        calendar.init();

        btnKonfirmasi.addEventListener('click', function() {
            if (selectedDate) {
                window.location.href = `/booking/create?date=${selectedDate}`;
            } else {
                alert('Silakan pilih tanggal terlebih dahulu!');
            }
        });
    }
}