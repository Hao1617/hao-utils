<?php
namespace Gongying\Utils;

class Snowflake
{
    protected int $datacenterId;
    protected int $workerId;
    protected int $sequence = 0;
    protected int $lastTimestamp = -1;

    protected const EPOCH = 1609459200000; // 自定义起始时间：2021-01-01

    public function __construct(int $datacenterId = 1, int $workerId = 1)
    {
        // 限制最大值（5位 -> 0~31）
        if ($datacenterId > 31 || $workerId > 31) {
            throw new \Exception("datacenterId and workerId must be between 0 and 31");
        }

        $this->datacenterId = $datacenterId;
        $this->workerId = $workerId;
    }

    public function nextId(): int
    {
        $timestamp = $this->timeGen();

        if ($timestamp < $this->lastTimestamp) {
            throw new \Exception("Clock moved backwards. Refusing to generate id");
        }

        if ($timestamp === $this->lastTimestamp) {
            $this->sequence = ($this->sequence + 1) & 0xfff; // 12 位序列 = 4095

            if ($this->sequence === 0) {
                // 等待下一毫秒
                $timestamp = $this->waitNextMillis($this->lastTimestamp);
            }
        } else {
            $this->sequence = 0;
        }

        $this->lastTimestamp = $timestamp;

        // 位移构造最终 ID
        return (
            (($timestamp - self::EPOCH) << 22) |
            ($this->datacenterId << 17) |
            ($this->workerId << 12) |
            $this->sequence
        );
    }

    protected function waitNextMillis(int $lastTimestamp): int
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }

    protected function timeGen(): int
    {
        return (int) floor(microtime(true) * 1000);
    }
}
