// file: resources/js/app.js
//alert('JS loaded!');
import './bootstrap';

// Import library kalender
import { Calendar } from 'vanilla-calendar-pro';

// IMPORT DUA FILE CSS INI

import 'vanilla-calendar-pro/styles/layout.css';




/**
 * ===================================================================
 * JALANKAN SEMUA FUNGSI SAAT HALAMAN SIAP
 * ===================================================================
 */
document.addEventListener('DOMContentLoaded', () => {
    initSideMenu();
    initImagePreview();
    initBookingCalendar();
    initAddress();
    initHours();
});

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
    const fileNameSpan = document.getElementById('file-name');
    const btnGantiFoto = document.getElementById('btn-ganti-foto');
    if (gambarInput && imagePreview) {
        gambarInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                imagePreview.style.display = 'block';
                imagePreview.src = URL.createObjectURL(file);
                fileNameSpan.textContent = file.name;
                fileNameSpan.style.display = 'inline-block';
                gambarInput.style.display = 'none';
                btnGantiFoto.style.display = 'inline-block';
            }
        });
        if (btnGantiFoto) {
            btnGantiFoto.addEventListener('click', function() {
                gambarInput.value = '';
                gambarInput.style.display = 'inline-block';
                fileNameSpan.style.display = 'none';
                btnGantiFoto.style.display = 'none';
                imagePreview.src = imagePreview.dataset.original || imagePreview.src;
            });
        }
    }
}

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
            disableDatesPast: true,
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


function initAddress() {
    const alamatDefault = document.getElementById('alamatDefault').textContent;
    const alamatBookingInput = document.getElementById('alamatBookingInput');
    const radioDefault = document.getElementById('alamat_default');
    const radioManual = document.getElementById('alamat_manual');

    radioDefault.addEventListener('change', function() {
        if (radioDefault.checked) {
            alamatBookingInput.value = alamatDefault;
            alamatBookingInput.readOnly = true;
        }
    });
    radioManual.addEventListener('change', function() {
        if (radioManual.checked) {
            alamatBookingInput.value = '';
            alamatBookingInput.readOnly = false;
            alamatBookingInput.focus();
        }
    });
}


function initHours() {
    const slider = document.getElementById('jamBookingSlider');
    const manual = document.getElementById('jamBookingManual');
    const label = document.getElementById('jamBookingLabel');

    function sliderToTime(val) {
        // val: 8 ... 18, step 0.5
        const hour = Math.floor(val);
        const minute = (val % 1 === 0.5) ? '30' : '00';
        return `${hour.toString().padStart(2, '0')}:${minute}`;
    }

    slider.addEventListener('input', function() {
        const jam = sliderToTime(slider.value);
        manual.value = jam;
        label.textContent = 'Jam dipilih: ' + jam;
    });

    manual.addEventListener('input', function() {
        label.textContent = 'Jam dipilih: ' + manual.value;
        // Sinkronisasi slider jika user input manual
        const [h, m] = manual.value.split(':');
        slider.value = parseInt(h) + (m === '30' ? 0.5 : 0);
    });
}
