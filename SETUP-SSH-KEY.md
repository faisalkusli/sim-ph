# Setup SSH Key untuk VPS Automation

## Langkah 1: Copy Public Key ke VPS

Jalankan command berikut di PowerShell:

```powershell
Get-Content "$env:USERPROFILE\.ssh\id_rsa.pub"
```

Copy semua output (text yang dimulai dengan `ssh-rsa...`)

## Langkah 2: Login ke VPS

```bash
ssh root@simph.cloud
```

Masukkan password: `Slikusli1#`

## Langkah 3: Tambahkan Public Key ke VPS

Setelah login, jalankan:

```bash
# Buat folder .ssh jika belum ada
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Edit file authorized_keys
nano ~/.ssh/authorized_keys
```

Paste public key yang sudah di-copy tadi ke dalam file, lalu save (Ctrl+X, Y, Enter).

Kemudian set permission:

```bash
chmod 600 ~/.ssh/authorized_keys
```

## Langkah 4: Test Koneksi

Keluar dari VPS (ketik `exit`), lalu test koneksi tanpa password:

```powershell
ssh root@simph.cloud "echo 'SSH key berhasil!'"
```

Jika berhasil, Anda tidak akan diminta password lagi.

## Langkah 5: Jalankan Deploy Script

Setelah SSH key setup selesai, deploy bisa dilakukan dengan:

### Windows:
```cmd
.\deploy.bat
```

### PowerShell:
```powershell
.\deploy.ps1
```

---

## Troubleshooting

### Jika masih diminta password:

1. Pastikan permission file benar:
   ```bash
   chmod 700 ~/.ssh
   chmod 600 ~/.ssh/authorized_keys
   ```

2. Cek konfigurasi SSH server di VPS:
   ```bash
   sudo nano /etc/ssh/sshd_config
   ```
   
   Pastikan baris berikut tidak di-comment dan set ke yes:
   ```
   PubkeyAuthentication yes
   ```
   
   Kemudian restart SSH service:
   ```bash
   sudo systemctl restart ssh
   ```

3. Cek log SSH untuk error:
   ```bash
   tail -f /var/log/auth.log
   ```
