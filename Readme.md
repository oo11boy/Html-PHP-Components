<div dir="rtl">

**PHP-Component** 
یه فریم‌ورک ساده و قدرتمنده که بهت اجازه می‌ده برنامه‌های وب رو با رویکرد کامپوننت‌محور بسازی، درست مثل React، ولی با استفاده از PHP، HTML، CSS و JavaScript. این فریم‌ورک برای ساختن پروژه‌های ماژولار، داینامیک و قابل‌نگهداری طراحی شده. توی این مستندات، همه‌چیز رو از صفر تا صد توضیح می‌دیم تا حتی اگه تازه‌کار باشی، بتونی باهاش کار کنی.

---

## ساختار اولیه پروژه

وقتی این فریم‌ورک رو دانلود می‌کنی، این چیزیه که می‌گیری:

```
📦 PHP-Component
 ┣ 📂 app
 ┃ ┣ 📂 config
 ┃ ┃ ┗ 📜 db.php              # تنظیمات اتصال به دیتابیس
 ┃ ┣ 📂 core
 ┃ ┃ ┣ 📜 renderer.php        # هسته رندرینگ کامپوننت‌ها
 ┃ ┃ ┗ 📜 router.php          # سیستم روتینگ پروژه
 ┃ ┗ 📂 pages                 # پوشه خالی برای صفحات شما
 ┣ 📂 assets                  # پوشه خالی برای CSS، JS و ...
 ┣ 📂 components              # پوشه خالی برای کامپوننت‌ها
 ┣ 📜 .htaccess               # تنظیمات روتینگ
 ┗ 📜 index.php               # نقطه ورود برنامه
```

> **توجه:** پوشه‌های `pages`، `assets` و `components` خالی هستن. تو باید خودت فایل‌هات رو داخلشون بسازی. ما اینجا قدم‌به‌قدم نشون می‌دیم چطور این کار رو بکنی.

---

## شروع سریع

### پیش‌نیازها
- **PHP 7.4+**: برای اجرای کدها.
- **وب‌سرور**: مثل Apache با پشتیبانی از `.htaccess` (Nginx هم با تنظیمات دستی کار می‌کنه).
- **MySQL** (اختیاری): اگه بخوای از دیتابیس استفاده کنی.

### نصب
1. پروژه رو توی پوشه وب‌سرور (مثل `htdocs` در XAMPP) کپی کن.
2. فایل `app/config/db.php` رو باز کن و اطلاعات دیتابیست رو تنظیم کن:
   ```php
   private $host = "localhost";
   private $dbname = "my_app_db";  // اسم دیتابیس خودت
   private $username = "root";     // نام کاربری
   private $password = "";         // رمز عبور
   ```
3. مطمئن شو که `mod_rewrite` توی Apache فعال باشه.
4. مرورگر رو باز کن و به آدرس پروژه برو (مثل `http://localhost/php-component`).

اگه همه‌چیز درست باشه، فعلاً یه صفحه خالی می‌بینی چون هنوز چیزی نساختی. بریم اولین قدم رو برداریم!

---

## مفاهیم اصلی

### 1. صفحات (Pages)
صفحات توی پوشه `app/pages` قرار می‌گیرن و هر چیزی که توی مرورگر نشون داده می‌شه، از اینجا شروع می‌شه.

#### ساخت یه صفحه ساده
1. توی `app/pages` یه فایل به اسم `home.php` بساز.
2. این کد رو داخلش بذار:
   ```php
   <html lang="fa">
   <head>
       <meta charset="UTF-8">
       <title>خانه</title>
   </head>
   <body>
       <h1>به PHP-Component خوش اومدی!</h1>
   </body>
   </html>
   ```
3. توی مرورگر به `/` یا `/home` برو. باید پیام بالا رو ببینی.

#### نکته
- اسم فایل‌ها مستقیماً با URL مپ می‌شن. مثلاً `contact.php` با `/contact` کار می‌کنه.
- `home.php` به‌صورت پیش‌فرض برای ریشه (`/`) لود می‌شه.

---

### 2. کامپوننت‌ها (Components)
کامپوننت‌ها تکه‌های قابل‌استفاده مجدد از کد هستن که می‌تونی توی صفحات یا حتی کامپوننت‌های دیگه ازشون استفاده کنی. مثل React، این‌ها رو با تگ‌های سفارشی (مثل `<Header />`) فراخوانی می‌کنی.

#### ساخت یه کامپوننت ساده
1. توی `components` یه فایل به اسم `Header.php` بساز.
2. این کد رو بذار:
   ```php
   <header>
       <nav>
           <a href="/">خانه</a>
           <a href="/contact">تماس</a>
       </nav>
   </header>
   ```

