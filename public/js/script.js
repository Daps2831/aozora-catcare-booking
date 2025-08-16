// file: public/js/script.js

/**
 * ===================================================================
 * JALANKAN SEMUA FUNGSI SAAT HALAMAN SIAP
 * ===================================================================
 */
document.addEventListener("DOMContentLoaded", () => {
    initSideMenu();
    initImagePreview();
    initAddress();
    initHours();
    initKucingchecked();
    validateBookingForm();
    initSelectAll();
    //initFullCalendar();
    initUserDropdown();
    initTotalHargaBooking();
    initBookingTimeValidation();
    initCatVerticalScroll();
});

/**
 * ===================================================================
 * FUNGSI-FUNGSI UTAMA UNTUK SETIAP FITUR
 * ===================================================================
 */

// Fungsi untuk mengaktifkan menu samping
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


// Fungsi untuk mengaktifkan preview gambar
function initImagePreview() {
    const gambarInput = document.getElementById("gambar-input");
    const imagePreview = document.getElementById("image-preview");
    const fileNameSpan = document.getElementById("file-name");
    const btnGantiFoto = document.getElementById("btn-ganti-foto");
    if (gambarInput && imagePreview) {
        gambarInput.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (file) {
                imagePreview.style.display = "block";
                imagePreview.src = URL.createObjectURL(file);
                fileNameSpan.textContent = file.name;
                fileNameSpan.style.display = "inline-block";
                gambarInput.style.display = "none";
                btnGantiFoto.style.display = "inline-block";
            }
        });
        if (btnGantiFoto) {
            btnGantiFoto.addEventListener("click", function () {
                gambarInput.value = "";
                gambarInput.style.display = "inline-block";
                fileNameSpan.style.display = "none";
                btnGantiFoto.style.display = "none";
                imagePreview.src =
                    imagePreview.dataset.original || imagePreview.src;
            });
        }
    }
}

// function initFullCalendar() {
//     const calendarEl = document.getElementById('calendar');
//     if (!calendarEl || typeof FullCalendar === "undefined") return;

//     const timeInfo = document.getElementById('calendar-time-info');
//     const btnKonfirmasi = document.getElementById('btn-konfirmasi-booking');
//     let selectedDate = null;

//     // Ambil data fullDates, disabledDatesData, dan events dari atribut data-*
//     let fullDates = [];
//     let disabledDatesData = {};
//     let events = [];

//     // Tambahkan filter event selesai
//     const showFinishedCheckbox = document.getElementById('show-finished-booking');
//     let allEvents = [];
//     if (calendarEl.dataset.events) {
//         try { allEvents = JSON.parse(calendarEl.dataset.events); } catch (e) { allEvents = []; }
//     }

//     // Fungsi untuk filter event sesuai status checkbox
//     function getFilteredEvents() {
//         if (showFinishedCheckbox && !showFinishedCheckbox.checked) {
//             // Hanya tampilkan event yang BUKAN selesai
//             return allEvents.filter(ev => 
//                 !ev.statusBooking || ev.statusBooking.toLowerCase() !== 'selesai'
//             );
//         }
//         return allEvents;
//     }
    
    
//     if (calendarEl.dataset.fullDates) {
//         try { 
//             fullDates = JSON.parse(calendarEl.dataset.fullDates);
//         } catch (e) { fullDates = []; }
//     }
    
//     if (calendarEl.dataset.disabledDatesData) {
//         try { 
//             disabledDatesData = JSON.parse(calendarEl.dataset.disabledDatesData);
//         } catch (e) { disabledDatesData = {}; }
//     }
    
//     if (calendarEl.dataset.events) {
//         try { events = JSON.parse(calendarEl.dataset.events); } catch (e) { events = []; }
//     }

//     // Fungsi untuk normalisasi tanggal ke UTC
//     function normalizeDate(dateStr) {
//         const date = new Date(dateStr + 'T00:00:00.000Z');
//         return date.toISOString().split('T')[0];
//     }

