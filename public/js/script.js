//F ungsi untuk menampilkan atau menyembunyikan sidebar
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
