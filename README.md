<p align="center">
    <h3 align="center">Краткая инструкция по развёртыванию</h3>
    <br>
    <br>
</p>

<p>
<br>
<h2>1. Клонирование репозитория</h2>
<br>
    <p> Скачиваем архив из данного репозитория и распаковываем или клонируем данный репозиторий на свой локальный компьютер, выполнив команду в терминале:
        <p><code> git clone </code></p>
        <p>Далее выполняем команду:</p>
        <p><code> composer update </code></p>
    </p>
<br>
<br>
<h2>2. Развёртывание</h2>
<br>
    <p> 2.1 Устанавливаем Docker на свой локальный копьютер, если он ранее не был установлен.</p>
    <p> 2.2 В терминале на папке с склонированным репозиторием выполняем команду:
        <p><code> docker-compose up -d </code></p>       
        Эта команда скачает все необходимые зависимости, поднимет контейнер на порту 8000, и API будет готов к работе.
    </p>
    <h4> Теперь API доступен по адресу http://localhost/orders/.</h4>
<br>
<br>
