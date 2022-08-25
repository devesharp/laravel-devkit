<?php

namespace Tests\Support;

use Devesharp\Support\Collection;

class CollectionTest extends \Tests\TestCase
{
    public function testGetSet()
    {
        $b = new Collection();
        $b['var'] = 'foo';

        $this->assertEquals('foo', $b['var']);
        $this->assertEquals('foo', $b->var);
        $this->assertEquals(['var' => 'foo'], $b->toArray());

        $b = new Collection();
        $b->var = 'foo';

        $this->assertEquals('foo', $b['var']);
        $this->assertEquals('foo', $b->var);
        $this->assertEquals(['var' => 'foo'], $b->toArray());
    }

    public function testGetSetDeep()
    {
        $b = new Collection();
        $b['var.b'] = 'foo';

        $this->assertEquals('foo', $b['var.b']);
        $this->assertEquals('foo', $b->{'var.b'});
        $this->assertEquals(['var' => ['b' => 'foo']], $b->toArray());

        $b = new Collection();
        $b->{'var.b'} = 'foo';

        $this->assertEquals('foo', $b['var.b']);
        $this->assertEquals('foo', $b->{'var.b'});
        $this->assertEquals(['var' => ['b' => 'foo']], $b->toArray());
    }

    public function testChangeValueDeep()
    {
        $b = new Collection();
        $b['var.b'] = 'foo';
        $b['var.b'] = 'foo2';

        $this->assertEquals('foo2', $b['var.b']);
        $this->assertEquals(['b' => 'foo2'], $b['var']);
        $this->assertEquals('foo2', $b->{'var.b'});
        $this->assertEquals(['b' => 'foo2'], $b->{'var'});
        $this->assertEquals(['var' => ['b' => 'foo2']], $b->toArray());

        $b = new Collection();
        $b->{'var.b'} = 'foo';
        $b->{'var.b'} = 'foo2';

        $this->assertEquals('foo2', $b['var.b']);
        $this->assertEquals(['b' => 'foo2'], $b['var']);
        $this->assertEquals('foo2', $b->{'var.b'});
        $this->assertEquals(['b' => 'foo2'], $b->{'var'});
        $this->assertEquals(['var' => ['b' => 'foo2']], $b->toArray());
    }

    public function testUnsetArray()
    {
        $b = new Collection();
        $b['var'] = 'foo';
        unset($b['var']);

        $this->assertEquals(true, empty($b['var']));
        $this->assertEquals(true, empty($b->var));

        $b = new Collection();
        $b->var = 'foo';
        unset($b->var);

        $this->assertEquals(true, empty($b['var']));
        $this->assertEquals(true, empty($b->var));
    }

    public function testUnsetArrayDeep()
    {
        $b = new Collection();
        $b['var'] = [
            'foo' => '1',
            'foo2' => '2',
        ];
        unset($b['var.foo']);

        $this->assertEquals(['foo2' => '2'], $b['var']);
        $this->assertEquals(['foo2' => '2'], $b->var);

        $b = new Collection();
        $b['var'] = [
            'foo' => '1',
            'foo2' => '2',
        ];
        unset($b->{'var.foo'});

        $this->assertEquals(['foo2' => '2'], $b['var']);
        $this->assertEquals(['foo2' => '2'], $b->var);
    }

    public function testIssetArray()
    {
        $b = new Collection();
        $b['var'] = 'foo';

        $this->assertEquals(true, isset($b['var']));
        $this->assertEquals(true, isset($b->var));

        $this->assertEquals(false, isset($b['var2']));
        $this->assertEquals(false, isset($b->var2));

        $b = new Collection();
        $b->var = 'foo';

        $this->assertEquals(true, isset($b['var']));
        $this->assertEquals(true, isset($b->var));

        $this->assertEquals(false, isset($b['var2']));
        $this->assertEquals(false, isset($b->var2));
    }

    public function testIssetArrayDeep()
    {
        $b = new Collection();
        $b['var'] = [
            'foo' => '1',
        ];

        $this->assertEquals(true, isset($b['var.foo']));
        $this->assertEquals(true, isset($b->{'var.foo'}));

        $this->assertEquals(false, isset($b['var.foo2']));
        $this->assertEquals(false, isset($b->{'var.foo2'}));

        $b = new Collection();
        $b->var = [
            'foo' => '1',
        ];

        $this->assertEquals(true, isset($b['var.foo']));
        $this->assertEquals(true, isset($b->{'var.foo'}));

        $this->assertEquals(false, isset($b['var.foo2']));
        $this->assertEquals(false, isset($b->{'var.foo2'}));
    }

    public function testEmptyCollectionIsEmpty()
    {
        $c = new Collection();

        $this->assertTrue($c->isEmpty());
    }

    public function testEmptyCollectionIsNotEmpty()
    {
        $c = new Collection(['foo', 'bar']);

        $this->assertFalse($c->isEmpty());
        $this->assertTrue($c->isNotEmpty());
    }

    public function testPluckWithArrayAndObjectValues()
    {
        $data = new Collection([(object) ['name' => 'taylor', 'email' => 'foo'], ['name' => 'dayle', 'email' => 'bar']]);
        $this->assertEquals(['taylor' => 'foo', 'dayle' => 'bar'], $data->pluck('email', 'name')->all());
        $this->assertEquals(['foo', 'bar'], $data->pluck('email')->all());
    }

    public function testPluckWithArrayAccessValues()
    {
        $data = new Collection([
            new TestArrayAccessImplementation(['name' => 'taylor', 'email' => 'foo']),
            new TestArrayAccessImplementation(['name' => 'dayle', 'email' => 'bar']),
        ]);

        $this->assertEquals(['taylor' => 'foo', 'dayle' => 'bar'], $data->pluck('email', 'name')->all());
        $this->assertEquals(['foo', 'bar'], $data->pluck('email')->all());
    }

}

class TestArrayAccessImplementation implements \ArrayAccess
{
    private $arr;

    public function __construct($arr)
    {
        $this->arr = $arr;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->arr[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->arr[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->arr[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->arr[$offset]);
    }
}