//     // Normalisasi fullDates
//     fullDates = fullDates.map(date => normalizeDate(date));
    
//     const today = normalizeDate(new Date().toISOString().split('T')[0]);

//     const calendar = new FullCalendar.Calendar(calendarEl, {
//         initialView: 'dayGridMonth',
//         locale: 'id',
//         selectable: true,
//         events: getFilteredEvents(),
//         timeZone: 'UTC',
      
//         eventContent: function(arg) {
//             let jumlahKucing = arg.event.extendedProps?.jumlahKucing ?? arg.event.jumlahKucing;
//             let namaTim = arg.event.extendedProps?.namaTim ?? arg.event.namaTim;
//             let statusBooking = arg.event.extendedProps?.statusBooking ?? arg.event.statusBooking;

//             let bgColor = '#eaf1fb';
//             if (statusBooking && statusBooking.toLowerCase() === 'selesai') {
//                 bgColor = '#eafbe7';
//             }

//             return {
//                 html: `
//                     <div style="background:${bgColor};border-radius:8px;padding:4px 8px;display:inline-block;">
//                         <div style="font-weight:bold;">${arg.event.title}</div>
//                         <div style="margin-top:2px;font-size:12px;">
//                             <span style="background:#3498db;color:#fff;border-radius:8px;padding:2px 6px;margin-right:4px;">${jumlahKucing} kucing</span>
//                             <span style="background:#2ecc71;color:#fff;border-radius:8px;padding:2px 6px;">${namaTim}</span>
//                         </div>
//                         ${statusBooking && statusBooking.toLowerCase() === 'selesai' ? '<span style="color:#27ae60;font-weight:bold;font-size:12px;">Selesai</span>' : ''}
//                     </div>
//                 `
//             }
//         },
//         selectAllow: function(selectInfo) {
//             const dateStr = normalizeDate(selectInfo.startStr);
//             return dateStr >= today && !fullDates.includes(dateStr);
//         },
//         dateClick: function(info) {
//             const dateStr = normalizeDate(info.dateStr);
            
//             const clickedMonth = info.date.getMonth();
//             const currentMonth = calendar.getDate().getMonth();

//             if (clickedMonth !== currentMonth) {
//                 calendar.gotoDate(info.date);
//                 setTimeout(() => {
//                     calendar.select(info.date);
//                 }, 10);
//             } else {
//                 calendar.select(info.date);
//             }

//             if (dateStr < today) {
//                 timeInfo.textContent = 'Tanggal sudah berlalu, tidak bisa dipilih.';
//                 btnKonfirmasi.disabled = true;
//                 return;
//             }
            
//             // Cek apakah tanggal dinonaktifkan admin dengan keterangan
//             if (disabledDatesData[dateStr]) {
//                 const keterangan = disabledDatesData[dateStr].keterangan;
//                 timeInfo.textContent = `Tanggal tidak tersedia dikarenakan ${keterangan}. Silakan pilih tanggal lain.`;
//                 btnKonfirmasi.disabled = true;
//                 return;
//             }
            
//             // Cek apakah tanggal penuh (kuota habis)
//             if (fullDates.includes(dateStr)) {
//                 timeInfo.textContent = 'Tanggal penuh, silakan pilih tanggal lain.';
//                 btnKonfirmasi.disabled = true;
//                 return;
//             }
            
//             selectedDate = dateStr;
//             timeInfo.textContent = 'Tanggal dipilih: ' + dateStr;
//             btnKonfirmasi.disabled = false;
//         },
//         dayCellDidMount: function(arg) {
//             const dateStr = normalizeDate(arg.date.toISOString().split('T')[0]);
            
//             // Cek jika tanggal disable
//             if (dateStr < today || fullDates.includes(dateStr)) {
//                 arg.el.classList.add('fc-day-disabled');
                
