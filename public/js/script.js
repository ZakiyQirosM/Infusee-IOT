// Fungsi untuk menampilkan atau menyembunyikan sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleButton = document.querySelector('.toggle-btn-open');

    sidebar.classList.toggle('show');
    content.classList.toggle('shift');

    // Sembunyikan tombol ketika sidebar dibuka
    if (sidebar.classList.contains('show')) {
        toggleButton.classList.add('hide');
    } else {
        toggleButton.classList.remove('hide');
    }
}

// ✅ Fungsi untuk mencari pasien berdasarkan no_register
document.getElementById('btn-search').addEventListener('click', async () => {
    const noRegister = document.getElementById('no_reg_pasien').value.trim();

    if (noRegister) {
        try {
            const response = await fetch(`/register/search?no_reg_pasien=${noRegister}`);
            if (!response.ok) throw new Error('Data tidak ditemukan.');

            const data = await response.json();

            document.getElementById('nama_pasien').value = data.nama_pasien;
            document.getElementById('umur').value = data.umur;
            document.getElementById('no_ruangan').value = data.no_ruangan;

        } catch (error) {
            alert(error.message);
        }
    }
});

function selectDevice(deviceId) {
    console.log(`Device ID yang dipilih: ${deviceId}`);

    if (confirm(`Pilih device dengan ID: ${deviceId}?`)) {
        fetch("{{ route('devices.assign') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                device_id: deviceId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Terjadi kesalahan saat memilih device.');
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            // ✅ Redirect ke infusion_sessions setelah sukses
            window.location.href = "{{ route('infusee.index') }}";
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Gagal memilih device: ${error.message}`);
        });
    }
}