#### استفاده از کامپوننت
توی `home.php` این‌طوری تغییرش بده:
```php
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>خانه</title>
</head>
<body>
    <Header />
    <h1>به PHP-Component خوش اومدی!</h1>
</body>
</html>
```
حالا یه نوار ناوبری بالای صفحه می‌بینی.

#### پراپ‌ها (Props)
کامپوننت‌ها می‌تونن ورودی بگیرن تا داینامیک بشن.
1. توی `components` فایل `Greeting.php` رو بساز:
   ```php
   <p>سلام، <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>!</p>
   ```
   > **امنیت:** از `htmlspecialchars` استفاده کردیم تا از حملات XSS جلوگیری کنیم.
2. توی `home.php`:
   ```php
   <Greeting name="نیما" />
   ```
خروجی می‌شه: `<p>سلام، نیما!</p>`.

#### پراپ‌های چندگانه
بیا یه کامپوننت پیچیده‌تر بسازیم:
1. توی `components` فایل `Profile.php` رو بساز:
   ```php
   <div class="profile">
       <h2><?php echo htmlspecialchars($title); ?></h2>
       <p>اسم: <?php echo htmlspecialchars($username); ?></p>
       <p>سن: <?php echo (int)$age; ?></p>
   </div>
   ```
2. توی `home.php`:
   ```php
   <Profile title="پروفایل من" username="سارا" age="25" />
   ```
خروجی می‌شه یه باکس با تیتر "پروفایل من"، اسم "سارا" و سن "25".

#### شرط‌ها و حلقه‌ها توی کامپوننت
می‌تونی منطق PHP رو داخل کامپوننت‌ها بذاری:
```php
<div>
    <h2><?php echo htmlspecialchars($title); ?></h2>
    <?php if ($age >= 18): ?>
        <p>شما بزرگسال هستید.</p>
    <?php else: ?>
        <p>شما زیر 18 سال هستید.</p>
    <?php endif; ?>
</div>
```
استفاده: `<Profile title="تست" age="15" />`.

#### کامپوننت‌های تودرتو
برای ساختارهای پیچیده‌تر:
1. توی `components` یه پوشه به اسم `Dash` بساز و داخلش `Header.php` رو بذار:
   ```php
   <header>
       <h1><?php echo htmlspecialchars($title ?? 'بدون عنوان'); ?></h1>
   </header>
   ```
2. توی `home.php`:
   ```php
   <Dash/Header title="هدر داشبورد" />
   ```
مسیر تگ (`Dash/Header`) مستقیماً به `components/Dash/Header.php` اشاره می‌کنه.

#### نکات مهم کامپوننت‌ها
- **محل ذخیره:** همیشه توی `components` باشن.
- **نام‌گذاری:** از حروف بزرگ برای ابتدای اسم تگ‌ها استفاده کن (مثل `<Header>` نه `<header>`).
- **پراپ‌های پیش‌فرض:** می‌تونی با `??` مقدار پیش‌فرض بذاری (مثل مثال بالا).
- **خطا:** اگه کامپوننت پیدا نشه، توی خروجی یه پیام مثل `<!-- Component 'X' not found -->` می‌بینی.

---

### 3. روتینگ (Routing)
روتینگ توی این فریم‌ورک با `router.php` مدیریت می‌شه و URLها رو به صفحات متصل می‌کنه. این سیستم خیلی انعطاف‌پذیره و هم مسیرهای ثابت و هم داینامیک رو پشتیبانی می‌کنه.

#### مسیرهای ثابت
- `/` یا `/home` → `app/pages/home.php`
- `/contact` → `app/pages/contact.php`

مثال:
1. توی `app/pages` فایل `contact.php` رو بساز:
   ```php
   <h1>تماس با ما</h1>
   <p>ایمیل: info@example.com</p>
   ```
2. توی مرورگر به `/contact` برو. صفحه تماس رو می‌بینی.

#### مسیرهای داینامیک
برای صفحاتی که پارامتر می‌گیرن (مثل `/posts/123`):
1. توی `app/pages` یه پوشه به اسم `posts` بساز.
2. داخلش فایل `[id].php` رو بذار:
   ```php
   <h1>پست شماره <?php echo htmlspecialchars($id); ?></h1>
   <p>این یه صفحه داینامیکه!</p>
   ```
3. توی مرورگر به `/posts/456` برو. می‌بینی: "پست شماره 456".

#### روتینگ تودرتو
اگه بخوای ساختار عمیق‌تر داشته باشی:
1. توی `app/pages` یه پوشه `blog` بساز و داخلش یه پوشه `posts`.
2. توی `posts` فایل `[slug].php` رو بذار:
   ```php
   <h1>پست: <?php echo htmlspecialchars($slug); ?></h1>
   ```
3. به `/blog/posts/my-first-post` برو. خروجی: "پست: my-first-post".

