<?php

use alimmvc\frontend\models\recoverDnk;

class recoverDnkTest extends \PHPUnit_Framework_TestCase
{
    private $recoverDnk;

    protected function setUp()
    {
        $this->recoverDnk = new recoverDnk();
    }

    protected function tearDown()
    {
        $this->recoverDnk = NULL;
    }

    public function addDataProvider() 
    {
        //
        return [тности
        // 1) фрагменты по порядку
        ["АГЦЦ-ЦЦГГУ-ГГУАА-УААЦ", "АГЦЦГГУААЦ"],
        // 2) фрагменты не по порядку
        ["ГГУАА-АГЦЦ-ЦЦГГУ-УААЦ", "АГЦЦГГУААЦ"],
        // 3) проверка соединения всех допустимых букв 
        ["ЦЦ-ЦЦГГ-ГГУУ-УУАА-АА", "ЦЦГГУУАА"],
        // 4) если фрагменты можно восстановить несколько раз
        ["ГУГУ-ГУ", false],
        // 5) пустая строка
        ["", false],
        // 6) Недопустимые символы
        ["fdnkfdnln", false],
        // 7) Недопустимые символы + допустимые
        ["УАГЦ-РРПНЫ", false],

        // Граничные условия
        // 1) соединение по двум символам (минимум)
        ["ЦУГ-УГЦА", "ЦУГЦА"],
        // 2) соединение по всем сиволам (максимум)
        ["УГЦАА-УГЦАА", "УГЦАА"],
        // 3) соединение по 3м сиволам
        ["ЦЦГГУ-ГГУАА", "ЦЦГГУАА2"],
        // 4) соединение по одному символу
        ["АУЦ-ЦУУ", false],
        // 5) на входе 1 фрагмент
        ["ЦУГ", "ЦУГ"],
        // 6) на входе больше 3х фрагментов
        ["ЦУГ-УГАЦА-ЦАА-ААЦ", "ЦУГАЦААЦ"],
        // 7) соединение по 1му символу
        ];
    }
    
    /**
     * @dataProvider addDataProvider
     */
    public function testAdd($a, $b)
    {
        $result = $this->recoverDnk->recover($a);
        $this->assertEquals($b, $result);
    }
}