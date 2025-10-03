 untuk setup php dan database
1. pastiin udah setup sqlitenya
di php.ini harus gini

extension=curl
extension=fileinfo
extension=gd
extension=mbstring
extension=openssl
extension=pdo_mysql  // Baik untuk diaktifkan jika nanti butuh MySQL
extension=pdo_sqlite // Ini yang paling penting untuk masalah Anda sekarang
extension=sqlite3    // Ini juga penting untuk SQLite

2. pastiin extention dirnya ke ext

bikin database sqllitenya di folder database dengan nama database.sqlite,awalnya pake txt document dulu terus extentionnya nanti diganti

3. download npm, composer, aplinejs, aos pake command install

4. bikin .env dari .env.example
copy .env.example .env

5. lalu migrate databasenya pake command ini
php artisan migrate:fresh --seed


untuk buat akun baru harus verifikasi email caranya?
6. buat email mailtrap, masukin konfigurasinya ke env, tanya ke Gemini/claude pasti tau mereka caranya, terus di mailtrap tekan tombol verifikasi email habistu bisa deh jalan! yey 