//                 // Tambahkan teks indicator jika tanggal dinonaktifkan admin
//                 if (disabledDatesData[dateStr] && dateStr >= today) {
//                     const dayNumber = arg.el.querySelector('.fc-daygrid-day-number');
//                     if (dayNumber) {
//                         dayNumber.style.textDecoration = 'line-through';
//                         dayNumber.style.color = '#999';
                        
//                         // Tambahkan tooltip dengan keterangan
//                         dayNumber.title = `Tidak tersedia: ${disabledDatesData[dateStr].keterangan}`;
//                     }
//                 }
//                 return;
//             }
            
//             // Handler klik pada seluruh cell tanggal
//             arg.el.addEventListener('click', function(e) {
//                 // Cek lagi saat diklik untuk memastikan
//                 if (disabledDatesData[dateStr]) {
//                     const keterangan = disabledDatesData[dateStr].keterangan;
//                     timeInfo.textContent = `Tanggal tidak tersedia dikarenakan ${keterangan}. Silakan pilih tanggal lain.`;
//                     btnKonfirmasi.disabled = true;
//                     return;
//                 }
                
//                 if (fullDates.includes(dateStr)) {
//                     timeInfo.textContent = 'Tanggal penuh, silakan pilih tanggal lain.';
//                     btnKonfirmasi.disabled = true;
//                     return;
//                 }
                
//                 selectedDate = dateStr;
//                 timeInfo.textContent = 'Tanggal dipilih: ' + dateStr;
//                 btnKonfirmasi.disabled = false;
//                 // Highlight cell yang dipilih
//                 document.querySelectorAll('.fc-daygrid-day.selected-date').forEach(el => el.classList.remove('selected-date'));
//                 arg.el.classList.add('selected-date');
//             });
//         },
//     });
//     calendar.render();

//     // Tambahkan event listener pada checkbox
//     if (showFinishedCheckbox) {
//         showFinishedCheckbox.addEventListener('change', function() {
//             calendar.removeAllEvents();
//             calendar.addEventSource(getFilteredEvents());
//         });
//     }

//     // PERBAIKAN: Tambahkan null safety untuk btnKonfirmasi
//     if (btnKonfirmasi) {
//         btnKonfirmasi.addEventListener('click', function () {
//             if (selectedDate && !fullDates.includes(selectedDate) && !disabledDatesData[selectedDate]) {
//                 window.location.href = `/booking/create?date=${selectedDate}`;
//             } else {
//                 alert("Silakan pilih tanggal yang tersedia terlebih dahulu!");
//             }
//         });
//     }
// }

//fungsi alamat
function initAddress() {
    const alamatBookingInput = document.getElementById("alamatBookingInput");
    const radioDefault = document.getElementById("alamat_default");
    const radioManual = document.getElementById("alamat_manual");

    // Pastikan semua elemen ada sebelum lanjut
    if (!alamatBookingInput || !radioDefault || !radioManual) return;

    // Ambil alamat default dari user (dari value input)
    const alamatDefaultText = alamatBookingInput.defaultValue || alamatBookingInput.value;

    // Listener saat radio "Alamat Sesuai Profil" dipilih
    radioDefault.addEventListener("change", function () {
        if (this.checked) {
            alamatBookingInput.value = alamatDefaultText;
            alamatBookingInput.readOnly = true;
            alamatBookingInput.style.backgroundColor = '#e9ecef';
            alamatBookingInput.style.cursor = 'not-allowed';
        }
    });

    // Listener saat radio "Masukkan Alamat Lain" dipilih
    radioManual.addEventListener("change", function () {
        if (this.checked) {
            alamatBookingInput.readOnly = false;
            alamatBookingInput.style.backgroundColor = '#f8f9fa';
            alamatBookingInput.style.cursor = 'text';
            alamatBookingInput.focus();
            // Kosongkan input agar user bisa ketik alamat baru
            alamatBookingInput.value = "";
        }
    });

    // Listener saat pengguna fokus pada kolom input alamat
    alamatBookingInput.addEventListener("focus", function () {
        // Hanya jalankan jika pilihan saat ini adalah "Alamat Sesuai Profil"
        if (radioDefault.checked) {
            // Secara otomatis "klik" radio button "Masukkan alamat lain"
            radioManual.checked = true;
            radioManual.dispatchEvent(new Event('change'));
        }
    });

    // Set kondisi awal
    if (radioDefault.checked) {
        alamatBookingInput.readOnly = true;
        alamatBookingInput.style.backgroundColor = '#e9ecef';
        alamatBookingInput.style.cursor = 'not-allowed';
    }
}

