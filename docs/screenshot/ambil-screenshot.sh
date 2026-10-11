#!/bin/bash
# Skrip pengambilan tangkapan layar seluruh halaman aplikasi DFC Cirebon.
# Memakai Chrome headless agar hasilnya nyata dan dapat dipakai sebagai
# bukti visual pada laporan.
#
# Jalankan dari direktori proyek: bash docs/screenshot/ambil-screenshot.sh

CHROME="/c/Program Files/Google/Chrome/Application/chrome.exe"
BASE="http://127.0.0.1:8000"
TUJUAN="C:\\Users\\riana\\AppData\\Local\\Temp\\shots"
TMPDIR="/c/Users/riana/AppData/Local/Temp/shots"
LEBAR=1440
TINGGI=1000

mkdir -p "$TMPDIR"

# Daftar: nama-berkas|alamat
DAFTAR=(
  "01-beranda|/"
  "02-menu|/menu"
  "03-lokasi|/lokasi"
  "04-profil|/profil"
  "05-kemitraan|/kemitraan"
  "06-pesanan|/pesanan"
  "07-tracking-riwayat|/tracking/riwayat"
  "08-login-gudang|/gudang/login"
  "09-login-outlet|/outlet/login"
  "10-login-mitra|/mitra/login"
  "11-login-owner|/owner/login"
)

for ITEM in "${DAFTAR[@]}"; do
  NAMA="${ITEM%%|*}"
  ALAMAT="${ITEM##*|}"
  "$CHROME" --headless=new --disable-gpu --no-sandbox --hide-scrollbars \
    --window-size=${LEBAR},${TINGGI} \
    --screenshot="${TUJUAN}\\${NAMA}.png" \
    --virtual-time-budget=9000 \
    "${BASE}${ALAMAT}" > /dev/null 2>&1
  if [ -f "${TMPDIR}/${NAMA}.png" ]; then
    UKURAN=$(stat -c %s "${TMPDIR}/${NAMA}.png" 2>/dev/null)
    echo "OK    ${NAMA}.png (${UKURAN} bita)"
  else
    echo "GAGAL ${NAMA}.png"
  fi
done

echo ""
echo "Selesai. Berkas tersimpan di ${TMPDIR}"
