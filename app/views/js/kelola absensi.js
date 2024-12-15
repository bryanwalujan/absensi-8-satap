document.addEventListener('DOMContentLoaded', function() {
    // Handle the "Tampilkan Daftar Siswa" button click
    document.querySelector('.load-button').addEventListener('click', function() {
        const selectedClass = document.getElementById('kelas').value;
        alert('Menampilkan daftar siswa untuk ' + selectedClass);
        // Optionally, you could add logic here to load the student list dynamically if needed
    });

    // Handle the "Simpan Kehadiran" button click
    document.querySelector('.submit-button').addEventListener('click', function() {
        const attendanceRows = document.querySelectorAll('tbody tr');
        const attendanceData = [];

        attendanceRows.forEach((row, index) => {
            const studentName = row.children[1].textContent;
            const status = row.querySelector('select').value;

            attendanceData.push({
                no: index + 1,
                name: studentName,
                status: status
            });
        });

        console.log('Kehadiran disimpan:', attendanceData);
        alert('Kehadiran berhasil disimpan!');

        // Here, you can add logic to send attendanceData to the server (e.g., via fetch or AJAX)
    });
});

function tampilkanDaftarSiswa() {
    let kelas = document.getElementById("kelas").value;
    let mataPelajaran = document.getElementById("mataPelajaran").value;
    
    // Asumsikan Anda memiliki fungsi atau API untuk mengambil data siswa berdasarkan kelas dan mata pelajaran
    let siswaList = getSiswaByClassAndSubject(kelas, mataPelajaran);
    
    let tabelSiswa = document.getElementById("tabelSiswa");
    tabelSiswa.innerHTML = "";  // Hapus isi tabel

    siswaList.forEach((siswa, index) => {
        let row = document.createElement("tr");

        let cellNo = document.createElement("td");
        cellNo.textContent = index + 1;
        row.appendChild(cellNo);

        let cellNama = document.createElement("td");
        cellNama.textContent = siswa.nama;
        row.appendChild(cellNama);

        let cellKehadiran = document.createElement("td");
        let selectStatus = document.createElement("select");
        selectStatus.innerHTML = `
            <option value="hadir">Hadir</option>
            <option value="tidak hadir">Tidak Hadir</option>
            <option value="izin">Izin</option>
            <option value="sakit">Sakit</option>
        `;
        cellKehadiran.appendChild(selectStatus);
        row.appendChild(cellKehadiran);

        tabelSiswa.appendChild(row);
    });
}

function simpanKehadiran() {
    // Mendapatkan data kehadiran dan menyimpannya, bisa ke database atau server backend
    alert("Kehadiran berhasil disimpan!");
}

// Contoh fungsi untuk mengambil data siswa (bisa diganti dengan API)
function getSiswaByClassAndSubject(kelas, mataPelajaran) {
    // Ini adalah data contoh. Gantilah dengan sumber data nyata atau API
    return [
        { nama: "Zefanya" },
        { nama: "Della" },
        // Tambahkan nama siswa lain sesuai kebutuhan
    ];
}