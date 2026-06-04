<?php
session_start();
if(!isset($_SESSION['user_id'])) die('Чтобы посмотреть историю заявок, надо войти в аккаунт.');
include('db.php');

// Код изменения отзыва
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review'])) {
    $review = $con->real_escape_string($_POST['review']);
    $user_id = (int)$_SESSION['user_id'];
    $con->query("UPDATE users SET review='$review' WHERE id='$user_id'");
    echo '<div style="color:green; padding:10px; background:#e6ffe6; margin-bottom:10px;">Отзыв оставлен</div>';
}

// Код истории заявок
$user_id = (int)$_SESSION['user_id'];
$query = $con->query("SELECT * FROM request WHERE user_id='$user_id' ORDER BY date DESC");
if(!$query) die('query error: ' . $con->error); 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет - история заявок</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #5885b9 0%, #2ca094 100%);
    min-height: 100vh;
    padding: 40px 20px;
    color: white;
    overflow-x: hidden;
}

/* РОМБЫ */
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

/* КОНТЕЙНЕР */
.container {
    max-width: 800px;
    margin: 0 auto;
    background: rgba(255,255,255,0.98);
    padding: 30px;
    border-radius: 25px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    position: relative;
    z-index: 2;
    animation: slideIn 0.6s ease-out;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(50px); }
    to { opacity: 1; transform: translateY(0); }
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* BUTTON HOME */
.btn-home {
    display: inline-block;
    padding: 12px 20px;
    border-radius: 12px;
    background: linear-gradient(135deg, #5885b9, #2ca094);
    color: white;
    text-decoration: none;
    margin-bottom: 20px;
    transition: 0.3s;
    font-weight: bold;
}

.btn-home:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* CARD REQUEST */
.request {
    background: rgba(255,255,255,0.9);
    color: #333;
    padding: 20px;
    margin: 20px 0;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.request:hover {
    transform: translateY(-5px);
}

/* TITLE */
.request h2 {
    margin-bottom: 10px;
    color: #2ca094;
}

/* STATUS */
.request b {
    color: #555;
}

/* REVIEW FORM */
.review-form {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px dashed #ccc;
}

input[type="text"] {
    width: 70%;
    padding: 10px;
    border-radius: 12px;
    border: 2px solid #e0e0e0;
    transition: 0.3s;
}

input[type="text"]:focus {
    border-color: #5885b9;
    outline: none;
}

/* BUTTON */
button {
    padding: 10px 18px;
    border: none;
    border-radius: 20px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(40,167,69,0.4);
}

/* EMPTY */
p {
    text-align: center;
    color: #666;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .container { padding: 20px; }
    input[type="text"] { width: 100%; margin-bottom: 10px; }
}
</style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn-home"> На главную</a>
        
        <h1>История заявок</h1>
        
        <?php
        $i = 0;
        if($query->num_rows == 0) {
            echo '<p style="text-align:center; color:#666;">У вас пока нет заявок.</p>';
        }
        while($request = $query->fetch_assoc()) {
            $i++; 
            echo '
            <div class="request">
                <h2>Заявка ' . $i . '</h2>
                <b>Дата: </b>' . htmlspecialchars($request['date']) . '<br>
                <b>Вид услуги: </b>' . htmlspecialchars($request['curses']) . '<br>
                <b>Тип оплаты: </b>' . htmlspecialchars($request['payment']) . '<br><br>
                <b>Статус: </b>' . htmlspecialchars($request['status']) . '<br>';
                
            if($request['status'] === 'Обучение завершено') {
                echo '
                <div class="review-form">
                    <form action="" method="POST">
                        <input type="text" name="review" placeholder="Отзыв об услуге" value="' . htmlspecialchars($request['review']) . '">
                        <button type="submit"> Оставить отзыв</button>
                    </form>
                </div>';
            }
            echo '</div>';
        }
        ?>
        <script>
                    // Создание анимированных ромбо на фоне
        function createDiamonds() {
            for (let i = 0; i < 10; i++) {
                const diamond = document.createElement('div');
                diamond.className = 'diamond';
                const size = Math.random() * 100 + 50;
                diamond.style.width = size + 'px';
                diamond.style.height = size + 'px';
                diamond.style.left = Math.random() * 100 + '%';
                diamond.style.bottom = '-' + size + 'px';
                diamond.style.animationDuration = Math.random() * 15 + 10 + 's';
                diamond.style.animationDelay = Math.random() * 5 + 's';
                document.body.appendChild(diamond);
            }
        }
        
        createDiamonds();
            </script>
    </div>
</body>
</html>