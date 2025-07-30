<?php
namespace Gongying\Utils;

class Snowflake
{
    protected $datacenterId;
    protected $workerId;
    protected $sequence = 0;
    protected $lastTimestamp = -1;

    const EPOCH = 1609459200000; // 可自定义起始时间（2021-01-01）
    const DATACENTER_ID_BITS = 5;
    const WORKER_ID_BITS = 5;
    const SEQUENCE_BITS = 12;

    const MAX_DATACENTER_ID = -1 ^ (-1 << self::DATACENTER_ID_BITS);
    const MAX_WORKER_ID = -1 ^ (-1 << self::WORKER_ID_BITS);

    const WORKER_ID_SHIFT = self::SEQUENCE_BITS;
    const DATACENTER_ID_SHIFT = self::SEQUENCE_BITS + self::WORKER_ID_BITS;
    const TIMESTAMP_LEFT_SHIFT = self::SEQUENCE_BITS + self::WORKER_ID_BITS + self::DATACENTER_ID_BITS;
    const SEQUENCE_MASK = -1 ^ (-1 << self::SEQUENCE_BITS);

    public function __construct($datacenterId, $workerId)
    {
        if ($datacenterId > self::MAX_DATACENTER_ID || $datacenterId < 0) {
            throw new \Exception("Datacenter ID out of range");
        }
        if ($workerId > self::MAX_WORKER_ID || $workerId < 0) {
            throw new \Exception("Worker ID out of range");
        }

        $this->datacenterId = $datacenterId;
        $this->workerId = $workerId;
    }

    public function nextId()
    {
        $timestamp = $this->timeGen();

        if ($timestamp < $this->lastTimestamp) {
            throw new \Exception("Clock moved backwards.");
        }

        if ($this->lastTimestamp == $timestamp) {
            $this->sequence = ($this->sequence + 1) & self::SEQUENCE_MASK;
            if ($this->sequence == 0) {
                $timestamp = $this->waitNextMillis($this->lastTimestamp);
            }
        } else {
            $this->sequence = 0;
        }

        $this->lastTimestamp = $timestamp;

        return (($timestamp - self::EPOCH) << self::TIMESTAMP_LEFT_SHIFT)
            | ($this->datacenterId << self::DATACENTER_ID_SHIFT)
            | ($this->workerId << self::WORKER_ID_SHIFT)
            | $this->sequence;
    }

    protected function waitNextMillis($lastTimestamp)
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }

    protected function timeGen()
    {
        return (int) (microtime(true) * 1000);
    }
}