#### اولویت روتینگ
- اول فایل ثابت (مثل `about.php`) چک می‌شه.
- اگه نبود، پوشه (مثل `posts/`) بررسی می‌شه.
- اگه فایل داینامیک (مثل `[id].php`) پیدا بشه، پارامترها استخراج می‌شن.
- اگه هیچ‌کدوم نباشه، "404 - Page Not Found" نشون داده می‌شه.

#### سفارشی‌سازی 404
توی `router.php` می‌تونی خطای 404 رو تغییر بدی:
```php
} else {
    include 'app/pages/404.php'; // یه فایل 404 بساز
}
```

#### نکته‌های روتینگ
- **پاکسازی URL:** آدرس‌ها به‌صورت خودکار تمیز می‌شن (مثلاً `/about/` به `/about` تبدیل می‌شه).
- **پارامترها:** فقط توی فایل‌های داینامیک مثل `[id].php` در دسترسن.
- **انعطاف‌پذیری:** می‌تونی پارامترهای دیگه مثل `[slug]` یا `[category]` بسازی.

---

### 4. استایل‌دهی (CSS)
فایل‌های CSS رو توی `assets/css` بذار و توی صفحات لودشون کن.

#### مثال
1. توی `assets/css` فایل `style.css` رو بساز:
   ```css
   h1 {
       color: #2ecc71;
       font-family: Arial, sans-serif;
   }
   header {
       background: #ecf0f1;
       padding: 15px;
   }
   ```
2. توی `home.php`:
   ```php
   <link rel="stylesheet" href="/assets/css/style.css">
   ```

#### نکته
- مسیرها باید از ریشه (`/`) شروع بشن چون `.htaccess` همه‌چیز رو به `index.php` هدایت می‌کنه.

---

### 5. اتصال به دیتابیس
فایل `db.php` یه کلاس ساده برای کار با MySQL داره.

#### مثال
توی `home.php`:
```php
<?php
require_once 'app/config/db.php';
$db = new Database();
$conn = $db->connect();
$user = $conn->query("SELECT name FROM users LIMIT 1")->fetch(PDO::FETCH_ASSOC);
?>
<h1>خوش آمدی، <?php echo htmlspecialchars($user['name']); ?>!</h1>
```

#### ساخت جدول نمونه
توی MySQL:
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50)
);
INSERT INTO users (name) VALUES ('علی');
```

---

### 6. متغیرها، آرایه‌ها و ثابت‌ها
#### متغیرها
```php
<?php $message = "سلام دنیا"; ?>
<p><?php echo htmlspecialchars($message); ?></p>
```

#### آرایه‌ها
```php
<?php
$data = ['title' => 'پست من', 'id' => 1];
extract($data, EXTR_SKIP);
?>
<h2><?php echo htmlspecialchars($title); ?></h2>
<p>ID: <?php echo (int)$id; ?></p>
```

#### ثابت‌ها
```php
<?php define('SITE_NAME', 'MyApp'); ?>
<footer><?php echo SITE_NAME; ?> &copy; 2025</footer>
```

---

## مثال کامل
بیا یه پروژه کوچک بسازیم:

**`app/pages/home.php`:**
```php
<?php
require_once 'app/config/db.php';
$db = new Database();
$conn = $db->connect();
$user = $conn->query("SELECT name FROM users LIMIT 1")->fetch(PDO::FETCH_ASSOC);
?>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>خانه</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <Header />
    <h1>خوش آمدی، <?php echo htmlspecialchars($user['name']); ?>!</h1>
    <Profile title="پروفایل" username="مهدی" age="30" />
</body>
</html>
```

**`components/Header.php`:**
```php
<header>
    <nav>
        <a href="/">خانه</a>
        <a href="/contact">تماس</a>
    </nav>
</header>
```

**`components/Profile.php`:**
```php
<div class="profile">
    <h2><?php echo htmlspecialchars($title); ?></h2>
    <p>اسم: <?php echo htmlspecialchars($username); ?></p>
    <p>سن: <?php echo (int)$age; ?></p>
</div>
```

**`assets/css/style.css`:**
```css
h1 { color: #3498db; }
header { background: #f1c40f; padding: 10px; }
.profile { border: 1px solid #ccc; padding: 10px; }
```

توی مرورگر یه صفحه با هدر زرد، تیتر آبی و یه باکس پروفایل می‌بینی.

---

## نکات پایانی
- **JavaScript:** توی `assets/js` بذارید و با `<script>` لود کنید.
- **امنیت:** همیشه خروجی‌ها رو با `htmlspecialchars` امن کنید.
- **سوالات:** توی بخش Issues پروژه بپرسید!

این مستندات باید همه‌چیز رو براتون روشن کرده باشه. موفق باشید! 🚀


</div>
