<?php

class TagTest extends TestCase {

    public function testLoad()
    {
        Tag::load();
        $tags = Tag::all();
        $this->assertCount(6, $tags);
    }

}