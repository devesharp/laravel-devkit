<?php

namespace Tests\Support;

use Devesharp\Support\Collection;

class HelpersTest extends \Tests\TestCase
{
    /**
     * @testdox array_only com duas dimensões
     */
    public function testArrayOnlyTwoDimension()
    {
        $array = \Devesharp\Support\Helpers::arrayOnly(
            [
                'name' => 'john',
                'two' => [
                    'first' => false,
                    'second' => false,
                ],
            ],
            ['two.first'],
        );

        $this->assertEquals($array, [
            'two' => [
                'first' => false,
            ],
        ]);
    }

    /**
     * @testdox array_only array sequencial
     */
    public function testArrayOnlyFirstExclude()
    {
        $array = \Devesharp\Support\Helpers::arrayOnly(
            [
                [
                    'two' => [
                        'first' => 'value',
                        'second' => 'value',
                    ],
                ],
                [
                    'two' => [
                        'first' => 'value',
                        'second' => 'value',
                    ],
                ],
            ],
            ['two.first'],
        );

        $this->assertEquals($array, [
            [
                'two' => [
                    'first' => 'value',
                ],
            ],
            [
                'two' => [
                    'first' => 'value',
                ],
            ],
        ]);
    }

    /**
     * @testdox array_only com array
     */
    public function testArrayOnlyWithArray()
    {
        $array = \Devesharp\Support\Helpers::arrayOnly(
            [
                'name' => 'john',
                'two' => ['first', 'second'],
                'object' => [
                    'first' => 'value',
                    'second' => 'value',
                ],
            ],
            ['two', 'object'],
        );

        $this->assertEquals($array, [
            'two' => ['first', 'second'],
            'object' => [
                'first' => 'value',
                'second' => 'value',
            ],
        ]);
    }

    /**
     * @testdox array_only com array de objeto
     */
    public function testArrayOnlyWithObjectsArray()
    {
        $array = \Devesharp\Support\Helpers::arrayOnly(
            [
                'name' => 'john',
                'two' => [
                    [
                        'first' => 'value',
                        'second' => 'value',
                    ],
                    [
                        'first' => 'value',
                        'second' => 'value',
                    ],
                ],
            ],
            ['two.first'],
        );

        $this->assertEquals($array, [
            'two' => [
                [
                    'first' => 'value',
                ],
                [
                    'first' => 'value',
                ],
            ],
        ]);
    }

    /**
     * @testdox array_only com 2 array de objeto
     */
    public function testArrayOnlyWith2ObjectsArray()
    {
        $array = \Devesharp\Support\Helpers::arrayOnly(
            [
                'name' => 'john',
                'two' => [
                    [
                        'first' => 'value',
                        'second' => 'value',
                        'third' => 'value',
                    ],
                    [
                        'first' => 'value',
                        'second' => 'value',
                        'third' => 'value',
                    ],
                ],
            ],
            ['two.first', 'two.second'],
        );

        $this->assertEquals($array, [
            'two' => [
                [
                    'first' => 'value',
                    'second' => 'value',
                ],
                [
                    'first' => 'value',
                    'second' => 'value',
                ],
            ],
        ]);
    }

    /**
     * @testdox array_only com string
     */
    public function testArrayOnlyWithString()
    {
        $array = \Devesharp\Support\Helpers::arrayOnly(
            [
                'name' => 'john',
                'two' => [
                    [
                        'first' => 'value',
                        'second' => 'value',
                        'third' => 'value',
                    ],
                    [
                        'first' => 'value',
                        'second' => 'value',
                        'third' => 'value',
                    ],
                ],
            ],
            'two.first,two.second',
        );

        $this->assertEquals($array, [
            'two' => [
                [
                    'first' => 'value',
                    'second' => 'value',
                ],
                [
                    'first' => 'value',
                    'second' => 'value',
                ],
            ],
        ]);
    }

    /**
     * @testdox array_only com *
     */
    public function testArrayOnlyWithJoker()
    {
        $array = \Devesharp\Support\Helpers::arrayOnly(
            [
                'name' => 'john',
                'two' => [
                    [
                        'first' => 'name',
                        'second' => 'name',
                    ],
                    [
                        'first' => 'name',
                        'second' => 'name',
                    ],
                ],
            ],
            ['two.*.first'],
        );

        $this->assertEquals($array, [
            'two' => [
                [
                    'first' => 'name',
                ],
                [
                    'first' => 'name',
                ],
            ],
        ]);
    }

