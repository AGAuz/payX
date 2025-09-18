PayX

PayX — loyihalaringizda to‘lov jarayonlarini avtomatlashtirish uchun ishlab chiqilgan loyiha.

O‘rnatish va sozlash

1. Token joylash
Bot tokenini quyidagi faylga yozing:
inc/config.php


2. MySQL sozlash

Ma’lumotlar bazasi login va parollarini quyidagi faylga yozing:

inc/mysql.php


3. Baza va webhook yaratish

Brauzer orqali quyidagi manzilni oching:

payx/mysql_base.php

Shu bilan MySQL jadvalari va webhook avtomatik yaratiladi.


4. PayX token joylash

invoice/pay.php va invoice/update.php fayllarida TOKEN so‘zini CTRL+F orqali qidiring va o‘z payx.uz tokeningizni joylashtiring.



Qisqacha izoh

Ushbu loyiha orqali siz to‘lovlarni avtomatlashtirasiz.

Kerakli joylarda faqat token va baza sozlamalarini kiritish kifoya.

Dasturchilar uchun oson integratsiya qilishga mo‘ljallangan.
