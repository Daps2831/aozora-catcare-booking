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
    if (window.location.pathname.startsWith('/admin')) {
        initFullCalendarAdmin();
    } else {
        initFullCalendar();
    }
    initUserDropdown();
    initTotalHargaBooking();
    
   
});


// ===================================================
// FUNGSI-FUNGSI UTAMA
// ===================================================

/**
 * Inisialisasi logika untuk menu samping (hamburger).
 */
function initSideMenu() {
    const menuBtn = document.getElementById("menu-btn");
    const sideMenu = document.getElementById("side-menu");
    const overlay = document.getElementById("side-menu-overlay");
    const closeBtn = document.getElementById("close-btn");

    if (!menuBtn || !sideMenu) return;

    // Function to open menu
    const openMenu = () => {
        menuBtn.classList.add("open");
        sideMenu.classList.add("open");
        if (overlay) overlay.classList.add("show");
        document.body.style.overflow = "hidden"; // Prevent scrolling
    };

    // Function to close menu
    const closeMenu = () => {
        menuBtn.classList.remove("open");
        sideMenu.classList.remove("open");
        if (overlay) overlay.classList.remove("show");
        document.body.style.overflow = ""; // Restore scrolling
    };

    // Menu button click
    if (menuBtn) {
        menuBtn.addEventListener("click", function(e) {
            e.stopPropagation();
            if (sideMenu.classList.contains("open")) {
                closeMenu();
            } else {
                openMenu();
            }
        });
    }

    // Close button click
    if (closeBtn) {
        closeBtn.addEventListener("click", function(e) {
            e.stopPropagation();
            closeMenu();
        });
    }

    // Overlay click
    if (overlay) {
        overlay.addEventListener("click", closeMenu);
    }

    // Close menu when clicking outside
    document.addEventListener("click", function(event) {
        if (sideMenu.classList.contains("open")) {
            if (!sideMenu.contains(event.target) && 
                !menuBtn.contains(event.target)) {
                closeMenu();
            }
        }
    });

    // Close menu with Escape key
    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && sideMenu.classList.contains("open")) {
            closeMenu();
        }
    });

    // Prevent menu from closing when clicking inside
    sideMenu.addEventListener("click", function(e) {
        e.stopPropagation();
    });
}

