<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


function getFullNameFromParts($surname, $name, $patronomyc)
{
    $fullName =  "$surname $name $patronomyc";
    return $fullName;
}

function getPartsFromFullname($fullName)
{
    $myArr = explode(' ', $fullName);
    return ['surname' => $myArr[0], 'name' => $myArr[1], 'patronomyc' => $myArr[2]];

}



function getShortName($fullName)
{
    $myArrName = getPartsFromFullname($fullName);
    $name = $myArrName['name'];
    $surname = $myArrName['surname'];
    $shortSurname = substr($surname, 0, 1);
    return"$name $shortSurname.";

}




function EndsWithLetter($line, $charsToCheck)
{
    $lineLength = strlen ($line);
    $charsCount = strlen ($charsToCheck);
    $lastSymbols = substr($line, $lineLength - $charsCount, $charsCount);
    if($lastSymbols === $charsToCheck)
    {
        return true;
    }
    return false;
}



function getGenderFromName($fullname)
{
    $arrName = getPartsFromFullname($fullname);

    $surname = $arrName['surname'];
    $name = $arrName['name'];
    $patronomyc = $arrName['patronomyc'];

    $femaleRate = 0;
    $maleRate = 0;

    if(EndsWithLetter($patronomyc, 'вна'))
    {
        $femaleRate++;
    }
    if(EndsWithLetter($name, 'а'))
    {
        $femaleRate++;
    }

    if(EndsWithLetter($patronomyc, 'ич'))
    {
        $maleRate++;
    }

    if(EndsWithLetter($surname, 'ва'))
    {
        $femaleRate++;
    }

    if(EndsWithLetter($name, 'й') || EndsWithLetter($name, 'н'))
    {
        $maleRate++;
    }
    if(EndsWithLetter($surname, 'в'))
    {
        $maleRate++;
    }

      return  $maleRate <=> $femaleRate;
}



function getGenderDescription($persons_array)
{
    $count = count($persons_array);
    $males = 0;
    $females = 0;
    $unknown = 0;

    foreach($persons_array as $key => $user)
    {
        $fullName = $user['fullname'];
        $gender = getGenderFromName($fullName);
        if($gender == 1)
        {
            $males++;
        }
        else if($gender == -1)
        {
            $females++;
        }
        else
        {
            $unknown++;
        }
    }
    $malesPercent = round(($males / $count) * 100, 1);
    $femalesPercent = round(($females / $count) * 100, 1);
    $unknownPercent = 100 - $malesPercent - $femalesPercent;
    echo <<<HEREDOCLETTER
Гендерный состав аудитории:
---------------------------
Мужчины - {$malesPercent}%
Женщины - {$femalesPercent}%
Не удалось определить - {$unknownPercent}%
HEREDOCLETTER;
}


function getPerfectPartner($surname, $name, $patronomyc, $array)
{
    $fullName = getFullnameFromParts($surname, $name, $patronomyc);
    $gender = getGenderFromName($fullName);
    $count = 0;
    $partner = -1;

    while ($count < 10) {
        $count++;

        $randomIndex = rand(0, count($array) - 1);
        $randomUser = $array[$randomIndex];
        $randomUserName = $randomUser['fullname'];
        $partnerGender = getGenderFromName($randomUserName);
        if($partnerGender + $gender == 0)
        {
            $partner = $randomUser;
            break;
        }
    }
    if($partner == -1)
    {
        echo 'Не удалось подобрать партнера';
        return;
    }

    $shortName1 = getShortName($fullName);
    $shortName2 = getShortName($partner['fullname']);
    $percent = (rand(5000, 10000)) / 100;
    echo <<<HEREDOCLETTER
{$shortName1} + {$shortName2} = 
♡ Идеально на {$percent}% ♡
HEREDOCLETTER;


}

getGenderDescription($example_persons_array);
echo "\n\n";
getPerfectPartner("Осадчук", "Александр", "Михайлович", $example_persons_array);