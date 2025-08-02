// file: public/js/script.js

// ===================================================
// JALANKAN SEMUA FUNGSI SAAT HALAMAN SIAP
// ===================================================
document.addEventListener('DOMContentLoaded', () => {
    initSideMenu();
    initImagePreview();
    initAddress();
    initHours();
    initKucingchecked();
    validateBookingForm();
    initSelectAll();
    initFullCalendar();
    
   
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

function initFullCalendar() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl || typeof FullCalendar === "undefined") return;

    const timeInfo = document.getElementById('calendar-time-info');
    const btnKonfirmasi = document.getElementById('btn-konfirmasi-booking');
    let selectedDate = null;

    // Ambil data fullDates dan events dari atribut data-* pada elemen #calendar
    let fullDates = [];
    let events = [];
    if (calendarEl.dataset.fullDates) {
        try { fullDates = JSON.parse(calendarEl.dataset.fullDates); } catch (e) { fullDates = []; }
    }
    if (calendarEl.dataset.events) {
        try { events = JSON.parse(calendarEl.dataset.events); } catch (e) { events = []; }
    }
    const today = new Date().toISOString().split('T')[0];

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        selectable: true,
        events: events,
        eventContent: function(arg) {
            // Custom tampilan event: jam booking - jam selesai
            let title = arg.event.title;
            return {
                html: `<div style="background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:8px;font-size:12px;margin-top:2px;display:inline-block">${title}</div>`
            };
        },
        eventClick: function(info) {
            // Tidak melakukan apapun
            info.jsEvent.preventDefault();
            return false;
        },
        selectAllow: function(selectInfo) {
            const dateStr = selectInfo.startStr;
            return dateStr >= today && !fullDates.includes(dateStr);
        },
        dateClick: function(info) {
            const dateStr = info.dateStr;
            const clickedMonth = info.date.getMonth();
            const currentMonth = calendar.getDate().getMonth();

            if (clickedMonth !== currentMonth) {
                calendar.gotoDate(info.date);
                setTimeout(() => {
                    calendar.select(info.date);
                }, 10);
            } else {
                calendar.select(info.date);
            }

            if (dateStr < today) {
                timeInfo.textContent = '';
                btnKonfirmasi.disabled = true;
                return;
            }
            if (fullDates.includes(dateStr)) {
                timeInfo.textContent = 'Tanggal penuh, silakan pilih tanggal lain.';
                btnKonfirmasi.disabled = true;
                return;
            }
            selectedDate = dateStr;
            timeInfo.textContent = 'Tanggal dipilih: ' + dateStr;
            btnKonfirmasi.disabled = false;
        },
        dayCellDidMount: function(arg) {
            const dateStr = arg.date.toISOString().split('T')[0];
            if (dateStr < today || fullDates.includes(dateStr)) {
                arg.el.classList.add('fc-day-disabled');
                arg.el.style.background = '#eee';
                arg.el.style.cursor = 'not-allowed';
            }
        }
    });
    calendar.render();

    if (btnKonfirmasi) {
        btnKonfirmasi.addEventListener('click', function () {
            if (selectedDate) {
                window.location.href = `/booking/create?date=${selectedDate}`;
            } else {
                alert("Silakan pilih tanggal terlebih dahulu!");
            }
        });
    }
}


//fungsi alamat
function initAddress() {
    const alamatDefaultEl = document.getElementById("alamatDefault");
    const alamatBookingInput = document.getElementById("alamatBookingInput");
    const radioDefault = document.getElementById("alamat_default");
    const radioManual = document.getElementById("alamat_manual");

    // Pastikan semua elemen ada sebelum lanjut
    if (
        !alamatDefaultEl ||
        !alamatBookingInput ||
        !radioDefault ||
        !radioManual
    )
        return;

    const alamatDefaultText = alamatDefaultEl.textContent;

    // Listener saat radio "Alamat Sesuai Profil" dipilih
    radioDefault.addEventListener("change", function () {
        if (this.checked) {
            alamatBookingInput.value = alamatDefaultText;
            alamatBookingInput.readOnly = true;
        }
    });

    // Listener saat radio "Masukkan Alamat Lain" dipilih
    radioManual.addEventListener("change", function () {
        if (this.checked) {
            // Kita tidak lagi mengosongkan input, agar pengguna bisa mengedit alamat default
            alamatBookingInput.value = ""; // Baris ini bisa di-nonaktifkan atau dihapus
            alamatBookingInput.readOnly = false;
            alamatBookingInput.focus();
        }
    });

    // === BAGIAN BARU YANG DITAMBAHKAN ===
    // Listener saat pengguna fokus pada kolom input alamat
    alamatBookingInput.addEventListener("focus", function () {
        // Hanya jalankan jika pilihan saat ini adalah "Alamat Sesuai Profil"
        if (radioDefault.checked) {
            // Secara otomatis "klik" radio button "Masukkan alamat lain"
            radioManual.click();
        }
    });
}

