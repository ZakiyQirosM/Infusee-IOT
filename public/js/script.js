//F ungsi untuk menampilkan atau menyembunyikan sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleButton = document.querySelector('.toggle-btn-open');

    sidebar.classList.toggle('show');
    content.classList.toggle('shift');

    // Sembunyikan tombol ketika sidebar dibukav
    if (sidebar.classList.contains('show')) {
        toggleButton.classList.add('hide');
    } else {
        toggleButton.classList.remove('hide');
    }
}

document.getElementById('btn-search').addEventListener('click', async () => {
    const noRegister = document.getElementById('no_reg_pasien').value.trim();

    if (noRegister) {
        try {
            const response = await fetch(`/register/search?no_reg_pasien=${noRegister}`);
            if (!response.ok) throw new Error('Data tidak ditemukan.');

            const data = await response.json();

            // ✅ Isi otomatis dari database
            document.getElementById('nama_pasien').value = data.nama_pasien;
            document.getElementById('umur').value = data.umur;
            document.getElementById('no_ruangan').value = data.no_ruangan;

        } catch (error) {
            alert(error.message);
        }
    }
});


document.getElementById('register-form').addEventListener('submit', (e) => {
    e.preventDefault();

    fetch("{{ route('register.store') }}", {
        method: 'POST',
        body: new FormData(e.target),
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.ok) {
            // ✅ Gunakan route Laravel sebagai path URL
            window.location.href = "{{ route('devices.index') }}";
        } else {
            alert('Gagal menyimpan data');
        }
    })
    .catch(error => console.error('Error:', error));
});



