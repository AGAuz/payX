PayX

PayX — loyihalaringizda to‘lov jarayonlarini avtomatlashtirish uchun ishlab chiqilgan loyiha.

O‘rnatish va sozlash

1. Bot tokenini quyidagi faylga yozing:
inc/config.php


2. Ma’lumotlar bazasi login va parollarini quyidagi faylga yozing:
inc/mysql.php


3. Brauzer orqali quyidagi manzilni ochish orqali MySQL jadvalari va webhook avtomatik yaratiladi:
payx/mysql_base.php


4. PayX token joylash
invoice/pay.php va invoice/update.php fayllarida TOKEN so‘zini CTRL+F orqali qidiring va o‘z payx.uz tokeningizni joylashtiring.



Qisqacha izoh:
Ushbu loyiha orqali siz to‘lovlarni avtomatlashtirasiz.
Kerakli joylarda faqat token va baza sozlamalarini kiritish kifoya.
Dasturchilar uchun oson integratsiya qilishga mo‘ljallangan.
