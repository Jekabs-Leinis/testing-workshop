<?php

use PF\BowlingGame;
use PF\TurnCountException;
use PHPUnit\Framework\TestCase;

class BowlingGameTest extends TestCase
{
    public function testGetScore_withAllZeros_returnsZero()
    {
        $game = new BowlingGame();
        for ($i = 0; $i < 20; $i++) {
            $game->roll(0);
        }

        $score = $game->getScore();

        self::assertEquals(0, $score);
    }

    public function testGetScore_withAllOnes_returnsScore20() {
        $game = new BowlingGame();
        for ($i = 0; $i < 20; $i++) {
            $game->roll(1);
        }

        $score = $game->getScore();

        self::assertEquals(20, $score);
    }

    public function testGetScore_withASpare_returnsScoreWithSpareBonus() {
        $game = new BowlingGame();
        $game->roll(2);
        $game->roll(8);
        $game->roll(5);

        for ($i = 0; $i < 17; $i++) {
            $game->roll(1);
        }

        $score = $game->getScore();

        self::assertEquals(37, $score);
    }

    public function testGetScore_withAStrike_returnsScoreWithStrikeBonus()
    {
        $game = new BowlingGame();
        $game->roll(10);
        $game->roll(3);
        $game->roll(5);

        for ($i = 0; $i < 16; $i++) {
            $game->roll(1);
        }

        $score = $game->getScore();

        self::assertEquals(42, $score);
    }

    public function testGetScore_withPerfectGame_returns300()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 12; $i++) {
            $game->roll(10);
        }

        $score = $game->getScore();

        self::assertEquals(300, $score);
    }

    public function testGetScore_withNotEnoughTurns_withAllOnes_shouldThrowException()
{
    $game = new BowlingGame();
    for ($i = 0; $i < 19; $i++) {
        $game->roll(1);
    }

    self::expectException(TurnCountException::class);

    $score = $game->getScore();
}

    public function testGetScore_withNotEnoughTurns_withPerfectGame_shouldThrowException()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 11; $i++) {
            $game->roll(10);
        }

        self::expectException(TurnCountException::class);

        $score = $game->getScore();
    }

    public function testGetScore_withTooManyTurns_withAllOnes_shouldThrowException()
    {
        $game = new BowlingGame();
        for ($i = 0; $i < 21; $i++) {
            $game->roll(1);
        }

        self::expectException(TurnCountException::class);

        $score = $game->getScore();
    }

    public function testGetScore_withTooManyTurns_withPerfectGame_shouldThrowException()
    {
        $game = new BowlingGame();

        for ($i = 0; $i < 13; $i++) {
            $game->roll(10);
        }

        self::expectException(TurnCountException::class);

        $score = $game->getScore();
    }
}