<html>
<head>
    <meta charset="UTF-8">
    <title>Чатик</title>
</head>
<style>
    body { font-family: 'Times New Roman', sans-serif; }
    button:hover { background: deepskyblue; }
    html {
        background: #e3c963;
        box-shadow: 0 -200px 100px -120px #d94d69 inset;
        animation: background 6s infinite alternate;
    }
    @keyframes background {
        50% { background: skyblue;
              box-shadow: 0 -200px 100px -100px #b3d56e inset;
        }}
</style>
<div>
    <form method="get" action="/">
        <label>Введите свой логин и пароль, чтобы пообщаться с друзьми.</label></p>
        <input size="28" name="login" placeholder="login"/>
        <input size="28" name="password" placeholder="password"/>
        <button type="submit" size="28">АУТЕНТИФИКАЦИЯ</button>
        <button type="submit" name="delete" size="28">ОЧИСТИТЬ ЧАТ</button></p>
    </form>
</div>
</html>

<?php
//Запись сообщений в файл
function add_message($user, $message){
    $content = json_decode(file_get_contents("message_archive.json"));
    $message_object = (object) [
        'date' => (new DateTimeImmutable())->format('Y-m-d h:i'),
        'user' => $user,
        'message' => $message];
    $content->messages[] = $message_object;
    file_put_contents("message_archive.json", json_encode($content));
}

function print_message(){
//Если файл существует - получаем его содержимое
        if (file_exists('message_archive.json')){
            $content = json_decode(file_get_contents("message_archive.json"));
            foreach($content->messages as $message){
                echo "<p>$message->date $message->user: $message->message</p>";
            }} else echo "История сообщений пуста :(</p>";
}

echo "История сообщений:</p>";
print_message();

$user = $_GET['login'];
$password = $_GET['password'];

if ((!empty($user)) || (!empty($password))) {
    if (($user == "admin" && $password == "qwerty") || ($user == "dasha")) {
    setcookie('global_login', $user, time() + 180);
    ?>

    <form method="get" action="/">
        <input size="28" name="message" placeholder="<?php echo $user;?> prints..."/>
        <button type="submit" size="28">ОТПРАВИТЬ СООБЩЕНИЕ</button></p>
    </form>

    <?php
} else {
    echo "<script> alert('Введены неверные данные.') </script>";
    }}

$message = $_GET['message'];
if(isset($_GET['message']) && $_GET['message'] != ''){
    add_message($_COOKIE['global_login'], $_GET['message']);
    header('Refresh: 0; url=chat.php');
}

//Удаление всех сообщений
if (isset($_GET['delete'])){
    unlink('message_archive.json');
    echo "<script> alert('Все данные удалены!') </script>";
    header('Refresh: 0; url=chat.php');
}?>