    /**
     * @testdox array_exclude
     */
    public function testArrayExclude()
    {
        $array = \Devesharp\Support\Helpers::arrayExclude(
            [
                'name' => 'john',
                'two' => [
                    'first' => 'value',
                    'second' => 'value',
                ],
                'array' => ['value', 'value'],
            ],
            ['two', 'array'],
        );

        $this->assertEquals($array, [
            'name' => 'john',
        ]);
    }

    /**
     * @testdox array_exclude array sequencial
     */
    public function testArrayFirstExclude()
    {
        $array = \Devesharp\Support\Helpers::arrayExclude(
            [
                [
                    'two' => [
                        'first' => 'value',
                        'second' => 'value',
                    ],
                ],
                [
                    'two' => [
                        'first' => 'value',
                        'second' => 'value',
                    ],
                ],
            ],
            ['two.first'],
        );

        $this->assertEquals($array, [
            [
                'two' => [
                    'second' => 'value',
                ],
            ],
            [
                'two' => [
                    'second' => 'value',
                ],
            ],
        ]);
    }

    /**
     * @testdox array_exclude com duas dimensões
     */
    public function testArrayExcludeTwoDimension()
    {
        $array = \Devesharp\Support\Helpers::arrayExclude(
            [
                'name' => 'john',
                'two' => [
                    'first' => 'value',
                    'second' => 'value',
                ],
            ],
            ['two.first'],
        );

        $this->assertEquals($array, [
            'name' => 'john',
            'two' => [
                'second' => 'value',
            ],
        ]);
    }

    /**
     * @testdox array_exclude com 2 array de objeto
     */
    public function testArrayExcludeWith2ObjectsArray()
    {
        $array = \Devesharp\Support\Helpers::arrayExclude(
            [
                'name' => 'john',
                'two' => [
                    [
                        'first' => 'value',
                        'second' => 'value',
                        'third' => 'value',
                    ],
                    [
                        'first' => 'value',
                        'second' => 'value',
                        'third' => 'value',
                    ],
                ],
            ],
            ['two.first', 'two.second'],
        );

        $this->assertEquals($array, [
            'name' => 'john',
            'two' => [
                [
                    'third' => 'value',
                ],
                [
                    'third' => 'value',
                ],
            ],
        ]);
    }

    /**
     * @testdox array_exclude com Array com objeto
     */
    public function testArrayExcludeWithArrayObjects()
    {
        $array = \Devesharp\Support\Helpers::arrayExclude(
            [
                'name' => 'john',
                'two' => [
                    [
                        'first' => 'value',
                        'second' => 'value',
                        'third' => [
                            'first' => 'value',
                            'second' => 'value',
                        ],
                    ],
                    [
                        'first' => 'value',
                        'second' => 'value',
                        'third' => 'value',
                    ],
                ],
            ],
            ['two.third.first'],
        );

        $this->assertEquals($array, [
            'name' => 'john',
            'two' => [
                [
                    'first' => 'value',
                    'second' => 'value',
                    'third' => [
                        'second' => 'value',
                    ],
                ],
                [
                    'first' => 'value',
                    'second' => 'value',
                    'third' => 'value',
                ],
            ],
        ]);
    }

    /**
     * @testdox array_only com Collection
     */
    public function testArrayOnlyWithCollection()
    {
        $array = \Devesharp\Support\Helpers::arrayOnly(
            new \Devesharp\Support\Collection([
                'name' => 'john',
                'two' => [
                    'first' => false,
                    'second' => false,
                ],
            ]),
            ['two.first'],
        );

        $this->assertEquals($array->toArray(), [
            'two' => [
                'first' => false,
            ],
        ]);
    }

    /**
     * @testdox array_exclude com Collection
     */
    public function testArrayExcludeWithCollection()
    {
        $array = \Devesharp\Support\Helpers::arrayExclude(
            new Collection([
                'name' => 'john',
                'two' => [
                    'first' => 'value',
                    'second' => 'value',
                ],
                'array' => ['value', 'value'],
            ]),
            ['two', 'array'],
        );

        $this->assertEquals($array->toArray(), [
            'name' => 'john',
        ]);
    }

    /**
     * @testdox array_filter_null com Collection
     */
    public function testArrayFilterNull()
    {
        $array = \Devesharp\Support\Helpers::arrayFilterNull([
            'name' => null,
            'two' => [
                'first' => 'value',
                'second' => 'value',
                'b' => null,
            ],
            'array' => [null, 'value', null],
        ]);

        $this->assertEquals(
            [
                'two' => [
                    'first' => 'value',
                    'second' => 'value',
                ],
                'array' => ['value'],
            ],
            $array,
        );
    }

