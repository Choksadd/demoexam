<?php
session_start();
if (!isset($_SESSION['user_id'])) die('Чтобы оставить заявку, надо войти в аккаунт.');

$success = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $review = $_POST['review'];
    $date = $_POST['date'];
    $curses = $_POST['curses'];
    $payment = $_POST['payment'];
    $status = 'Новая'; // Статус устанавливается автоматически
    
    include('db.php');
    
    // Для безопасности в реальном проекте используйте подготовленные выражения (prepared statements)
    $user_id = (int)$_SESSION['user_id']; // Защита от SQL-инъекций
    $review = $con->real_escape_string($review);
    $curses = $con->real_escape_string($curses);
    $payment = $con->real_escape_string($payment);
    
    $query = $con->query("INSERT INTO request (review, date, curses, payment, user_id, status) 
                          VALUES ('$review', '$date', '$curses', '$payment', '$user_id', '$status')");
    
    if (!$query) {
        $error = true;
        $error_msg = 'Ошибка: ' . $con->error;
    } else {
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание заявки - Водить.РФ</title>
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
    overflow-x: hidden;
    color: white;
}

/* РОМБЫ (как в регистрации) */
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
    max-width: 550px;
    margin: 0 auto;
    background: rgba(255,255,255,0.98);
    padding: 35px;
    border-radius: 25px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    position: relative;
    z-index: 2;
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from { opacity: 0; transform: translateY(50px); }
    to { opacity: 1; transform: translateY(0); }
}

/* NAV */
.nav-buttons {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
}

.btn-nav {
    flex: 1;
    text-align: center;
    padding: 12px;
    border-radius: 12px;
    text-decoration: none;
    color: white;
    font-weight: bold;
    background: linear-gradient(135deg, #5885b9, #2ca094);
    transition: 0.3s;
}

.btn-nav:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* TITLE */
h1 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

/* FORM */
form {
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
    transition: 0.3s;
}

label:hover {
    color: #5885b9;
    transform: translateX(5px);
}

input, select, textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    background: #fafafa;
    transition: 0.3s;
    font-size: 14px;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #5885b9;
    box-shadow: 0 0 0 3px rgba(88,133,185,0.2);
    background: white;
    transform: scale(1.02);
}

/* BUTTON */
button {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: bold;
    color: white;
    background: linear-gradient(135deg, #28a745, #20c997);
    cursor: pointer;
    transition: 0.3s;
    position: relative;
    overflow: hidden;
}

button:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(40,167,69,0.4);
}

/* SUCCESS / ERROR */
.success-message {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
    text-align: center;
}

.error-message {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
    text-align: center;
}

/* RESPONSIVE */
@media (max-width: 600px) {
    .container { padding: 25px; }
    .nav-buttons { flex-direction: column; }
}
</style>
</head>
<body>
    <div class="container">
        <!-- Кнопки навигации -->
        <div class="nav-buttons">
            <a href="index.php" class="btn-nav"> Главная</a>
            <a href="history.php" class="btn-nav"> История заявок</a>
        </div>
        
        <h1> Создание заявки</h1>

        <?php if ($success): ?>
            <div class="success-message">
                 Заявка успешно отправлена!<br><br>
                <a href="history.php"> Перейти к истории моих заявок →</a>
                <br><br>
                 Спасибо, что выбрали нас!
            </div>
        <?php elseif ($error): ?>
            <div class="error-message">
                 Ошибка при отправке заявки: <?php echo htmlspecialchars($error_msg); ?><br>
                <a href="javascript:history.back()">Попробовать снова</a>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="" id="requestForm">
            
            <label for="curses">Название курса</label>
            <select id="curses" name="curses" required>
                <option value="Курсы повышения квалификации">Курсы повышения квалификации</option>
                <option value="Курсы переподготовки">Курсы переподготовки</option>
                <option value="Курсы по охране труда">Курсы по охране труда</option>
               
            </select>

            <label for="date"> Когда желаете начать обучение?</label>
            <input id="date" type="datetime-local" name="date" required>

            <label for="payment"> Способ оплаты</label>
            <select id="payment" name="payment" required>
                <option value="наличные">Наличные</option>
                <option value="перевод">Переводом по номеру</option>
                <option value="карта">Банковской картой</option>
            </select>

            <label for="review"> Дополнительная информация</label>
            <textarea id="review" name="review" placeholder="Опишите ваши пожелания или комментарий..."></textarea>
             
            <button type="submit" id="submitBtn"> Отправить заявку</button>
        </form>
        <?php endif; ?>
    </div>

    <script>
        // Анимация при отправке формы
        const form = document.getElementById('requestForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                // Добавляем класс загрузки на кнопку
                submitBtn.classList.add('loading');
                submitBtn.textContent = 'Отправка';
            });
        }

        // Анимация при фокусе на полях
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transition = 'all 0.3s ease';
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.style.transform = 'scale(1)';
                }
            });
        });
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
</body>
</html>