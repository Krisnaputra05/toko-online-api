const API = {
  kategori: "http://localhost/toko-online-api/kategori",
  produk: "http://localhost/toko-online-api/produk",
  keranjang: "http://localhost/toko-online-api/keranjang",
  transaksi: "http://localhost/toko-online-api/transaksi",
};

// --- KATEGORI ---
async function loadKategori() {
  const res = await fetch(`${API.kategori}/read.php`);
  const data = await res.json();
  const tbody = document.querySelector("#kategoriTable tbody");
  const select = document.getElementById("produkKategori");
  tbody.innerHTML = "";
  select.innerHTML = "";
  data.forEach((kat) => {
    tbody.innerHTML += `
        <tr>
          <td>${kat.id}</td>
          <td>${kat.nama}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary" onclick="editKategori(${kat.id}, '${kat.nama}')">Edit</button>
            <button class="btn btn-sm btn-outline-danger" onclick="hapusKategori(${kat.id})">Hapus</button>
          </td>
        </tr>`;
    select.innerHTML += `<option value="${kat.id}">${kat.nama}</option>`;
  });
}

async function tambahKategori() {
  const nama = document.getElementById("kategoriNama").value;
  if (!nama) return alert("Nama kosong");
  await fetch(`${API.kategori}/create.php`, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `nama=${encodeURIComponent(nama)}`,
  });
  document.getElementById("kategoriNama").value = "";
  loadKategori();
}

function editKategori(id, lama) {
  const baru = prompt("Ubah Nama:", lama);
  if (baru && baru !== lama) {
    fetch(`${API.kategori}/update.php`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, nama: baru }),
    }).then(loadKategori);
  }
}

function hapusKategori(id) {
  if (confirm("Hapus kategori ini?")) {
    fetch(`${API.kategori}/delete.php`, {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    }).then(loadKategori);
  }
}

// --- PRODUK ---
async function loadProduk() {
  const res = await fetch(`${API.produk}/read.php`);
  const data = await res.json();
  const tbody = document.querySelector("#produkTable tbody");
  const select = document.getElementById("keranjangProduk");
  tbody.innerHTML = "";
  select.innerHTML = "";
  data.forEach((p) => {
    tbody.innerHTML += `
        <tr>
          <td>${p.id}</td>
          <td>${p.nama}</td>
          <td>${p.harga}</td>
          <td>${p.stok}</td>
          <td>${p.kategori}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary" onclick="editProduk(${p.id})">Edit</button>
            <button class="btn btn-sm btn-outline-danger" onclick="hapusProduk(${p.id})">Hapus</button>
          </td>
        </tr>`;
    select.innerHTML += `<option value="${p.id}" data-harga="${p.harga}">${p.nama}</option>`;
  });
}

async function tambahProduk() {
  const nama = document.getElementById("produkNama").value;
  const harga = document.getElementById("produkHarga").value;
  const stok = document.getElementById("produkStok").value;
  const kategori = document.getElementById("produkKategori").value;

  if (!nama || !harga || !stok) return alert("Isi semua data produk");

  await fetch(`${API.produk}/create.php`, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `nama=${encodeURIComponent(
      nama
    )}&harga=${harga}&stok=${stok}&kategori_id=${kategori}`,
  });
  document.getElementById("produkNama").value = "";
  document.getElementById("produkHarga").value = "";
  document.getElementById("produkStok").value = "";
  loadProduk();
}

function editProduk(id) {
  alert("Fitur edit produk bisa kamu tambah nanti sesuai kebutuhan.");
}

function hapusProduk(id) {
  if (confirm("Hapus produk ini?")) {
    fetch(`${API.produk}/delete.php`, {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    }).then(loadProduk);
  }
}

// --- KERANJANG ---
let keranjang = [];

function tambahKeranjang() {
  const select = document.getElementById("keranjangProduk");
  const jumlah = parseInt(document.getElementById("keranjangJumlah").value);
  const id = select.value;
  const nama = select.options[select.selectedIndex].text;
  const harga = parseFloat(select.options[select.selectedIndex].dataset.harga);

  if (!jumlah || jumlah <= 0) return alert("Jumlah harus lebih dari 0");

  // Cek apakah produk sudah ada di keranjang
  const existingItem = keranjang.find((item) => item.id === id);
  if (existingItem) {
    existingItem.jumlah += jumlah;
    existingItem.totalHarga = existingItem.harga * existingItem.jumlah;
  } else {
    keranjang.push({
      id,
      nama,
      jumlah,
      harga,
      totalHarga: harga * jumlah, // Hitung harga total per produk
    });
  }

  renderKeranjang();
}

function renderKeranjang() {
  const tbody = document.querySelector("#keranjangTable tbody");
  tbody.innerHTML = "";
  let totalKeranjang = 0; // Inisialisasi total harga

  keranjang.forEach((item, i) => {
    tbody.innerHTML += `
        <tr>
          <td>${item.nama}</td>
          <td>${item.jumlah}</td>
          <td>Rp ${item.harga}</td>
          <td>Rp ${item.totalHarga}</td>
          <td><button class="btn btn-sm btn-outline-danger" onclick="hapusItemKeranjang(${i})">Hapus</button></td>
        </tr>`;
    totalKeranjang += item.totalHarga; // Tambahkan total harga setiap item ke total keranjang
  });

  // Update total harga di UI
  document.getElementById(
    "totalHarga"
  ).innerText = `Total: Rp ${totalKeranjang}`;
}

function hapusItemKeranjang(i) {
  keranjang.splice(i, 1);
  renderKeranjang();
}

function checkout() {
  if (!keranjang.length) return alert("Keranjang kosong");

  fetch(`${API.transaksi}/create.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ items: keranjang }),
  }).then(() => {
    alert("Transaksi berhasil!");
    keranjang = [];
    renderKeranjang();
    loadProduk(); // update stok
  });
}

// --- INIT ---
loadKategori();
loadProduk();