    /**
     * @testdox searchableString
     */
    public function testSearchingString()
    {
        $string = \Devesharp\Support\Helpers::searchableString('éÁ   _=-3~');

        $this->assertEquals("ea 3", $string);
    }

    /**
     * @testdox normalizeString
     */
    public function testNormalizeString()
    {
        $string = \Devesharp\Support\Helpers::normalizeString('éÁ   _=-3~');

        $this->assertEquals("ea    3", $string);
    }

    /**
     * @testdox removeAccents deve remover acentos
     */
    public function testRemoveAccents()
    {
        $string = \Devesharp\Support\Helpers::removeAccents('éÁ_=-3~');

        $this->assertEquals("eA_=-3~", $string);
    }

    /**
     * @testdox isArrayAssoc
     */
    public function testIsArrayAssoc()
    {
        $this->assertEquals(false, \Devesharp\Support\Helpers::isArrayAssoc([1,2,3]));
        $this->assertEquals(false, \Devesharp\Support\Helpers::isArrayAssoc(['0' => 1, '1' => 2]));
        $this->assertEquals(true, \Devesharp\Support\Helpers::isArrayAssoc(['sd' => 1,2,3]));
        $this->assertEquals(true, \Devesharp\Support\Helpers::isArrayAssoc(['0' => 1, '2' => 2]));
    }

    /**
     * @testdox objectToArray
     */
    public function testObjectToArray()
    {
        $this->assertEquals(['name' => 'dfdf'], \Devesharp\Support\Helpers::objectToArray((object) ['name' => 'dfdf']));
        $this->assertEquals(['name' => 'dfdf', 'sub' => ['name' => 'dfdf']], \Devesharp\Support\Helpers::objectToArray((object) ['name' => 'dfdf', 'sub' => (object) ['name' => 'dfdf']]));
    }

    /**
     * @testdox trim_spaces
     */
    public function testTrimSpaces()
    {
        $this->assertEquals('trim spaces', \Devesharp\Support\Helpers::trim_spaces('  trim      spaces  '));
    }

    /**
     * @testdox isArrayString
     */
    public function testIsArrayString()
    {
        $this->assertEquals(true, \Devesharp\Support\Helpers::isArrayString(['1','2','3']));
        $this->assertEquals(false, \Devesharp\Support\Helpers::isArrayString(['1','2',3]));
        $this->assertEquals(false, \Devesharp\Support\Helpers::isArrayString(['1',true,'3']));
        $this->assertEquals(false, \Devesharp\Support\Helpers::isArrayString(['1',null,'3']));
    }

    /**
     * @testdox isArrayNumber
     */
    public function testIsArrayNumber()
    {
        $this->assertEquals(true, \Devesharp\Support\Helpers::isArrayNumber([1,2,3]));
        $this->assertEquals(true, \Devesharp\Support\Helpers::isArrayNumber([1,2.21515,3]));
        $this->assertEquals(true, \Devesharp\Support\Helpers::isArrayNumber([1,(double) 2.21515,3]));
        $this->assertEquals(false, \Devesharp\Support\Helpers::isArrayNumber(['1','2','zero']));
        $this->assertEquals(false, \Devesharp\Support\Helpers::isArrayNumber(['1',true,'3']));
        $this->assertEquals(false, \Devesharp\Support\Helpers::isArrayNumber(['1',null,'3']));
    }

    /**
     * @testdox randomLetters
     */
    public function testRandomLetters()
    {
        $this->assertEquals(5, strlen(\Devesharp\Support\Helpers::randomLetters(5)));
        $this->assertEquals(10, strlen(\Devesharp\Support\Helpers::randomLetters(10)));
        $this->assertEquals(20, strlen(\Devesharp\Support\Helpers::randomLetters(20)));
    }

    /**
     * @testdox onlyNumbers
     */
    public function testOnlyNumbers()
    {
        $this->assertEquals('1199994444', \Devesharp\Support\Helpers::onlyNumbers('(11) 9999-4444'));
    }

    /**
     * @testdox convertUrl
     */
    public function testConvertUrl()
    {
        $this->assertEquals('urlevvao-testne', \Devesharp\Support\Helpers::convertUrl('UrlÉVvão Testné-!#%'));
    }
}