//fungsi jam
function initHours() {
    const slider = document.getElementById("jamBookingSlider");
    const manual = document.getElementById("jamBookingManual");
    const label = document.getElementById("jamBookingLabel");

    // Pastikan semua elemen ada sebelum lanjut
    if (!slider || !manual || !label) return;

    function sliderToTime(val) {
        const hour = Math.floor(val);
        const minute = val % 1 === 0.5 ? "30" : "00";
        return `${hour.toString().padStart(2, "0")}:${minute}`;
    }

    slider.addEventListener("input", function () {
        const jam = sliderToTime(slider.value);
        manual.value = jam;
        label.textContent = "Jam dipilih: " + jam;
    });

    manual.addEventListener("input", function () {
        label.textContent = "Jam dipilih: " + manual.value;
        const [h, m] = manual.value.split(":");
        slider.value = parseInt(h) + (m === "30" ? 0.5 : 0);
    });
}

// Show layanan select layanan when kucing is checked
function initKucingchecked() {
    document.querySelectorAll(".kucing-checkbox").forEach(function (checkbox) {
        const layananContainer = document.getElementById(
            "layanan_container_" + checkbox.value
        );
        const layananSelect = layananContainer.querySelector("select");

        // Fungsi untuk mengatur status dropdown
        const setLayananState = (isChecked) => {
            if (isChecked) {
                layananContainer.style.display = "block";
                layananSelect.disabled = false; // AKTIFKAN dropdown
            } else {
                layananContainer.style.display = "none";
                layananSelect.disabled = true; // NONAKTIFKAN dropdown
                layananSelect.value = ""; // Reset nilainya
            }
        };

        // Atur kondisi awal saat halaman pertama kali dimuat
        setLayananState(checkbox.checked);

        // Tambahkan listener untuk setiap perubahan
        checkbox.addEventListener("change", function () {
            setLayananState(this.checked);
        });
    });
}

// Validasi booking layanan tiap kucing
// GANTIKAN FUNGSI LAMA DENGAN YANG INI
function validateBookingForm() {
    const form = document.querySelector("#booking-form");
    // Pastikan form ada di halaman ini sebelum lanjut
    if (!form) return;

    form.addEventListener("submit", function (event) {
        // --- VALIDASI 1: Cek apakah minimal satu kucing dipilih ---
        const checkedKucing = form.querySelectorAll(".kucing-checkbox:checked");
        if (checkedKucing.length === 0) {
            // Hentikan pengiriman form
            event.preventDefault();
            // Tampilkan peringatan
            alert("Anda harus memilih setidaknya satu kucing untuk booking.");
            // Hentikan eksekusi fungsi agar tidak menampilkan alert kedua
            return;
        }

        // --- VALIDASI 2: Cek apakah setiap kucing yang dipilih punya layanan ---
        // Loop setiap kucing yang dicentang dan cek layanannya.
        for (let i = 0; i < checkedKucing.length; i++) {
            const checkbox = checkedKucing[i];
            const layananSelect = form.querySelector(
                "#layanan_container_" + checkbox.value + " select"
            );

            // ==========================================================
            // ================== INI BARIS YANG DIPERBAIKI =============
            // ==========================================================
            // Cek jika elemen select tidak ada ATAU jika nilainya adalah string kosong.
            if (!layananSelect || layananSelect.value === "") {
                event.preventDefault();
                alert(
                    "Silakan pilih layanan untuk setiap kucing yang dicentang."
                );
                return; // Langsung berhenti setelah menemukan error pertama.
            }
        }
    });
}