//fungsi jam
// REPLACE fungsi initHours() dengan yang sudah disederhanakan:
function initHours() {
    const slider = document.getElementById('jamBookingSlider');
    const manualInput = document.getElementById('jamBookingManual');
    const label = document.getElementById('jamBookingLabel');

    if (!slider || !manualInput || !label) return;

    // SIMPLIFIED: Fungsi konversi yang lebih sederhana dan akurat
    function decimalToTime(decimal) {
        const totalMinutes = Math.round(decimal * 60);
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;
        
        return String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0');
    }

    function timeToDecimal(timeString) {
        if (!timeString || !timeString.includes(':')) return 8.0;
        
        const [hours, minutes] = timeString.split(':').map(Number);
        if (isNaN(hours) || isNaN(minutes)) return 8.0;
        
        return hours + (minutes / 60);
    }

    function updateFromSlider() {
        const value = parseFloat(slider.value);
        const timeString = decimalToTime(value);
        
        manualInput.value = timeString;
        label.textContent = `Jam dipilih: ${timeString}`;
        
        // Trigger validation events
        manualInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function updateFromManual() {
        const timeValue = manualInput.value;
        if (timeValue && timeValue.includes(':')) {
            const decimal = timeToDecimal(timeValue);
            
            // Clamp to slider range
            const clampedDecimal = Math.max(8, Math.min(18.5, decimal));
            
            slider.value = clampedDecimal;
            label.textContent = `Jam dipilih: ${timeValue}`;
        }
    }

    // Event listeners
    slider.addEventListener('input', updateFromSlider);
    slider.addEventListener('change', updateFromSlider);
    
    manualInput.addEventListener('change', updateFromManual);
    manualInput.addEventListener('blur', updateFromManual);

    // Initial setup
    const initialTime = manualInput.value || '08:00';
    manualInput.value = initialTime;
    slider.value = timeToDecimal(initialTime);
    label.textContent = `Jam dipilih: ${initialTime}`;
    
    console.log('Hours initialized:', { 
        initialTime, 
        sliderValue: slider.value,
        inputValue: manualInput.value 
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

// Update fungsi initFullCalendarAdmin untuk mobile event yang lebih compact:

// Update initFullCalendarAdmin untuk zoom mobile yang lebih baik:



function initTotalHargaBooking() {
    const hargaTotalContainer = document.getElementById("hargaTotal");
    
    // Pastikan element ada
    if (!hargaTotalContainer) return;
    
    let totalHarga = 0;

    // Fungsi untuk menghitung total harga
    const updateTotalHarga = () => {
        totalHarga = 0;
        
        // Cari semua kucing yang dicentang
        const checkedKucing = document.querySelectorAll('.kucing-checkbox:checked');
        
        checkedKucing.forEach(checkbox => {
            const kucingId = checkbox.value;
            const layananSelect = document.querySelector(`#layanan_container_${kucingId} select`);
            
            if (layananSelect && layananSelect.value) {
                const selectedOption = layananSelect.options[layananSelect.selectedIndex];
                const harga = parseInt(selectedOption.dataset.harga || 0);
                totalHarga += harga;
            }
        });
        
        hargaTotalContainer.textContent = `Rp ${totalHarga.toLocaleString("id-ID")}`;
    };

    // Event listener untuk checkbox kucing
    document.querySelectorAll('.kucing-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateTotalHarga();
        });
    });

    // Event listener untuk dropdown layanan
    document.querySelectorAll('.service-select').forEach(select => {
        select.addEventListener('change', function() {
            updateTotalHarga();
        });
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

    const now = new Date();
    const selectedDate = document.querySelector('input[name="tanggalBooking"]')?.value;
    
    if (!selectedDate) return;
    
    const isToday = selectedDate === now.toISOString().split('T')[0];
    
    function validateTime() {
        clearTimeError(); // Clear error first
        
        const selectedTime = jamBookingManual.value;
        
        // BASIC: Check if time is filled
        if (!selectedTime) {
            showTimeError('Silakan pilih jam booking');
            return false;
        }

        // BASIC: Check format
        if (!/^\d{1,2}:\d{2}$/.test(selectedTime)) {
            showTimeError('Format jam tidak valid');
            return false;
        }
        
        const [hours, minutes] = selectedTime.split(':').map(Number);
        
        // BASIC: Check valid numbers
        if (isNaN(hours) || isNaN(minutes)) {
            showTimeError('Jam tidak valid');
            return false;
        }
        
        // BASIC: Check operational hours (08:00 - 18:30)
        const timeInMinutes = hours * 60 + minutes;
        const openTime = 8 * 60; // 08:00
        const closeTime = 18 * 60 + 30; // 18:30
        
        if (timeInMinutes < openTime || timeInMinutes > closeTime) {
            showTimeError('Jam operasional: 08:00 - 18:30');
            return false;
        }
        
        // CONDITIONAL: Check for today only
        if (isToday) {
            const selectedDateTime = new Date();
            selectedDateTime.setHours(hours, minutes, 0, 0);
            
            const minimumTime = new Date(now.getTime() + (2 * 60 * 60 * 1000));
            
            if (selectedDateTime < minimumTime) {
                const minTimeStr = minimumTime.toTimeString().substring(0, 5);
                showTimeError(`Booking minimal 2 jam dari sekarang (minimal: ${minTimeStr})`);
                return false;
            }
        }
        
        // SUCCESS: Enable submit
        enableSubmit();
        return true;
    }
    
    function showTimeError(message) {
        let errorDiv = document.getElementById('time-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'time-error';
            errorDiv.style.cssText = `
                margin-top: 8px;
                padding: 8px 12px;
                background: #fee;
                color: #c33;
                border: 1px solid #fcc;
                border-radius: 5px;
                font-size: 13px;
                display: flex;
                align-items: center;
                gap: 6px;
            `;
            
            const timeContainer = jamBookingManual.closest('.time-input-container');
            if (timeContainer) {
                timeContainer.appendChild(errorDiv);
            }
        }
        
        errorDiv.innerHTML = `⚠ ${message}`;
        errorDiv.style.display = 'flex';
        
        // Disable submit
        disableSubmit();
    }
    
    function clearTimeError() {
        const errorDiv = document.getElementById('time-error');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }
    
    function disableSubmit() {
        const submitBtn = bookingForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
        }
    }
    
    function enableSubmit() {
        const submitBtn = bookingForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    }
    
    // Event listeners dengan debounce
    let validationTimeout;
    const debouncedValidate = () => {
        clearTimeout(validationTimeout);
        validationTimeout = setTimeout(validateTime, 150);
    };
    
    jamBookingManual.addEventListener('change', validateTime);
    jamBookingManual.addEventListener('input', debouncedValidate);
    jamBookingSlider.addEventListener('input', debouncedValidate);
    jamBookingSlider.addEventListener('change', validateTime);
    
    // Initial validation
    setTimeout(validateTime, 100);
    
    console.log('Time validation initialized for date:', selectedDate, 'isToday:', isToday);
}


// slider pada buat booking jika kucing lebih dari 5
// REPLACE FUNGSI initCatVerticalScroll() DENGAN INI:
function initCatVerticalScroll() {
    const scrollContainer = document.getElementById('cats-scroll-container');
    const scrollUpBtn = document.getElementById('scroll-up');
    const scrollDownBtn = document.getElementById('scroll-down');
    const scrollIndicator = document.getElementById('scroll-indicator');
    
    if (!scrollContainer) return;
    
    // PERBAIKAN: Pastikan wrapper dibuat terlebih dahulu
    let itemsWrapper = scrollContainer.querySelector('.cats-items-wrapper');
    if (!itemsWrapper) {
        itemsWrapper = createItemsWrapper();
    }
    
    const catItems = itemsWrapper.querySelectorAll('.cat-item');
    const totalItems = catItems.length;
    
    console.log(`Found ${totalItems} cat items`);
    
    if (totalItems <= 4) {
        // Jika kucing <= 4, tidak perlu scroll controls
        if (scrollUpBtn) scrollUpBtn.style.display = 'none';
        if (scrollDownBtn) scrollDownBtn.style.display = 'none';
        if (scrollIndicator && scrollIndicator.parentElement) {
            scrollIndicator.parentElement.style.display = 'none';
        }
        
        scrollContainer.style.height = 'auto';
        scrollContainer.style.maxHeight = 'none';
        scrollContainer.style.overflow = 'visible';
        return;
    }
    
    let currentScroll = 0;
    let maxScroll = 0;
    let containerHeight = 540; // Default: 4 items visible
    
    // PERBAIKAN: Kalkulasi tinggi yang lebih akurat dengan mempertimbangkan service selection
    function calculateDimensions() {
        const visibleItems = window.innerWidth <= 768 ? 3 : 4;
        
        // CRITICAL: Hitung tinggi total dari semua items secara real-time
        let totalContentHeight = 0;
        const itemHeights = [];
        
        catItems.forEach((item, index) => {
            // Force refresh layout
            item.style.display = 'flex';
            item.style.flexDirection = 'column';
            
            // Ukur tinggi real item termasuk service selection
            const itemRect = item.getBoundingClientRect();
            const itemHeight = item.offsetHeight || 135;
            const marginBottom = parseInt(window.getComputedStyle(item).marginBottom) || 15;
            const totalItemHeight = itemHeight + marginBottom;
            
            itemHeights.push(totalItemHeight);
            totalContentHeight += totalItemHeight;
            
            console.log(`Item ${index + 1}: height=${itemHeight}, margin=${marginBottom}, total=${totalItemHeight}`);
        });
        
        // Tinggi container berdasarkan item yang terlihat
        const averageItemHeight = Math.max(135, totalContentHeight / totalItems);
        containerHeight = visibleItems * averageItemHeight;
        
        // CRITICAL: maxScroll harus berdasarkan tinggi konten AKTUAL dikurangi tinggi container
        maxScroll = Math.max(0, totalContentHeight - containerHeight);
        
        console.log(`Calculated: totalContent=${totalContentHeight}, container=${containerHeight}, maxScroll=${maxScroll}`);
        
        // Set container properties
        scrollContainer.style.height = `${containerHeight}px`;
        scrollContainer.style.overflowY = 'hidden';
        scrollContainer.style.position = 'relative';
        
        // Adjust current scroll if needed
        if (currentScroll > maxScroll) {
            currentScroll = maxScroll;
        }
        
        updateScroll();
    }
    
    // PERBAIKAN: Fungsi untuk mendapatkan tinggi scroll per step yang dinamis
    function getScrollStep() {
        // Scroll berdasarkan rata-rata tinggi item, bukan fixed
        const totalVisibleHeight = containerHeight;
        return Math.max(100, totalVisibleHeight / 4); // Scroll 1/4 dari container height
    }
    
    function updateScroll() {
        if (!itemsWrapper) return;
        
        // CLAMP current scroll to valid bounds
        currentScroll = Math.max(0, Math.min(maxScroll, currentScroll));
        
        itemsWrapper.style.transform = `translateY(-${currentScroll}px)`;
        
        // Update buttons state
        if (scrollUpBtn) {
            scrollUpBtn.disabled = currentScroll <= 0;
            scrollUpBtn.style.opacity = currentScroll <= 0 ? '0.5' : '1';
        }
        if (scrollDownBtn) {
            scrollDownBtn.disabled = currentScroll >= maxScroll;
            scrollDownBtn.style.opacity = currentScroll >= maxScroll ? '0.5' : '1';
        }
        
        // Update indicator
        if (scrollIndicator && maxScroll > 0) {
            const percentage = Math.min(100, (currentScroll / maxScroll) * 100);
            const trackHeight = 60;
            const indicatorHeight = 20;
            const maxMove = trackHeight - indicatorHeight;
            const moveDistance = (percentage / 100) * maxMove;
            scrollIndicator.style.transform = `translateY(${moveDistance}px)`;
        }
        
        console.log(`Scroll updated: current=${currentScroll}, max=${maxScroll}, step=${getScrollStep()}`);
    }
    
    // PERBAIKAN: Scroll functions dengan step dinamis
    function scrollUp() {
        const step = getScrollStep();
        currentScroll = Math.max(0, currentScroll - step);
        updateScroll();
    }
    
    function scrollDown() {
        const step = getScrollStep();
        currentScroll = Math.min(maxScroll, currentScroll + step);
        updateScroll();
    }
    
    // Event listeners
    if (scrollUpBtn) {
        scrollUpBtn.onclick = (e) => {
            e.preventDefault();
            scrollUp();
        };
    }
    
    if (scrollDownBtn) {
        scrollDownBtn.onclick = (e) => {
            e.preventDefault();
            scrollDown();
        };
    }
    
    // Mouse wheel scroll
    scrollContainer.onwheel = (e) => {
        e.preventDefault();
        
        const scrollStep = getScrollStep() / 3; // Smaller steps for wheel
        
        if (e.deltaY > 0) {
            currentScroll = Math.min(maxScroll, currentScroll + scrollStep);
        } else {
            currentScroll = Math.max(0, currentScroll - scrollStep);
        }
        updateScroll();
    };
    
    // Touch support - IMPROVED
    let startY = 0;
    let isScrolling = false;
    let lastTouchY = 0;
    
    scrollContainer.ontouchstart = (e) => {
        startY = e.touches[0].clientY;
        lastTouchY = startY;
        isScrolling = false;
    };
    
    scrollContainer.ontouchmove = (e) => {
        const currentY = e.touches[0].clientY;
        const deltaY = Math.abs(startY - currentY);
        
        if (deltaY > 5) {
            isScrolling = true;
            e.preventDefault();
            
            // Real-time scroll during touch move
            const touchDelta = lastTouchY - currentY;
            lastTouchY = currentY;
            
            currentScroll = Math.max(0, Math.min(maxScroll, currentScroll + touchDelta));
            updateScroll();
        }
    };
    
    scrollContainer.ontouchend = (e) => {
        if (!isScrolling) return;
        // Final adjustment - snap to nearest logical position
        updateScroll();
    };
    
    // Keyboard navigation
    scrollContainer.onkeydown = (e) => {
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            scrollUp();
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            scrollDown();
        }
    };
    
    scrollContainer.setAttribute('tabindex', '0');
    
    // CRITICAL: Observer untuk service selection changes
    function observeItemChanges() {
        const checkboxes = itemsWrapper.querySelectorAll('.kucing-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                // Delay untuk memastikan DOM sudah terupdate
                setTimeout(() => {
                    console.log('Checkbox changed, recalculating...');
                    calculateDimensions();
                }, 350); // Increased delay
            });
        });
        
        // Observer untuk service select
        const selects = itemsWrapper.querySelectorAll('.service-select');
        selects.forEach(select => {
            select.addEventListener('change', () => {
                setTimeout(() => {
                    console.log('Service select changed, recalculating...');
                    calculateDimensions();
                }, 100);
            });
        });
        
        // TAMBAHAN: MutationObserver untuk perubahan style
        const observer = new MutationObserver(() => {
            console.log('DOM mutation detected, recalculating...');
            setTimeout(calculateDimensions, 200);
        });
        
        observer.observe(itemsWrapper, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });
    }
    
    // TAMBAHAN: Function to ensure last item is fully visible
    function ensureLastItemVisible() {
        if (totalItems === 0) return;
        
        setTimeout(() => {
            const lastItem = catItems[totalItems - 1];
            if (!lastItem) return;
            
            const lastItemRect = lastItem.getBoundingClientRect();
            const containerRect = scrollContainer.getBoundingClientRect();
            const lastItemBottom = lastItemRect.bottom;
            const containerBottom = containerRect.bottom;
            
            // If last item is cut off
            if (lastItemBottom > containerBottom) {
                const extraScroll = lastItemBottom - containerBottom + 20; // 20px buffer
                maxScroll += extraScroll;
                console.log(`Added extra scroll for last item: ${extraScroll}px`);
                updateScroll();
            }
        }, 500);
    }
    
    // Buat wrapper
    function createItemsWrapper() {
        console.log('Creating items wrapper...');
        
        let existingWrapper = scrollContainer.querySelector('.cats-items-wrapper');
        if (existingWrapper) {
            return existingWrapper;
        }
        
        const wrapper = document.createElement('div');
        wrapper.className = 'cats-items-wrapper';
        wrapper.style.transition = 'transform 0.3s ease';
        wrapper.style.width = '100%';
        wrapper.style.position = 'relative';
        
        // Move all cat items to wrapper
        const allCatItems = scrollContainer.querySelectorAll('.cat-item');
        allCatItems.forEach(item => {
            wrapper.appendChild(item);
        });
        
        scrollContainer.appendChild(wrapper);
        return wrapper;
    }
    
    // IMPROVED: Responsive resize handler
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            console.log('Window resized, recalculating dimensions...');
            calculateDimensions();
            ensureLastItemVisible();
        }, 200);
    });
    
    // IMPROVED: Auto-scroll to selected cat
    function scrollToSelectedCat() {
        const selectedCat = itemsWrapper.querySelector('.cat-item input:checked');
        if (selectedCat) {
            const catItem = selectedCat.closest('.cat-item');
            const itemRect = catItem.getBoundingClientRect();
            const containerRect = scrollContainer.getBoundingClientRect();
            
            // Check if item is fully visible
            const itemTop = itemRect.top;
            const itemBottom = itemRect.bottom;
            const containerTop = containerRect.top;
            const containerBottom = containerRect.bottom;
            
            if (itemBottom > containerBottom || itemTop < containerTop) {
                // Calculate scroll needed to center the item
                const itemOffsetTop = catItem.offsetTop;
                const centerScroll = itemOffsetTop - (containerHeight / 2) + (itemRect.height / 2);
                currentScroll = Math.max(0, Math.min(maxScroll, centerScroll));
                updateScroll();
            }
        }
    }
    
    // Listen selection changes with improved timing
    catItems.forEach(item => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        if (checkbox) {
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    setTimeout(() => {
                        calculateDimensions();
                        setTimeout(scrollToSelectedCat, 100);
                    }, 400);
                }
            });
        }
    });
    
    // Initial setup dengan sequence yang tepat
    setTimeout(() => {
        calculateDimensions();
        observeItemChanges();
        setTimeout(() => {
            ensureLastItemVisible();
        }, 200);
    }, 100);
    
    // TAMBAHAN: Periodic recalculation untuk memastikan accuracy
    setInterval(() => {
        if (scrollContainer.offsetParent !== null) { // Only if visible
            calculateDimensions();
        }
    }, 2000);
}