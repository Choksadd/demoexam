<?php
session_start();

// Выход из системы
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Проверяем, установлен ли ключ admin в сессии
$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Главная - Учусь.РФ</title>
  <style>
:root {
    --blue1: #5885b9;
    --blue2: #2ca094;
    --white: #ffffff;
}

/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, var(--blue1) 0%, var(--blue2) 100%);
    min-height: 100vh;
    color: white;
    overflow-x: hidden;
}

/* АНИМИРОВАННЫЕ РОМБЫ  */
.diamond {
    position: fixed;
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.05);
    transform: rotate(45deg);
    animation: float 20s linear infinite;
    z-index: 0;
}

@keyframes float {
    0% { transform: translateY(0) rotate(0deg); }
    100% { transform: translateY(-120vh) rotate(360deg); }
}

/* HEADER */
.header {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
    padding: 15px 0;
    position: sticky;
    top: 0;
    z-index: 10;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: auto;
    padding: 0 20px;
}

.logo {
    color: white;
    font-size: 22px;
    font-weight: bold;
    text-decoration: none;
}

/* BUTTONS */
.nav-buttons a {
    margin-left: 10px;
    padding: 10px 18px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.5);
    color: white;
    text-decoration: none;
    transition: 0.3s;
    font-weight: 500;
}

.nav-buttons a:hover {
    background: white;
    color: var(--blue1);
    transform: translateY(-2px);
}

/* SLIDER */
.slideshow-container {
    max-width: 1000px;
    margin: 50px auto;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    z-index: 1;
}

.mySlides {
    display: none;
}

.mySlides img {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.text {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(0,0,0,0.4);
    padding: 10px 15px;
    border-radius: 10px;
}

/* ARROWS */
.prev, .next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    padding: 12px 16px;
    border-radius: 50%;
    cursor: pointer;
    transition: 0.3s;
}

.prev:hover, .next:hover {
    background: white;
    color: var(--blue1);
}

.prev { left: 10px; }
.next { right: 10px; }

/* DOTS */
.dot-container {
    text-align: center;
    margin: 15px 0;
}

.dot {
    height: 12px;
    width: 12px;
    margin: 5px;
    display: inline-block;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    cursor: pointer;
}

.dot.active {
    background: white;
}

/* CARDS SECTION */
section {
    position: relative;
    z-index: 1;
}

section h2 {
    text-align: center;
    margin-bottom: 30px;
}

section div > div {
    background: rgba(255,255,255,0.08) !important;
    backdrop-filter: blur(10px);
    border-radius: 20px !important;
    transition: 0.3s;
}

section div > div:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,0.15) !important;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .mySlides img { height: 300px; }

    .nav {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
<!-- Шапка сайта -->
<header class="header">
  <div class="nav">
    <a href="index.php" class="logo">Учусь.РФ</a>

    <!-- Кнопки навигации -->
    <div class="nav-buttons">
      <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="btn-login">Войти</a>
        <a href="register.php" class="btn-register">Регистрация</a>
      <?php elseif ($is_admin): ?>
        <a href="admin.php" class="btn-admin">Панель администратора</a>
        <a href="?logout=1" class="btn-exit">Выход</a>
      <?php elseif (isset($_SESSION['user_id'])): ?>
        <a href="history.php" class="btn-lk">Мои заявки</a>
        <a href="create.php" class="btn-create">Новая заявка</a>
        <a href="?logout=1" class="btn-exit">Выход</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- Слайдер с картинками -->
<div class="slideshow-container">
  <!-- Слайды -->
  <div class="mySlides fade">
    <img src="slider_1.jpg" alt="Слайд 1">
    <div class="text">Курсы повышения квалификации</div>
  </div>

  <div class="mySlides fade">
    <img src="slider_2.jpg" alt="Слайд 2">
    <div class="text">Курсы переподготовки</div>
  </div>

  <div class="mySlides fade">
    <img src="slider_3.png" alt="Слайд 3">
    <div class="text">Курсы по охране труда</div>
  </div>

  <div class="mySlides fade">
    <img src="slider_4.jpg" alt="Слайд 4">
    <div class="text">Изучение нового</div>
  </div>
  <a class="prev" onclick="plusSlides(-1)">❮</a>
  <a class="next" onclick="plusSlides(1)">❯</a>
</div>

<!-- Точки навигации -->
<div class="dot-container">
  <span class="dot" onclick="currentSlide(1)"></span>
  <span class="dot" onclick="currentSlide(2)"></span>
  <span class="dot" onclick="currentSlide(3)"></span>
  <span class="dot" onclick="currentSlide(4)"></span>
</div>
<!-- Основной контент -->
<section style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
  <h2 style="text-align: center; color: var(--silver); margin-bottom: 30px;">Почему выбирают нас?</h2>
  
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">    
    <div style="background: rgba(26, 58, 95, 0.8); padding: 25px; border-radius: 10px; text-align: center;">
      <h3 style="color: var(--silver-light);">Современное оборудование</h3>
      <p style="color: var(--silver); line-height: 1.5;">Мы используем только новые программы для обучения.</p>
    </div>
        <div style="background: rgba(26, 58, 95, 0.8); padding: 25px; border-radius: 10px; text-align: center;">
      <h3 style="color: var(--silver-light);">Опытные преподаватели</h3>
      <p style="color: var(--silver); line-height: 1.5;">Все наши преподаватели имеют высокую квалификацию.</p>
    </div>
    <div style="background: rgba(26, 58, 95, 0.8); padding: 25px; border-radius: 10px; text-align: center;">
      <h3 style="color: var(--silver-light);">Гибкий график</h3>
      <p style="color: var(--silver); line-height: 1.5;">Подберём удобное время для занятий под ваш график.</p>
    </div>
  </div>
</section>

<script>
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");

  if (n > slides.length) { slideIndex = 1 }
  if (n < 1) { slideIndex = slides.length }

  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }

  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
}

// Автоматическое переключение слайдов каждые 3 секунды
let slideInterval = setInterval(function() {
  plusSlides(1);
}, 3000);

// Останавливаем автоматическое переключение при наведении на слайдер
const slideshowContainer = document.querySelector('.slideshow-container');
if (slideshowContainer) {
  slideshowContainer.addEventListener('mouseenter', function() {
    clearInterval(slideInterval);
  });
  
  slideshowContainer.addEventListener('mouseleave', function() {
    slideInterval = setInterval(function() {
      plusSlides(1);
    }, 3000);
  });
}

</script>
</body>
</html>