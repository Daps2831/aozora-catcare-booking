// file: resources/js/app.js
//alert('JS loaded!');
import './bootstrap';

// Import library kalender
import { Calendar } from 'vanilla-calendar-pro';



/**
 * ===================================================================
 * FUNGSI-FUNGSI UTAMA UNTUK SETIAP FITUR
 * ===================================================================
 */

// Fungsi untuk mengaktifkan menu samping
function initSideMenu() {
    const menuBtn = document.getElementById('menu-btn');
    const sideMenu = document.getElementById('side-menu');
    if (menuBtn && sideMenu) {
        menuBtn.addEventListener('click', function() {
            menuBtn.classList.toggle('open');
            sideMenu.classList.toggle('open');
        });
    }
    // Untuk tombol close (X)
    const closeBtn = document.getElementById('close-btn');
    if (closeBtn && sideMenu && menuBtn) {
        closeBtn.addEventListener('click', function() {
            sideMenu.classList.remove('open');
            menuBtn.classList.remove('open');
        });
    }
}

// Fungsi untuk mengaktifkan preview gambar
function initImagePreview() {
    const gambarInput = document.getElementById('gambar-input');
    const imagePreview = document.getElementById('image-preview');
    if (gambarInput && imagePreview) {
        gambarInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                imagePreview.style.display = 'block';
                imagePreview.src = URL.createObjectURL(file);
            }
        });
    }
}

function initBookingCalendar() {
    const calendarEl = document.getElementById('my-calendar');
    const timeInfo = document.getElementById('calendar-time-info');
    const btnKonfirmasi = document.getElementById('btn-konfirmasi-booking');
    let selectedDate = null;

    if (calendarEl && timeInfo && btnKonfirmasi) {
        const calendar = new Calendar('#my-calendar', {
            settings: {
                lang: 'en',
                iso8601: true,
                range: {
                    disablePast: true,
                    disabled: [],
                },
                selectionDatesMode: 'single',
                selected: { dates: [] },
                visibility: {
                    controls: true,
                },
            },
            locale: {
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
            },
            onClickDate(self) {
                // self.context.selectedDates adalah array tanggal terpilih
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
                    calendar.set({
                        range: { disabled: datesToDisable }
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

/**
 * ===================================================================
 * JALANKAN SEMUA FUNGSI SAAT HALAMAN SIAP
 * ===================================================================
 */
document.addEventListener('DOMContentLoaded', () => {
    initSideMenu();
    initImagePreview();
    initBookingCalendar();
});