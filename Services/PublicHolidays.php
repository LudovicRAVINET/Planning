<?php

class PublicHolidays
{
    private array $holidays = [];

    public function __construct(int $year)
    {
        $this->holidays = $this->generateHolidays($year);
    }

    private function generateHolidays(int $year): array
    {
        // Fixed-date holidays
        $holidays = [
            "Jour de l'An" => "$year-01-01",
            'Fete du Travail' => "$year-05-01",
            'Victoire 1945' => "$year-05-08",
            'Fete Nationale' => "$year-07-14",
            'Assomption' => "$year-08-15",
            'Toussaint' => "$year-11-01",
            'Armistice 1918' => "$year-11-11",
            'Noel' => "$year-12-25",
        ];

        // Movable holidays
        $easter = new DateTime('@' . easter_date($year));
        $easter->setTimezone(new DateTimeZone('Europe/Paris'));

        $easterMonday = (clone $easter)->modify('+1 day');
        $ascensionDay = (clone $easter)->modify('+39 days');
        $pentecostMonday = (clone $easter)->modify('+50 days');

        $holidays['Lundi de Paques'] = $easterMonday->format('Y-m-d');
        $holidays['Ascension'] = $ascensionDay->format('Y-m-d');
        $holidays['Lundi de Pentecote'] = $pentecostMonday->format('Y-m-d');

        return $holidays;
    }

    public function isHoliday(string $date): bool
    {
        return in_array($date, $this->holidays);
    }
}