// Fungsi untuk fitur "Pilih Semua" kucing
function initSelectAll() {
    const selectAllCheckbox = document.getElementById('select-all-kucing');
    const kucingCheckboxes = document.querySelectorAll('.kucing-checkbox');

    // Jika elemen tidak ada di halaman ini, hentikan fungsi
    if (!selectAllCheckbox || kucingCheckboxes.length === 0) return;

    // 1. Logika saat checkbox "Pilih Semua" diklik
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        kucingCheckboxes.forEach(checkbox => {
            if (checkbox.checked !== isChecked) {
                checkbox.checked = isChecked;
                // PENTING: Picu event 'change' agar dropdown layanan muncul/hilang
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });

    // 2. Logika untuk mengubah status "Pilih Semua" jika semua checkbox individu dicentang
    const updateTotal = () => {
        const totalChecked = document.querySelectorAll('.kucing-checkbox:checked').length;
        // Indeterminate state jika ada yang dipilih tapi tidak semua
        if (totalChecked > 0 && totalChecked < kucingCheckboxes.length) {
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = totalChecked === kucingCheckboxes.length;
        }
    };

    kucingCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotal);
    });

    // Panggil sekali saat load untuk inisialisasi
    updateTotal();
}

// Fungsi untuk inisialisasi chart booking
window.initBookingChart = function(labels7, labels30, labels365, data7, data30, data365) {
    const allLabels = {
        7: labels7,
        30: labels30,
        365: labels365
    };
    const allData = {
        7: data7,
        30: data30,
        365: data365
    };

    let chartType = 'bar';
    let chartPeriod = '7';

    function getDataset(type, period) {
        return [{
            label: 'Booking',
            data: allData[period],
            backgroundColor: type === 'bar' ? 'rgba(54, 162, 235, 0.5)' : 'rgba(0,0,0,0)',
            borderColor: 'rgba(54, 162, 235, 1)',
            fill: false,
            tension: 0.3,
            pointRadius: type === 'line' ? 3 : 0
        }];
    }

    var ctx = document.getElementById('bookingChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: allLabels[chartPeriod],
            datasets: getDataset(chartType, chartPeriod)
        },
        options: {
            responsive: true
        }
    });

    document.getElementById('chartType').addEventListener('change', function() {
        chartType = this.value;
        myChart.destroy();
        myChart = new Chart(ctx, {
            type: chartType,
            data: {
                labels: allLabels[chartPeriod],
                datasets: getDataset(chartType, chartPeriod)
            },
            options: {
                responsive: true
            }
        });
    });

    document.getElementById('chartPeriod').addEventListener('change', function() {
        chartPeriod = this.value;
        myChart.destroy();
        myChart = new Chart(ctx, {
            type: chartType,
            data: {
                labels: allLabels[chartPeriod],
                datasets: getDataset(chartType, chartPeriod)
            },
            options: {
                responsive: true
            }
        });
    });
}

function initFullCalendarAdmin() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl || typeof FullCalendar === "undefined") return;

    let events = [];
    if (calendarEl.dataset.events) {
        try { events = JSON.parse(calendarEl.dataset.events); } catch (e) { events = []; }
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        events: events,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        eventContent: function(arg) {
            let jumlahKucing = arg.event.extendedProps?.jumlahKucing ?? arg.event.jumlahKucing;
            let namaTim = arg.event.extendedProps?.namaTim ?? arg.event.namaTim;
            return {
                html: `
                    <div style="background:#eaf1fb;border-radius:8px;padding:4px 8px;display:inline-block;">
                        <div style="font-weight:bold;">${arg.event.title}</div>
                        <div style="margin-top:2px;font-size:12px;">
                            <span style="background:#3498db;color:#fff;border-radius:8px;padding:2px 6px;margin-right:4px;">${jumlahKucing} kucing</span>
                            <span style="background:#2ecc71;color:#fff;border-radius:8px;padding:2px 6px;">${namaTim}</span>
                        </div>
                    </div>
                `
            }
        },
        eventClick: function(info) {
            // (Optional) klik event bisa redirect ke detail booking
            // window.location.href = `/admin/booking/${info.event.id}`;
        },
        dateClick: function(info) {
            // Redirect ke halaman list booking di tanggal yang diklik
            window.location.href = `/admin/booking/by-date/${info.dateStr}`;
        }
    });
    calendar.render();
}