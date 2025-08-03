<?php

readonly class ScheduleDay {
    public DateTime $date;
    public int $workedHours;
    public PublicHolidays $publicHolidays;

    public function __construct(
        PublicHolidays $publicHolidays,
        string $date,
        int $workedHours,
    ) {
        $this->publicHolidays = $publicHolidays;
        $this->date = new DateTime($date);
        $this->workedHours = min($workedHours, 8);
    }

    public function isWorked(): bool
    {
        return $this->workedHours > 0;
    }

    public function isSunday(): bool
    {
        return $this->date->format('w') === '0';
    }

    public function isPublicHoliday(): bool
    {
        return $this->publicHolidays->isHoliday($this->date->format('Y-m-d'));
    }
}
