<?php

//приветствие и вопрос сыграть в игру
//while пока кто-то из игроков не выйграет
// Если один из игроков достигает 100 очков игра аканчивается
require "logic.php";
const COUNT_DICE = 5;// 5 костей
$countPlayerTotal = 0;
$countBotTotal = 0;
$setCombination = ['poker' => 40, 'kare' => 35,'fullHouse' => 30, 'street' => 25, 'set' => 20, '2Pair' => 15, 'pair' => 10];


do{
    echo "Вы хотите начать игру? Ответ y или n\n";
    $answerPlayerStartGame = readline('');
        if($answerPlayerStartGame == 'y'){
            gameloop($countBotTotal,$countPlayerTotal,$setCombination);
        }
        elseif($answerPlayerStartGame == 'n'){
            echo "Пока\n";
            return;
        }
        else{
            echo "Некорректные данные. Введите y или n\n";
        }

}while(true);

