/**
 * Skrip bantu dokumentasi (bukan bagian aplikasi).
 *
 * Mengambil tangkapan layar halaman panel yang butuh login dengan menjalankan
 * alur login sungguhan lewat Chrome DevTools Protocol:
 *   1. Buka halaman login tiap panel.
 *   2. Isi kolom username dan password, lalu kirim formulir.
 *   3. Tunggu pengalihan selesai, lalu simpan tangkapan layar panel.
 *
 * Hanya dipakai untuk keperluan dokumentasi.
 */

const { spawn } = require('node:child_process');
const { mkdtempSync, writeFileSync, rmSync, mkdirSync } = require('node:fs');
const { tmpdir } = require('node:os');
const { join, resolve } = require('node:path');

const CHROME = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const PORT_DEBUG = 9333;
const BASIS = 'http://127.0.0.1:8000';
const KELUARAN = resolve(__dirname);

const PANEL = [
	{
		berkas: '12-panel-gudang',
		login: '/gudang/login',
		tujuan: '/gudang',
		username: 'Gudang',
		password: 'gudang123',
	},
	{
		berkas: '13-panel-outlet',
		login: '/outlet/login',
		tujuan: '/outlet',
		username: 'sutawinangun',
		password: 'outlet123',
	},
	{
		berkas: '14-panel-mitra',
		login: '/mitra/login',
		tujuan: '/mitra',
		username: 'kharis',
		password: 'kharis123',
	},
	{
		berkas: '15-panel-owner',
		login: '/owner/login',
		tujuan: '/owner/dashboard',
		username: 'bigboss',
		password: 'dfcjayaterus',
		kolomUser: 'user',
		kolomPass: 'pass',
	},
];

const jeda = (ms) => new Promise((selesai) => setTimeout(selesai, ms));

async function ambilEndpoint(percobaan = 40) {
	for (let i = 0; i < percobaan; i++) {
		try {
			const respons = await fetch(`http://127.0.0.1:${PORT_DEBUG}/json/version`);
			const data = await respons.json();
			if (data.webSocketDebuggerUrl) return data.webSocketDebuggerUrl;
		} catch (_) {
			// Chrome belum siap.
		}
		await jeda(300);
	}
	throw new Error('Chrome tidak merespons pada port debug.');
}

class KlienCdp {
	constructor(url) {
		this.url = url;
		this.id = 0;
		this.tunggu = new Map();
	}

	async sambung() {
		this.ws = new WebSocket(this.url);
		await new Promise((selesai, gagal) => {
			this.ws.addEventListener('open', selesai, { once: true });
			this.ws.addEventListener('error', gagal, { once: true });
		});
		this.ws.addEventListener('message', (peristiwa) => {
			const pesan = JSON.parse(peristiwa.data);
			if (pesan.id && this.tunggu.has(pesan.id)) {
				const { selesai, gagal } = this.tunggu.get(pesan.id);
				this.tunggu.delete(pesan.id);
				if (pesan.error) gagal(new Error(pesan.error.message));
				else selesai(pesan.result);
			}
		});
	}

	kirim(method, params = {}, sesiId) {
		const id = ++this.id;
		const pesan = { id, method, params };
		if (sesiId) pesan.sessionId = sesiId;
		return new Promise((selesai, gagal) => {
			this.tunggu.set(id, { selesai, gagal });
			this.ws.send(JSON.stringify(pesan));
		});
	}

	tutup() {
		try {
			this.ws.close();
		} catch (_) {
			// Abaikan.
		}
	}
}

/** Mengevaluasi ekspresi JavaScript di halaman. */
async function evaluasi(klien, sesiId, ekspresi) {
	const { result } = await klien.kirim('Runtime.evaluate', {
		expression: ekspresi,
		returnByValue: true,
		awaitPromise: true,
	}, sesiId);
	return result.value;
}

async function utama() {
	mkdirSync(KELUARAN, { recursive: true });
	const profil = mkdtempSync(join(tmpdir(), 'chrome-panel2-'));

	const chrome = spawn(CHROME, [
		'--headless=new',
		'--disable-gpu',
		'--no-sandbox',
		'--hide-scrollbars',
		'--force-device-scale-factor=1',
		'--window-size=1440,1000',
		`--remote-debugging-port=${PORT_DEBUG}`,
		`--user-data-dir=${profil}`,
		'--no-first-run',
		'--no-default-browser-check',
		'about:blank',
	], { stdio: 'ignore' });

	try {
		const endpoint = await ambilEndpoint();
		const klien = new KlienCdp(endpoint);
		await klien.sambung();

		const { targetId } = await klien.kirim('Target.createTarget', { url: 'about:blank' });
		const { sessionId } = await klien.kirim('Target.attachToTarget', { targetId, flatten: true });

		await klien.kirim('Page.enable', {}, sessionId);
		await klien.kirim('Network.enable', {}, sessionId);
		await klien.kirim('Runtime.enable', {}, sessionId);

		let jumlah = 0;

		for (const panel of PANEL) {
			// 1. Buka halaman login.
			await klien.kirim('Page.navigate', { url: BASIS + panel.login }, sessionId);
			await jeda(2500);

			// 2. Isi formulir dan kirim.
			const kolomUser = panel.kolomUser || 'username';
			const kolomPass = panel.kolomPass || 'password';
			const skrip = `
				(function(){
					const form = document.querySelector('form');
					if (!form) return 'form-tidak-ada';
					const isi = (nama, nilai) => {
						const el = form.querySelector('[name="'+nama+'"]') || document.querySelector('[name="'+nama+'"]');
						if (!el) return false;
						el.value = nilai;
						el.dispatchEvent(new Event('input', {bubbles:true}));
						el.dispatchEvent(new Event('change', {bubbles:true}));
						return true;
					};
					const a = isi(${JSON.stringify(kolomUser)}, ${JSON.stringify(panel.username)});
					const b = isi(${JSON.stringify(kolomPass)}, ${JSON.stringify(panel.password)});
					form.submit();
					return JSON.stringify({user:a, pass:b});
				})()
			`;
			const hasilIsi = await evaluasi(klien, sessionId, skrip);
			console.log(`${panel.berkas}: isi formulir ${hasilIsi}`);

			// 3. Tunggu pengalihan selesai.
			await jeda(4000);

			const urlSekarang = await evaluasi(klien, sessionId, 'location.pathname');

			// Pastikan preloader tersembunyi.
			await evaluasi(klien, sessionId,
				'document.documentElement.style.overflow="auto";document.body&&(document.body.style.overflow="auto",document.body.style.opacity="1");const l=document.querySelector(".preloader");l&&l.remove();'
			);
			await jeda(700);

			// 4. Simpan tangkapan layar.
			const { data } = await klien.kirim('Page.captureScreenshot', {
				format: 'png',
				captureBeyondViewport: true,
			}, sessionId);

			writeFileSync(join(KELUARAN, panel.berkas + '.png'), Buffer.from(data, 'base64'));
			const ukuran = Buffer.from(data, 'base64').length;
			console.log(`${panel.berkas}.png  <- ${urlSekarang} (${ukuran} bita)`);
			jumlah++;
		}

		klien.tutup();
		console.log('\nSelesai. ' + jumlah + ' tangkapan layar tersimpan.');
	} finally {
		chrome.kill();
		await jeda(500);
		try {
			rmSync(profil, { recursive: true, force: true });
		} catch (_) {
			// Profil sementara mungkin terkunci.
		}
	}
}

utama().catch((galat) => {
	console.error('Gagal: ' + galat.message);
	process.exit(1);
});
