document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("search-form");
    const noRegInput = document.getElementById("no_reg_pasien");
    const namaPasienInput = document.getElementById("nama_pasien");
    const umurInput = document.getElementById("umur");
    const noRuanganInput = document.getElementById("no_ruangan");

    searchForm.addEventListener("submit", async function (e) {
        e.preventDefault(); // 🚀 Mencegah reload halaman

        let noReg = noRegInput.value.trim();

        if (!noReg) {
            alert("Masukkan Nomor Registrasi terlebih dahulu!");
            return;
        }

        try {
            // 🔍 Kirim request ke server
            let response = await fetch(`/register/search?no_reg_pasien=${encodeURIComponent(noReg)}`);

            if (!response.ok) {
                throw new Error("Gagal mengambil data pasien!");
            }

            let data = await response.json();

            // 🏥 Jika pasien ditemukan, isi form
            if (data.nama_pasien) {
                namaPasienInput.value = data.nama_pasien;
                umurInput.value = data.umur;
                noRuanganInput.value = data.no_ruangan;
            } else {
                alert("Data pasien tidak ditemukan!");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Terjadi kesalahan, coba lagi nanti.");
        }
    });
});

// Fungsi untuk menampilkan atau menyembunyikan sidebar
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");
    const toggleButton = document.querySelector(".toggle-btn-open");

    sidebar.classList.toggle("show");
    content.classList.toggle("shift");

    // Sembunyikan tombol ketika sidebar dibuka
    toggleButton.classList.toggle("hide", sidebar.classList.contains("show"));
}
