<?php

use PHPUnit\Framework\TestCase;

class GrootTest extends TestCase
{
    // La méthode de test est préfixée par `test`
    // Le nom du test reflète ce qu'on test
    public function testGrootSpeaking()
    {
        // Todo : on teste que le voccabulaire de Groot se limite à "I am Groot"
        // https://phpunit.de/manual/current/en/appendixes.assertions.html#appendixes.assertions.assertEquals
        // Groot est le "SUT" (system under test)
        $groot = new Groot();

        $this->assertEquals($groot->speak(), 'I am Groot');
    }
}
