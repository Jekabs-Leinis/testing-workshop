<?php

namespace PF;

use Exception;

error_reporting(E_ALL);

class TurnCountException extends Exception {}

class BowlingGame
{
    private array $rolls;


    public function roll(int $score): void
    {
        $this->rolls[] = $score;
    }

    public function getElapsedTurnCount()
    {
        $turnCount = 0;
        $rollCount = sizeof($this->rolls);
        for ($roll = 0; $roll < $rollCount; $roll++) {
            $turnCount++;
            if (!$this->isStrike($roll)) {
                $roll++;
            }
        }

        return $turnCount;
    }

    /**
     * @return bool
     * @throws TurnCountException
     */
    public function isElapsedTurnCountValid()
    {
        $turnCount = $this->getElapsedTurnCount();
        if ($turnCount < 10 || $turnCount > 12) {
            return false;
        }
        $rollCount = sizeof($this->rolls);

        if (!$this->isSpare($rollCount - 3) && !$this->isStrike($rollCount - 3)) {
            return $turnCount === 10;
        }

        return true;
    }

    /**
     * @return int
     * @throws TurnCountException
     */
    public function getScore(): int
    {
        echo $this->getElapsedTurnCount() . "\n";
        if (!$this->isElapsedTurnCountValid()) {
            throw new TurnCountException();
        }

        $score = 0;
        $roll = 0;
        for ($frame = 0; $frame < 10; $frame++) {
            if ($this->isStrike($roll)) {
                $score += $this->getStrikeBonus($roll);
                $roll++;
                continue;
            }

            if ($this->isSpare($roll)) {
                $score += $this->getSpareBonus($roll);
            }
            $score += $this->getFrameAmount($roll);

            $roll += 2;
        }

        return $score;
    }

    /**
     * @param int $roll
     * @return mixed
     * @throws TurnCountException
     */
    private function getFrameAmount(int $roll): int
    {
        if ($this->isFrameDataSet($roll)) {
            return $this->rolls[$roll] + $this->rolls[$roll + 1];
        } else {
            throw new TurnCountException();
        }
    }

    /**
     * @param int $roll
     * @return bool
     * @throws TurnCountException
     */
    private function isSpare(int $roll): bool
    {
        return $this->getFrameAmount($roll) === 10;
    }

    /**
     * @param int $roll
     * @return mixed
     * @throws TurnCountException
     */
    private function getSpareBonus(int $roll): int
    {
        if ($this->isSpareBonusDataSet($roll)) {
            return $this->rolls[$roll + 2];
        } else {
            throw new TurnCountException();
        }
    }

    /**
     * @param int $roll
     * @return int
     * @throws TurnCountException
     */
    private function getStrikeBonus(int $roll): int
    {
        return 10 + $this->getFrameAmount($roll + 1);
    }

    /**
     * @param int $roll
     * @return bool
     */
    private function isFrameDataSet(int $roll): bool
    {
        return isset($this->rolls[$roll]) && isset($this->rolls[$roll + 1]);
    }

    /**
     * @param int $roll
     * @return bool
     */
    private function isSpareBonusDataSet(int $roll): bool
    {
        return isset($this->rolls[$roll + 2]);
    }

    /**
     * @param int $roll
     * @return bool
     */
    private function isStrike(int $roll): bool
    {
        return $this->rolls[$roll] == 10;
    }
}