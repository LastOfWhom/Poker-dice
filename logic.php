pwd<?php
function gameloop($countBotTotal,$countPlayerTotal,$setCombination){ // зацикливание игры пока кто-то не выйграет
    do{
        $countPlayerTotal = rollDicesPlayer($countPlayerTotal, $setCombination);
        $countBotTotal = rollDicesBot($countBotTotal, $setCombination);
        echo "У игрока {$countPlayerTotal} очков \n";
        echo "У бота {$countBotTotal} очков \n\n";

        if($countPlayerTotal >= 70){
            echo "Победил игрок со счетом - {$countPlayerTotal}";
            return;
        }elseif ($countBotTotal >= 70){
            echo "Победил бот со счетом - {$countBotTotal}";
            return;
        }
    }while(true);
}


function rollDicesPlayer($countPlayerTotal, $setCombination){
    $valueDices = [];
    for ($count = 0; $count < COUNT_DICE; $count++) {
        $valueDices[] = mt_rand(1,6);
    }
    echo "Комбинация игрока - ".implode('', $valueDices)."\n";
    echo "Вы хотите перебросить кубики? Ответ только y или n \n";
    do{
        $answerPlayer = readline();
        if($answerPlayer == 'n')
        {
            echo "Нет так нет. Понадеемся на удачу! \nКомбинация игрока -";
            return  makeChekCombination($valueDices, $countPlayerTotal, $setCombination);
        }
        elseif($answerPlayer == 'y')
            return reRollDices($valueDices, $countPlayerTotal, $setCombination);
        else
            echo "Введите корректный ответ из предложенного.\nВы хотите перебросить кубики? Ответ только y или n  \n";
    }while(true);
}



function reRollDices($valueDices, $countPlayerTotal, $setCombination){
    echo "Сколько перебросить кубиков? \n";
    $answerPlayer = correctInput();
    if($answerPlayer != 5){
        $newDices = reRollEachDice($valueDices, $answerPlayer);
        $valueDices = replaceDicesOnNew($newDices, $answerPlayer, $valueDices);
    }
    if($answerPlayer == 5){
        $valueDices = [];
        $valueDices = replaceDicesOnNew($newDices, $answerPlayer, $valueDices);
    }
    echo "Комбинация игрока - ";
    return  makeChekCombination($valueDices, $countPlayerTotal, $setCombination);
}

function replaceDicesOnNew($newDices, $answerPlayer, $valueDices) {
    if($answerPlayer != 5){
        for ($i = 0; $i < count($newDices); $i++) {
            $keysFoundElement[] = array_search($newDices[$i], $valueDices);// находим ключи элементов которые хотим удалить
            array_splice($valueDices,$keysFoundElement[$i],1); // удаляем
        }
    }
    for ($i = 0; $i < $answerPlayer; $i++) {
        $valueDices[] = mt_rand(1,6);
    }
    return $valueDices;
}



function correctInput(){
    $answerPlayer = (integer) readline();
    do{
        if($answerPlayer < 1 || $answerPlayer >5){
            echo "Так не пойдет. Введи кооректное число \n";
            $answerPlayer = readline();
        }
        else
            return $answerPlayer;
    }while(true);
}
function reRollEachDice($valueDices, $answerPlayer){
    $count = 0;
    $balanceDice = 0;
    $newDices = [];
    while($count < $answerPlayer) {
        echo "Какой кубик(значение) хотите поменять? \n".implode('',$valueDices)."\n";
        $balanceDice = $answerPlayer - 1 - $count;
        $newDice = (integer)readline('');
        if ($newDice < 1 || $newDice > 7)
            echo "Так не пойдет. Введи кооректное число от 1 до 6\n";
        elseif(!in_array($newDice, $valueDices))
            echo "Такого кубика нет\n";
        else{
            echo "Осталось еще {$balanceDice} \n";
            $newDices[] = $newDice;
            $count++;
        }
    }
    return $newDices;
}


function rollDicesBot($countBotTotal, $setCombination){
    $valueDices = [];
    for ($count = 0; $count < COUNT_DICE; $count++) {
        $valueDices[] =  mt_rand(1,6);
    }
    echo "Комбинация бота - ";
    return makeChekCombination($valueDices, $countBotTotal, $setCombination);

}

function makeChekCombination($valueDices, $countTotal,$setCombination){
    $biggestDice = 0;
    sort($valueDices); // сортирую массив по порядку
    $countValuesCards = array_count_values($valueDices); // подсчитываю количество всех значении
    rsort($countValuesCards);
    echo implode('',$valueDices)."\n";
    foreach ($countValuesCards as $key => $item) {
        if ($item == 5) {
            echo "Это покер \n";
            return sumTotalPlayer($countTotal,$setCombination['poker']);
        }
        elseif ($item == 4){
            echo "Это каре\n";
            return sumTotalPlayer($countTotal,$setCombination['kare']);
        }
        elseif (($item == 3) && count($countValuesCards) == 2){
            echo "Это Фул Хауз\n";
            return sumTotalPlayer($countTotal,$setCombination['fullHouse']);
        }
        elseif(count($countValuesCards) == 5){
            $countStreet = 0;
            for($count = 0; $count < count($valueDices); $count++){
                $diff = $valueDices[$count] - $valueDices[$count+1];
                if($diff == -1)
                    $countStreet++;
            }
            if($countStreet == 4){
                echo "Это стрит";
                return sumTotalPlayer($countTotal, $setCombination['street']);
            }
            else{
                echo "Cтаршая кость\n";
                $biggestDice = max($valueDices);
                return sumTotalPlayer($countTotal, $biggestDice);
            }
        }
        elseif (($countValuesCards[$key] == 3)  && count($countValuesCards) == 3){
            echo "Это сет\n";
            return sumTotalPlayer($countTotal,$setCombination['set']);
        }
        elseif (($countValuesCards[$key+1] == 2 ) && count($countValuesCards) == 3){
            echo "Это 2 пары\n";
            return sumTotalPlayer($countTotal,$setCombination['2Pair']);
        }
        elseif($item == 2 && count($countValuesCards) == 4){
            echo "Это 1 пара\n";
            return sumTotalPlayer($countTotal,$setCombination['pair']);
        }
    }
}
function sumTotalPlayer($countPlayerTotal, $addCountCombination): int
{
    return $countPlayerTotal + $addCountCombination;
}