// Fungsi untuk mengaktifkan user dropdown
function initUserDropdown() {
    const userDropdown = document.getElementById("userDropdown");
    const dropdownMenu = document.getElementById("dropdownMenu");
    const userDropdownContainer = document.querySelector(".user-dropdown");

    if (!userDropdown || !dropdownMenu || !userDropdownContainer) {
        console.log("User dropdown elements not found");
        return;
    }

    console.log("User dropdown initialized successfully");

    // Toggle dropdown saat diklik
    userDropdown.addEventListener("click", function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const isOpen = dropdownMenu.classList.contains("show");
        console.log("Dropdown clicked, current state:", isOpen);
        
        if (isOpen) {
            closeDropdown();
        } else {
            openDropdown();
        }
    });

    // Function to open dropdown
    function openDropdown() {
        console.log("Opening dropdown");
        dropdownMenu.classList.add("show");
        userDropdownContainer.classList.add("open");
        
        // Force CSS styles for immediate visibility
        dropdownMenu.style.display = "block";
        dropdownMenu.style.opacity = "1";
        dropdownMenu.style.visibility = "visible";
        dropdownMenu.style.transform = "translateY(0)";
        dropdownMenu.style.pointerEvents = "auto";
    }

    // Function to close dropdown
    function closeDropdown() {
        console.log("Closing dropdown");
        dropdownMenu.classList.remove("show");
        userDropdownContainer.classList.remove("open");
        
        // Reset styles with delay for smooth animation
        setTimeout(() => {
            if (!dropdownMenu.classList.contains("show")) {
                dropdownMenu.style.display = "";
                dropdownMenu.style.opacity = "";
                dropdownMenu.style.visibility = "";
                dropdownMenu.style.transform = "";
                dropdownMenu.style.pointerEvents = "";
            }
        }, 300);
    }

    // Close dropdown saat klik di luar
    document.addEventListener("click", function(event) {
        if (!userDropdownContainer.contains(event.target)) {
            closeDropdown();
        }
    });

    // Close dropdown dengan ESC key
    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape") {
            closeDropdown();
        }
    });

    // Prevent dropdown from closing when clicking inside
    dropdownMenu.addEventListener("click", function(e) {
        e.stopPropagation();
    });
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

    // Ambil data fullDates, disabledDatesData, dan events dari atribut data-*
    let fullDates = [];
    let disabledDatesData = {};
    let events = [];
    
    if (calendarEl.dataset.fullDates) {
        try { 
            fullDates = JSON.parse(calendarEl.dataset.fullDates);
        } catch (e) { fullDates = []; }
    }
    
    if (calendarEl.dataset.disabledDatesData) {
        try { 
            disabledDatesData = JSON.parse(calendarEl.dataset.disabledDatesData);
        } catch (e) { disabledDatesData = {}; }
    }
    
    if (calendarEl.dataset.events) {
        try { events = JSON.parse(calendarEl.dataset.events); } catch (e) { events = []; }
    }

    // Fungsi untuk normalisasi tanggal ke UTC
    function normalizeDate(dateStr) {
        const date = new Date(dateStr + 'T00:00:00.000Z');
        return date.toISOString().split('T')[0];
    }

    // Normalisasi fullDates
    fullDates = fullDates.map(date => normalizeDate(date));
    
    const today = normalizeDate(new Date().toISOString().split('T')[0]);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        selectable: true,
        events: events,
        timeZone: 'UTC',
      
        eventContent: function(arg) {
            let jumlahKucing = arg.event.extendedProps?.jumlahKucing ?? arg.event.jumlahKucing;
            let namaTim = arg.event.extendedProps?.namaTim ?? arg.event.namaTim;
            let statusBooking = arg.event.extendedProps?.statusBooking ?? arg.event.statusBooking;

            let bgColor = '#eaf1fb';
            if (statusBooking && statusBooking.toLowerCase() === 'selesai') {
                bgColor = '#eafbe7';
            }

            return {
                html: `
                    <div style="background:${bgColor};border-radius:8px;padding:4px 8px;display:inline-block;">
                        <div style="font-weight:bold;">${arg.event.title}</div>
                        <div style="margin-top:2px;font-size:12px;">
                            <span style="background:#3498db;color:#fff;border-radius:8px;padding:2px 6px;margin-right:4px;">${jumlahKucing} kucing</span>
                            <span style="background:#2ecc71;color:#fff;border-radius:8px;padding:2px 6px;">${namaTim}</span>
                        </div>
                        ${statusBooking && statusBooking.toLowerCase() === 'selesai' ? '<span style="color:#27ae60;font-weight:bold;font-size:12px;">Selesai</span>' : ''}
                    </div>
                `
            }
        },
        selectAllow: function(selectInfo) {
            const dateStr = normalizeDate(selectInfo.startStr);
            return dateStr >= today && !fullDates.includes(dateStr);
        },
        dateClick: function(info) {
            const dateStr = normalizeDate(info.dateStr);
            
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
                timeInfo.textContent = 'Tanggal sudah berlalu, tidak bisa dipilih.';
                btnKonfirmasi.disabled = true;
                return;
            }
            
            // Cek apakah tanggal dinonaktifkan admin dengan keterangan
            if (disabledDatesData[dateStr]) {
                const keterangan = disabledDatesData[dateStr].keterangan;
                timeInfo.textContent = `Tanggal tidak tersedia dikarenakan ${keterangan}. Silakan pilih tanggal lain.`;
                btnKonfirmasi.disabled = true;
                return;
            }
            
            // Cek apakah tanggal penuh (kuota habis)
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
            const dateStr = normalizeDate(arg.date.toISOString().split('T')[0]);
            
            // Cek jika tanggal disable
            if (dateStr < today || fullDates.includes(dateStr)) {
                arg.el.classList.add('fc-day-disabled');
                
                // Tambahkan teks indicator jika tanggal dinonaktifkan admin
                if (disabledDatesData[dateStr] && dateStr >= today) {
                    const dayNumber = arg.el.querySelector('.fc-daygrid-day-number');
                    if (dayNumber) {
                        dayNumber.style.textDecoration = 'line-through';
                        dayNumber.style.color = '#999';
                        
                        // Tambahkan tooltip dengan keterangan
                        dayNumber.title = `Tidak tersedia: ${disabledDatesData[dateStr].keterangan}`;
                    }
                }
                return;
            }
            
            // Handler klik pada seluruh cell tanggal
            arg.el.addEventListener('click', function(e) {
                // Cek lagi saat diklik untuk memastikan
                if (disabledDatesData[dateStr]) {
                    const keterangan = disabledDatesData[dateStr].keterangan;
                    timeInfo.textContent = `Tanggal tidak tersedia dikarenakan ${keterangan}. Silakan pilih tanggal lain.`;
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
                // Highlight cell yang dipilih
                document.querySelectorAll('.fc-daygrid-day.selected-date').forEach(el => el.classList.remove('selected-date'));
                arg.el.classList.add('selected-date');
            });
        },
    });
    calendar.render();

    btnKonfirmasi.addEventListener('click', function () {
        if (selectedDate && !fullDates.includes(selectedDate) && !disabledDatesData[selectedDate]) {
            window.location.href = `/booking/create?date=${selectedDate}`;
        } else {
            alert("Silakan pilih tanggal yang tersedia terlebih dahulu!");
        }
    });
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
function validateBookingForm() {
    const form = document.querySelector("#booking-form");
    if (!form) return;

    form.addEventListener("submit", function (event) {
        let hasError = false;
        
        // VALIDASI 1: Cek minimal satu kucing
        const checkedKucing = form.querySelectorAll(".kucing-checkbox:checked");
        if (checkedKucing.length === 0) {
            event.preventDefault();
            alert("Anda harus memilih setidaknya satu kucing untuk booking.");
            return;
        }

        // VALIDASI 2: Cek layanan untuk setiap kucing
        for (let i = 0; i < checkedKucing.length; i++) {
            const checkbox = checkedKucing[i];
            const layananSelect = form.querySelector(
                "#layanan_container_" + checkbox.value + " select"
            );

            if (!layananSelect || layananSelect.value === "") {
                event.preventDefault();
                alert("Silakan pilih layanan untuk setiap kucing yang dicentang.");
                return;
            }
        }
        
        // VALIDASI 3: Cek waktu booking (tambahan)
        const jamBooking = form.querySelector('input[name="jamBooking"]');
        const tanggalBooking = form.querySelector('input[name="tanggalBooking"]');
        
        if (jamBooking && tanggalBooking) {
            const selectedDate = tanggalBooking.value;
            const selectedTime = jamBooking.value;
            const now = new Date();
            const isToday = selectedDate === now.toISOString().split('T')[0];
            
            if (isToday && selectedTime) {
                const [hours, minutes] = selectedTime.split(':').map(Number);
                const selectedDateTime = new Date();
                selectedDateTime.setHours(hours, minutes, 0, 0);
                
                const minimumTime = new Date(now.getTime() + (2 * 60 * 60 * 1000));
                
                if (selectedDateTime <= now) {
                    event.preventDefault();
                    alert('Tidak dapat booking pada waktu yang sudah lewat!');
                    return;
                }
                
                if (selectedDateTime < minimumTime) {
                    event.preventDefault();
                    alert('Booking minimal 2 jam dari sekarang!');
                    return;
                }
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

//grafik masa depan
window.initBookingChartFuture = function(labels7Future, labels30Future, labels365Future, data7Future, data30Future, data365Future) {
    const allLabelsFuture = {
        7: labels7Future,
        30: labels30Future,
        365: labels365Future
    };
    const allDataFuture = {
        7: data7Future,
        30: data30Future,
        365: data365Future
    };

    let chartTypeFuture = 'bar';
    let chartPeriodFuture = '7';

    function getDatasetFuture(type, period) {
        return [{
            label: 'Booking Masa Depan',
            data: allDataFuture[period],
            backgroundColor: type === 'bar' ? 'rgba(75, 192, 192, 0.5)' : 'rgba(0,0,0,0)',
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false,
            tension: 0.3,
            pointRadius: type === 'line' ? 3 : 0
        }];
    }

    var ctxFuture = document.getElementById('bookingChartFuture').getContext('2d');
    var myChartFuture = new Chart(ctxFuture, {
        type: chartTypeFuture,
        data: {
            labels: allLabelsFuture[chartPeriodFuture],
            datasets: getDatasetFuture(chartTypeFuture, chartPeriodFuture)
        },
        options: {
            responsive: true
        }
    });

    document.getElementById('chartPeriodFuture').addEventListener('change', function() {
        chartPeriodFuture = this.value;
        myChartFuture.data.labels = allLabelsFuture[chartPeriodFuture];
        myChartFuture.data.datasets = getDatasetFuture(chartTypeFuture, chartPeriodFuture);
        myChartFuture.update();
    });

    document.getElementById('chartTypeFuture').addEventListener('change', function() {
        chartTypeFuture = this.value;
        myChartFuture.config.type = chartTypeFuture;
        myChartFuture.data.datasets = getDatasetFuture(chartTypeFuture, chartPeriodFuture);
        myChartFuture.update();
    });
};

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
            let statusBooking = arg.event.extendedProps?.statusBooking ?? arg.event.statusBooking;

            let bgColor = '#eaf1fb';
            if (statusBooking && statusBooking.toLowerCase() === 'selesai') {
                bgColor = '#eafbe7';
            }

            return {
                html: `
                    <div style="background:${bgColor};border-radius:8px;padding:4px 8px;display:inline-block;">
                        <div style="font-weight:bold;">${arg.event.title}</div>
                        <div style="margin-top:2px;font-size:12px;">
                            <span style="background:#3498db;color:#fff;border-radius:8px;padding:2px 6px;margin-right:4px;">${jumlahKucing} kucing</span>
                            <span style="background:#2ecc71;color:#fff;border-radius:8px;padding:2px 6px;">${namaTim}</span>
                        </div>
                        ${statusBooking && statusBooking.toLowerCase() === 'selesai' ? '<span style="color:#27ae60;font-weight:bold;font-size:12px;">Selesai</span>' : ''}
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

function initTotalHargaBooking() {
    const layananSelects = document.querySelectorAll(".layanan-container select");
    const hargaTotalContainer = document.getElementById("hargaTotal");
    let totalHarga = 0;

    // Fungsi untuk menghitung total harga
    const updateTotalHarga = () => {
        totalHarga = 0;
        layananSelects.forEach(select => {
            const selectedOption = select.options[select.selectedIndex];
            const harga = parseInt(selectedOption.dataset.harga || 0);
            if (!select.disabled && harga) {
                totalHarga += harga;
            }
        });
        hargaTotalContainer.textContent = `Rp ${totalHarga.toLocaleString("id-ID")}`;
    };

    // Tambahkan event listener untuk setiap dropdown layanan
    layananSelects.forEach(select => {
        select.addEventListener("change", updateTotalHarga);
    });

    // Panggil fungsi saat halaman pertama kali dimuat
    updateTotalHarga();
}

// Tambahkan fungsi ini di bagian atas setelah initTotalHargaBooking()
function initBookingTimeValidation() {
    const jamBookingSlider = document.getElementById('jamBookingSlider');
    const jamBookingManual = document.getElementById('jamBookingManual');
    const jamBookingLabel = document.getElementById('jamBookingLabel');
    const bookingForm = document.getElementById('booking-form');
    
    if (!jamBookingSlider || !jamBookingManual) return;

    // Get current date and time
    const now = new Date();
    const selectedDate = document.querySelector('input[name="tanggalBooking"]')?.value;
    
    if (!selectedDate) return;
    
    const isToday = selectedDate === now.toISOString().split('T')[0];
    
    function validateTime() {
        const selectedTime = jamBookingManual.value;
        if (!selectedTime) return true;
        
        // Jika booking untuk hari ini
        if (isToday) {
            const [hours, minutes] = selectedTime.split(':').map(Number);
            const selectedDateTime = new Date();
            selectedDateTime.setHours(hours, minutes, 0, 0);
            
            // Minimal 2 jam dari sekarang
            const minimumTime = new Date(now.getTime() + (2 * 60 * 60 * 1000));
            
            if (selectedDateTime <= now) {
                showTimeError('Tidak dapat booking pada waktu yang sudah lewat!');
                return false;
            }
            
            if (selectedDateTime < minimumTime) {
                const minTimeStr = minimumTime.toTimeString().substring(0, 5);
                showTimeError(`Booking minimal 2 jam dari sekarang. Waktu minimal: ${minTimeStr}`);
                return false;
            }
        }
        
        clearTimeError();
        return true;
    }
    
    function showTimeError(message) {
        let errorDiv = document.getElementById('time-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'time-error';
            errorDiv.className = 'alert alert-danger';
            errorDiv.style.marginTop = '10px';
            errorDiv.style.padding = '10px';
            errorDiv.style.backgroundColor = '#f8d7da';
            errorDiv.style.color = '#721c24';
            errorDiv.style.border = '1px solid #f5c6cb';
            errorDiv.style.borderRadius = '5px';
            jamBookingManual.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        
        // Disable submit button
        const submitBtn = bookingForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
        }
    }
    
    function clearTimeError() {
        const errorDiv = document.getElementById('time-error');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
        
        // Enable submit button
        const submitBtn = bookingForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
        }
    }
    
    // Update jam operasional untuk hari ini
    if (isToday) {
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        
        // Set minimum time untuk slider (2 jam dari sekarang)
        const minSliderValue = Math.max(8, currentHour + 2 + (currentMinute > 30 ? 1 : 0.5));
        jamBookingSlider.min = minSliderValue;
        jamBookingSlider.value = Math.max(jamBookingSlider.value, minSliderValue);
        
        // Update manual input juga
        const minTimeStr = new Date(now.getTime() + (2 * 60 * 60 * 1000)).toTimeString().substring(0, 5);
        jamBookingManual.min = minTimeStr;
        
        // Update initial value jika perlu
        if (jamBookingManual.value < minTimeStr) {
            jamBookingManual.value = minTimeStr;
            jamBookingSlider.value = minSliderValue;
        }
    }
    
    // Event listeners
    jamBookingManual.addEventListener('change', validateTime);
    jamBookingSlider.addEventListener('input', function() {
        validateTime();
    });
    
    // Validasi saat form submit
    bookingForm.addEventListener('submit', function(e) {
        if (!validateTime()) {
            e.preventDefault();
            return false;
        }
    });
    
    // Initial validation
    validateTime();
}