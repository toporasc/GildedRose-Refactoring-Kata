<?php

require_once __DIR__.'/../src/gilded_rose.php';

use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase {

    //Default Test
    function testFoo() {
        $items = array(new Item("foo", 0, 0));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals("foo", $items[0]->name);
    }

    //Test if after every day the quality drops 1
    function testDegradation(){
        $items = array(new Item("FirstItem", 5, 7));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals(6, $items[0]->quality);
        $this->assertEquals(4, $items[0]->sell_in);
        $gildedRose->update_quality();
        $this->assertEquals(5, $items[0]->quality);
        $this->assertEquals(3, $items[0]->sell_in);
        $gildedRose->update_quality();    
        $this->assertEquals(4, $items[0]->quality);
        $this->assertEquals(2, $items[0]->sell_in);
    }

    //Test if after the sellin date the quality drops twice as fast
    function testTwiceDegradationAfterDay(){
        $items = array(new Item("Item", 0, 7));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals(5, $items[0]->quality);
    }

    //Test if the quality does go negative
    function testNegativeQuality(){
        $items = array(new Item("Unusable", 3, 1));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals(0, $items[0]->quality);
        $gildedRose->update_quality();
        $this->assertEquals(0, $items[0]->quality);
    }

    //Test if "Aged Brie"'s quality grows as the sellin day lowers
    function testBrie(){
        $items = array(new Item("Aged Brie", 3, 49));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals(50, $items[0]->quality);
    }

    //Test if "Aged Brie"'s quality grows over 50
    function testMaxBrieQuality(){
        $items = array(new Item("Aged Brie", 3, 49));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals(50, $items[0]->quality);
    }

    //Test if legendary item quality changes.
    function testLegendary(){
        $items = array(new Item("Sulfuras, Hand of Ragnaros", 3, 80));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals(80, $items[0]->quality);
    }

    //Backstage as it's sellin value aproaches
    function testBackstage(){
        $items = array(new Item("Backstage passes to a TAFKAL80ETC concert", 6, 10), new Item("Backstage passes to a TAFKAL80ETC concert", 11, 10), new Item("Backstage passes to a TAFKAL80ETC concert", 3, 10));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals(12, $items[0]->quality);
        $this->assertEquals(11, $items[1]->quality);
        $this->assertEquals(13, $items[2]->quality);
    }

    //Test conjured items which degrade twice as fast than normal items
    function testConjuring(){
        $items = array(new Item("Conjured Gouda", 1, 8));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals(6, $items[0]->quality);
        $gildedRose->update_quality();
        $this->assertEquals(2, $items[0]->quality);
    }
}
