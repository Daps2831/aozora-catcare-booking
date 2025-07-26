// file: resources/js/app.js
//alert('JS loaded!');
import "./bootstrap";

// Import library kalender
import { Calendar } from "vanilla-calendar-pro";

// IMPORT DUA FILE CSS INI

import "vanilla-calendar-pro/styles/layout.css";

/**
 * ===================================================================
 * JALANKAN SEMUA FUNGSI SAAT HALAMAN SIAP
 * ===================================================================
 */
document.addEventListener("DOMContentLoaded", () => {
    initSideMenu();
    initImagePreview();
    initBookingCalendar();
    initAddress();
    initHours();
    initKucingchecked();
    validateBookingForm();
    initSelectAll();
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
    if (menuBtn && sideMenu) {
        menuBtn.addEventListener("click", function () {
            menuBtn.classList.toggle("open");
            sideMenu.classList.toggle("open");
        });
    }
    // Untuk tombol close (X)
    const closeBtn = document.getElementById("close-btn");
    if (closeBtn && sideMenu && menuBtn) {
        closeBtn.addEventListener("click", function () {
            sideMenu.classList.remove("open");
            menuBtn.classList.remove("open");
        });
    }
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

// Fungsi untuk menampilkan kalender booking
function initBookingCalendar() {
    const calendarEl = document.getElementById("my-calendar");
    const timeInfo = document.getElementById("calendar-time-info");
    const btnKonfirmasi = document.getElementById("btn-konfirmasi-booking");
    let selectedDate = null;

    if (calendarEl && timeInfo && btnKonfirmasi) {
        const calendar = new Calendar("#my-calendar", {
            lang: "en",
            iso8601: true,
            selectionDatesMode: "single",
            selected: { dates: [] },
            controls: true,
            disableDatesPast: true,
            months: {
                long: [
                    "Januari",
                    "Februari",
                    "Maret",
                    "April",
                    "Mei",
                    "Juni",
                    "Juli",
                    "Agustus",
                    "September",
                    "Oktober",
                    "November",
                    "Desember",
                ],
                short: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "Mei",
                    "Jun",
                    "Jul",
                    "Agu",
                    "Sep",
                    "Okt",
                    "Nov",
                    "Des",
                ],
            },
            weekdays: {
                long: [
                    "Minggu",
                    "Senin",
                    "Selasa",
                    "Rabu",
                    "Kamis",
                    "Jumat",
                    "Sabtu",
                ],
                short: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
            },
            onClickDate(self) {
                if (self.context.selectedDates.length > 0) {
                    selectedDate = self.context.selectedDates[0];
                    timeInfo.textContent = `Tanggal dipilih: ${selectedDate}`;
                    btnKonfirmasi.disabled = false;
                } else {
                    selectedDate = null;
                    timeInfo.textContent = "";
                    btnKonfirmasi.disabled = true;
                }
            },
            onClickDayDisabled(self) {
                alert("Tanggal penuh, silakan pilih tanggal lain.");
            },
        });

        // Fetch data, set disable, TANPA init ulang!
        const fetchAndRenderBookings = (month, year, calendar) => {
            fetch(`/api/monthly-bookings?month=${month}&year=${year}`)
                .then((response) => response.json())
                .then((data) => {
                    const datesToDisable = [];
                    for (const date in data) {
                        const count = data[date];
                        if (count >= 10) datesToDisable.push(date);
                    }
                    // GUNAKAN disableDates di root, BUKAN settings.range.disabled!
                    calendar.set({
                        disableDates: datesToDisable,
                    });
                });
        };

        const today = new Date();
        fetchAndRenderBookings(
            today.getMonth() + 1,
            today.getFullYear(),
            calendar
        );

        calendar.init();

        btnKonfirmasi.addEventListener("click", function () {
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