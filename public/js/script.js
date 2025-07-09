// Ambil elemen tombol dan menu samping
const menuBtn = document.getElementById('menu-btn');
const sideMenu = document.getElementById('side-menu');
const closeBtn = document.getElementById('close-btn');

// Menambahkan event listener ke tombol garis tiga untuk membuka menu samping
menuBtn.addEventListener('click', () => {
    sideMenu.classList.toggle('open');  // Membuka dan menutup menu samping
    menuBtn.classList.toggle('open');  // Mengubah animasi tombol garis tiga
});

// Menambahkan event listener ke tombol X untuk menutup menu samping
closeBtn.addEventListener('click', () => {
    sideMenu.classList.remove('open');  // Menutup menu samping
    menuBtn.classList.remove('open');  // Mengubah animasi tombol garis tiga
});
